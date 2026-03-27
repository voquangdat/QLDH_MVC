<?php
// app/controllers/frontend/PaymentController.php
require_once __DIR__ . '/../../models/PaymentModel.php';
require_once __DIR__ . '/../../models/OrderModel.php';
require_once __DIR__ . '/../../models/MoMoPayment.php';

class PaymentController {
    private $paymentModel;
    private $orderModel;
    private $momoPayment;
    
    public function __construct() {
        $this->paymentModel = new PaymentModel();
        $this->orderModel = new OrderModel();
        $this->momoPayment = new MoMoPayment();
    }
    
    /**
     * Điều hướng các action
     */
    public function index() {
        $action = $_GET['action'] ?? 'show';
        
        switch ($action) {
            case 'show':
            case 'payment':
                $this->showPaymentPage();
                break;
            case 'process':
                $this->processPayment();
                break;
            case 'momo_return':
                $this->momoReturn();
                break;
            case 'momo_notify':
                $this->momoNotify();
                break;
            case 'callback':
                $this->paymentCallback();
                break;
            default:
                $this->showPaymentPage();
                break;
        }
    }
    
    /**
     * Hiển thị trang thanh toán
     */
    private function showPaymentPage() {
        $sessionId = session_id();
        
        // Lấy order_id từ URL parameter hoặc session
        $currentOrderId = $_GET['order_id'] ?? Session::get('current_order_id');
        
        if (!$currentOrderId) {
            $this->setFlashMessage('error', 'Vui lòng điền thông tin giao hàng trước');
            header('Location: index.php?page=delivery');
            exit;
        }
        
        // Lưu order_id vào session
        Session::set('current_order_id', $currentOrderId);
        
        // Lấy thông tin đơn hàng với tên địa chỉ đầy đủ
        $order = $this->orderModel->getOrderWithAddressNames($currentOrderId);
        
        if (!$order) {
            $this->setFlashMessage('error', 'Không tìm thấy đơn hàng');
            header('Location: index.php?page=delivery');
            exit;
        }
        
        // Kiểm tra giỏ hàng có sản phẩm không
        $cartItems = $this->paymentModel->getCartItems($sessionId);
        
        // Tính tổng tiền từ order
        $cartTotal = $order['total_amount'];
        
        // Kiểm tra đã có payment record chưa
        $existingPayment = $this->paymentModel->getPaymentByOrderId($currentOrderId);
        
        $data = [
            'cartItems' => $cartItems,
            'cartTotal' => $cartTotal,
            'order' => $order,
            'existingPayment' => $existingPayment,
            'pageTitle' => 'Thanh toán - VoxFootball',
            'pageClass' => 'payment-page'
        ];
        
        $this->loadView('payment', $data);
    }
    
    /**
     * Xử lý thanh toán
     */
    public function processPayment() {
        error_log("processPayment started");
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            error_log("Not POST request, redirecting");
            header('Location: index.php?page=payment');
            exit;
        }
        
        $sessionId = session_id();
        
        // Lấy order_id từ session
        $currentOrderId = Session::get('current_order_id');
        error_log("Current order ID: " . ($currentOrderId ?? 'null'));
        
        if (!$currentOrderId) {
            error_log("No current order ID found");
            $this->setFlashMessage('error', 'Không tìm thấy đơn hàng');
            header('Location: index.php?page=delivery');
            exit;
        }
        
        // Validate input
        $deliveryMethod = $_POST['deliver-method'] ?? '';
        $paymentMethod = $_POST['method-payment'] ?? '';
        error_log("Delivery method: $deliveryMethod, Payment method: $paymentMethod");
        
        if (empty($deliveryMethod)) {
            $this->setFlashMessage('error', 'Vui lòng chọn phương thức giao hàng');
            header('Location: index.php?page=payment&order_id=' . $currentOrderId);
            exit;
        }
        
        if (empty($paymentMethod)) {
            $this->setFlashMessage('error', 'Vui lòng chọn phương thức thanh toán');
            header('Location: index.php?page=payment&order_id=' . $currentOrderId);
            exit;
        }
        
