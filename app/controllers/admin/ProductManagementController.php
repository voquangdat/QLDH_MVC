<?php
require_once __DIR__ . '/../../models/CategoryModel.php';
require_once __DIR__ . '/../../models/ProductModel.php';
require_once __DIR__ . '/../../models/ColorModel.php';

class ProductManagementController {
    private $categoryModel;
    private $productModel;
    private $colorModel;
    
    public function __construct() {
        $this->categoryModel = new CategoryModel();
        $this->productModel = new ProductModel();
        $this->colorModel = new ColorModel();
        
        // Kiểm tra đăng nhập admin
        if (!$this->isAdminLoggedIn()) {
            header('Location: index.php?page=admin');
            exit;
        }
    }
    
    /**
     * Trang chính quản lý sản phẩm
     */
    public function index() {
        $data = [
            'pageTitle' => 'Quản lý Sản phẩm - VoxFootball Admin',
            'admin_name' => $_SESSION['admin_name'] ?? 'Admin'
        ];
        
        $this->loadView('product_management/index', $data);
    }
    
    /**
     * Quản lý danh mục sản phẩm
     */
    public function categories() {
        $action = $_GET['action'] ?? 'list';
        
        switch ($action) {
            case 'add':
                $this->addCategory();
                break;
            case 'edit':
                $this->editCategory();
                break;
            case 'delete':
                $this->deleteCategory();
                break;
            default:
                $this->listCategories();
                break;
        }
    }
    
    /**
     * Quản lý loại sản phẩm
     */
    public function productTypes() {
        $action = $_GET['action'] ?? 'list';
        
        switch ($action) {
            case 'add':
                $this->addProductType();
                break;
            case 'edit':
                $this->editProductType();
                break;
            case 'delete':
                $this->deleteProductType();
                break;
            default:
                $this->listProductTypes();
                break;
        }
    }
    
    /**
     * Quản lý sản phẩm
     */
    public function products() {
        $action = $_GET['action'] ?? 'list';
        
        switch ($action) {
            case 'add':
                $this->addProduct();
                break;
            case 'edit':
                $this->editProduct();
                break;
            case 'delete':
                $this->deleteProduct();
                break;
            default:
                $this->listProducts();
                break;
        }
    }
    
    /**
     * Quản lý ảnh sản phẩm
     */
    public function productImages() {
        $action = $_GET['action'] ?? 'list';
        
        switch ($action) {
            case 'add':
                $this->addProductImage();
                break;
            case 'edit':
                $this->editProductImage();
                break;
            case 'delete':
                $this->deleteProductImage();
                break;
            default:
                $this->listProductImages();
                break;
        }
    }
    
    /**
     * Quản lý size sản phẩm
     */
    public function productSizes() {
        $action = $_GET['action'] ?? 'list';
        
        switch ($action) {
            case 'add':
                $this->addProductSize();
                break;
            case 'edit':
                $this->editProductSize();
                break;
            case 'delete':
                $this->deleteProductSize();
                break;
            default:
                $this->listProductSizes();
                break;
        }
    }
    
    /**
     * Quản lý màu sắc sản phẩm
     */
    public function productColors() {
        $action = $_GET['action'] ?? 'list';
        
        switch ($action) {
            case 'add':
                $this->addProductColor();
                break;
            case 'edit':
                $this->editProductColor();
                break;
            case 'delete':
                $this->deleteProductColor();
                break;
            default:
                $this->listProductColors();
                break;
        }
    }
    
