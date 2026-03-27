<?php
// app/models/PaymentModel.php
require_once __DIR__ . '/../../config/database.php';

class PaymentModel {
    private $db;
    
    public function __construct() {
        $this->db = new Database();
    }
    
    /**
     * Tạo payment record với foreign key constraint
     * Tự động gọi recomputePaymentStatus nếu payment được tạo với status completed/refunded
     */
    public function createPayment($orderId, $sessionId, $deliveryMethod, $paymentMethod, $amount) {
        // Kiểm tra order_id có tồn tại không
        if ($orderId) {
            $orderId = mysqli_real_escape_string($this->db->link, $orderId);
            $orderCheck = "SELECT order_id FROM tbl_order WHERE order_id = '$orderId'";
            $orderResult = $this->db->select($orderCheck);
            
            if (!$orderResult || $orderResult->num_rows == 0) {
                return ['success' => false, 'message' => 'Order ID không tồn tại'];
            }
        }
        
        // Escape data
        $sessionId = mysqli_real_escape_string($this->db->link, $sessionId);
        $deliveryMethod = mysqli_real_escape_string($this->db->link, $deliveryMethod);
        $paymentMethod = mysqli_real_escape_string($this->db->link, $paymentMethod);
        $amount = mysqli_real_escape_string($this->db->link, $amount);
        
        try {
            $paymentQuery = "INSERT INTO tbl_payment (
                order_id,
                session_idA,
                delivery_method,
                payment_method,
                amount,
                payment_status,
                payment_date
            ) VALUES (
                " . ($orderId ? "'$orderId'" : "NULL") . ",
                '$sessionId',
                '$deliveryMethod',
                '$paymentMethod',
                '$amount',
                'pending',
                NOW()
            )";
            
            error_log("Creating payment with query: " . $paymentQuery);
            
            $paymentId = $this->db->insert($paymentQuery);
            
            if ($paymentId) {
                // Note: Không gọi recompute ở đây vì payment mới tạo luôn là 'pending'
                // Chỉ gọi recompute khi updatePaymentStatus() sang completed/refunded
                
                return [
                    'success' => true,
                    'payment_id' => $paymentId,
                    'message' => 'Tạo payment thành công'
                ];
            } else {
                error_log("Database insert failed: " . $this->db->link->error);
                return ['success' => false, 'message' => 'Lỗi tạo payment: ' . $this->db->link->error];
            }
            
        } catch (Exception $e) {
            error_log("Error creating payment: " . $e->getMessage());
            return ['success' => false, 'message' => 'Có lỗi xảy ra: ' . $e->getMessage()];
        }
    }
    
    /**
     * Lấy thông tin payment theo order_id
     */
    public function getPaymentByOrderId($orderId) {
        $orderId = mysqli_real_escape_string($this->db->link, $orderId);
        
        $query = "SELECT * FROM tbl_payment WHERE order_id = '$orderId'";
        $result = $this->db->select($query);
        
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        
        return null;
    }
    
    /**
     * Lấy thông tin payment theo session
     */
    public function getPaymentBySession($sessionId) {
        $sessionId = mysqli_real_escape_string($this->db->link, $sessionId);
        
        $query = "SELECT * FROM tbl_payment 
                  WHERE session_idA = '$sessionId' 
                  ORDER BY created_at DESC";
        return $this->db->select($query);
    }
    
    /**
     * Cập nhật trạng thái payment
     * Tự động gọi recomputePaymentStatus sau khi cập nhật
     */
    public function updatePaymentStatus($paymentId, $status, $transactionId = null) {
        $paymentId = mysqli_real_escape_string($this->db->link, $paymentId);
        $status = mysqli_real_escape_string($this->db->link, $status);
        
        $transactionPart = "";
        if ($transactionId) {
            $transactionId = mysqli_real_escape_string($this->db->link, $transactionId);
            $transactionPart = ", transaction_id = '$transactionId'";
        }
        
        $query = "UPDATE tbl_payment 
                  SET payment_status = '$status', 
                      updated_at = NOW() 
                      $transactionPart
                  WHERE payment_id = '$paymentId'";
        
        $result = $this->db->update($query);
        
        // Tự động tính lại trạng thái thanh toán của order
        if ($result) {
            // Lấy order_id từ payment
            $orderIdQuery = "SELECT order_id FROM tbl_payment WHERE payment_id = '$paymentId'";
            $orderIdResult = $this->db->select($orderIdQuery);
            
            if ($orderIdResult && $orderIdResult->num_rows > 0) {
                $payment = $orderIdResult->fetch_assoc();
                $orderId = $payment['order_id'];
                
                if ($orderId) {
                    // Gọi OrderModel để recompute
                    require_once __DIR__ . '/OrderModel.php';
                    $orderModel = new OrderModel();
                    $recomputeResult = $orderModel->recomputePaymentStatus($orderId);
                    
                    if (!$recomputeResult['success']) {
                        error_log("Failed to recompute payment status for order $orderId: " . $recomputeResult['message']);
                    }
                }
            }
        }
        
        return $result;
    }
    
    /**
     * Lấy items trong giỏ hàng để hiển thị
     */
    public function getCartItems($sessionId) {
        $sessionId = mysqli_real_escape_string($this->db->link, $sessionId);
        
        $query = "SELECT c.*,
                         s.sanpham_tieude,
                         s.sanpham_anh,
                         bt.bienthe_ma,
                         sz.sanpham_size,
                         col.color_ten
                  FROM tbl_cart c
                  LEFT JOIN tbl_sanpham s ON c.sanpham_id = s.sanpham_id
                  LEFT JOIN tbl_sanpham_bienthe bt ON c.bienthe_id = bt.bienthe_id
                  LEFT JOIN tbl_sanpham_size sz ON bt.sanpham_size_id = sz.sanpham_size_id
                  LEFT JOIN tbl_color col ON bt.color_id = col.color_id
                  WHERE c.session_idA = '$sessionId' 
                  ORDER BY c.cart_id DESC";
        return $this->db->select($query);
    }
    
    /**
     * Tính tổng tiền giỏ hàng
     */
    public function getCartTotal($sessionId) {
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
    
    /**
     * Xóa giỏ hàng sau khi thanh toán thành công
     */
    public function clearCart($sessionId) {
        $sessionId = mysqli_real_escape_string($this->db->link, $sessionId);
        
        $query = "DELETE FROM tbl_cart WHERE session_idA = '$sessionId'";
        return $this->db->delete($query);
    }
    
    /**
     * Lấy thông tin đơn hàng gần nhất của session
     */
    public function getLatestOrder($sessionId) {
        $sessionId = mysqli_real_escape_string($this->db->link, $sessionId);
        
        $query = "SELECT * FROM tbl_order 
                  WHERE session_idA = '$sessionId' 
                  ORDER BY created_at DESC 
                  LIMIT 1";
        $result = $this->db->select($query);
        
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        
        return null;
    }

    /**
     * Lấy thông tin payment kèm order details
     */
    public function getPaymentWithOrder($paymentId) {
        $paymentId = mysqli_real_escape_string($this->db->link, $paymentId);
        
        $query = "SELECT p.*, 
                         o.customer_name, 
                         o.customer_phone, 
                         o.customer_diachi, 
                         o.order_status,
                         o.total_amount,
                         o.order_date
                  FROM tbl_payment p
                  LEFT JOIN tbl_order o ON p.order_id = o.order_id
                  WHERE p.payment_id = '$paymentId'";
                
        $result = $this->db->select($query);
        
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        
        return null;
    }
    
    /**
     * Lấy thông tin payment kèm order và địa chỉ đầy đủ
     */
    public function getPaymentWithOrderAndAddress($paymentId) {
        $paymentId = mysqli_real_escape_string($this->db->link, $paymentId);
        
        $query = "SELECT p.*, 
                         o.customer_name, 
                         o.customer_phone, 
                         o.customer_diachi, 
                         o.order_status,
                         o.total_amount,
                         o.order_date,
                         t.tinh_tp as tinh_name,
                         h.quan_huyen as huyen_name,
                         x.phuong_xa as xa_name
                  FROM tbl_payment p
                  LEFT JOIN tbl_order o ON p.order_id = o.order_id
                  LEFT JOIN (SELECT DISTINCT ma_tinh, tinh_tp FROM tbl_diachi) t 
                      ON o.customer_tinh = t.ma_tinh
                  LEFT JOIN (SELECT DISTINCT ma_qh, quan_huyen FROM tbl_diachi) h 
                      ON o.customer_huyen = h.ma_qh  
                  LEFT JOIN (SELECT DISTINCT ma_px, phuong_xa FROM tbl_diachi) x 
                      ON o.customer_xa = x.ma_px
                  WHERE p.payment_id = '$paymentId'";
                
        $result = $this->db->select($query);
        
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        
        return null;
    }

    /**
     * Lấy tất cả payments của một order
     */
    public function getPaymentsByOrder($orderId) {
        $orderId = mysqli_real_escape_string($this->db->link, $orderId);
        
        $query = "SELECT * FROM tbl_payment 
                  WHERE order_id = '$orderId' 
                  ORDER BY created_at DESC";
        return $this->db->select($query);
    }
    
    /**
     * Lấy chi tiết order items cho payment
     */
    public function getOrderItemsForPayment($orderId) {
        $orderId = mysqli_real_escape_string($this->db->link, $orderId);
        
        $query = "SELECT od.*,
                         s.sanpham_tieude,
                         s.sanpham_anh,
                         bt.bienthe_ma,
                         sz.sanpham_size,
                         col.color_ten
                  FROM tbl_order_details od
                  LEFT JOIN tbl_sanpham s ON od.sanpham_id = s.sanpham_id
                  LEFT JOIN tbl_sanpham_bienthe bt ON od.bienthe_id = bt.bienthe_id
                  LEFT JOIN tbl_sanpham_size sz ON bt.sanpham_size_id = sz.sanpham_size_id
                  LEFT JOIN tbl_color col ON bt.color_id = col.color_id
                  WHERE od.order_id = '$orderId'";
        
        return $this->db->select($query);
    }
}
?>