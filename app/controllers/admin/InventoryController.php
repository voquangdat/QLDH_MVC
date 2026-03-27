<?php
// filepath: e:\QLDH_MVC\app\controllers\admin\InventoryController.php
require_once __DIR__ . '/../../models/InventoryModel.php';
require_once __DIR__ . '/../../models/ProductModel.php';
require_once __DIR__ . '/../../models/ColorModel.php';
require_once __DIR__ . '/../../models/CategoryModel.php';

class InventoryController {
    private $inventoryModel;
    private $productModel;
    private $colorModel;
    private $categoryModel;
    
    public function __construct() {
        $this->inventoryModel = new InventoryModel();
        $this->productModel = new ProductModel();
        $this->colorModel = new ColorModel();
        $this->categoryModel = new CategoryModel();
        
        // Kiểm tra đăng nhập admin
        if (!$this->isAdminLoggedIn()) {
            header('Location: index.php?page=admin');
            exit;
        }
    }
    
    /**
     * Trang chính quản lý tồn kho
     */
    public function index() {
        $section = $_GET['section'] ?? 'index';
        $action = $_GET['action'] ?? 'list';
        
        switch ($section) {
            case 'index':
                $this->showInventoryIndex();
                break;
                
            case 'list_inventory':
                $this->listInventory();
                break;
                
            case 'detail':
                switch ($action) {
                    case 'detail':
                        $this->productDetail();
                        break;
                    case 'add_variant':
                        $this->handleAddVariant();
                        break;
                    case 'edit_variant':
                        $this->handleEditVariant();
                        break;
                    case 'delete_variant':
                        $this->deleteVariant();
                        break;
                    case 'update_stock':
                        $this->updateStock();
                        break;
                    case 'show_add_variant':
                        $this->showAddVariant();
                        break;
                    case 'show_edit_variant':
                        $this->showEditVariant();
                        break;
                    default:
                        $this->productDetail();
                        break;
                }
                break;
                
            default:
                $this->listInventory();
                break;
        }
    }
    
    /**
     * Trang index chính của inventory
     */
    private function showInventoryIndex() {
        $data = [
            'pageTitle' => 'Quản lý Tồn kho - VoxFootball Admin',
            'admin_name' => $_SESSION['admin_name'] ?? 'Admin'
        ];
        
        $this->loadView('inventory/index', $data);
    }
    
    /**
     * Danh sách tồn kho
     */
    private function listInventory() {
        // Lấy parameters từ URL
        $page = max(1, (int)($_GET['page_num'] ?? 1));
        $limit = 20;
        $offset = ($page - 1) * $limit;
        $search = trim($_GET['search'] ?? '');
        $category_filter = $_GET['category'] ?? '';
        
        // Lấy dữ liệu
        $inventoryItems = $this->inventoryModel->getInventoryList($search, $category_filter, $limit, $offset);
        $totalItems = $this->inventoryModel->countInventoryItems($search, $category_filter);
        $totalPages = ceil($totalItems / $limit);
        
        // Lấy danh mục cho filter
        $categories = $this->categoryModel->getAllCategories();
        
        // Lấy thống kê
        $stats = $this->inventoryModel->getInventoryStats();
        
        // Lấy sản phẩm sắp hết hàng
        $lowStockItems = $this->inventoryModel->getLowStockItems(5);
        
        $data = [
            'pageTitle' => 'Quản lý Tồn kho - VoxFootball Admin',
            'admin_name' => $_SESSION['admin_name'] ?? 'Admin',
            'inventoryItems' => $inventoryItems,
            'categories' => $categories,
            'stats' => $stats,
            'lowStockItems' => $lowStockItems,
            'pagination' => [
                'current_page' => $page,
                'total_pages' => $totalPages,
                'total_items' => $totalItems,
                'limit' => $limit
            ],
            'filters' => [
                'search' => $search,
                'category' => $category_filter
            ]
        ];
        
        $this->loadView('inventory/list_inventory', $data);
    }
    
    /**
     * Chi tiết sản phẩm tồn kho
     */
    private function productDetail() {
        $sanpham_id = $_GET['id'] ?? null;
        
        if (!$sanpham_id) {
            $_SESSION['error'] = 'Không tìm thấy sản phẩm!';
            header('Location: index.php?page=admin_inventory&section=list_inventory');
            exit;
        }
        
        // Lấy thông tin sản phẩm
        $product = $this->productModel->getProductById($sanpham_id);
        
        if (!$product) {
            $_SESSION['error'] = 'Sản phẩm không tồn tại!';
            header('Location: index.php?page=admin_inventory&section=list_inventory');
            exit;
        }
        
        // Lấy tất cả biến thể của sản phẩm
        $variants = $this->inventoryModel->getProductVariants($sanpham_id);
        
        $data = [
            'pageTitle' => 'Chi tiết Tồn kho - ' . $product['sanpham_tieude'],
            'admin_name' => $_SESSION['admin_name'] ?? 'Admin',
            'product' => $product,
            'variants' => $variants
        ];
        
        $this->loadView('inventory/detail', $data);
    }
    
