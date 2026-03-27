<?php
// app/models/OrderModel.php
require_once __DIR__ . '/../../config/database.php';

class OrderModel {
    private $db;
    
    public function __construct() {
        $this->db = new Database();
    }

    // ==================== PHẦN KHÁCH HÀNG ====================
    
    public function getLatestOrderBySession($sessionId) {
        $sessionId = mysqli_real_escape_string($this->db->link, $sessionId);
        
        $query = "SELECT o.*,
                         t.tinh_tp as tinh_name,
                         h.quan_huyen as huyen_name,
                         x.phuong_xa as xa_name
                  FROM tbl_order o
                  LEFT JOIN (SELECT DISTINCT ma_tinh, tinh_tp FROM tbl_diachi) t 
                      ON o.customer_tinh = t.ma_tinh
                  LEFT JOIN (SELECT DISTINCT ma_qh, quan_huyen FROM tbl_diachi) h 
                      ON o.customer_huyen = h.ma_qh  
                  LEFT JOIN (SELECT DISTINCT ma_px, phuong_xa FROM tbl_diachi) x 
                      ON o.customer_xa = x.ma_px
                  WHERE o.session_idA = '$sessionId'
                  ORDER BY o.created_at DESC 
                  LIMIT 1";
        
        $result = $this->db->select($query);
        
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        
        return null;
    }
    
    public function getOrderItemsFromCart($orderId) {
        $orderId = mysqli_real_escape_string($this->db->link, $orderId);
        
        $orderQuery = "SELECT session_idA FROM tbl_order WHERE order_id = '$orderId'";
        $orderResult = $this->db->select($orderQuery);
        
        if (!$orderResult || $orderResult->num_rows == 0) {
            return null;
        }
        
        $order = $orderResult->fetch_assoc();
        $sessionId = mysqli_real_escape_string($this->db->link, $order['session_idA']);
        
        $query = "SELECT c.*, 
                         s.sanpham_tieude,
                         s.sanpham_anh,
                         (c.sanpham_gia * c.quantitys) as subtotal
                  FROM tbl_cart c
                  LEFT JOIN tbl_sanpham s ON c.sanpham_id = s.sanpham_id
                  WHERE c.session_idA = '$sessionId'
                  ORDER BY c.cart_id DESC";
        
        return $this->db->select($query);
    }
    