    /**
     * AJAX handler
     */
    public function ajax() {
        // Debug log
        error_log("AJAX called with action: " . ($_GET['action'] ?? 'none'));
        error_log("danhmuc_id: " . ($_GET['danhmuc_id'] ?? 'none'));
        
        $action = $_GET['action'] ?? '';
        
        if ($action === 'get_product_types') {
            $danhmuc_id = $_GET['danhmuc_id'] ?? '';
            
            if ($danhmuc_id) {
                try {
                    $productTypes = $this->categoryModel->getProductTypesByCategory($danhmuc_id);
                    $data = [];
                    
                    if ($productTypes && $productTypes->num_rows > 0) {
                        while ($row = $productTypes->fetch_assoc()) {
                            $data[] = [
                                'loaisanpham_id' => $row['loaisanpham_id'],
                                'loaisanpham_ten' => $row['loaisanpham_ten']
                            ];
                        }
                    }
                    
                    error_log("Found " . count($data) . " product types");
                    
                    header('Content-Type: application/json');
                    echo json_encode(['success' => true, 'data' => $data]);
                    exit;
                } catch (Exception $e) {
                    error_log("AJAX error: " . $e->getMessage());
                    header('Content-Type: application/json');
                    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
                    exit;
                }
            } else {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Missing danhmuc_id']);
                exit;
            }
        }
        
        if ($action === 'delete_product_image') {
            $image_id = $_POST['image_id'] ?? '';
            
            if ($image_id) {
                try {
                    $result = $this->productModel->deleteProductImageById($image_id);
                    if ($result) {
                        header('Content-Type: application/json');
                        echo json_encode(['success' => true, 'message' => 'Xóa ảnh thành công!']);
                        exit;
                    } else {
                        header('Content-Type: application/json');
                        echo json_encode(['success' => false, 'message' => 'Không thể xóa ảnh!']);
                        exit;
                    }
                } catch (Exception $e) {
                    error_log("Delete image error: " . $e->getMessage());
                    header('Content-Type: application/json');
                    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
                    exit;
                }
            } else {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Missing image_id']);
                exit;
            }
        }
        
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Invalid action: ' . $action]);
        exit;
    }

    // ==================== CATEGORY METHODS ====================
    
    private function listCategories() {
        $categories = $this->categoryModel->getAllCategories();
        $data = [
            'pageTitle' => 'Quản lý Danh mục - VoxFootball Admin',
            'admin_name' => $_SESSION['admin_name'] ?? 'Admin',
            'categories' => $categories
        ];
        
        $this->loadView('product_management/categories', $data);
    }
    
    private function addCategory() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $danhmuc_ten = trim($_POST['danhmuc_ten'] ?? '');
            
