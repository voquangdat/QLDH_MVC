<?php
// filepath: e:\QLDH_MVC\app\models\InventoryModel.php
require_once __DIR__ . '/../../config/database.php';

class InventoryModel {
    private $db;
    
    public function __construct() {
        $this->db = new Database();
    }
    
    /**
     * Lấy danh sách tồn kho với thông tin đầy đủ
     */
    public function getInventoryList($search = '', $category_filter = '', $limit = 50, $offset = 0) {
        $whereClause = "WHERE 1=1";
        
        if (!empty($search)) {
            $search = $this->db->link->real_escape_string($search);
            $whereClause .= " AND (sp.sanpham_tieude LIKE '%$search%' OR bt.bienthe_ma LIKE '%$search%')";
        }
        
        if (!empty($category_filter)) {
            $category_filter = $this->db->link->real_escape_string($category_filter);
            $whereClause .= " AND sp.danhmuc_id = '$category_filter'";
        }
        
        $query = "SELECT 
                    bt.bienthe_id,
                    bt.bienthe_ma as sku,
                    sp.sanpham_id,
                    sp.sanpham_tieude as ten_sanpham,
                    sp.sanpham_anh,
                    sz.sanpham_size as size,
                    c.color_ten as mau_sac,
                    c.color_anh as mau_anh,
                    COALESCE(tk.soluong_ton, 0) as soluong_ton,
                    COALESCE(tk.soluong_dat, 0) as soluong_dat,
                    COALESCE(tk.soluong_co_the_ban, 0) as soluong_co_the_ban,
                    COALESCE(tk.muc_canh_bao, 10) as muc_canh_bao,
                    CASE 
                        WHEN COALESCE(tk.soluong_ton, 0) <= COALESCE(tk.muc_canh_bao, 10) THEN 'warning'
                        WHEN COALESCE(tk.soluong_ton, 0) = 0 THEN 'danger'
                        ELSE 'normal'
                    END as trang_thai_ton,
                    dm.danhmuc_ten,
                    lsp.loaisanpham_ten
                  FROM tbl_sanpham_bienthe bt
                  INNER JOIN tbl_sanpham sp ON bt.sanpham_id = sp.sanpham_id
                  INNER JOIN tbl_sanpham_size sz ON bt.sanpham_size_id = sz.sanpham_size_id
                  INNER JOIN tbl_color c ON bt.color_id = c.color_id
                  LEFT JOIN tbl_tonkho tk ON bt.bienthe_id = tk.bienthe_id
                  LEFT JOIN tbl_danhmuc dm ON sp.danhmuc_id = dm.danhmuc_id
                  LEFT JOIN tbl_loaisanpham lsp ON sp.loaisanpham_id = lsp.loaisanpham_id
                  $whereClause
                  ORDER BY sp.sanpham_tieude ASC, c.color_ten ASC, sz.sanpham_size ASC
                  LIMIT $limit OFFSET $offset";
        
        return $this->db->select($query);
    }
    
    /**
     * Đếm tổng số biến thể cho phân trang
     */
    public function countInventoryItems($search = '', $category_filter = '') {
        $whereClause = "WHERE 1=1";
        
        if (!empty($search)) {
            $search = $this->db->link->real_escape_string($search);
            $whereClause .= " AND (sp.sanpham_tieude LIKE '%$search%' OR bt.bienthe_ma LIKE '%$search%')";
        }
        
        if (!empty($category_filter)) {
            $category_filter = $this->db->link->real_escape_string($category_filter);
            $whereClause .= " AND sp.danhmuc_id = '$category_filter'";
        }
        
        $query = "SELECT COUNT(*) as total
                  FROM tbl_sanpham_bienthe bt
                  INNER JOIN tbl_sanpham sp ON bt.sanpham_id = sp.sanpham_id
                  $whereClause";
        
        $result = $this->db->select($query);
        $row = $result->fetch_assoc();
        return $row['total'];
    }
    