        try {
            // Lấy thông tin đơn hàng
            $order = $this->orderModel->getOrderById($currentOrderId);
            
            if (!$order) {
                $this->setFlashMessage('error', 'Không tìm thấy đơn hàng');
                header('Location: index.php?page=delivery');
                exit;
            }
            
            $cartTotal = $order['total_amount'];
            
            // Kiểm tra đã có payment record chưa
            $existingPayment = $this->paymentModel->getPaymentByOrderId($currentOrderId);
            
            if ($existingPayment) {
                $paymentId = $existingPayment['payment_id'];
            } else {
                // Tạo payment record mới
                $result = $this->paymentModel->createPayment(
                    $currentOrderId,
                    $sessionId,
                    $deliveryMethod,
                    $paymentMethod,
                    $cartTotal
                );
                
                if (!$result['success']) {
                    $this->setFlashMessage('error', $result['message']);
                    header('Location: index.php?page=payment&order_id=' . $currentOrderId);
                    exit;
                }
                
                $paymentId = $result['payment_id'];
            }
            
            // Xử lý theo phương thức thanh toán
            $this->handlePaymentMethod($paymentMethod, $paymentId, $cartTotal, $currentOrderId);
            
        } catch (Exception $e) {
            error_log("Error in processPayment: " . $e->getMessage());
            $this->setFlashMessage('error', 'Có lỗi xảy ra khi thanh toán');
            header('Location: index.php?page=payment&order_id=' . $currentOrderId);
            exit;
        }
    }
    
    /**
     * Xử lý các phương thức thanh toán khác nhau
     */
    private function handlePaymentMethod($paymentMethod, $paymentId, $amount, $orderId) {
        switch ($paymentMethod) {
            case 'Thu tiền tận nơi':
                // COD
                $this->paymentModel->updatePaymentStatus($paymentId, 'pending');
                $this->completeCODOrder($paymentId, $orderId);
                break;
                
            case 'Thanh toán bằng thẻ tín dụng(OnePay)':
                // Redirect to OnePay gateway
                $this->redirectToOnePay($paymentId, $amount, $orderId, 'credit');
                break;
                
            case 'Thanh toán bằng thẻ ATM(OnePay)':
                // Redirect to OnePay gateway
                $this->redirectToOnePay($paymentId, $amount, $orderId, 'atm');
                break;
                
            case 'Thanh toán Momo':
                // Xử lý MoMo
                $this->redirectToMoMo($paymentId, $amount, $orderId);
                break;
                
            default:
                $this->setFlashMessage('error', 'Phương thức thanh toán không hợp lệ');
                header('Location: index.php?page=payment&order_id=' . $orderId);
                exit;
        }
    }
    
    /**
     * Redirect đến MoMo
     */
    private function redirectToMoMo($paymentId, $amount, $orderId) {
        // Lưu payment_id vào session để dùng khi callback
        Session::set('momo_payment_id', $paymentId);
        
        // Tạo order info
        $orderInfo = "Thanh toán đơn hàng #" . $orderId . " - VoxFootball";
        
        // Gọi API MoMo
        $result = $this->momoPayment->createPayment($orderId, $amount, $orderInfo);
        
        if (isset($result['payUrl']) && !empty($result['payUrl'])) {
            // Cập nhật trạng thái payment thành processing
            $this->paymentModel->updatePaymentStatus($paymentId, 'pending');
            
            // Lưu thông tin MoMo request vào database (optional)
            // Có thể thêm field momo_request_id vào bảng tbl_payment
            
            // Redirect đến trang thanh toán MoMo
            error_log("Redirecting to MoMo: " . $result['payUrl']);
            header('Location: ' . $result['payUrl']);
            exit;
        } else {
            // Lỗi khi tạo payment
            $errorMsg = isset($result['message']) ? $result['message'] : 'Không thể kết nối đến MoMo';
            error_log("MoMo error: " . $errorMsg);
            
            $this->setFlashMessage('error', 'Lỗi thanh toán MoMo: ' . $errorMsg);
            header('Location: index.php?page=payment&order_id=' . $orderId);
            exit;
        }
    }
    
    /**
     * Xử lý khi người dùng quay lại từ MoMo (Return URL)
     */
    public function momoReturn() {
        error_log("MoMo Return URL called");
        error_log("GET params: " . json_encode($_GET));
        
        // Lấy các tham số từ URL
        $partnerCode = $_GET['partnerCode'] ?? '';
        $orderId = $_GET['orderId'] ?? '';
        $requestId = $_GET['requestId'] ?? '';
        $amount = $_GET['amount'] ?? '';
        $orderInfo = $_GET['orderInfo'] ?? '';
        $orderType = $_GET['orderType'] ?? '';
        $transId = $_GET['transId'] ?? '';
        $resultCode = $_GET['resultCode'] ?? '';
        $message = $_GET['message'] ?? '';
        $payType = $_GET['payType'] ?? '';
        $responseTime = $_GET['responseTime'] ?? '';
        $extraData = $_GET['extraData'] ?? '';
        $signature = $_GET['signature'] ?? '';
        
        // Xác thực signature
        $isValid = $this->momoPayment->verifySignature($_GET);
        
        if (!$isValid) {
            error_log("MoMo signature verification failed");
            $this->setFlashMessage('error', 'Xác thực giao dịch thất bại. Vui lòng liên hệ hỗ trợ.');
            header('Location: index.php?page=cart&id=live');
            exit;
        }
        
        // Lấy payment_id từ session
        $paymentId = Session::get('momo_payment_id');
        
        if (!$paymentId) {
            // Nếu không có trong session, tìm theo order_id
            $payment = $this->paymentModel->getPaymentByOrderId($orderId);
            if ($payment) {
                $paymentId = $payment['payment_id'];
            }
        }
        
        // resultCode = 0: Thành công
        if ($resultCode == '0') {
            error_log("MoMo payment successful for order: $orderId");
            
            if ($paymentId) {
                // Cập nhật trạng thái payment
                $this->paymentModel->updatePaymentStatus($paymentId, 'completed', $transId);
                
                // Hoàn thành đơn hàng
                $this->completePayment($paymentId, $orderId);
            } else {
                error_log("Payment ID not found for order: $orderId");
                $this->setFlashMessage('error', 'Không tìm thấy thông tin thanh toán');
                header('Location: index.php?page=cart&id=live');
                exit;
            }
        } else {
            // Thanh toán thất bại
            error_log("MoMo payment failed for order: $orderId, resultCode: $resultCode");
            
            if ($paymentId) {
                $this->paymentModel->updatePaymentStatus($paymentId, 'failed', $transId);
            }
            
            $errorMessage = $this->momoPayment->getErrorMessage($resultCode);
            $this->setFlashMessage('error', 'Thanh toán thất bại: ' . $errorMessage);
            header('Location: index.php?page=payment&order_id=' . $orderId);
            exit;
        }
    }
    
    /**
     * Xử lý IPN (Instant Payment Notification) từ MoMo
     * Đây là server-to-server callback, không phụ thuộc vào người dùng
     */
    public function momoNotify() {
        error_log("MoMo IPN called");
        
        // Nhận dữ liệu JSON từ MoMo
        $jsonData = file_get_contents('php://input');
        error_log("MoMo IPN data: " . $jsonData);
        
        $data = json_decode($jsonData, true);
        
        if (!$data) {
            error_log("Invalid JSON data from MoMo IPN");
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Invalid data']);
            exit;
        }
        
        // Xác thực signature
        $isValid = $this->momoPayment->verifySignature($data);
        
        if (!$isValid) {
            error_log("MoMo IPN signature verification failed");
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Invalid signature']);
            exit;
        }
        
        $orderId = $data['orderId'] ?? '';
        $resultCode = $data['resultCode'] ?? '';
        $transId = $data['transId'] ?? '';
        
        // Lấy payment theo order_id
        $payment = $this->paymentModel->getPaymentByOrderId($orderId);
        
        if (!$payment) {
            error_log("Payment not found for order: $orderId");
            http_response_code(404);
            echo json_encode(['status' => 'error', 'message' => 'Payment not found']);
            exit;
        }
        
        $paymentId = $payment['payment_id'];
        
        // resultCode = 0: Thành công
        if ($resultCode == '0') {
            // Cập nhật trạng thái payment
            $this->paymentModel->updatePaymentStatus($paymentId, 'completed', $transId);
            
            // Xóa giỏ hàng (nếu chưa xóa)
            $sessionId = $payment['session_idA'];
            $this->paymentModel->clearCart($sessionId);
            
            error_log("MoMo IPN processed successfully for order: $orderId");
            http_response_code(200);
            echo json_encode(['status' => 'success']);
        } else {
            // Thanh toán thất bại
            $this->paymentModel->updatePaymentStatus($paymentId, 'failed', $transId);
            
            error_log("MoMo IPN payment failed for order: $orderId, resultCode: $resultCode");
            http_response_code(200);
            echo json_encode(['status' => 'failed', 'resultCode' => $resultCode]);
        }
        
        exit;
    }
    
    /**
     * Hoàn thành thanh toán
     */
    private function completePayment($paymentId, $orderId) {
        $sessionId = session_id();
        
        // Cập nhật trạng thái payment
        $this->paymentModel->updatePaymentStatus($paymentId, 'completed');
        
        // Xóa giỏ hàng
        $this->paymentModel->clearCart($sessionId);
        
        // Cập nhật session
        Session::set('SL', 0);
        Session::set('TT', 0);
        
        // Xóa current_order_id khỏi session
        unset($_SESSION['current_order_id']);
        unset($_SESSION['momo_payment_id']);
        
        $this->setFlashMessage('success', 'Thanh toán thành công!');
        header('Location: index.php?page=success&payment_id=' . $paymentId);
        exit;
    }
    
    /**
     * Hoàn thành đơn hàng COD
     */
    private function completeCODOrder($paymentId, $orderId) {
        $sessionId = session_id();
        
        // Xóa giỏ hàng
        $this->paymentModel->clearCart($sessionId);
        
        // Cập nhật session
        Session::set('SL', 0);
        Session::set('TT', 0);
        
        // Xóa current_order_id khỏi session
        unset($_SESSION['current_order_id']);
        
        $this->setFlashMessage('success', 'Đặt hàng thành công! Bạn sẽ thanh toán khi nhận hàng.');
        header('Location: index.php?page=success&payment_id=' . $paymentId);
        exit;
    }
    
    /**
     * Redirect đến OnePay (demo)
     */
    private function redirectToOnePay($paymentId, $amount, $orderId, $type) {
        switch($type) {
            case 'credit':
                $this->setFlashMessage('info', 'Chuyển hướng đến OnePay Credit...');
                break;
            case 'atm':
                $this->setFlashMessage('info', 'Chuyển hướng đến OnePay ATM...');
                break;
            default:
                $this->setFlashMessage('info', 'Chuyển hướng đến cổng thanh toán...');
                break;
        }
        
        // Cập nhật trạng thái payment thành processing
        $this->paymentModel->updatePaymentStatus($paymentId, 'processing');
        
        // Tạm thời complete luôn cho demo
        $this->completePayment($paymentId, $orderId);
    }
    
    /**
     * Callback từ payment gateway
     */
    public function paymentCallback() {
        $paymentId = $_GET['payment_id'] ?? null;
        $status = $_GET['status'] ?? 'failed';
        
        if ($paymentId && $status === 'success') {
            $payment = $this->paymentModel->getPaymentWithOrder($paymentId);
            if ($payment && $payment['order_id']) {
                $this->completePayment($paymentId, $payment['order_id']);
            }
        } else {
            $this->setFlashMessage('error', 'Thanh toán không thành công');
            header('Location: index.php?page=cart&id=live');
            exit;
        }
    }
    
    /**
     * Set flash message
     */
    private function setFlashMessage($type, $message) {
        Session::set('flash_' . $type, $message);
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