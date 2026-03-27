<?php
require_once __DIR__ . '/../../config/database.php';

class CategoryModel {
    private $db;
    
    public function __construct() {
        $this->db = new Database();
    }
    
    /**
     * Lấy tất cả sản phẩm theo danh mục
     */
    public function getProductsByCategory($danhmuc_id) {
        $query = "SELECT sp.*, dm.danhmuc_ten, lsp.loaisanpham_ten 
                  FROM tbl_sanpham sp 
                  LEFT JOIN tbl_danhmuc dm ON sp.danhmuc_id = dm.danhmuc_id 
                  LEFT JOIN tbl_loaisanpham lsp ON sp.loaisanpham_id = lsp.loaisanpham_id 
                  WHERE sp.danhmuc_id = '$danhmuc_id' 
                  ORDER BY sp.sanpham_id DESC";
        
        $result = $this->db->select($query);
        $products = [];
        
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $products[] = $row;
            }
        }
        
        return $products;
    }
    
    /**
     * Lấy sản phẩm theo loại sản phẩm
     */
    public function getProductsByType($loaisanpham_id) {
        $query = "SELECT sp.*, dm.danhmuc_ten, lsp.loaisanpham_ten 
                  FROM tbl_sanpham sp 
                  LEFT JOIN tbl_danhmuc dm ON sp.danhmuc_id = dm.danhmuc_id 
                  LEFT JOIN tbl_loaisanpham lsp ON sp.loaisanpham_id = lsp.loaisanpham_id 
                  WHERE sp.loaisanpham_id = '$loaisanpham_id' 
                  ORDER BY sp.sanpham_id DESC";
        
        $result = $this->db->select($query);
        $products = [];
        
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $products[] = $row;
            }
        }
        
        return $products;
    }
    
    /**
     * Lấy tất cả sản phẩm
     */
    public function getAllProducts() {
        $query = "SELECT sp.*, dm.danhmuc_ten, lsp.loaisanpham_ten 
                  FROM tbl_sanpham sp 
                  LEFT JOIN tbl_danhmuc dm ON sp.danhmuc_id = dm.danhmuc_id 
                  LEFT JOIN tbl_loaisanpham lsp ON sp.loaisanpham_id = lsp.loaisanpham_id 
                  ORDER BY sp.sanpham_id DESC";
        
        $result = $this->db->select($query);
        $products = [];
        
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $products[] = $row;
            }
        }
        
        return $products;
    }
    
    /**
     * Lấy thông tin danh mục theo ID
     */
    public function getCategoryById($danhmuc_id) {
        $query = "SELECT * FROM tbl_danhmuc WHERE danhmuc_id = '$danhmuc_id'";
        $result = $this->db->select($query);
        
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        
        return null;
    }
    
    /**
     * Lấy thông tin loại sản phẩm theo ID
     */
    public function getProductTypeById($loaisanpham_id) {
        $query = "SELECT lsp.*, dm.danhmuc_ten 
                  FROM tbl_loaisanpham lsp 
                  LEFT JOIN tbl_danhmuc dm ON lsp.danhmuc_id = dm.danhmuc_id 
                  WHERE lsp.loaisanpham_id = '$loaisanpham_id'";
        $result = $this->db->select($query);
        
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        
        return null;
    }
    
    /**
     * Lấy tất cả danh mục với subcategories
     */
    public function getAllCategoriesWithSubs() {
        $categories = [];
        
        // Lấy danh mục chính
        $query = "SELECT * FROM tbl_danhmuc ORDER BY danhmuc_id";
        $result = $this->db->select($query);
        
        if ($result && $result->num_rows > 0) {
            while ($category = $result->fetch_assoc()) {
                // Lấy subcategories
                $subQuery = "SELECT * FROM tbl_loaisanpham WHERE danhmuc_id = " . $category['danhmuc_id'] . " ORDER BY loaisanpham_id";
                $subResult = $this->db->select($subQuery);
                
                $subcategories = [];
                if ($subResult && $subResult->num_rows > 0) {
                    while ($subCat = $subResult->fetch_assoc()) {
                        $subcategories[] = $subCat;
                    }
                }
                
                $category['subcategories'] = $subcategories;
                $categories[] = $category;
            }
        }
        
        return $categories;
    }

    // test thử 
    public function getAllCategories() {
        $query = "SELECT * FROM tbl_danhmuc ORDER BY danhmuc_id";
        return $this->db->select($query);
    }
    
    public function getSubCategories($danhmuc_id) {
        $query = "SELECT * FROM tbl_loaisanpham WHERE danhmuc_id = '$danhmuc_id' ORDER BY loaisanpham_id";
        return $this->db->select($query);
    }
    
    /**
     * Lấy loại sản phẩm theo danh mục
     */
    public function getProductTypesByCategory($danhmuc_id) {
        $query = "SELECT * FROM tbl_loaisanpham WHERE danhmuc_id = '$danhmuc_id' ORDER BY loaisanpham_id";
        return $this->db->select($query);
    }
    
    public function getCategoryInfo($loaisanpham_id) {
        $query = "SELECT tbl_danhmuc.danhmuc_ten, tbl_loaisanpham.loaisanpham_ten
                  FROM tbl_danhmuc 
                  INNER JOIN tbl_loaisanpham ON tbl_danhmuc.danhmuc_id = tbl_loaisanpham.danhmuc_id
                  WHERE tbl_loaisanpham.loaisanpham_id = '$loaisanpham_id'";
        return $this->db->select($query);
    }

    // ==================== ADMIN MANAGEMENT METHODS ====================
    
    /**
     * Thêm danh mục mới
     */
    public function addCategory($danhmuc_ten) {
        $danhmuc_ten = $this->db->link->real_escape_string($danhmuc_ten);
        $query = "INSERT INTO tbl_danhmuc (danhmuc_ten) VALUES ('$danhmuc_ten')";
        return $this->db->insert($query);
    }
    
    /**
     * Cập nhật danh mục
     */
    public function updateCategory($danhmuc_id, $danhmuc_ten) {
        $danhmuc_ten = $this->db->link->real_escape_string($danhmuc_ten);
        $query = "UPDATE tbl_danhmuc SET danhmuc_ten = '$danhmuc_ten' WHERE danhmuc_id = '$danhmuc_id'";
        return $this->db->update($query);
    }
    
    /**
     * Xóa danh mục
     */
    public function deleteCategory($danhmuc_id) {
        // Kiểm tra xem có sản phẩm nào thuộc danh mục này không
        $checkQuery = "SELECT COUNT(*) as count FROM tbl_sanpham WHERE danhmuc_id = '$danhmuc_id'";
        $result = $this->db->select($checkQuery);
        if ($result) {
            $row = $result->fetch_assoc();
            if ($row['count'] > 0) {
                return false; // Không thể xóa vì còn sản phẩm
            }
        }
        
        $query = "DELETE FROM tbl_danhmuc WHERE danhmuc_id = '$danhmuc_id'";
        return $this->db->delete($query);
    }
    
    /**
     * Lấy tất cả loại sản phẩm kèm tên danh mục
     */
    public function getAllProductTypes() {
        $query = "SELECT lsp.*, dm.danhmuc_ten 
                  FROM tbl_loaisanpham lsp 
                  LEFT JOIN tbl_danhmuc dm ON lsp.danhmuc_id = dm.danhmuc_id 
                  ORDER BY lsp.loaisanpham_id DESC";
        return $this->db->select($query);
    }
    
    /**
     * Thêm loại sản phẩm mới
     */
    public function addProductType($danhmuc_id, $loaisanpham_ten) {
        $loaisanpham_ten = $this->db->link->real_escape_string($loaisanpham_ten);
        $query = "INSERT INTO tbl_loaisanpham (danhmuc_id, loaisanpham_ten) VALUES ('$danhmuc_id', '$loaisanpham_ten')";
        return $this->db->insert($query);
    }
    
    /**
     * Cập nhật loại sản phẩm
     */
    public function updateProductType($loaisanpham_id, $danhmuc_id, $loaisanpham_ten) {
        $loaisanpham_ten = $this->db->link->real_escape_string($loaisanpham_ten);
        $query = "UPDATE tbl_loaisanpham SET danhmuc_id = '$danhmuc_id', loaisanpham_ten = '$loaisanpham_ten' WHERE loaisanpham_id = '$loaisanpham_id'";
        return $this->db->update($query);
    }
    
    /**
     * Xóa loại sản phẩm
     */
    public function deleteProductType($loaisanpham_id) {
        // Kiểm tra xem có sản phẩm nào thuộc loại này không
        $checkQuery = "SELECT COUNT(*) as count FROM tbl_sanpham WHERE loaisanpham_id = '$loaisanpham_id'";
        $result = $this->db->select($checkQuery);
        if ($result) {
            $row = $result->fetch_assoc();
            if ($row['count'] > 0) {
                return false; // Không thể xóa vì còn sản phẩm
            }
        }
        
        $query = "DELETE FROM tbl_loaisanpham WHERE loaisanpham_id = '$loaisanpham_id'";
        return $this->db->delete($query);
    }
    
}
?>
