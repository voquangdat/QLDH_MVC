<?php
require_once __DIR__ . '/../../models/CategoryModel.php';
require_once __DIR__ . '/../../models/ProductModel.php';
// require_once __DIR__ . '/../../models/HeaderModel.php';

class CategoryController {
    private $categoryModel;
    private $productModel;
    // private $headerModel;

    public function __construct() {
        $this->categoryModel = new CategoryModel();
        $this->productModel = new ProductModel();
        // $this->headerModel = new HeaderModel();
    }
    
    public function index() {
        // Lấy loaisanpham_id từ URL
        $loaisanpham_id = isset($_GET['loaisanpham_id']) ? $_GET['loaisanpham_id'] : null;
        
        if (!$loaisanpham_id) {
            // Redirect hoặc hiển thị lỗi
            header('Location: index.php');
            exit;
        }
        
        // Lấy thông tin danh mục
        $categoryInfo = $this->categoryModel->getCategoryInfo($loaisanpham_id);
        $categoryData = $categoryInfo ? $categoryInfo->fetch_assoc() : null;

        $orderBy = $this->getOrderBy($_GET['sort'] ?? '');
        $products = $this->categoryModel->getProductsByType($loaisanpham_id, $orderBy);
        
        // Lấy sản phẩm theo loại
        $orderBy = 'sanpham_id DESC'; // Mặc định
        if (isset($_GET['sort'])) {
            switch ($_GET['sort']) {
                case 'price_high':
                    $orderBy = 'sanpham_gia DESC';
                    break;
                case 'price_low':
                    $orderBy = 'sanpham_gia ASC';
                    break;
                default:
                    $orderBy = 'sanpham_id DESC';
            }
        }
        
        $products = $this->productModel->getProductsByCategory($loaisanpham_id, $orderBy);
        
        // Truyền dữ liệu đến view
        // $this->loadView('cartegory', [
        //     'categoryData' => $categoryData,
        //     'products' => $products,
        //     'loaisanpham_id' => $loaisanpham_id
        // ]);
        $data=[
            'categoryData' => $categoryData,
            'products' => $products,
            'loaisanpham_id' => $loaisanpham_id
        ];
        $this->loadView('cartegory', $data);
    }

    private function getOrderBy($sort) {
    switch ($sort) {
        case 'price_high': return 'sanpham_gia DESC';
        case 'price_low': return 'sanpham_gia ASC';
        default: return 'sanpham_id DESC';
    }
    }
    
    public function getMenuData() {
        return $this->categoryModel->getAllCategories();
    }
    
    public function getSubCategories($danhmuc_id) {
        return $this->categoryModel->getSubCategories($danhmuc_id);
    }
    
    /**
     * Lấy dữ liệu sidebar (danh mục + breadcrumb) - LOGIC MOVE TỪ VIEW
     */
    public function getSidebarData() {
        // Lấy danh mục chính
        $categories = $this->categoryModel->getAllCategories();
        
        // Lấy breadcrumb info từ loaisanpham_id (nếu có)
        $loaisanpham_id = isset($_GET['loaisanpham_id']) ? $_GET['loaisanpham_id'] : null;
        $breadcrumbInfo = null;
        
        if ($loaisanpham_id) {
            $result = $this->categoryModel->getCategoryInfo($loaisanpham_id);
            $breadcrumbInfo = $result ? $result->fetch_assoc() : null;
        }
        
        // Lấy tất cả sub-categories cùng với danh mục chính của chúng
        $categoriesWithSubs = [];
        if ($categories && $categories->num_rows > 0) {
            while ($category = $categories->fetch_assoc()) {
                $danhmuc_id = $category['danhmuc_id'];
                $subCategories = $this->categoryModel->getSubCategories($danhmuc_id);
                
                $category['subCategories'] = [];
                if ($subCategories && $subCategories->num_rows > 0) {
                    while ($subCat = $subCategories->fetch_assoc()) {
                        $category['subCategories'][] = $subCat;
                    }
                }
                
                $categoriesWithSubs[] = $category;
            }
        }
        
        return [
            'categories' => $categoriesWithSubs,
            'breadcrumbInfo' => $breadcrumbInfo,
            'loaisanpham_id' => $loaisanpham_id
        ];
    }
    
    /**
     * Load view với sidebar data từ controller
     */
    private function loadView($view, $data = []) {
        // Lấy sidebar data từ controller
        $sidebarData = $this->getSidebarData();
        
        // Merge sidebar data vào $data
        $data = array_merge($data, $sidebarData);
        
        extract($data);
        
        // Include header (sử dụng APP_PATH như HomeController)
        include APP_PATH . '/views/layouts/header.php';
        
        // Include left sidebar for category page (thay vì slider)
        include APP_PATH . '/views/frontend_views/leftside.php';
        
        // Include main content
        include APP_PATH . '/views/frontend_views/' . $view . '.php';

        // Include footer (cùng footer với home)
        include APP_PATH . '/views/layouts/footer.php';
    }
}
?>
