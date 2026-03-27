<?php
// require_once 'models/HeaderModel.php';
require_once __DIR__ . '/../../models/HeaderModel.php';
class HeaderController {
    private $headerModel;
    
    
    public function __construct() {
        $this->headerModel = new HeaderModel();
    }
    
    public function getHeaderData() {
        // Đảm bảo session đã được start
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        $session_id = session_id();
        
        return [
            'categories' => $this->getAllCategoriesWithSubs(), // Sửa key này
            'cart_items' => $this->getCartItems($session_id),
            'cart_count' => $this->getCartCount($session_id),
            'cart_total' => $this->getCartTotal($session_id)
        ];
    }
    
    /**
     * Lấy danh mục kèm loại sản phẩm con
     */
    private function getAllCategoriesWithSubs() {
        $categories = [];
        $categoryResult = $this->headerModel->getAllCategories();
        
        if ($categoryResult) {
            while ($category = $categoryResult->fetch_assoc()) {
                $category['subcategories'] = [];
                
                $subResult = $this->headerModel->getSubCategoriesByCategory($category['danhmuc_id']);
                if ($subResult) {
                    while ($sub = $subResult->fetch_assoc()) {
                        $category['subcategories'][] = $sub;
                    }
                }
                
                $categories[] = $category;
            }
        }
        
        return $categories;
    }

    
    
    /**
     * Lấy sản phẩm trong giỏ hàng
     */
    private function getCartItems($session_id) {
        $items = [];
        $result = $this->headerModel->getCartItems($session_id);
        
        if ($result) {
            while ($item = $result->fetch_assoc()) {
                $items[] = $item;
            }
        }
        
        return $items;
    }
    
    /**
     * Lấy số lượng sản phẩm trong giỏ
     */
    private function getCartCount($session_id) {
        return $this->headerModel->getCartCount($session_id);
    }
    
    /**
     * Lấy tổng tiền giỏ hàng
     */
    private function getCartTotal($session_id) {
        return $this->headerModel->getCartTotal($session_id);
    }
    
    /**
     * Xử lý tìm kiếm
     */
    public function handleSearch($keyword) {
        if (empty($keyword)) {
            return [];
        }
        
        $results = [];
        $searchResult = $this->headerModel->searchProducts($keyword);
        
        if ($searchResult) {
            while ($item = $searchResult->fetch_assoc()) {
                $results[] = $item;
            }
        }
        
        return $results;
    }
}
?>