    /**
     * Hiển thị trang thêm biến thể
     */
    private function showAddVariant() {
        $sanpham_id = $_GET['id'] ?? null;
        
        if (!$sanpham_id) {
            $_SESSION['error'] = 'Không tìm thấy sản phẩm!';
            header('Location: index.php?page=admin_inventory&section=list_inventory');
            exit;
        }
        
        // Lấy thông tin sản phẩm
        $product = $this->productModel->getProductById($sanpham_id);
        
        if (!$product) {
            $_SESSION['error'] = 'Sản phẩm không tồn tại!';
            header('Location: index.php?page=admin_inventory&section=list_inventory');
            exit;
        }
        
        // Lấy tất cả size và color có sẵn cho sản phẩm
        $availableSizes = $this->productModel->getProductSizes($sanpham_id);
        $availableColors = $this->colorModel->getAllColors();
        
        $data = [
            'pageTitle' => 'Thêm biến thể - ' . $product['sanpham_tieude'],
            'admin_name' => $_SESSION['admin_name'] ?? 'Admin',
            'product' => $product,
            'availableSizes' => $availableSizes,
            'availableColors' => $availableColors
        ];
        
        $this->loadView('inventory/add_variant', $data);
    }
    
    /**
     * Hiển thị trang chỉnh sửa biến thể
     */
    private function showEditVariant() {
        $bienthe_id = $_GET['variant_id'] ?? null;
        
        if (!$bienthe_id) {
            $_SESSION['error'] = 'Không tìm thấy biến thể!';
            header('Location: index.php?page=admin_inventory&section=list_inventory');
            exit;
        }
        
        // Lấy thông tin biến thể
        $variant = $this->inventoryModel->getVariantById($bienthe_id);
        
        if (!$variant) {
            $_SESSION['error'] = 'Biến thể không tồn tại!';
            header('Location: index.php?page=admin_inventory&section=list_inventory');
            exit;
        }
        
        $data = [
            'pageTitle' => 'Chỉnh sửa biến thể - ' . $variant['sanpham_tieude'],
            'admin_name' => $_SESSION['admin_name'] ?? 'Admin',
            'variant' => $variant
        ];
        
        $this->loadView('inventory/edit_variant', $data);
    }
    
    /**
     * Xử lý thêm biến thể
     */
    private function handleAddVariant() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $sanpham_id = $_POST['sanpham_id'] ?? '';
            $color_id = $_POST['color_id'] ?? '';
            $size_id = $_POST['size_id'] ?? '';
            $soluong = max(0, (int)($_POST['soluong'] ?? 0));
            $muc_canh_bao = max(1, (int)($_POST['muc_canh_bao'] ?? 10));
            
            if (empty($sanpham_id) || empty($color_id) || empty($size_id)) {
                $_SESSION['error'] = 'Vui lòng điền đầy đủ thông tin!';
                header("Location: index.php?page=admin_inventory&section=detail&action=show_add_variant&id=$sanpham_id");
                exit;
            }
            
            // Kiểm tra biến thể đã tồn tại chưa
            if ($this->inventoryModel->variantExists($sanpham_id, $color_id, $size_id)) {
                $_SESSION['error'] = 'Biến thể này đã tồn tại!';
                header("Location: index.php?page=admin_inventory&section=detail&action=show_add_variant&id=$sanpham_id");
                exit;
            }
            
            $result = $this->inventoryModel->addVariant($sanpham_id, $color_id, $size_id, $soluong);
            
