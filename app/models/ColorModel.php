<?php
require_once __DIR__ . '/../../config/database.php';

class ColorModel {
    private $db;
    
    public function __construct() {
        $this->db = new Database();
    }
    
    /**
     * Lấy tất cả màu sắc
     */
    public function getAllColors() {
        $query = "SELECT * FROM tbl_color ORDER BY color_id DESC";
        return $this->db->select($query);
    }
    
    /**
     * Lấy màu sắc theo ID
     */
    public function getColorById($color_id) {
        $query = "SELECT * FROM tbl_color WHERE color_id = '$color_id'";
        $result = $this->db->select($query);
        return $result ? $result->fetch_assoc() : null;
    }
    
    /**
     * Thêm màu sắc mới
     */
    public function addColor($color_ten, $color_anh) {
        $color_ten = $this->db->link->real_escape_string($color_ten);
        $color_anh = $this->db->link->real_escape_string($color_anh);
        $query = "INSERT INTO tbl_color (color_ten, color_anh) VALUES ('$color_ten', '$color_anh')";
        return $this->db->insert($query);
    }
    
    /**
     * Cập nhật màu sắc
     */
    public function updateColor($color_id, $color_ten, $color_anh) {
        $color_ten = $this->db->link->real_escape_string($color_ten);
        $color_anh = $this->db->link->real_escape_string($color_anh);
        $query = "UPDATE tbl_color SET color_ten = '$color_ten', color_anh = '$color_anh' WHERE color_id = '$color_id'";
        return $this->db->update($query);
    }
    
    /**
     * Xóa màu sắc
     */
    public function deleteColor($color_id) {
        // Kiểm tra xem có sản phẩm nào sử dụng màu này không
        $checkQuery = "SELECT COUNT(*) as count FROM tbl_sanpham WHERE color_id = '$color_id'";
        $result = $this->db->select($checkQuery);
        if ($result) {
            $row = $result->fetch_assoc();
            if ($row['count'] > 0) {
                return false; // Không thể xóa vì còn sản phẩm sử dụng
            }
        }
        
        $query = "DELETE FROM tbl_color WHERE color_id = '$color_id'";
        return $this->db->delete($query);
    }
}
?>