<?php
// app/controllers/frontend/SuccessController.php
require_once __DIR__ . '/../../models/PaymentModel.php';
require_once __DIR__ . '/../../models/OrderModel.php';
// require_once __DIR__ . '/../../config/session.php';

class SuccessController {
    private $paymentModel;
    private $orderModel;
    
    public function __construct() {
        $this->paymentModel = new PaymentModel();
        $this->orderModel = new OrderModel();
    }
    
    /**
     * Hiển thị trang thành công
     */
    public function index() {
        $paymentId = $_GET['payment_id'] ?? null;
        
        if (!$paymentId) {
            // Nếu không có payment_id, redirect về trang chủ
            header('Location: index.php');
            exit;
        }
        
        // Lấy thông tin payment và order với địa chỉ đầy đủ
        $paymentInfo = $this->paymentModel->getPaymentWithOrderAndAddress($paymentId);
        
        if (!$paymentInfo) {
            // Nếu không tìm thấy payment, redirect về trang chủ
            header('Location: index.php');
            exit;
        }
        
        // Tạo mã đơn hàng từ order_id hoặc payment_id
        $orderCode = 'VFB' . str_pad($paymentInfo['order_id'] ?? $paymentId, 6, '0', STR_PAD_LEFT);
        
        // Xóa các flash messages cũ
        Session::set('flash_success', null);
        Session::set('flash_error', null);
        Session::set('flash_info', null);
        
        $data = [
            'paymentInfo' => $paymentInfo,
            'orderCode' => $orderCode,
            'pageTitle' => 'Đặt hàng thành công - VoxFootball',
            'pageClass' => 'success-page'
        ];
        
        $this->loadView('success', $data);
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