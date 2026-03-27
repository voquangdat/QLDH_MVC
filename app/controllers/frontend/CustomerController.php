<?php
// app/controllers/frontend/CustomerController.php
require_once __DIR__ . '/../../models/CustomerModel.php';
require_once __DIR__ . '/../../models/OrderModel.php';

class CustomerController {
    private $customerModel;
    private $orderModel;
    
    public function __construct() {
        $this->customerModel = new CustomerModel();
        $this->orderModel = new OrderModel();
    }
    
    /**
     * Kiểm tra xem customer đã đăng nhập chưa
     */
    private function requireLogin() {
        if (!Session::get('customer_id')) {
            $_SESSION['error'] = 'Vui lòng đăng nhập để truy cập trang này';
            header('Location: index.php?page=login');
            exit;
        }
    }
    
    /**
     * Hiển thị trang thông tin cá nhân
     */
    public function profile() {
        $this->requireLogin();
        
        $customerId = Session::get('customer_id');
        $customer = $this->customerModel->getCustomerById($customerId);
        
        if (!$customer) {
            $_SESSION['error'] = 'Không tìm thấy thông tin khách hàng';
            header('Location: index.php');
            exit;
        }
        
        $data = [
            'pageTitle' => 'Thông tin cá nhân - VoxFootball',
            'customer' => $customer
        ];
        
        $this->loadView('profile', $data);
    }
    
    /**
     * Cập nhật thông tin cá nhân
     */
    public function updateProfile() {
        ob_clean();
        header('Content-Type: application/json');
        
        $this->requireLogin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            exit;
        }
        
        $customerId = Session::get('customer_id');
        
        // Validate input
        $fullName = trim($_POST['full_name'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $city = trim($_POST['city'] ?? '');
        $district = trim($_POST['district'] ?? '');
        $ward = trim($_POST['ward'] ?? '');
        $birthDate = trim($_POST['birth_date'] ?? '');
        $gender = trim($_POST['gender'] ?? '');
        
        if (empty($fullName)) {
            echo json_encode(['success' => false, 'message' => 'Vui lòng nhập họ tên']);
            exit;
        }
        
        $updateData = [
            'full_name' => $fullName,
            'phone' => $phone,
            'address' => $address,
            'city' => $city,
            'district' => $district,
            'ward' => $ward,
            'birth_date' => $birthDate ?: null,
            'gender' => $gender ?: null
        ];
        
        try {
            $result = $this->customerModel->updateCustomer($customerId, $updateData);
            
            if ($result) {
                // Cập nhật session nếu cần
                Session::set('customer_name', $fullName);
                
                echo json_encode([
                    'success' => true, 
                    'message' => 'Cập nhật thông tin thành công'
                ]);
            } else {
                echo json_encode([
                    'success' => false, 
                    'message' => 'Có lỗi xảy ra khi cập nhật thông tin'
                ]);
            }
        } catch (Exception $e) {
            error_log("Update profile error: " . $e->getMessage());
            echo json_encode([
                'success' => false, 
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ]);
        }
        
        exit;
    }
    
    /**
     * Đổi mật khẩu
     */
    public function changePassword() {
        ob_clean();
        header('Content-Type: application/json');
        
        $this->requireLogin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            exit;
        }
        
        $customerId = Session::get('customer_id');
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        
        // Validate input
        if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
            echo json_encode(['success' => false, 'message' => 'Vui lòng nhập đầy đủ thông tin']);
            exit;
        }
        
        if ($newPassword !== $confirmPassword) {
            echo json_encode(['success' => false, 'message' => 'Mật khẩu mới không khớp']);
            exit;
        }
        
        if (strlen($newPassword) < 6) {
            echo json_encode(['success' => false, 'message' => 'Mật khẩu mới phải có ít nhất 6 ký tự']);
            exit;
        }
        
        try {
            // Kiểm tra mật khẩu hiện tại
            $customer = $this->customerModel->getCustomerById($customerId);
            if (!$customer || !password_verify($currentPassword, $customer['password_hash'])) {
                echo json_encode(['success' => false, 'message' => 'Mật khẩu hiện tại không đúng']);
                exit;
            }
            
            // Cập nhật mật khẩu mới
            $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
            $result = $this->customerModel->updatePassword($customerId, $hashedPassword);
            
            if ($result) {
                echo json_encode([
                    'success' => true, 
                    'message' => 'Đổi mật khẩu thành công'
                ]);
            } else {
                echo json_encode([
                    'success' => false, 
                    'message' => 'Có lỗi xảy ra khi đổi mật khẩu'
                ]);
            }
        } catch (Exception $e) {
            error_log("Change password error: " . $e->getMessage());
            echo json_encode([
                'success' => false, 
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ]);
        }
        
