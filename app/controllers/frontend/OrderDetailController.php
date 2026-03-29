<?php
// app/controllers/frontend/OrderDetailController.php
require_once __DIR__ . '/../../models/OrderModel.php';
require_once __DIR__ . '/../../models/PaymentModel.php';

class OrderDetailController {
    private $orderModel;
    private $paymentModel;



    
    public function __construct() {
        $this->orderModel = new OrderModel();
        $this->paymentModel = new PaymentModel();

    }
    
    /**
     * Hiển thị chi tiết đơn hàng
     */
    public function index() {
        $sessionId = session_id();
        $orderId = $_GET['order_id'] ?? null;
        
        // Nếu có order_id thì lấy theo order_id, nếu không thì lấy theo session
        if ($orderId) {
            $orderInfo = $this->orderModel->getOrderWithAddressNames($orderId);
            $paymentInfo = $this->paymentModel->getPaymentByOrderId($orderId);
            
            // Lấy order details với thông tin đầy đủ về biến thể
            $orderItems = $this->getOrderDetailsWithVariants($orderId);
        } else {
            // Lấy đơn hàng gần nhất theo session
            $orderInfo = $this->orderModel->getLatestOrderBySession($sessionId);
            if ($orderInfo) {
                $paymentInfo = $this->paymentModel->getPaymentByOrderId($orderInfo['order_id']);
                $orderItems = $this->getOrderDetailsWithVariants($orderInfo['order_id']);
            } else {
                $paymentInfo = null;
                $orderItems = null;
            }
        }
        
        // Tính tổng tiền và số lượng
        $totalQuantity = 0;
        $totalAmount = 0;
        
        if ($orderItems && $orderItems->num_rows > 0) {
            $orderItems->data_seek(0);
            while ($item = $orderItems->fetch_assoc()) {
                // Xử lý cả trường hợp từ order_details và cart
                $quantity = $item['quantity'] ?? $item['quantitys'] ?? 0;
                $subtotal = $item['subtotal'] ?? ($item['sanpham_gia'] * $quantity);
                
                $totalQuantity += $quantity;
                $totalAmount += $subtotal;
            }
            $orderItems->data_seek(0); // Reset cursor
        }
        
        // Nếu không có totalAmount từ items, lấy từ order
        if ($totalAmount == 0 && $orderInfo) {
            $totalAmount = $orderInfo['total_amount'];
        }
        
        // Tạo mã đơn hàng - ưu tiên lấy từ DB, nếu không có thì tạo từ ID
        $orderCode = $orderInfo['order_code'] ?? ('VFB' . str_pad($orderInfo['order_id'], 6, '0', STR_PAD_LEFT));
        
        $data = [
            'orderInfo' => $orderInfo,
            'paymentInfo' => $paymentInfo,
            'orderItems' => $orderItems,
            'totalQuantity' => $totalQuantity,
            'totalAmount' => $totalAmount,
            'orderCode' => $orderCode,
            'pageTitle' => 'Chi tiết đơn hàng - VoxFootball',
            'pageClass' => 'order-detail-page'
        ];
        
        $this->loadView('detaill', $data);
    }
    
    /**
     * Lấy order details với thông tin biến thể đầy đủ
     * Sử dụng method có sẵn trong OrderModel
     */
    private function getOrderDetailsWithVariants($orderId) {
        // Sử dụng method có sẵn trong OrderModel
        $orderDetails = $this->orderModel->getOrderDetailsForAdmin($orderId);
        
        if ($orderDetails && isset($orderDetails['details'])) {
            return $orderDetails['details'];
        }
        
        // Fallback: lấy từ cart nếu không có order_details
        return $this->orderModel->getOrderItemsFromCart($orderId);
    }
    
    /**
     * Load view với layout
     */
    private function loadView($view, $data = []) {
        extract($data);
        
        include APP_PATH . '/views/layouts/header.php';
        include APP_PATH . '/views/frontend_views/' . $view . '.php';
        include APP_PATH . '/views/layouts/footer.php';
    }
}
?>