    /**
     * Lấy thông tin chi tiết biến thể theo ID
     */
    public function getVariantById($bienthe_id) {
        $query = "SELECT 
                    bt.*,
                    sp.sanpham_tieude,
                    sp.sanpham_anh,
                    sz.sanpham_size,
                    c.color_ten,
                    c.color_anh,
                    COALESCE(tk.soluong_ton, 0) as soluong_ton,
                    COALESCE(tk.soluong_dat, 0) as soluong_dat,
                    COALESCE(tk.muc_canh_bao, 10) as muc_canh_bao
                  FROM tbl_sanpham_bienthe bt
                  INNER JOIN tbl_sanpham sp ON bt.sanpham_id = sp.sanpham_id
                  INNER JOIN tbl_sanpham_size sz ON bt.sanpham_size_id = sz.sanpham_size_id
                  INNER JOIN tbl_color c ON bt.color_id = c.color_id
                  LEFT JOIN tbl_tonkho tk ON bt.bienthe_id = tk.bienthe_id
                  WHERE bt.bienthe_id = '$bienthe_id'";
        
        $result = $this->db->select($query);
        return $result ? $result->fetch_assoc() : null;
    }
    
    /**
     * Lấy tất cả biến thể của một sản phẩm
     */
    public function getProductVariants($sanpham_id) {
        $query = "SELECT 
                    bt.*,
                    sz.sanpham_size,
                    c.color_ten,
                    c.color_anh,
                    COALESCE(tk.soluong_ton, 0) as soluong_ton,
                    COALESCE(tk.soluong_dat, 0) as soluong_dat,
                    COALESCE(tk.muc_canh_bao, 10) as muc_canh_bao
                  FROM tbl_sanpham_bienthe bt
                  INNER JOIN tbl_sanpham_size sz ON bt.sanpham_size_id = sz.sanpham_size_id
                  INNER JOIN tbl_color c ON bt.color_id = c.color_id
                  LEFT JOIN tbl_tonkho tk ON bt.bienthe_id = tk.bienthe_id
                  WHERE bt.sanpham_id = '$sanpham_id'
                  ORDER BY c.color_ten ASC, sz.sanpham_size ASC";
        
        return $this->db->select($query);
    }
    
    /**
     * Thêm biến thể mới
     */
    public function addVariant($sanpham_id, $color_id, $size_id, $soluong = 0) {
        // Tạo mã biến thể tự động
        $bienthe_ma = $this->generateVariantCode($sanpham_id, $color_id, $size_id);
        
        $query = "INSERT INTO tbl_sanpham_bienthe (sanpham_id, color_id, sanpham_size_id, soluong, bienthe_ma)
                  VALUES ('$sanpham_id', '$color_id', '$size_id', '$soluong', '$bienthe_ma')";
        
        $result = $this->db->insert($query);
        
        if ($result) {
            $bienthe_id = $this->db->link->insert_id;
            
            // Tạo record tồn kho tương ứng
            $this->createInventoryRecord($bienthe_id, $soluong);
            
            return $bienthe_id;
        }
        
        return false;
    }
    
    /**
     * Cập nhật biến thể
     */
    public function updateVariant($bienthe_id, $soluong) {
        $query = "UPDATE tbl_sanpham_bienthe SET soluong = '$soluong' WHERE bienthe_id = '$bienthe_id'";
        $result = $this->db->update($query);
        
        if ($result) {
            // Cập nhật tồn kho
            $this->updateInventoryStock($bienthe_id, $soluong);
        }
        
        return $result;
    }
    
    /**
     * Xóa biến thể
     */
    public function deleteVariant($bienthe_id) {
        // Xóa record tồn kho trước
        $this->db->delete("DELETE FROM tbl_tonkho WHERE bienthe_id = '$bienthe_id'");
        
        // Xóa biến thể
        $query = "DELETE FROM tbl_sanpham_bienthe WHERE bienthe_id = '$bienthe_id'";
        return $this->db->delete($query);
    }
    
    /**
     * Cập nhật tồn kho
     */
    public function updateInventoryStock($bienthe_id, $soluong_ton, $soluong_dat = null, $muc_canh_bao = null) {
        // Kiểm tra xem đã có record tồn kho chưa
        $checkQuery = "SELECT tonkho_id FROM tbl_tonkho WHERE bienthe_id = '$bienthe_id'";
        $result = $this->db->select($checkQuery);
        
        if ($result && $result->num_rows > 0) {
            // Update existing record
            $updateFields = ["soluong_ton = '$soluong_ton'"];
            
            if ($soluong_dat !== null) {
                $updateFields[] = "soluong_dat = '$soluong_dat'";
            }
            
            if ($muc_canh_bao !== null) {
                $updateFields[] = "muc_canh_bao = '$muc_canh_bao'";
            }
            
            $query = "UPDATE tbl_tonkho SET " . implode(', ', $updateFields) . " WHERE bienthe_id = '$bienthe_id'";
            return $this->db->update($query);
        } else {
            // Create new record
            return $this->createInventoryRecord($bienthe_id, $soluong_ton, $soluong_dat ?? 0, $muc_canh_bao ?? 10);
        }
    }
    
