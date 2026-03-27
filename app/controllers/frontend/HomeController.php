<?php
//filepath: e:\QLDH_MVC\app\controllers\frontend\HomeController.php
require_once __DIR__ . '/../../models/ProductModel.php';
require_once __DIR__ . '/../../models/HeaderModel.php';
require_once __DIR__ . '/../../models/CategoryModel.php';

class HomeController {
    private $productModel;
    private $headerModel;
    private $categoryModel;

    public function __construct() {
        $this->productModel = new ProductModel();
        $this->headerModel = new HeaderModel();
        $this->categoryModel = new CategoryModel();
    }
    
    /**
     * Hiển thị trang chủ
     */
    public function index() {
        // Lấy dữ liệu từ models
        $data = [
            'featuredProducts' => $this->productModel->getFeaturedProducts(8),
            'pageTitle' => 'Trang chủ - VoxFootball',
            'metaDescription' => 'Chuyên cung cấp áo bóng đá chính hãng với giá tốt nhất'
        ];
        
        // Load view với dữ liệu
        $this->loadView('home', $data);
    }
    
    /**
     * AJAX: Thêm sản phẩm vào giỏ hàng
     */
    public function addToCart() {
        // Set header JSON ngay từ đầu
        header('Content-Type: application/json');
        
        // Kiểm tra phương thức request
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            exit();
        }
        
        // Lấy và validate dữ liệu đầu vào
        $productId = $_POST['product_id'] ?? 0;
        $quantity = $_POST['quantity'] ?? 1;
        $size = $_POST['size'] ?? 'M';
        
        // Kiểm tra thông tin sản phẩm
        if (!$productId) {
            echo json_encode(['success' => false, 'message' => 'Thiếu thông tin sản phẩm']);
            exit();
        }
        
        // Kiểm tra quantity hợp lệ
        if ($quantity <= 0) {
            echo json_encode(['success' => false, 'message' => 'Số lượng không hợp lệ']);
            exit();
        }
        
        // Gọi method xử lý logic thêm vào giỏ hàng
        $result = $this->addProductToCart($productId, $quantity, $size);
        
        // Trả về kết quả JSON
        echo json_encode($result);
        exit();
    }
    
    /**
     * Xử lý logic thêm sản phẩm vào giỏ hàng
     * @param int $productId ID sản phẩm
     * @param int $quantity Số lượng
     * @param string $size Kích thước
     * @return array Kết quả xử lý
     */
    private function addProductToCart($productId, $quantity, $size) {
        try {
            // Khởi tạo session cart nếu chưa có
            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }
            
            // Tạo key duy nhất cho sản phẩm (product_id + size)
            $cartKey = $productId . '_' . $size;
            
            // Kiểm tra sản phẩm đã có trong giỏ hàng chưa
            if (isset($_SESSION['cart'][$cartKey])) {
                // Cập nhật số lượng nếu đã có
                $_SESSION['cart'][$cartKey]['quantity'] += $quantity;
                $_SESSION['cart'][$cartKey]['updated_time'] = time();
            } else {
                // Thêm sản phẩm mới vào giỏ hàng
                $_SESSION['cart'][$cartKey] = [
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'size' => $size,
                    'added_time' => time(),
                    'updated_time' => time()
                ];
            }
            
            // Tính tổng số lượng sản phẩm trong giỏ hàng
            $totalQuantity = array_sum(array_column($_SESSION['cart'], 'quantity'));
            
            // Trả về kết quả thành công
            return [
                'success' => true,
                'message' => 'Đã thêm vào giỏ hàng thành công',
                'cart_count' => $totalQuantity,
                'cart_items' => count($_SESSION['cart'])
            ];
            
        } catch (Exception $e) {
            // Xử lý lỗi nếu có
            return [
                'success' => false,
                'message' => 'Có lỗi xảy ra khi thêm vào giỏ hàng: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Load view với layout
     * @param string $view Tên view cần load
     * @param array $data Dữ liệu truyền vào view
     */
    private function loadView($view, $data = []) {
        // Extract data để sử dụng trong view
        extract($data);
        
        // Include các phần của layout theo thứ tự
        include APP_PATH . '/views/layouts/header.php';
        include APP_PATH . '/views/layouts/slider.php';
        include APP_PATH . '/views/frontend_views/' . $view . '.php';
        include APP_PATH . '/views/layouts/footer.php';
    }
    
    /**
     * Lấy thông tin giỏ hàng (AJAX)
     */
    public function getCartInfo() {
        header('Content-Type: application/json');
        
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        
        $totalQuantity = array_sum(array_column($_SESSION['cart'], 'quantity'));
        $totalItems = count($_SESSION['cart']);
        
        echo json_encode([
            'success' => true,
            'cart_count' => $totalQuantity,
            'cart_items' => $totalItems
        ]);
        exit();
    }
}
?>