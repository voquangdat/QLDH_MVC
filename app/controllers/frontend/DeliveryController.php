<?php
// app/controllers/frontend/DeliveryController.php
require_once __DIR__ . '/../../models/OrderModel.php';
require_once __DIR__ . '/../../models/CartModel.php';
require_once __DIR__ . '/../../models/AuthModel.php';


class DeliveryController {
    private $orderModel;
    private $cartModel;
    private $authModel;

    public function __construct() {
        $this->orderModel = new OrderModel();
        $this->cartModel = new CartModel();
        $this->authModel = new AuthModel();
    }
    
    /**
     * Hiển thị trang giao hàng
     */
    public function index() {
        $sessionId = session_id();
        
        // Kiểm tra giỏ hàng có sản phẩm không
        $cartItems = $this->orderModel->getCartItems($sessionId);
        
        if (!$cartItems || $cartItems->num_rows == 0) {
            // Redirect về giỏ hàng nếu trống
            header('Location: index.php?page=cart&id=live');
            exit;
        }
        
        // Lấy danh sách tỉnh/thành phố
        $provinces = $this->orderModel->getProvinces();
        
        // Lấy danh sách quận/huyện
        // $districts = $this->orderModel->getDistricts();

        // Lấy danh sách phường/xã
        // $wards = $this->orderModel->getWards();

        // Tính tổng tiền
        $cartTotal = $this->cartModel->getCartTotal($sessionId);
        
        $data = [
            'cartItems' => $cartItems,
            'provinces' => $provinces,
            // 'districts' => $districts,
            // 'wards' => $wards,
            'cartTotal' => $cartTotal,
            'pageTitle' => 'Thông tin giao hàng - VoxFootball',
            'pageClass' => 'delivery-page'
        ];
        
        $this->loadView('delivery', $data);
    }
    
    /**
 * Xử lý đặt hàng - redirect đến payment
 */
    public function createOrder() {
        error_log("=== DELIVERY CREATE ORDER START ===");
        error_log("POST data: " . print_r($_POST, true));
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            error_log("Not POST method");
            header('Location: index.php?page=delivery');
            exit;
        }
        
        $sessionId = session_id();
        error_log("Session ID: " . $sessionId);
        
        // Validate input
        $required_fields = ['loaikhach', 'customer_name', 'customer_phone', 'customer_tinh', 'customer_huyen', 'customer_xa', 'customer_diachi'];
        
        foreach ($required_fields as $field) {
            if (empty($_POST[$field])) {
                $this->setFlashMessage('error', 'Vui lòng điền đầy đủ thông tin');
                header('Location: index.php?page=delivery');
                exit;
            }
        }
        
        $customerType = $_POST['loaikhach'];
        $customerName = $_POST['customer_name'];
        $customerPhone = $_POST['customer_phone'];
        $customerProvince = $_POST['customer_tinh'];
        $customerDistrict = $_POST['customer_huyen'];
        $customerWard = $_POST['customer_xa'];
        $customerAddress = $_POST['customer_diachi'];
        
        // Nếu là đăng ký mới, validate thêm password
        if ($customerType === 'dangky') {
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            
            if (empty($password) || empty($confirmPassword)) {
                $this->setFlashMessage('error', 'Vui lòng nhập mật khẩu');
                header('Location: index.php?page=delivery');
                exit;
            }
            
            if ($password !== $confirmPassword) {
                $this->setFlashMessage('error', 'Mật khẩu xác nhận không khớp');
                header('Location: index.php?page=delivery');
                exit;
            }
            
            // Tạo customer mới
            $customerId = $this->createCustomerAccount($customerName, $customerPhone, $password, $customerAddress);
        } else {
            $customerId = Session::get('customer_id'); // Null nếu khách lẻ
        }
        