    /**
     * Tạo record tồn kho mới
     */
    private function createInventoryRecord($bienthe_id, $soluong_ton = 0, $soluong_dat = 0, $muc_canh_bao = 10) {
        $query = "INSERT INTO tbl_tonkho (bienthe_id, soluong_ton, soluong_dat, muc_canh_bao)
                  VALUES ('$bienthe_id', '$soluong_ton', '$soluong_dat', '$muc_canh_bao')";
        return $this->db->insert($query);
    }
    
    /**
     * Tạo mã biến thể tự động
     */
    private function generateVariantCode($sanpham_id, $color_id, $size_id) {
        // Lấy thông tin sản phẩm, màu, size
        $query = "SELECT sp.sanpham_ma, c.color_ten, sz.sanpham_size
                  FROM tbl_sanpham sp, tbl_color c, tbl_sanpham_size sz
                  WHERE sp.sanpham_id = '$sanpham_id' 
                  AND c.color_id = '$color_id' 
                  AND sz.sanpham_size_id = '$size_id'";
        
        $result = $this->db->select($query);
        
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $sanpham_ma = $row['sanpham_ma'];
            $color_code = strtoupper(substr($row['color_ten'], 0, 2));
            $size_code = $row['sanpham_size'];
            
            return $sanpham_ma . '-' . $color_code . '-' . $size_code;
        }
        
        return 'VAR-' . time();
    }
    
    /**
     * Kiểm tra biến thể đã tồn tại chưa
     */
    public function variantExists($sanpham_id, $color_id, $size_id, $exclude_id = null) {
        $query = "SELECT bienthe_id FROM tbl_sanpham_bienthe 
                  WHERE sanpham_id = '$sanpham_id' 
                  AND color_id = '$color_id' 
                  AND sanpham_size_id = '$size_id'";
        
        if ($exclude_id) {
            $query .= " AND bienthe_id != '$exclude_id'";
        }
        
        $result = $this->db->select($query);
        return $result && $result->num_rows > 0;
    }
    
    /**
     * Lấy thống kê tồn kho
     */
    public function getInventoryStats() {
        $query = "SELECT 
                    COUNT(*) as tong_bienthe,
                    SUM(COALESCE(tk.soluong_ton, 0)) as tong_ton_kho,
                    SUM(CASE WHEN COALESCE(tk.soluong_ton, 0) <= COALESCE(tk.muc_canh_bao, 10) THEN 1 ELSE 0 END) as canh_bao,
                    SUM(CASE WHEN COALESCE(tk.soluong_ton, 0) = 0 THEN 1 ELSE 0 END) as het_hang
                  FROM tbl_sanpham_bienthe bt
                  LEFT JOIN tbl_tonkho tk ON bt.bienthe_id = tk.bienthe_id";
        
        $result = $this->db->select($query);
        return $result ? $result->fetch_assoc() : null;
    }
    
    /**
     * Lấy sản phẩm sắp hết hàng
     */
    public function getLowStockItems($limit = 10) {
        $query = "SELECT 
                    bt.bienthe_id,
                    bt.bienthe_ma as sku,
                    sp.sanpham_tieude as ten_sanpham,
                    sp.sanpham_anh,
                    sz.sanpham_size as size,
                    c.color_ten as mau_sac,
                    COALESCE(tk.soluong_ton, 0) as soluong_ton,
                    COALESCE(tk.muc_canh_bao, 10) as muc_canh_bao
                  FROM tbl_sanpham_bienthe bt
                  INNER JOIN tbl_sanpham sp ON bt.sanpham_id = sp.sanpham_id
                  INNER JOIN tbl_sanpham_size sz ON bt.sanpham_size_id = sz.sanpham_size_id
                  INNER JOIN tbl_color c ON bt.color_id = c.color_id
                  LEFT JOIN tbl_tonkho tk ON bt.bienthe_id = tk.bienthe_id
                  WHERE COALESCE(tk.soluong_ton, 0) <= COALESCE(tk.muc_canh_bao, 10)
                  ORDER BY COALESCE(tk.soluong_ton, 0) ASC
                  LIMIT $limit";
        
        return $this->db->select($query);
    }
}