        exit;
    }
    
    /**
     * Hiển thị danh sách đơn hàng của khách hàng
     */
    public function orders() {
        $this->requireLogin();
        
        $customerId = Session::get('customer_id');
        $page = isset($_GET['p']) ? (int)$_GET['p'] : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;
        
        // Lấy danh sách đơn hàng
        $orders = $this->orderModel->getOrdersByCustomerId($customerId, $limit, $offset);
        $totalOrders = $this->orderModel->getTotalOrdersByCustomerId($customerId);
        $totalPages = ceil($totalOrders / $limit);
        
        $data = [
            'pageTitle' => 'Đơn hàng của tôi - VoxFootball',
            'orders' => $orders,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalOrders' => $totalOrders
        ];
        
        $this->loadView('orders', $data);
    }
    
    /**
     * Xem chi tiết đơn hàng
     */
    public function orderDetail() {
        $this->requireLogin();
        
        $orderId = $_GET['order_id'] ?? 0;
        $customerId = Session::get('customer_id');
        
        if (!$orderId) {
            $_SESSION['error'] = 'Không tìm thấy đơn hàng';
            header('Location: index.php?page=orders');
            exit;
        }
        
        // Lấy thông tin đơn hàng và kiểm tra quyền sở hữu
        $order = $this->orderModel->getOrderWithAddressNames($orderId);
        
        if (!$order || $order['customer_id'] != $customerId) {
            $_SESSION['error'] = 'Không có quyền truy cập đơn hàng này';
            header('Location: index.php?page=orders');
            exit;
        }
        
        // Lấy chi tiết đơn hàng
        $orderDetails = $this->orderModel->getOrderDetailsForCustomer($orderId);
        
        $data = [
            'pageTitle' => 'Chi tiết đơn hàng #' . $orderId . ' - VoxFootball',
            'order' => $order,
            'orderDetails' => $orderDetails
        ];
        
        $this->loadView('order-detail', $data);
    }
    
    /**
     * Hủy đơn hàng
     */
    public function cancelOrder() {
        ob_clean();
        header('Content-Type: application/json');
        
        $this->requireLogin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            exit;
        }
        
        $orderId = $_POST['order_id'] ?? 0;
        $customerId = Session::get('customer_id');
        $cancelReason = trim($_POST['cancel_reason'] ?? '');
        
        if (!$orderId) {
            echo json_encode(['success' => false, 'message' => 'Không tìm thấy đơn hàng']);
            exit;
        }
        
        try {
            // Lấy thông tin đơn hàng và kiểm tra quyền sở hữu
            $order = $this->orderModel->getOrderById($orderId);
            
            if (!$order || $order['customer_id'] != $customerId) {
                echo json_encode(['success' => false, 'message' => 'Không có quyền hủy đơn hàng này']);
                exit;
            }
            
            // Kiểm tra trạng thái đơn hàng (chỉ cho phép hủy khi đang pending hoặc confirmed)
            if (!in_array($order['order_status'], ['pending', 'confirmed'])) {
                echo json_encode([
                    'success' => false, 
                    'message' => 'Không thể hủy đơn hàng ở trạng thái hiện tại'
                ]);
                exit;
            }
            
            // Thực hiện hủy đơn hàng
            $result = $this->orderModel->cancelOrderByCustomer($orderId, $cancelReason);
            
            if ($result) {
                echo json_encode([
                    'success' => true, 
                    'message' => 'Hủy đơn hàng thành công'
                ]);
            } else {
                echo json_encode([
                    'success' => false, 
                    'message' => 'Có lỗi xảy ra khi hủy đơn hàng'
                ]);
            }
        } catch (Exception $e) {
            error_log("Cancel order error: " . $e->getMessage());
            echo json_encode([
                'success' => false, 
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ]);
        }
        
        exit;
    }
    
    /**
     * Load view với layout
     */
    private function loadView($view, $data = []) {
        extract($data);
        include __DIR__ . '/../../views/layouts/header.php';
        include __DIR__ . '/../../views/frontend_views/customer/' . $view . '.php';
        include __DIR__ . '/../../views/layouts/footer.php';
    }
}