        try {
            // Tạo đơn hàng
            $result = $this->orderModel->createOrder(
                $sessionId, $customerType, $customerName, $customerPhone,
                $customerProvince, $customerDistrict, $customerWard, $customerAddress,
                $customerId
            );
            
            if ($result['success']) {
                // LƯU order_id vào session để sử dụng ở payment
                Session::set('current_order_id', $result['order_id']);
                
                $this->setFlashMessage('success', 'Thông tin giao hàng đã được lưu. Vui lòng chọn phương thức thanh toán.');
                
                // REDIRECT ĐÉN PAYMENT để chọn phương thức thanh toán
                header('Location: index.php?page=payment&order_id=' . $result['order_id']);
                exit;
            } else {
                $this->setFlashMessage('error', $result['message']);
                header('Location: index.php?page=delivery');
                exit;
            }
            
        } catch (Exception $e) {
            error_log("Error in createOrder: " . $e->getMessage());
            $this->setFlashMessage('error', 'Có lỗi xảy ra khi đặt hàng');
            header('Location: index.php?page=delivery');
            exit;
        }
    }
    
    /**
     * Tạo tài khoản customer mới (cho option đăng ký)
     */
    private function createCustomerAccount($name, $phone, $password, $address) {
        try {
            // Tạo email tạm thời từ phone
            $tempEmail = $phone . '@gmail.com';
            
            $authModel = new AuthModel();
            $result = $authModel->registerCustomer($phone, $tempEmail, $password, $name, $phone);
            
            if ($result['success']) {
                // Lấy customer_id vừa tạo
                $customerQuery = "SELECT customer_id FROM tbl_customers WHERE phone = '$phone' ORDER BY customer_id DESC LIMIT 1";
                $db = new Database();
                $customerResult = $db->select($customerQuery);
                
                if ($customerResult && $customerResult->num_rows > 0) {
                    $customer = $customerResult->fetch_assoc();
                    
                    // Cập nhật thêm thông tin địa chỉ
                    $updateQuery = "UPDATE tbl_customers SET address = '$address' WHERE customer_id = " . $customer['customer_id'];
                    $db->update($updateQuery);
                    
                    return $customer['customer_id'];
                }
            } else {
                error_log("Failed to create customer account: " . $result['message']);
            }
        } catch (Exception $e) {
            error_log("Error in createCustomerAccount: " . $e->getMessage());
        }
        
        return null;
    }
    
    /**
 * AJAX - Lấy danh sách quận/huyện theo tỉnh
 */
public function getDistricts() {
    // Không set JSON header vì trả về HTML options
    
    $provinceCode = $_GET['tinh_id'] ?? '';
    
    error_log("getDistricts called with province code: " . $provinceCode);
    
    if (empty($provinceCode) || $provinceCode === '#') {
        echo '<option value="">Chọn Quận/Huyện</option>';
        exit;
    }
    
    try {
        $districts = $this->orderModel->getDistricts($provinceCode);
        $options = '<option value="">Chọn Quận/Huyện</option>';
        
        if ($districts && $districts->num_rows > 0) {
            while ($district = $districts->fetch_assoc()) {
                $options .= '<option value="' . htmlspecialchars($district['ma_qh']) . '">' . 
                           htmlspecialchars($district['quan_huyen']) . '</option>';
            }
            error_log("Found " . $districts->num_rows . " districts");
        } else {
            error_log("No districts found for province: " . $provinceCode);
        }
        
        echo $options;
        
    } catch (Exception $e) {
        error_log("Error in getDistricts: " . $e->getMessage());
        echo '<option value="">Lỗi tải dữ liệu</option>';
    }
    
    exit;
}

/**
 * AJAX - Lấy danh sách phường/xã theo quận/huyện
 */
public function getWards() {
    $districtCode = $_GET['quan_huyen_id'] ?? '';
    
    error_log("getWards called with district code: " . $districtCode);
    
    if (empty($districtCode) || $districtCode === '#') {
        echo '<option value="">Chọn Phường/Xã</option>';
        exit;
    }
    
    try {
        $wards = $this->orderModel->getWards($districtCode);
        $options = '<option value="">Chọn Phường/Xã</option>';
        
        if ($wards && $wards->num_rows > 0) {
            while ($ward = $wards->fetch_assoc()) {
                $options .= '<option value="' . htmlspecialchars($ward['ma_px']) . '">' . 
                           htmlspecialchars($ward['phuong_xa']) . '</option>';
            }
            error_log("Found " . $wards->num_rows . " wards");
        } else {
            error_log("No wards found for district: " . $districtCode);
        }
        
        echo $options;
        
    } catch (Exception $e) {
        error_log("Error in getWards: " . $e->getMessage());
        echo '<option value="">Lỗi tải dữ liệu</option>';
    }
    
    exit;
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