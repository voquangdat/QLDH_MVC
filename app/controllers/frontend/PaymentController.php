<?php
// app/controllers/frontend/PaymentController.php
require_once __DIR__ . '/../../models/PaymentModel.php';
require_once __DIR__ . '/../../models/OrderModel.php';

class PaymentController {
    private $paymentModel;
    private $orderModel;
    // private $momoPayment;
    
    public function __construct() {
        $this->paymentModel = new PaymentModel();
        $this->orderModel = new OrderModel();
        // $this->momoPayment = new MoMoPayment();
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
    public function execPostRequest($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data))
        );
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        //execute post
        $result = curl_exec($ch);
        //close connection
        curl_close($ch);
        return $result;
    }

    public function momoPayment($amount, $orderId)
    {
        $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";

        $partnerCode = 'MOMOBKUN20180529';
        $accessKey = 'klm05TvNBzhg7h7j';
        $secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';


        $orderInfo = "Thanh toán qua MoMo";
        // Sử dụng $amount và $orderId từ tham số hàm, không ghi đè
        // $amount = (int)$amount;
        $amount = (int)$amount; // Đảm bảo amount là số nguyên
        $redirectUrl = 'http://localhost:3000/index.php?page=payment&action=callback';
        $ipnUrl = "http://localhost:3000/index.php?page=payment&order_id=$orderId";
        $extraData = "";
        $requestId = time() . "";
        $requestType = "payWithATM";

        // Validate amount theo giới hạn MoMo (10,000 - 50,000,000 VND)
        if ($amount < 10000 || $amount > 50000000) {
            $this->setFlashMessage('error', 'Số tiền thanh toán phải từ 10.000đ đến 50.000.000đ');
            header('Location: index.php?page=payment&order_id=' . $orderId);
            exit;
        }

        $rawHash = "accessKey=" . $accessKey . "&amount=" . $amount . "&extraData=" . $extraData . "&ipnUrl=" . $ipnUrl . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&partnerCode=" . $partnerCode . "&redirectUrl=" . $redirectUrl . "&requestId=" . $requestId . "&requestType=" . $requestType;
        $signature = hash_hmac("sha256", $rawHash, $secretKey);
        $data = array('partnerCode' => $partnerCode,
            'partnerName' => "Test",
            "storeId" => "MomoTestStore",
            'requestId' => $requestId,
            'amount' => $amount,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $redirectUrl,
            'ipnUrl' => $ipnUrl,
            'lang' => 'vi',
            'extraData' => $extraData,
            'requestType' => $requestType,
            'signature' => $signature);
        $result = $this->execPostRequest($endpoint, json_encode($data));
        $jsonResult = json_decode($result, true);
        // dd($jsonResult);
        
        if (isset($jsonResult['payUrl'])) {
            header('Location: ' . $jsonResult['payUrl']);
            exit;
        } else {
            error_log("MoMo payment error: " . json_encode($jsonResult));
            $errorMessage = $jsonResult['message'] ?? 'Thanh toán MoMo không thành công';
            $this->setFlashMessage('error', 'Lỗi MoMo: ' . $errorMessage);
            header('Location: index.php?page=payment&order_id=' . $orderId);
            exit;
        }
    }       
    /**
     * Xử lý các phương thức thanh toán khác nhau
     */
    private function handlePaymentMethod($paymentMethod, $paymentId, $amount, $orderId) {
        switch ($paymentMethod) {
            case 'COD':
                // COD
                $this->paymentModel->updatePaymentStatus($paymentId, 'pending');
                $this->completeCODOrder($paymentId, $orderId);
                break;
            case 'momo':
                // set trạng thái chờ thanh toán
                // $this->paymentModel->updatePaymentStatus($paymentId, 'pending');
                
                // gọi momo
                $this->momoPayment($amount, $orderId);
                // $this->paymentModel->updatePaymentStatus($paymentId, 'pending');
                $this->completePayment($paymentId, $orderId);
                exit;
                
            default:
                $this->setFlashMessage('error', 'Phương thức thanh toán không hợp lệ');
                header('Location: index.php?page=payment&order_id=' . $orderId);
                exit;
        }
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
     * Callback từ payment gateway
     */
    public function paymentCallback() {
        // MoMo gửi về resultCode=0 là thành công
        $resultCode = $_GET['resultCode'] ?? -1;
        $orderId    = $_GET['orderId'] ?? null;   // đây là order_id của bạn

        if ($resultCode == 0 && $orderId) {
            // Tìm payment record theo order_id
            $payment = $this->paymentModel->getPaymentByOrderId($orderId);

            if ($payment) {
                $this->completePayment($payment['payment_id'], $orderId);
            } else {
                $this->setFlashMessage('error', 'Không tìm thấy thông tin thanh toán');
                header('Location: index.php?page=payment&order_id=' . $orderId);
                exit;
            }
        } else {
            $message = $_GET['message'] ?? 'Thanh toán không thành công';
            $this->setFlashMessage('error', 'MoMo: ' . $message);
            header('Location: index.php?page=payment&order_id=' . $orderId);
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