            if (empty($danhmuc_ten)) {
                $_SESSION['error'] = 'Tên danh mục không được để trống!';
            } else {
                $result = $this->categoryModel->addCategory($danhmuc_ten);
                if ($result) {
                    $_SESSION['success'] = 'Thêm danh mục thành công!';
                    header('Location: index.php?page=admin_product&section=categories');
                    exit;
                } else {
                    $_SESSION['error'] = 'Có lỗi xảy ra khi thêm danh mục!';
                }
            }
        }
        
        $data = [
            'pageTitle' => 'Thêm Danh mục - VoxFootball Admin',
            'admin_name' => $_SESSION['admin_name'] ?? 'Admin'
        ];
        
        $this->loadView('product_management/add_category', $data);
    }
    
    private function editCategory() {
        $danhmuc_id = $_GET['id'] ?? null;
        
        if (!$danhmuc_id) {
            $_SESSION['error'] = 'Không tìm thấy danh mục cần sửa!';
            header('Location: index.php?page=admin_product&section=categories');
            exit;
        }
        
        $category = $this->categoryModel->getCategoryById($danhmuc_id);
        
        if (!$category) {
            $_SESSION['error'] = 'Danh mục không tồn tại!';
            header('Location: index.php?page=admin_product&section=categories');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $danhmuc_ten = trim($_POST['danhmuc_ten'] ?? '');
            
            if (empty($danhmuc_ten)) {
                $_SESSION['error'] = 'Tên danh mục không được để trống!';
            } else {
                $result = $this->categoryModel->updateCategory($danhmuc_id, $danhmuc_ten);
                if ($result) {
                    $_SESSION['success'] = 'Cập nhật danh mục thành công!';
                    header('Location: index.php?page=admin_product&section=categories');
                    exit;
                } else {
                    $_SESSION['error'] = 'Có lỗi xảy ra khi cập nhật danh mục!';
                }
            }
        }
        
        $data = [
            'pageTitle' => 'Sửa Danh mục - VoxFootball Admin',
            'admin_name' => $_SESSION['admin_name'] ?? 'Admin',
            'category' => $category
        ];
        
        $this->loadView('product_management/edit_category', $data);
    }
    
    private function deleteCategory() {
        $danhmuc_id = $_GET['id'] ?? null;
        
        if ($danhmuc_id) {
            $result = $this->categoryModel->deleteCategory($danhmuc_id);
            if ($result) {
                $_SESSION['success'] = 'Xóa danh mục thành công!';
            } else {
                $_SESSION['error'] = 'Không thể xóa danh mục này! Có thể đã có sản phẩm thuộc danh mục này.';
            }
        }
        
        header('Location: index.php?page=admin_product&section=categories');
        exit;
    }

    // ==================== PRODUCT TYPE METHODS ====================
    
    private function listProductTypes() {
        $productTypes = $this->categoryModel->getAllProductTypes();
        $categories = $this->categoryModel->getAllCategories();
        
        $data = [
            'pageTitle' => 'Quản lý Loại sản phẩm - VoxFootball Admin',
            'admin_name' => $_SESSION['admin_name'] ?? 'Admin',
            'productTypes' => $productTypes,
            'categories' => $categories
        ];
        
        $this->loadView('product_management/product_types', $data);
    }
    
    private function addProductType() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $danhmuc_id = $_POST['danhmuc_id'] ?? '';
            $loaisanpham_ten = trim($_POST['loaisanpham_ten'] ?? '');
            
            if (empty($danhmuc_id) || empty($loaisanpham_ten)) {
                $_SESSION['error'] = 'Vui lòng điền đầy đủ thông tin!';
            } else {
                $result = $this->categoryModel->addProductType($danhmuc_id, $loaisanpham_ten);
                if ($result) {
                    $_SESSION['success'] = 'Thêm loại sản phẩm thành công!';
                    header('Location: index.php?page=admin_product&section=product_types');
                    exit;
                } else {
                    $_SESSION['error'] = 'Có lỗi xảy ra khi thêm loại sản phẩm!';
                }
            }
        }
        
        $categories = $this->categoryModel->getAllCategories();
        
        $data = [
            'pageTitle' => 'Thêm Loại sản phẩm - VoxFootball Admin',
            'admin_name' => $_SESSION['admin_name'] ?? 'Admin',
            'categories' => $categories
        ];
        
        $this->loadView('product_management/add_product_type', $data);
    }
    
    private function editProductType() {
        $loaisanpham_id = $_GET['id'] ?? null;
        
        if (!$loaisanpham_id) {
            $_SESSION['error'] = 'Không tìm thấy loại sản phẩm cần sửa!';
            header('Location: index.php?page=admin_product&section=product_types');
            exit;
        }
        
        $productType = $this->categoryModel->getProductTypeById($loaisanpham_id);
        
        if (!$productType) {
            $_SESSION['error'] = 'Loại sản phẩm không tồn tại!';
            header('Location: index.php?page=admin_product&section=product_types');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $danhmuc_id = $_POST['danhmuc_id'] ?? '';
            $loaisanpham_ten = trim($_POST['loaisanpham_ten'] ?? '');
            
            if (empty($danhmuc_id) || empty($loaisanpham_ten)) {
                $_SESSION['error'] = 'Vui lòng điền đầy đủ thông tin!';
            } else {
                $result = $this->categoryModel->updateProductType($loaisanpham_id, $danhmuc_id, $loaisanpham_ten);
                if ($result) {
                    $_SESSION['success'] = 'Cập nhật loại sản phẩm thành công!';
                    header('Location: index.php?page=admin_product&section=product_types');
                    exit;
                } else {
                    $_SESSION['error'] = 'Có lỗi xảy ra khi cập nhật loại sản phẩm!';
                }
            }
        }
        
        $categories = $this->categoryModel->getAllCategories();
        
        $data = [
            'pageTitle' => 'Sửa Loại sản phẩm - VoxFootball Admin',
            'admin_name' => $_SESSION['admin_name'] ?? 'Admin',
            'productType' => $productType,
            'categories' => $categories
        ];
        
        $this->loadView('product_management/edit_product_type', $data);
    }
    
    private function deleteProductType() {
        $loaisanpham_id = $_GET['id'] ?? null;
        
        if ($loaisanpham_id) {
            $result = $this->categoryModel->deleteProductType($loaisanpham_id);
            if ($result) {
                $_SESSION['success'] = 'Xóa loại sản phẩm thành công!';
            } else {
                $_SESSION['error'] = 'Không thể xóa loại sản phẩm này! Có thể đã có sản phẩm thuộc loại này.';
            }
        }
        
        header('Location: index.php?page=admin_product&section=product_types');
        exit;
    }

    // ==================== PRODUCT METHODS ====================
    
    private function listProducts() {
        $products = $this->productModel->getAllProductsForAdmin();
        
        $data = [
            'pageTitle' => 'Quản lý Sản phẩm - VoxFootball Admin',
            'admin_name' => $_SESSION['admin_name'] ?? 'Admin',
            'products' => $products
        ];
        
        $this->loadView('product_management/products', $data);
    }
    
    private function addProduct() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $this->processProductForm();
            if ($result['success']) {
                $_SESSION['success'] = 'Thêm sản phẩm thành công!';
                header('Location: index.php?page=admin_product&section=products');
                exit;
            } else {
                $_SESSION['error'] = $result['message'];
            }
        }
        
        $categories = $this->categoryModel->getAllCategories();
        $colors = $this->colorModel->getAllColors();
        
        // Tạo dữ liệu JavaScript cho category-producttype mapping
        $categoryProductTypeMap = [];
        if ($categories && mysqli_num_rows($categories) > 0) {
            mysqli_data_seek($categories, 0); // Reset pointer
            while ($category = mysqli_fetch_assoc($categories)) {
                $productTypes = $this->categoryModel->getProductTypesByCategory($category['danhmuc_id']);
                $productTypeArray = [];
                if ($productTypes && mysqli_num_rows($productTypes) > 0) {
                    while ($type = mysqli_fetch_assoc($productTypes)) {
                        $productTypeArray[] = [
                            'id' => $type['loaisanpham_id'],
                            'name' => $type['loaisanpham_ten']
                        ];
                    }
                }
                $categoryProductTypeMap[$category['danhmuc_id']] = $productTypeArray;
            }
            mysqli_data_seek($categories, 0); // Reset pointer for view
        }
        
        $data = [
            'pageTitle' => 'Thêm Sản phẩm - VoxFootball Admin',
            'admin_name' => $_SESSION['admin_name'] ?? 'Admin',
            'categories' => $categories,
            'colors' => $colors,
            'categoryProductTypeMap' => json_encode($categoryProductTypeMap)
        ];
        
        $this->loadView('product_management/add_product', $data);
    }
    
    private function editProduct() {
        $sanpham_id = $_GET['id'] ?? null;
        
        if (!$sanpham_id) {
            $_SESSION['error'] = 'Không tìm thấy sản phẩm cần sửa!';
            header('Location: index.php?page=admin_product&section=products');
            exit;
        }
        
        $product = $this->productModel->getProductById($sanpham_id);
        
        if (!$product) {
            $_SESSION['error'] = 'Sản phẩm không tồn tại!';
            header('Location: index.php?page=admin_product&section=products');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $this->processProductForm($sanpham_id);
            if ($result['success']) {
                $_SESSION['success'] = 'Cập nhật sản phẩm thành công!';
                header('Location: index.php?page=admin_product&section=products');
                exit;
            } else {
                $_SESSION['error'] = $result['message'];
            }
        }
        
        $categories = $this->categoryModel->getAllCategories();
        $colors = $this->colorModel->getAllColors();
        $productTypes = $this->categoryModel->getProductTypesByCategory($product['danhmuc_id']);
        
        // Lấy size hiện tại của sản phẩm
        $currentSizes = [];
        $productSizes = $this->productModel->getProductSizes($product['sanpham_id']);
        if ($productSizes && mysqli_num_rows($productSizes) > 0) {
            while ($size = mysqli_fetch_assoc($productSizes)) {
                $currentSizes[] = $size['sanpham_size'];
            }
        }
        
        // Lấy ảnh hiện có của sản phẩm
        $existingImages = $this->productModel->getProductImages($product['sanpham_id']);
        
        // Tạo dữ liệu JavaScript cho category-producttype mapping
        $categoryProductTypeMap = [];
        if ($categories && mysqli_num_rows($categories) > 0) {
            mysqli_data_seek($categories, 0); // Reset pointer
            while ($category = mysqli_fetch_assoc($categories)) {
                $productTypesForCategory = $this->categoryModel->getProductTypesByCategory($category['danhmuc_id']);
                $productTypeArray = [];
                if ($productTypesForCategory && mysqli_num_rows($productTypesForCategory) > 0) {
                    while ($type = mysqli_fetch_assoc($productTypesForCategory)) {
                        $productTypeArray[] = [
                            'id' => $type['loaisanpham_id'],
                            'name' => $type['loaisanpham_ten']
                        ];
                    }
                }
                $categoryProductTypeMap[$category['danhmuc_id']] = $productTypeArray;
            }
            mysqli_data_seek($categories, 0); // Reset pointer for view
        }
        
        $data = [
            'pageTitle' => 'Sửa Sản phẩm - VoxFootball Admin',
            'admin_name' => $_SESSION['admin_name'] ?? 'Admin',
            'product' => $product,
            'categories' => $categories,
            'colors' => $colors,
            'productTypes' => $productTypes,
            'currentSizes' => $currentSizes,
            'existingImages' => $existingImages,
            'categoryProductTypeMap' => json_encode($categoryProductTypeMap)
        ];
        
        $this->loadView('product_management/edit_product', $data);
    }
    
    private function deleteProduct() {
        $sanpham_id = $_GET['id'] ?? null;
        
        if ($sanpham_id) {
            $result = $this->productModel->deleteProduct($sanpham_id);
            if ($result) {
                $_SESSION['success'] = 'Xóa sản phẩm thành công!';
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra khi xóa sản phẩm!';
            }
        }
        
        header('Location: index.php?page=admin_product&section=products');
        exit;
    }
    
    private function processProductForm($sanpham_id = null) {
        try {
            // Validate dữ liệu đầu vào
            $sanpham_tieude = trim($_POST['sanpham_tieude'] ?? '');
            $sanpham_ma = trim($_POST['sanpham_ma'] ?? '');
            $danhmuc_id = $_POST['danhmuc_id'] ?? '';
            $loaisanpham_id = $_POST['loaisanpham_id'] ?? '';
            $color_id = $_POST['color_id'] ?? '';
            $sanpham_gia = $_POST['sanpham_gia'] ?? 0;
            $sanpham_giakhuyenmai = $_POST['sanpham_giakhuyenmai'] ?? 0;
            $sanpham_chitiet = trim($_POST['sanpham_chitiet'] ?? '');
            $sanpham_baoquan = trim($_POST['sanpham_baoquan'] ?? '');
            $sanpham_hot = isset($_POST['sanpham_hot']) ? 1 : 0;
            $sanpham_sizes = $_POST['sanpham-size'] ?? [];
            
            // Validation
            if (empty($sanpham_tieude) || empty($sanpham_ma) || empty($danhmuc_id) || 
                empty($loaisanpham_id) || empty($color_id) || empty($sanpham_chitiet) || empty($sanpham_baoquan)) {
                return ['success' => false, 'message' => 'Vui lòng điền đầy đủ thông tin bắt buộc!'];
            }
            
            if (empty($sanpham_sizes)) {
                return ['success' => false, 'message' => 'Vui lòng chọn ít nhất một size sản phẩm!'];
            }
            
            if (strlen($sanpham_tieude) < 5) {
                return ['success' => false, 'message' => 'Tên sản phẩm phải có ít nhất 5 ký tự!'];
            }
            
            if (strlen($sanpham_ma) < 3) {
                return ['success' => false, 'message' => 'Mã sản phẩm phải có ít nhất 3 ký tự!'];
            }
            
            if ($sanpham_gia <= 0) {
                return ['success' => false, 'message' => 'Giá sản phẩm phải lớn hơn 0!'];
            }
            
            // Kiểm tra mã sản phẩm trùng lặp
            if ($sanpham_id) {
                $existingProduct = $this->productModel->getProductByCode($sanpham_ma, $sanpham_id);
            } else {
                $existingProduct = $this->productModel->getProductByCode($sanpham_ma);
            }
            
            if ($existingProduct) {
                return ['success' => false, 'message' => 'Mã sản phẩm đã tồn tại!'];
            }
            
            // Xử lý upload ảnh
            $sanpham_anh = '';
            $sanpham_anhkhac = '';
            
            // Upload ảnh chính
            if (isset($_FILES['sanpham_anh']) && $_FILES['sanpham_anh']['error'] === UPLOAD_ERR_OK) {
                $result = $this->uploadImage($_FILES['sanpham_anh']);
                if ($result['success']) {
                    $sanpham_anh = $result['filename'];
                } else {
                    return ['success' => false, 'message' => 'Lỗi upload ảnh chính: ' . $result['message']];
                }
            } elseif (!$sanpham_id) {
                // Nếu là thêm mới và không có ảnh thì báo lỗi
                return ['success' => false, 'message' => 'Vui lòng chọn ảnh chính cho sản phẩm!'];
            }
            
            // Upload ảnh phụ
            if (isset($_FILES['sanpham_anhkhac']) && $_FILES['sanpham_anhkhac']['error'] === UPLOAD_ERR_OK) {
                $result = $this->uploadImage($_FILES['sanpham_anhkhac']);
                if ($result['success']) {
                    $sanpham_anhkhac = $result['filename'];
                } else {
                    return ['success' => false, 'message' => 'Lỗi upload ảnh phụ: ' . $result['message']];
                }
            }
            
            // Chuẩn bị dữ liệu
            $productData = [
                'sanpham_tieude' => $sanpham_tieude,
                'sanpham_ma' => $sanpham_ma,
                'danhmuc_id' => $danhmuc_id,
                'loaisanpham_id' => $loaisanpham_id,
                'color_id' => $color_id,
                'sanpham_gia' => $sanpham_gia,
                'sanpham_giakhuyenmai' => $sanpham_giakhuyenmai,
                'sanpham_chitiet' => $sanpham_chitiet,
                'sanpham_baoquan' => $sanpham_baoquan,
                'sanpham_hot' => $sanpham_hot,
                'sanpham_sizes' => $sanpham_sizes
            ];
            
            // Chỉ cập nhật ảnh nếu có upload mới
            if ($sanpham_anh) {
                $productData['sanpham_anh'] = $sanpham_anh;
            }
            if ($sanpham_anhkhac) {
                $productData['sanpham_anhkhac'] = $sanpham_anhkhac;
            }
            
            // Thực hiện thêm/cập nhật
            if ($sanpham_id) {
                $result = $this->productModel->updateProduct($sanpham_id, $productData);
                $current_sanpham_id = $sanpham_id;
            } else {
                $result = $this->productModel->addProduct($productData);
                $current_sanpham_id = $result; // addProduct trả về ID của sản phẩm mới
            }
            
            if ($result) {
                // Xử lý multiple images nếu có
                if (isset($_FILES['multiple_images']) && !empty($_FILES['multiple_images']['name'][0])) {
                    $this->processMultipleImages($current_sanpham_id);
                }
                return ['success' => true];
            } else {
                return ['success' => false, 'message' => 'Có lỗi xảy ra khi lưu sản phẩm!'];
            }
            
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Lỗi hệ thống: ' . $e->getMessage()];
        }
    }
    
    private function uploadImage($file) {
        try {
            // Kiểm tra lỗi upload
            if ($file['error'] !== UPLOAD_ERR_OK) {
                return ['success' => false, 'message' => 'Lỗi upload file!'];
            }
            
            // Kiểm tra kích thước file (max 5MB)
            if ($file['size'] > 5 * 1024 * 1024) {
                return ['success' => false, 'message' => 'File ảnh quá lớn! Tối đa 5MB.'];
            }
            
            // Kiểm tra loại file
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);
            
            if (!in_array($mimeType, $allowedTypes)) {
                return ['success' => false, 'message' => 'Chỉ hỗ trợ file ảnh JPG, PNG, GIF, WebP!'];
            }
            
            // Tạo tên file unique
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . '.' . strtolower($extension);
            
            // Đường dẫn upload
            $uploadDir = __DIR__ . '/../../../public/uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            $uploadPath = $uploadDir . $filename;
            
            // Di chuyển file
            if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                return ['success' => true, 'filename' => $filename];
            } else {
                return ['success' => false, 'message' => 'Không thể lưu file ảnh!'];
            }
            
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Lỗi xử lý ảnh: ' . $e->getMessage()];
        }
    }

    // ==================== PLACEHOLDER METHODS ====================
    
    private function listProductImages() {
        echo "Product Images Management - Coming Soon";
    }
    
    private function addProductImage() {
        echo "Add Product Image - Coming Soon";
    }
    
    private function editProductImage() {
        echo "Edit Product Image - Coming Soon";
    }
    
    private function deleteProductImage() {
        echo "Delete Product Image - Coming Soon";
    }
    
    private function listProductSizes() {
        echo "Product Sizes Management - Coming Soon";
    }
    
    private function addProductSize() {
        echo "Add Product Size - Coming Soon";
    }
    
    private function editProductSize() {
        echo "Edit Product Size - Coming Soon";
    }
    
    private function deleteProductSize() {
        echo "Delete Product Size - Coming Soon";
    }
    
    private function listProductColors() {
        echo "Product Colors Management - Coming Soon";
    }
    
    private function addProductColor() {
        echo "Add Product Color - Coming Soon";
    }
    
    private function editProductColor() {
        echo "Edit Product Color - Coming Soon";
    }
    
    private function deleteProductColor() {
        echo "Delete Product Color - Coming Soon";
    }

    // ==================== HELPER METHODS ====================
    
    private function processMultipleImages($sanpham_id) {
        if (!isset($_FILES['multiple_images']) || empty($_FILES['multiple_images']['name'][0])) {
            return;
        }
        
        $uploadDir = 'public/uploads/';
        $maxFiles = 10;
        $maxFileSize = 5 * 1024 * 1024; // 5MB
        
        $fileCount = count($_FILES['multiple_images']['name']);
        
        if ($fileCount > $maxFiles) {
            return; // Đã validate ở frontend
        }
        
        for ($i = 0; $i < $fileCount; $i++) {
            $fileName = $_FILES['multiple_images']['name'][$i];
            $fileTmpName = $_FILES['multiple_images']['tmp_name'][$i];
            $fileSize = $_FILES['multiple_images']['size'][$i];
            $fileError = $_FILES['multiple_images']['error'][$i];
            
            // Skip nếu có lỗi hoặc không có file
            if ($fileError !== UPLOAD_ERR_OK || empty($fileName)) {
                continue;
            }
            
            // Validate file size
            if ($fileSize > $maxFileSize) {
                continue;
            }
            
            // Validate file type
            $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $fileTmpName);
            finfo_close($finfo);
            
            if (!in_array($mimeType, $allowedTypes)) {
                continue;
            }
            
            // Generate unique filename
            $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
            $newFileName = uniqid() . '.' . $fileExtension;
            $uploadPath = $uploadDir . $newFileName;
            
            // Upload file
            if (move_uploaded_file($fileTmpName, $uploadPath)) {
                // Lưu vào database
                $this->productModel->addProductImage($sanpham_id, $newFileName);
            }
        }
    }
    
    private function isAdminLoggedIn() {
        return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
    }
    
    private function loadView($view, $data = []) {
        extract($data);
        require_once __DIR__ . '/../../views/admin/' . $view . '.php';
    }
}