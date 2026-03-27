<?php
// Khởi tạo ứng dụng
session_start();

// Khởi tạo Session class
require_once __DIR__ . '/config/session.php';
Session::init();

// Định nghĩa các constants cho đường dẫn
define('ROOT_PATH', __DIR__);
define('APP_PATH', ROOT_PATH . '/app');
define('CONFIG_PATH', ROOT_PATH . '/config');
define('PUBLIC_PATH', ROOT_PATH . '/public');

// Set error reporting cho development
error_reporting(E_ALL); 
ini_set('display_errors', 1);

// Autoloader đơn giản
spl_autoload_register(function ($className) {
    $paths = [
        APP_PATH . '/controllers/frontend/',
        APP_PATH . '/controllers/admin/',
        APP_PATH . '/models/',
        APP_PATH . '/core/',
        CONFIG_PATH . '/'
    ];
    
    foreach ($paths as $path) {
        $file = $path . $className . '.php';
        if (file_exists($file)) {
            require_once $file;
            return true;
        }
    }
    return false;
});

// Manually include config files that aren't classes
// require_once CONFIG_PATH . '/session.php';
// require_once CONFIG_PATH . '/format.php';

// Router đơn giản
$page = isset($_GET['page']) ? $_GET['page'] : 'home';
$action = isset($_GET['action']) ? $_GET['action'] : 'index';

try {
    switch ($page) {
        case 'home':
        case 'index':
            $controller = new HomeController();
            // Xử lý các action
            if ($action === 'addToCart') {
                $controller->addToCart();
            } else {
                $controller->index();
            }
            break;

        case 'auth':
            // Authentication routes - THÊM CASE NÀY
            $controller = new AuthController();
            if ($action === 'login') {
                $controller->login();
            } elseif ($action === 'register') {
                $controller->register();
            } elseif ($action === 'logout') {
                $controller->logout();
            }
            break;
            
        case 'login':
            // Hiển thị trang đăng nhập
            $controller = new AuthController();
            $controller->showLogin();
            break;
            
        case 'register':
        case 'signup':
            // Hiển thị trang đăng ký
            $controller = new AuthController();
            $controller->showRegister();
            break;

        case 'category':
            // Trang danh mục
            $controller = new CategoryController();
            $controller->index();
            break;
            
        case 'product':
            // Trang chi tiết sản phẩm
            $controller = new ProductController();
            if ($action === 'addToCart') {
                $controller->addToCart();
            } else {
                $controller->show();
            }
            break;
            
        case 'customer':
            // Customer functions
            $controller = new CustomerController();
            if ($action === 'profile') {
                $controller->profile();
            } elseif ($action === 'updateProfile') {
                $controller->updateProfile();
            } elseif ($action === 'changePassword') {
                $controller->changePassword();
            } elseif ($action === 'orders') {
                $controller->orders();
            } elseif ($action === 'orderDetail') {
                $controller->orderDetail();
            } elseif ($action === 'cancelOrder') {
                $controller->cancelOrder();
            }
            break;
            
        case 'profile':
            // Redirect to customer profile
            $controller = new CustomerController();
            $controller->profile();
            break;
            
        case 'orders':
            // Redirect to customer orders
            $controller = new CustomerController();
            $controller->orders();
            break;
            
        case 'cart':
            // Trang giỏ hàng
            $controller = new CartController();
            if ($action === 'remove') {
                $controller->removeItem();
            } elseif ($action === 'add') {
                $controller->addToCart();
            } elseif ($action === 'update') {
                $controller->updateQuantity();
            } else {
                $controller->index();
            }
            break;
        case 'delivery':
            $controller = new DeliveryController();
            if ($action === 'create') {
                $controller->createOrder();
            } elseif ($action === 'districts') {
                $controller->getDistricts();
            } elseif ($action === 'wards') {
                $controller->getWards();
            } else {
                $controller->index();
            }
            break;
        case 'contact':
            // Trang liên hệ
            require_once APP_PATH . '/views/layouts/header.php';
            require_once APP_PATH . '/views/frontend_views/lienhe.php';
            require_once APP_PATH . '/views/layouts/footer.php';
            break;
            
        case 'news':
            // Trang tin tức
            require_once APP_PATH . '/views/layouts/header.php';
            require_once APP_PATH . '/views/frontend_views/news.php';
            require_once APP_PATH . '/views/layouts/footer.php';
            break;
            
        case 'payment':
            $controller = new PaymentController();
            $controller->index(); // Let PaymentController handle all actions internally
            break;
            
        case 'success':
            $controller = new SuccessController();
            $controller->index();
            break;
            
        case 'order-detail':
        case 'detaill':
            $controller = new OrderDetailController();
            $controller->index();
            break;
            
        case 'admin':
            require_once APP_PATH . '/controllers/admin/AdminAuthController.php';
            $controller = new AdminAuthController();
            $adminAction = $_GET['action'] ?? 'showLogin';
            
            switch ($adminAction) {
                case 'login':
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $controller->login();
                    } else {
                        $controller->showLogin();
                    }
                    break;
                case 'dashboard':
                    $controller->dashboard();
                    break;
                case 'logout':
                    $controller->logout();
                    break;
                default:
                    $controller->showLogin();
                    break;
            }
            break;
            
        case 'admin_product':
            require_once APP_PATH . '/controllers/admin/ProductManagementController.php';
            $controller = new ProductManagementController();
            $section = $_GET['section'] ?? 'index';
            $action = $_GET['action'] ?? 'list';
            
            switch ($section) {
                case 'categories':
                    $controller->categories();
                    break;
                case 'product_types':
                    $controller->productTypes();
                    break;
                case 'products':
                    $controller->products();
                    break;
                case 'product_images':
                    $controller->productImages();
                    break;
                case 'product_sizes':
                    $controller->productSizes();
                    break;
                case 'product_colors':
                    $controller->productColors();
                    break;
                case 'ajax':
                    $controller->ajax();
                    break;
                default:
                    $controller->index();
                    break;
            }
            break;
            
            case 'admin_inventory':
            require_once APP_PATH . '/controllers/admin/InventoryController.php';
            $controller = new InventoryController();
            // Xử lý AJAX requests
            if (isset($_GET['ajax_action'])) {
                $controller->ajax();
                exit;
            }
            $controller->index();
            break;
        case 'admin_order':
            require_once APP_PATH . '/controllers/admin/OrderManagementController.php';
            $controller = new OrderManagementController();
            $controller->index(); // Gọi method index như InventoryController
            exit;
            
        case 'admin_reports':
            require_once APP_PATH . '/controllers/admin/ReportsController.php';
            $controller = new ReportsController();
            $controller->index();
            break;
            
        default:
            // Trang không tồn tại
            http_response_code(404);
            require_once APP_PATH . '/views/layouts/header.php';
            echo '<div class="container"><h1>404 - Trang không tồn tại</h1></div>';
            require_once APP_PATH . '/views/layouts/footer.php';
            break;
    }
    
} catch (Exception $e) {
    // Xử lý lỗi
    http_response_code(500);
    echo '<h1>Đã xảy ra lỗi</h1>';
    echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
    
    // Log lỗi trong môi trường production
    error_log('Error in index.php: ' . $e->getMessage() . ' - Stack trace: ' . $e->getTraceAsString());
}
?>