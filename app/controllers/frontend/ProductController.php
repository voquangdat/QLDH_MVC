<?php
require_once __DIR__ . '/../../models/CategoryModel.php';
require_once __DIR__ . '/../../models/ProductModel.php';
require_once __DIR__ . '/../../models/CartModel.php';

class ProductController {
    private $productModel;
    private $categoryModel;
    private $cartModel;

    public function __construct() {
        $this->productModel = new ProductModel();
        $this->categoryModel = new CategoryModel();
        $this->cartModel = new CartModel();
    }

    // Các phương thức xử lý yêu cầu liên quan đến sản phẩm
   public function show() {
        $sanpham_id = isset($_GET['sanpham_id']) ? $_GET['sanpham_id'] : null;
        
        if (!$sanpham_id) {
            header('Location: index.php');
            exit;
        }
        
        // Lấy thông tin sản phẩm
        $productData = $this->productModel->getProductById($sanpham_id);
        
        if (!$productData) {
            header('Location: index.php');
            exit;
        }
        
        // Lấy hình ảnh sản phẩm
        $productImages = $this->productModel->getProductImages($sanpham_id);
        
        // Lấy size sản phẩm
        // $productSizes = $this->productModel->getProductSizes($sanpham_id);
        // Lấy biến thể + tồn kho (thay vì bảng size độc lập)
        $variantsRS = $this->productModel->getProductVariantsWithStock($sanpham_id);
        $variants = [];
        if ($variantsRS) {
            while ($row = $variantsRS->fetch_assoc()) {
                $variants[] = $row;
            }
        }
        
        // Lấy sản phẩm liên quan
        $relatedProducts = $this->productModel->getRelatedProducts(
            $productData['loaisanpham_id'], 
            $sanpham_id
        );

        // Prepare data for view
        $data = [
            'productData' => $productData,
            'productImages' => $productImages,
            // 'productSizes' => $productSizes,
            'variants' => $variants,
            'relatedProducts' => $relatedProducts,
            'sanpham_id' => $sanpham_id
        ];
        
        // Load view
        $this->loadView('product', $data);
    }

    
    

    // Lấy dữ liệu cho breadcrumb
    public function getBreadcrumb($sanpham_id) {
        $product = $this->productModel->getProductById($sanpham_id);
        return $product ? $product->fetch_assoc() : null;
    }

    // AJAX: thêm vào giỏ (đã cập nhật ở các bước trước)
    public function addToCart() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Phương thức không hợp lệ']); return;
        }

        $session_id     = $_POST['session_id'] ?? session_id();
        $sanpham_id     = (int)($_POST['sanpham_id'] ?? 0);
        $bienthe_id     = (int)($_POST['bienthe_id'] ?? 0);
        $quantitys      = max(1, (int)($_POST['quantitys'] ?? 1));
        $sanpham_tieude = $_POST['sanpham_tieude'] ?? '';
        $sanpham_anh    = $_POST['sanpham_anh'] ?? '';
        $sanpham_gia    = (float)($_POST['sanpham_gia'] ?? 0);

        if (!$sanpham_id || !$bienthe_id) {
            echo json_encode(['success' => false, 'message' => 'Thiếu thông tin biến thể']); return;
        }

        $res = $this->cartModel->addToCart($session_id, $sanpham_id, $bienthe_id, $quantitys, $sanpham_tieude, $sanpham_anh, $sanpham_gia);

        if (!$res['success']) {
            $msg = $res['error'] ?? 'Không thể thêm vào giỏ';
            $payload = ['success' => false, 'message' => $msg];
            if (isset($res['max'])) $payload['max'] = $res['max'];
            echo json_encode($payload); return;
        }

        $cartQty   = $this->cartModel->getTotalQuantity($session_id);
        $cartTotal = $this->cartModel->getCartTotal($session_id);
        \Session::set('SL', $cartQty);

        echo json_encode([
            'success'    => true,
            'message'    => 'Đã thêm vào giỏ hàng thành công',
            'cart_qty'   => $cartQty,
            'cart_total' => $cartTotal
        ]);
    }

    private function validateProductId() {
        if (!isset($_GET['sanpham_id']) || empty($_GET['sanpham_id'])) {
            return false;
        }
         $sanpham_id = filter_var($_GET['sanpham_id'], FILTER_VALIDATE_INT);
        return $sanpham_id && $sanpham_id > 0 ? $sanpham_id : false;
    }

    private function loadView($view, $data = []) {
        extract($data);
        // Include header (sử dụng APP_PATH như HomeController)
        include APP_PATH . '/views/layouts/header.php';
        
        // Include left sidebar for category page (thay vì slider)
        // include APP_PATH . '/views/frontend_views/leftside.php';
        
        // Include main content
        include APP_PATH . '/views/frontend_views/' . $view . '.php';

        // Include footer (cùng footer với home)
        include APP_PATH . '/views/layouts/footer.php';
    }
}