            if ($result) {
                // Cập nhật mức cảnh báo nếu khác mặc định
                if ($muc_canh_bao != 10) {
                    $this->inventoryModel->updateInventoryStock($result, $soluong, 0, $muc_canh_bao);
                }
                $_SESSION['success'] = 'Thêm biến thể thành công!';
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra khi thêm biến thể!';
            }
        }
        
        $sanpham_id = $_POST['sanpham_id'] ?? $_GET['id'] ?? '';
        header("Location: index.php?page=admin_inventory&section=detail&action=detail&id=$sanpham_id");
        exit;
    }
    
    /**
     * Xử lý chỉnh sửa biến thể
     */
    private function handleEditVariant() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $bienthe_id = $_POST['bienthe_id'] ?? '';
            $soluong_ton = max(0, (int)($_POST['soluong_ton'] ?? 0));
            $soluong_dat = max(0, (int)($_POST['soluong_dat'] ?? 0));
            $muc_canh_bao = max(1, (int)($_POST['muc_canh_bao'] ?? 10));
            
            if (empty($bienthe_id)) {
                $_SESSION['error'] = 'Biến thể không hợp lệ!';
                header('Location: index.php?page=admin_inventory&section=list_inventory');
                exit;
            }
            
            if ($soluong_dat > $soluong_ton) {
                $_SESSION['error'] = 'Số lượng đặt không thể lớn hơn số lượng tồn!';
                header("Location: index.php?page=admin_inventory&section=detail&action=show_edit_variant&variant_id=$bienthe_id");
                exit;
            }
            
            // Lấy thông tin biến thể để redirect về đúng sản phẩm
            $variant = $this->inventoryModel->getVariantById($bienthe_id);
            $sanpham_id = $variant['sanpham_id'] ?? '';
            
            $result = $this->inventoryModel->updateInventoryStock($bienthe_id, $soluong_ton, $soluong_dat, $muc_canh_bao);
            
            if ($result) {
                $_SESSION['success'] = 'Cập nhật biến thể thành công!';
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra khi cập nhật biến thể!';
            }
            
            header("Location: index.php?page=admin_inventory&section=detail&action=detail&id=$sanpham_id");
            exit;
        }
        
        // Redirect về danh sách nếu không phải POST
        header('Location: index.php?page=admin_inventory&section=list_inventory');
        exit;
    }
    
    /**
     * Xóa biến thể
     */
    private function deleteVariant() {
        $bienthe_id = $_GET['variant_id'] ?? null;
        $sanpham_id = $_GET['product_id'] ?? '';
        
        if ($bienthe_id) {
            $result = $this->inventoryModel->deleteVariant($bienthe_id);
            if ($result) {
                $_SESSION['success'] = 'Xóa biến thể thành công!';
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra khi xóa biến thể!';
            }
        }
        
        header("Location: index.php?page=admin_inventory&section=detail&action=detail&id=$sanpham_id");
        exit;
    }
    
    /**
     * Cập nhật tồn kho - AJAX
     */
    private function updateStock() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            exit;
        }
        
        $bienthe_id = $_POST['bienthe_id'] ?? '';
        $soluong_ton = max(0, (int)($_POST['soluong_ton'] ?? 0));
        $soluong_dat = max(0, (int)($_POST['soluong_dat'] ?? 0));
        $muc_canh_bao = max(0, (int)($_POST['muc_canh_bao'] ?? 10));
        
        if (empty($bienthe_id)) {
            echo json_encode(['success' => false, 'message' => 'Biến thể không hợp lệ!']);
            exit;
        }
        
        if ($soluong_dat > $soluong_ton) {
            echo json_encode(['success' => false, 'message' => 'Số lượng đặt không thể lớn hơn số lượng tồn!']);
            exit;
        }
        
        $result = $this->inventoryModel->updateInventoryStock($bienthe_id, $soluong_ton, $soluong_dat, $muc_canh_bao);
        
        if ($result) {
            $soluong_co_the_ban = $soluong_ton - $soluong_dat;
            echo json_encode([
                'success' => true, 
                'message' => 'Cập nhật tồn kho thành công!',
                'data' => [
                    'soluong_co_the_ban' => $soluong_co_the_ban
                ]
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Có lỗi xảy ra khi cập nhật!']);
        }
        exit;
    }
    
    /**
     * AJAX handler cho các action khác
     */
    public function ajax() {
        $action = $_GET['ajax_action'] ?? '';
        
        switch ($action) {
            case 'update_stock':
                $this->updateStock();
                break;
            
            case 'get_variant_info':
                $this->getVariantInfo();
                break;
                
            default:
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Invalid action']);
                exit;
        }
    }
    
    /**
     * Lấy thông tin biến thể - AJAX
     */
    private function getVariantInfo() {
        header('Content-Type: application/json');
        
        $bienthe_id = $_GET['bienthe_id'] ?? '';
        
        if (empty($bienthe_id)) {
            echo json_encode(['success' => false, 'message' => 'Biến thể không hợp lệ!']);
            exit;
        }
        
        $variant = $this->inventoryModel->getVariantById($bienthe_id);
        
        if ($variant) {
            echo json_encode([
                'success' => true,
                'data' => $variant
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Không tìm thấy biến thể!']);
        }
        exit;
    }
    
    /**
     * Kiểm tra đăng nhập admin
     */
    private function isAdminLoggedIn() {
        return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
    }
    
    /**
     * Load view
     */
    private function loadView($view, $data = []) {
        extract($data);
        require_once __DIR__ . '/../../views/admin/' . $view . '.php';
    }
}