    public function createOrder($sessionId, $customerType, $customerName, $customerPhone, 
                           $customerProvince, $customerDistrict, $customerWard, $customerAddress, 
                           $customerId = null) {
    
        $cartTotal = $this->getCartTotal($sessionId);
        if ($cartTotal <= 0) {
            return ['success' => false, 'message' => 'Giỏ hàng trống'];
        }
        
        $sessionId = mysqli_real_escape_string($this->db->link, $sessionId);
        $customerType = mysqli_real_escape_string($this->db->link, $customerType);
        $customerName = mysqli_real_escape_string($this->db->link, $customerName);
        $customerPhone = mysqli_real_escape_string($this->db->link, $customerPhone);
        $customerProvince = mysqli_real_escape_string($this->db->link, $customerProvince);
        $customerDistrict = mysqli_real_escape_string($this->db->link, $customerDistrict);
        $customerWard = mysqli_real_escape_string($this->db->link, $customerWard);
        $customerAddress = mysqli_real_escape_string($this->db->link, $customerAddress);
        
        try {
            // Tạo mã đơn hàng tự động
            $orderCode = $this->generateOrderCode();
            
            $orderQuery = "INSERT INTO tbl_order (
                order_code,
                customer_id, 
                session_idA,
                loaikhach,
                customer_name, 
                customer_phone, 
                customer_tinh, 
                customer_huyen, 
                customer_xa, 
                customer_diachi,
                total_amount,
                order_status,
                payment_status,
                order_date,
                shipping_fee
            ) VALUES (
                '$orderCode',
                " . ($customerId ? "'" . mysqli_real_escape_string($this->db->link, $customerId) . "'" : "NULL") . ",
                '$sessionId',
                '$customerType',
                '$customerName',
                '$customerPhone', 
                '$customerProvince',
                '$customerDistrict',
                '$customerWard',
                '$customerAddress',
                '$cartTotal',
                'pending',
                'unpaid',
                NOW(),
                0.00
            )";
            
            $orderId = $this->db->insert($orderQuery);
            
            if ($orderId) {
                $this->createOrderDetails($orderId, $sessionId);
                
                return [
                    'success' => true, 
                    'message' => 'Đặt hàng thành công',
                    'order_id' => $orderId,
                    'order_code' => $orderCode
                ];
            } else {
                return ['success' => false, 'message' => 'Lỗi tạo đơn hàng'];
            }
            
        } catch (Exception $e) {
            error_log("Error creating order: " . $e->getMessage());
            return ['success' => false, 'message' => 'Có lỗi xảy ra: ' . $e->getMessage()];
        }
    }
    
    /**
     * Tạo mã đơn hàng tự động theo format VFB000001
     */
    private function generateOrderCode() {
        // Lấy order_id lớn nhất hiện tại
        $query = "SELECT MAX(order_id) as max_id FROM tbl_order";
        $result = $this->db->select($query);
        
        $nextId = 1;
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $nextId = ($row['max_id'] ?? 0) + 1;
        }
        
        // Format: VFB + 6 chữ số
        return 'VFB' . str_pad($nextId, 6, '0', STR_PAD_LEFT);
    }
    
    private function createOrderDetails($orderId, $sessionId) {
        $orderId = mysqli_real_escape_string($this->db->link, $orderId);
        $sessionId = mysqli_real_escape_string($this->db->link, $sessionId);
        
        $cartQuery = "SELECT * FROM tbl_cart WHERE session_idA = '$sessionId'";
        $cartItems = $this->db->select($cartQuery);
        
        if (!$cartItems || $cartItems->num_rows == 0) {
            return false;
        }
        
        while ($item = $cartItems->fetch_assoc()) {
            $bientheId = isset($item['bienthe_id']) && $item['bienthe_id'] ? 
                         "'" . mysqli_real_escape_string($this->db->link, $item['bienthe_id']) . "'" : 
                         "NULL";
            
            $detailQuery = "INSERT INTO tbl_order_details (
                order_id,
                sanpham_id,
                bienthe_id,
                sanpham_tieude,
                sanpham_anh,
                sanpham_gia,
                quantity,
                subtotal
            ) VALUES (
                '$orderId',
                '" . mysqli_real_escape_string($this->db->link, $item['sanpham_id']) . "',
                $bientheId,
                '" . mysqli_real_escape_string($this->db->link, $item['sanpham_tieude']) . "',
                '" . mysqli_real_escape_string($this->db->link, $item['sanpham_anh']) . "',
                '" . mysqli_real_escape_string($this->db->link, $item['sanpham_gia']) . "',
                '" . mysqli_real_escape_string($this->db->link, $item['quantitys']) . "',
                '" . ($item['sanpham_gia'] * $item['quantitys']) . "'
            )";
            
            if (!$this->db->insert($detailQuery)) {
                error_log("Failed to insert order detail: " . $this->db->link->error);
                return false;
            }
        }
        
        return true;
    }
    
    private function getCartTotal($sessionId) {
        $sessionId = mysqli_real_escape_string($this->db->link, $sessionId);
        
        $query = "SELECT SUM(sanpham_gia * quantitys) as total 
                  FROM tbl_cart 
                  WHERE session_idA = '$sessionId'";
        $result = $this->db->select($query);
        
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['total'] ?? 0;
        }
        
        return 0;
    }
    
    public function getCartItems($sessionId) {
        $sessionId = mysqli_real_escape_string($this->db->link, $sessionId);
        
        $query = "SELECT * FROM tbl_cart WHERE session_idA = '$sessionId' ORDER BY cart_id DESC";
        return $this->db->select($query);
    }
    
    private function clearCart($sessionId) {
        $sessionId = mysqli_real_escape_string($this->db->link, $sessionId);
        
        $query = "DELETE FROM tbl_cart WHERE session_idA = '$sessionId'";
        return $this->db->delete($query);
    }
    
    public function getProvinces() {
        $query = "SELECT DISTINCT ma_tinh, tinh_tp FROM tbl_diachi ORDER BY tinh_tp ASC";
        return $this->db->select($query);
    }
    
    public function getDistricts($provinceCode) {
        $provinceCode = mysqli_real_escape_string($this->db->link, $provinceCode);
        
        $query = "SELECT DISTINCT ma_qh, quan_huyen 
                  FROM tbl_diachi 
                  WHERE ma_tinh = '$provinceCode' 
                  ORDER BY quan_huyen ASC";
        return $this->db->select($query);
    }
    
    public function getWards($districtCode) {
        $districtCode = mysqli_real_escape_string($this->db->link, $districtCode);
        
        $query = "SELECT DISTINCT ma_px, phuong_xa 
                  FROM tbl_diachi 
                  WHERE ma_qh = '$districtCode' 
                  ORDER BY phuong_xa ASC";
        return $this->db->select($query);
    }
    
    public function getOrderById($orderId) {
        $orderId = mysqli_real_escape_string($this->db->link, $orderId);
        
        $query = "SELECT * FROM tbl_order WHERE order_id = '$orderId'";
        $result = $this->db->select($query);
        
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        
        return null;
    }
    
    /**
     * Lấy thông tin order theo mã đơn hàng (order_code)
     */
    public function getOrderByCode($orderCode) {
        $orderCode = mysqli_real_escape_string($this->db->link, $orderCode);
        
        $query = "SELECT * FROM tbl_order WHERE order_code = '$orderCode'";
        $result = $this->db->select($query);
        
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        
        return null;
    }
    
    public function getOrderWithAddressNames($orderId) {
        $orderId = mysqli_real_escape_string($this->db->link, $orderId);
        
        $query = "SELECT o.*,
                         t.tinh_tp as tinh_name,
                         h.quan_huyen as huyen_name,
                         x.phuong_xa as xa_name
                  FROM tbl_order o
                  LEFT JOIN (SELECT DISTINCT ma_tinh, tinh_tp FROM tbl_diachi) t 
                      ON o.customer_tinh = t.ma_tinh
                  LEFT JOIN (SELECT DISTINCT ma_qh, quan_huyen FROM tbl_diachi) h 
                      ON o.customer_huyen = h.ma_qh  
                  LEFT JOIN (SELECT DISTINCT ma_px, phuong_xa FROM tbl_diachi) x 
                      ON o.customer_xa = x.ma_px
                  WHERE o.order_id = '$orderId'";
        
        $result = $this->db->select($query);
        
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        
        return null;
    }
    
    public function getOrderDetails($orderId) {
        $orderId = mysqli_real_escape_string($this->db->link, $orderId);
        
        $query = "SELECT * FROM tbl_order_details WHERE order_id = '$orderId'";
        $result = $this->db->select($query);
        
        if ($result && $result->num_rows > 0) {
            return $result;
        }
        
        return $this->getOrderItemsFromCart($orderId);
    }
    
    public function updateOrderStatus($orderId, $status) {
        $orderId = mysqli_real_escape_string($this->db->link, $orderId);
        $status = mysqli_real_escape_string($this->db->link, $status);
        
        $query = "UPDATE tbl_order 
                  SET order_status = '$status' 
                  WHERE order_id = '$orderId'";
        return $this->db->update($query);
    }

    // ==================== PHẦN ADMIN - QUẢN LÝ ĐƠN HÀNG ====================
    
    public function getAllOrders($limit = 20, $offset = 0, $filters = []) {
        $where = [];
        
        if (!empty($filters['order_status'])) {
            $status = mysqli_real_escape_string($this->db->link, $filters['order_status']);
            $where[] = "o.order_status = '$status'";
        }
        
        if (!empty($filters['payment_status'])) {
            $payment = mysqli_real_escape_string($this->db->link, $filters['payment_status']);
            $where[] = "o.payment_status = '$payment'";
        }
        
        if (!empty($filters['search'])) {
            $search = mysqli_real_escape_string($this->db->link, $filters['search']);
            $where[] = "(o.order_id LIKE '%$search%' OR o.customer_name LIKE '%$search%' OR o.customer_phone LIKE '%$search%')";
        }
        
        if (!empty($filters['date_from'])) {
            $dateFrom = mysqli_real_escape_string($this->db->link, $filters['date_from']);
            $where[] = "DATE(o.order_date) >= '$dateFrom'";
        }
        
        if (!empty($filters['date_to'])) {
            $dateTo = mysqli_real_escape_string($this->db->link, $filters['date_to']);
            $where[] = "DATE(o.order_date) <= '$dateTo'";
        }
        
        $whereClause = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";
        
        $query = "SELECT o.*,
                         t.tinh_tp as tinh_name,
                         h.quan_huyen as huyen_name,
                         x.phuong_xa as xa_name,
                         p.payment_method,
                         p.payment_status
                  FROM tbl_order o
                  LEFT JOIN (SELECT DISTINCT ma_tinh, tinh_tp FROM tbl_diachi) t 
                      ON o.customer_tinh = t.ma_tinh
                  LEFT JOIN (SELECT DISTINCT ma_qh, quan_huyen FROM tbl_diachi) h 
                      ON o.customer_huyen = h.ma_qh  
                  LEFT JOIN (SELECT DISTINCT ma_px, phuong_xa FROM tbl_diachi) x 
                      ON o.customer_xa = x.ma_px
                  LEFT JOIN tbl_payment p ON o.order_id = p.order_id
                  $whereClause
                  ORDER BY o.created_at DESC
                  LIMIT $limit OFFSET $offset";
        
        return $this->db->select($query);
    }
    
    public function countOrders($filters = []) {
        $where = [];
        
        if (!empty($filters['order_status'])) {
            $status = mysqli_real_escape_string($this->db->link, $filters['order_status']);
            $where[] = "order_status = '$status'";
        }
        
        if (!empty($filters['payment_status'])) {
            $payment = mysqli_real_escape_string($this->db->link, $filters['payment_status']);
            $where[] = "payment_status = '$payment'";
        }
        
        if (!empty($filters['search'])) {
            $search = mysqli_real_escape_string($this->db->link, $filters['search']);
            $where[] = "(order_id LIKE '%$search%' OR customer_name LIKE '%$search%' OR customer_phone LIKE '%$search%')";
        }
        
        if (!empty($filters['date_from'])) {
            $dateFrom = mysqli_real_escape_string($this->db->link, $filters['date_from']);
            $where[] = "DATE(order_date) >= '$dateFrom'";
        }
        
        if (!empty($filters['date_to'])) {
            $dateTo = mysqli_real_escape_string($this->db->link, $filters['date_to']);
            $where[] = "DATE(order_date) <= '$dateTo'";
        }
        
        $whereClause = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";
        
        $query = "SELECT COUNT(*) as total FROM tbl_order $whereClause";
        $result = $this->db->select($query);
        
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['total'];
        }
        
        return 0;
    }
    
    public function getOrderDetailsForAdmin($orderId) {
        $orderId = mysqli_real_escape_string($this->db->link, $orderId);
        
        $order = $this->getOrderWithAddressNames($orderId);
        
        if (!$order) {
            return null;
        }
        
        $detailsQuery = "SELECT od.*,
                               bt.bienthe_ma,
                               bt.color_id,
                               bt.sanpham_size_id,
                               c.color_ten,
                               c.color_anh,
                               sz.sanpham_size
                        FROM tbl_order_details od
                        LEFT JOIN tbl_sanpham_bienthe bt ON od.bienthe_id = bt.bienthe_id
                        LEFT JOIN tbl_color c ON bt.color_id = c.color_id
                        LEFT JOIN tbl_sanpham_size sz ON bt.sanpham_size_id = sz.sanpham_size_id
                        WHERE od.order_id = '$orderId'";
        
        $details = $this->db->select($detailsQuery);

        $historyQuery = "SELECT * FROM tbl_order_status_history 
                        WHERE order_id = '$orderId' 
                        ORDER BY created_at DESC";
        $history = $this->db->select($historyQuery);
        
        $paymentQuery = "SELECT * FROM tbl_payment WHERE order_id = '$orderId'";
        $payment = $this->db->select($paymentQuery);
        
        return [
            'order' => $order,
            'details' => $details,
            'history' => $history,
            'payment' => $payment ? $payment->fetch_assoc() : null
        ];
    }
    
    public function confirmOrder($orderId, $adminId = null) {
        $orderId = mysqli_real_escape_string($this->db->link, $orderId);
        
        try {
            mysqli_begin_transaction($this->db->link);
            
            $checkQuery = "SELECT order_status FROM tbl_order WHERE order_id = '$orderId' FOR UPDATE";
            $checkResult = $this->db->select($checkQuery);
            
            if (!$checkResult || $checkResult->num_rows == 0) {
                throw new Exception("Không tìm thấy đơn hàng");
            }
            
            $order = $checkResult->fetch_assoc();
            
            if ($order['order_status'] !== 'pending') {
                throw new Exception("Đơn hàng không ở trạng thái pending. Trạng thái hiện tại: " . $order['order_status']);
            }
            
            $lockQuery = "SELECT t.*, od.quantity, od.detail_id, bt.bienthe_ma
                         FROM tbl_order_details od
                         JOIN tbl_tonkho t ON t.bienthe_id = od.bienthe_id
                         JOIN tbl_sanpham_bienthe bt ON bt.bienthe_id = od.bienthe_id
                         WHERE od.order_id = '$orderId'
                         FOR UPDATE";
            
            $stockResult = $this->db->select($lockQuery);
            
            if (!$stockResult || $stockResult->num_rows == 0) {
                throw new Exception("Không tìm thấy thông tin tồn kho cho đơn hàng");
            }
            
            $insufficientItems = [];
            while ($item = $stockResult->fetch_assoc()) {
                $available = $item['soluong_ton'] - $item['soluong_dat'];
                
                if ($available < $item['quantity']) {
                    $insufficientItems[] = "SKU {$item['bienthe_ma']}: Cần {$item['quantity']}, còn {$available}";
                }
            }
            
            if (!empty($insufficientItems)) {
                throw new Exception("Không đủ hàng trong kho:\n" . implode("\n", $insufficientItems));
            }
            
            $updateStockQuery = "UPDATE tbl_tonkho t
                                JOIN tbl_order_details od ON od.bienthe_id = t.bienthe_id
                                SET t.soluong_dat = t.soluong_dat + od.quantity
                                WHERE od.order_id = '$orderId'";
            
            if (!$this->db->update($updateStockQuery)) {
                throw new Exception("Lỗi cập nhật tồn kho");
            }
            
            $updateOrderQuery = "UPDATE tbl_order 
                                SET order_status = 'confirmed'
                                WHERE order_id = '$orderId' AND order_status = 'pending'";
            
            if (!$this->db->update($updateOrderQuery)) {
                throw new Exception("Lỗi cập nhật trạng thái đơn hàng");
            }
            
            $this->insertStatusHistory($orderId, 'pending', 'confirmed', 'Xác nhận đơn hàng và giữ hàng', $adminId);
            
            mysqli_commit($this->db->link);
            
            return ['success' => true, 'message' => 'Xác nhận đơn hàng thành công'];
            
        } catch (Exception $e) {
            mysqli_rollback($this->db->link);
            error_log("Error confirming order: " . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    public function processOrder($orderId, $adminId = null) {
        $orderId = mysqli_real_escape_string($this->db->link, $orderId);
        
        try {
            mysqli_begin_transaction($this->db->link);
            
            $checkQuery = "SELECT order_status FROM tbl_order WHERE order_id = '$orderId' FOR UPDATE";
            $checkResult = $this->db->select($checkQuery);
            
            if (!$checkResult || $checkResult->num_rows == 0) {
                throw new Exception("Không tìm thấy đơn hàng");
            }
            
            $order = $checkResult->fetch_assoc();
            
            if ($order['order_status'] !== 'confirmed') {
                throw new Exception("Đơn hàng phải ở trạng thái confirmed");
            }
            
            $updateQuery = "UPDATE tbl_order 
                           SET order_status = 'processing'
                           WHERE order_id = '$orderId' AND order_status = 'confirmed'";
            
            if (!$this->db->update($updateQuery)) {
                throw new Exception("Lỗi cập nhật trạng thái");
            }
            
            $this->insertStatusHistory($orderId, 'confirmed', 'processing', 'Bắt đầu xử lý đơn hàng', $adminId);
            
            mysqli_commit($this->db->link);
            
            return ['success' => true, 'message' => 'Chuyển sang trạng thái xử lý thành công'];
            
        } catch (Exception $e) {
            mysqli_rollback($this->db->link);
            error_log("Error processing order: " . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    public function deliverOrder($orderId, $adminId = null) {
        $orderId = mysqli_real_escape_string($this->db->link, $orderId);
        
        try {
            mysqli_begin_transaction($this->db->link);
            
            $checkQuery = "SELECT order_status FROM tbl_order WHERE order_id = '$orderId' FOR UPDATE";
            $checkResult = $this->db->select($checkQuery);
            
            if (!$checkResult || $checkResult->num_rows == 0) {
                throw new Exception("Không tìm thấy đơn hàng");
            }
            
            $order = $checkResult->fetch_assoc();
            
            if (!in_array($order['order_status'], ['confirmed', 'processing'])) {
                throw new Exception("Đơn hàng phải ở trạng thái confirmed hoặc processing");
            }
            
            $lockQuery = "SELECT t.*, od.quantity
                         FROM tbl_order_details od
                         JOIN tbl_tonkho t ON t.bienthe_id = od.bienthe_id
                         WHERE od.order_id = '$orderId'
                         FOR UPDATE";
            
            $this->db->select($lockQuery);
            
            $updateStockQuery = "UPDATE tbl_tonkho t
                                JOIN tbl_order_details od ON od.bienthe_id = t.bienthe_id
                                SET t.soluong_ton = t.soluong_ton - od.quantity,
                                    t.soluong_dat = GREATEST(t.soluong_dat - od.quantity, 0)
                                WHERE od.order_id = '$orderId'";
            
            if (!$this->db->update($updateStockQuery)) {
                throw new Exception("Lỗi cập nhật tồn kho");
            }
            
            $oldStatus = $order['order_status'];
            $updateOrderQuery = "UPDATE tbl_order 
                                SET order_status = 'delivered'
                                WHERE order_id = '$orderId' 
                                AND order_status IN ('confirmed', 'processing')";
            
            if (!$this->db->update($updateOrderQuery)) {
                throw new Exception("Lỗi cập nhật trạng thái đơn hàng");
            }
            
            $this->insertStatusHistory($orderId, $oldStatus, 'delivered', 'Hoàn tất giao hàng và xuất kho', $adminId);
            
            // Kiểm tra và cập nhật payment status cho COD
            $this->updateCODPaymentOnDelivery($orderId);
            
            mysqli_commit($this->db->link);
            
            return ['success' => true, 'message' => 'Hoàn tất đơn hàng và xuất kho thành công'];
            
        } catch (Exception $e) {
            mysqli_rollback($this->db->link);
            error_log("Error delivering order: " . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    public function cancelOrder($orderId, $note = '', $adminId = null) {
        $orderId = mysqli_real_escape_string($this->db->link, $orderId);
        $note = mysqli_real_escape_string($this->db->link, $note);
        
        try {
            mysqli_begin_transaction($this->db->link);
            
            $checkQuery = "SELECT order_status FROM tbl_order WHERE order_id = '$orderId' FOR UPDATE";
            $checkResult = $this->db->select($checkQuery);
            
            if (!$checkResult || $checkResult->num_rows == 0) {
                throw new Exception("Không tìm thấy đơn hàng");
            }
            
            $order = $checkResult->fetch_assoc();
            $oldStatus = $order['order_status'];
            
            if ($order['order_status'] === 'delivered') {
                throw new Exception("Không thể huỷ đơn hàng đã giao. Vui lòng tạo đơn đổi trả.");
            }
            
            if (!in_array($order['order_status'], ['pending', 'confirmed', 'processing'])) {
                throw new Exception("Không thể huỷ đơn hàng với trạng thái: " . $order['order_status']);
            }
            
            if (in_array($order['order_status'], ['confirmed', 'processing'])) {
                $releaseStockQuery = "UPDATE tbl_tonkho t
                                     JOIN tbl_order_details od ON od.bienthe_id = t.bienthe_id
                                     JOIN tbl_order o ON o.order_id = od.order_id
                                     SET t.soluong_dat = GREATEST(t.soluong_dat - od.quantity, 0)
                                     WHERE od.order_id = '$orderId' 
                                     AND o.order_status IN ('confirmed', 'processing')";
                
                if (!$this->db->update($releaseStockQuery)) {
                    throw new Exception("Lỗi trả lại hàng đã giữ");
                }
            }
            
            $updateOrderQuery = "UPDATE tbl_order 
                                SET order_status = 'cancelled'
                                WHERE order_id = '$orderId' 
                                AND order_status IN ('pending', 'confirmed', 'processing')";
            
            if (!$this->db->update($updateOrderQuery)) {
                throw new Exception("Lỗi cập nhật trạng thái đơn hàng");
            }
            
            $historyNote = 'Huỷ đơn hàng' . (!empty($note) ? ': ' . $note : '');
            $this->insertStatusHistory($orderId, $oldStatus, 'cancelled', $historyNote, $adminId);
            
            mysqli_commit($this->db->link);
            
            return ['success' => true, 'message' => 'Huỷ đơn hàng thành công'];
            
        } catch (Exception $e) {
            mysqli_rollback($this->db->link);
            error_log("Error cancelling order: " . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    private function insertStatusHistory($orderId, $oldStatus, $newStatus, $note = '', $adminId = null) {
        $orderId = mysqli_real_escape_string($this->db->link, $orderId);
        $oldStatus = mysqli_real_escape_string($this->db->link, $oldStatus);
        $newStatus = mysqli_real_escape_string($this->db->link, $newStatus);
        $note = mysqli_real_escape_string($this->db->link, $note);
        $adminIdValue = $adminId ? "'" . mysqli_real_escape_string($this->db->link, $adminId) . "'" : "NULL";
        
        $query = "INSERT INTO tbl_order_status_history 
                  (order_id, old_status, new_status, note, actor_admin_id, created_at) 
                  VALUES 
                  ('$orderId', '$oldStatus', '$newStatus', '$note', $adminIdValue, NOW())";
        
        return $this->db->insert($query);
    }
    
    public function updatePaymentStatus($orderId, $paymentStatus) {
        $orderId = mysqli_real_escape_string($this->db->link, $orderId);
        $paymentStatus = mysqli_real_escape_string($this->db->link, $paymentStatus);
        
        $query = "UPDATE tbl_order 
                  SET payment_status = '$paymentStatus'
                  WHERE order_id = '$orderId'";
        
        return $this->db->update($query);
    }
    
    public function getOrderStatistics() {
        $query = "SELECT 
                    COUNT(*) as total_orders,
                    SUM(CASE WHEN order_status = 'pending' THEN 1 ELSE 0 END) as pending_orders,
                    SUM(CASE WHEN order_status = 'confirmed' THEN 1 ELSE 0 END) as confirmed_orders,
                    SUM(CASE WHEN order_status = 'processing' THEN 1 ELSE 0 END) as processing_orders,
                    SUM(CASE WHEN order_status = 'delivered' THEN 1 ELSE 0 END) as delivered_orders,
                    SUM(CASE WHEN order_status = 'cancelled' THEN 1 ELSE 0 END) as cancelled_orders,
                    SUM(CASE WHEN payment_status = 'unpaid' THEN 1 ELSE 0 END) as unpaid_orders,
                    SUM(total_amount) as total_revenue
                  FROM tbl_order";
        
        $result = $this->db->select($query);
        
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        
        return null;
    }
    
    public function checkStockAvailability($orderId) {
        $orderId = mysqli_real_escape_string($this->db->link, $orderId);
        
        $query = "SELECT 
                    od.detail_id,
                    od.quantity as required_qty,
                    bt.bienthe_ma,
                    bt.sanpham_id,
                    sp.sanpham_tieude,
                    t.soluong_ton,
                    t.soluong_dat,
                    (t.soluong_ton - t.soluong_dat) as available_qty,
                    CASE 
                        WHEN (t.soluong_ton - t.soluong_dat) >= od.quantity THEN 'OK'
                        ELSE 'INSUFFICIENT'
                    END as status
                  FROM tbl_order_details od
                  JOIN tbl_sanpham_bienthe bt ON od.bienthe_id = bt.bienthe_id
                  JOIN tbl_sanpham sp ON bt.sanpham_id = sp.sanpham_id
                  JOIN tbl_tonkho t ON t.bienthe_id = od.bienthe_id
                  WHERE od.order_id = '$orderId'";
        
        return $this->db->select($query);
    }
    
    /**
     * Lấy thông tin thanh toán của đơn hàng
     * Bao gồm: tổng tiền, đã trả, còn nợ
     * 
     * @param int $orderId
     * @return array|null
     */
    public function getOrderPaymentInfo($orderId) {
        $orderId = mysqli_real_escape_string($this->db->link, $orderId);
        
        $query = "SELECT 
                    o.order_id,
                    o.order_code,
                    o.total_amount,
                    o.payment_status,
                    o.order_status,
                    SUM(CASE WHEN p.payment_status = 'completed' THEN p.amount ELSE 0 END) as total_paid,
                    SUM(CASE WHEN p.payment_status = 'refunded' THEN p.amount ELSE 0 END) as total_refunded,
                    (SUM(CASE WHEN p.payment_status = 'completed' THEN p.amount ELSE 0 END) - 
                     SUM(CASE WHEN p.payment_status = 'refunded' THEN p.amount ELSE 0 END)) as net_paid,
                    (o.total_amount - 
                     (SUM(CASE WHEN p.payment_status = 'completed' THEN p.amount ELSE 0 END) - 
                      SUM(CASE WHEN p.payment_status = 'refunded' THEN p.amount ELSE 0 END))) as remaining
                  FROM tbl_order o
                  LEFT JOIN tbl_payment p ON o.order_id = p.order_id
                  WHERE o.order_id = '$orderId'
                  GROUP BY o.order_id";
        
        $result = $this->db->select($query);
        
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        
        return null;
    }
    
    /**
     * Tính tổng tiền thực tế đã thanh toán (Net Paid)
     * = SUM(completed) - SUM(refunded)
     * 
     * @param int $orderId
     * @return float Số tiền đã thanh toán thực tế
     */
    public function getNetPaid($orderId) {
        $orderId = mysqli_real_escape_string($this->db->link, $orderId);
        
        $query = "SELECT 
                    SUM(CASE WHEN payment_status = 'completed' THEN amount ELSE 0 END) as total_completed,
                    SUM(CASE WHEN payment_status = 'refunded' THEN amount ELSE 0 END) as total_refunded
                  FROM tbl_payment 
                  WHERE order_id = '$orderId'";
        
        $result = $this->db->select($query);
        
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $completed = $row['total_completed'] ?? 0;
            $refunded = $row['total_refunded'] ?? 0;
            
            return $completed - $refunded;
        }
        
        return 0;
    }
    
    /**
     * Tính toán lại và cập nhật trạng thái thanh toán của đơn hàng
     * Dựa trên tổng tiền đã thanh toán so với tổng tiền đơn hàng
     * 
     * @param int $orderId
     * @return array Kết quả cập nhật
     */
    public function recomputePaymentStatus($orderId) {
        $orderId = mysqli_real_escape_string($this->db->link, $orderId);
        
        try {
            // Lấy tổng tiền đơn hàng
            $orderQuery = "SELECT total_amount, payment_status as current_status 
                          FROM tbl_order 
                          WHERE order_id = '$orderId'";
            $orderResult = $this->db->select($orderQuery);
            
            if (!$orderResult || $orderResult->num_rows == 0) {
                return ['success' => false, 'message' => 'Không tìm thấy đơn hàng'];
            }
            
            $order = $orderResult->fetch_assoc();
            $totalAmount = $order['total_amount'];
            $currentStatus = $order['current_status'];
            
            // Tính tổng tiền đã thanh toán thực tế
            $netPaid = $this->getNetPaid($orderId);
            
            // Xác định trạng thái mới
            $newStatus = 'unpaid';
            
            if ($netPaid <= 0) {
                // Chưa thanh toán hoặc đã hoàn tiền hết
                if ($netPaid < 0) {
                    // Đã hoàn tiền nhiều hơn đã trả (trường hợp đặc biệt)
                    $newStatus = 'refunded';
                } else {
                    $newStatus = 'unpaid';
                }
            } else if ($netPaid >= $totalAmount) {
                // Đã thanh toán đủ hoặc thừa
                $newStatus = 'paid';
            } else {
                // Đã thanh toán một phần (có thể thêm status 'partial' nếu cần)
                $newStatus = 'unpaid'; // Hoặc 'partial' nếu DB hỗ trợ
            }
            
            // Chỉ cập nhật nếu trạng thái thay đổi
            if ($newStatus !== $currentStatus) {
                $updateQuery = "UPDATE tbl_order 
                               SET payment_status = '$newStatus'
                               WHERE order_id = '$orderId'";
                
                if ($this->db->update($updateQuery)) {
                    return [
                        'success' => true,
                        'message' => 'Cập nhật trạng thái thanh toán thành công',
                        'old_status' => $currentStatus,
                        'new_status' => $newStatus,
                        'net_paid' => $netPaid,
                        'total_amount' => $totalAmount
                    ];
                } else {
                    return ['success' => false, 'message' => 'Lỗi cập nhật database'];
                }
            }
            
            // Trạng thái không thay đổi
            return [
                'success' => true,
                'message' => 'Trạng thái thanh toán không thay đổi',
                'status' => $currentStatus,
                'net_paid' => $netPaid,
                'total_amount' => $totalAmount
            ];
            
        } catch (Exception $e) {
            error_log("Error in recomputePaymentStatus: " . $e->getMessage());
            return ['success' => false, 'message' => 'Có lỗi xảy ra: ' . $e->getMessage()];
        }
    }
    
    /**
     * Cập nhật payment status cho đơn hàng COD khi delivered
     */
    private function updateCODPaymentOnDelivery($orderId) {
        $orderId = mysqli_real_escape_string($this->db->link, $orderId);
        
        try {
            // Kiểm tra xem có payment nào với method COD và status pending không
            $checkPaymentQuery = "SELECT payment_id, payment_method, payment_status 
                                 FROM tbl_payment 
                                 WHERE order_id = '$orderId' 
                                 AND payment_method = 'Thu tiền tận nơi'
                                 AND payment_status = 'pending'";
            
            $paymentResult = $this->db->select($checkPaymentQuery);
            
            if ($paymentResult && $paymentResult->num_rows > 0) {
                // Cập nhật payment status từ pending -> completed cho COD
                $updatePaymentQuery = "UPDATE tbl_payment 
                                      SET payment_status = 'completed',
                                          updated_at = NOW()
                                      WHERE order_id = '$orderId' 
                                      AND payment_method = 'Thu tiền tận nơi'
                                      AND payment_status = 'pending'";
                
                $this->db->update($updatePaymentQuery);
                
                error_log("Updated COD payment status to completed for order: $orderId");
            }
            
        } catch (Exception $e) {
            error_log("Error updating COD payment on delivery: " . $e->getMessage());
            // Không throw exception để không ảnh hưởng đến việc deliver order
        }
    }
    
    // ==================== PHẦN CUSTOMER FUNCTIONS ====================
    
    /**
     * Lấy danh sách đơn hàng của khách hàng theo customer_id
     */
    public function getOrdersByCustomerId($customerId, $limit = 10, $offset = 0) {
        $customerId = mysqli_real_escape_string($this->db->link, $customerId);
        $limit = (int)$limit;
        $offset = (int)$offset;
        
        $query = "SELECT o.*,
                         t.tinh_tp as tinh_name,
                         h.quan_huyen as huyen_name,
                         x.phuong_xa as xa_name,
                         COUNT(od.detail_id) as total_items
                  FROM tbl_order o
                  LEFT JOIN (SELECT DISTINCT ma_tinh, tinh_tp FROM tbl_diachi) t 
                      ON o.customer_tinh = t.ma_tinh
                  LEFT JOIN (SELECT DISTINCT ma_qh, quan_huyen FROM tbl_diachi) h 
                      ON o.customer_huyen = h.ma_qh  
                  LEFT JOIN (SELECT DISTINCT ma_px, phuong_xa FROM tbl_diachi) x 
                      ON o.customer_xa = x.ma_px
                  LEFT JOIN tbl_order_details od ON o.order_id = od.order_id
                  WHERE o.customer_id = '$customerId'
                  GROUP BY o.order_id
                  ORDER BY o.created_at DESC 
                  LIMIT $limit OFFSET $offset";
        
        $result = $this->db->select($query);
        $orders = [];
        
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $orders[] = $row;
            }
        }
        
        return $orders;
    }
    
    /**
     * Đếm tổng số đơn hàng của khách hàng
     */
    public function getTotalOrdersByCustomerId($customerId) {
        $customerId = mysqli_real_escape_string($this->db->link, $customerId);
        
        $query = "SELECT COUNT(*) as total FROM tbl_order WHERE customer_id = '$customerId'";
        $result = $this->db->select($query);
        
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return (int)$row['total'];
        }
        
        return 0;
    }
    
    /**
     * Lấy chi tiết đơn hàng cho customer (với thông tin đầy đủ)
     */
    public function getOrderDetailsForCustomer($orderId) {
        $orderId = mysqli_real_escape_string($this->db->link, $orderId);
        
        $query = "SELECT od.*,
                         p.sanpham_tieude,
                         p.sanpham_anh,
                         bt.color_id,
                         bt.sanpham_size_id,
                         c.color_ten,
                         sz.sanpham_size
                  FROM tbl_order_details od
                  LEFT JOIN tbl_sanpham p ON od.sanpham_id = p.sanpham_id
                  LEFT JOIN tbl_sanpham_bienthe bt ON od.bienthe_id = bt.bienthe_id
                  LEFT JOIN tbl_color c ON bt.color_id = c.color_id
                  LEFT JOIN tbl_sanpham_size sz ON bt.sanpham_size_id = sz.sanpham_size_id
                  WHERE od.order_id = '$orderId'
                  ORDER BY od.detail_id";
        
        $result = $this->db->select($query);
        $details = [];
        
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $details[] = $row;
            }
        }
        
        return $details;
    }
    
    /**
     * Hủy đơn hàng cho customer
     */
    public function cancelOrderByCustomer($orderId, $cancelReason = '') {
        $orderId = mysqli_real_escape_string($this->db->link, $orderId);
        $cancelReason = mysqli_real_escape_string($this->db->link, $cancelReason);
        
        // Cập nhật trạng thái đơn hàng
        $updateQuery = "UPDATE tbl_order 
                       SET order_status = 'cancelled' 
                       WHERE order_id = '$orderId' 
                       AND order_status IN ('pending', 'confirmed')";
        
        $result = $this->db->update($updateQuery);
        
        if ($result) {
            // Ghi log lịch sử thay đổi trạng thái
            $historyQuery = "INSERT INTO tbl_order_status_history 
                            (order_id, old_status, new_status, note, created_at) 
                            VALUES ('$orderId', 
                                    (SELECT order_status FROM tbl_order WHERE order_id = '$orderId'), 
                                    'cancelled', 
                                    'Khách hàng hủy đơn: $cancelReason', 
                                    NOW())";
            
            $this->db->insert($historyQuery);
            
            // TODO: Cập nhật lại số lượng tồn kho nếu cần
            
            return true;
        }
        
        return false;
    }
    
    /**
     * Kiểm tra xem đơn hàng có thể hủy không
     */
    public function canCancelOrder($orderId, $customerId) {
        $orderId = mysqli_real_escape_string($this->db->link, $orderId);
        $customerId = mysqli_real_escape_string($this->db->link, $customerId);
        
        $query = "SELECT order_status 
                  FROM tbl_order 
                  WHERE order_id = '$orderId' 
                  AND customer_id = '$customerId'";
        
        $result = $this->db->select($query);
        
        if ($result && $result->num_rows > 0) {
            $order = $result->fetch_assoc();
            return in_array($order['order_status'], ['pending', 'confirmed']);
        }
        
        return false;
    }
}
?>