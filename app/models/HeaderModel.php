<?php
// require_once '/config/database.php';  
require_once __DIR__ . '/../../config/database.php';
class HeaderModel {
    private $db;
    
    public function __construct() {
        $this->db = new Database();
    }
    
    /**
     * Lấy tất cả danh mục cho menu chính
     */
    public function getAllCategories() {
        $query = "SELECT danhmuc_id, danhmuc_ten FROM tbl_danhmuc ORDER BY danhmuc_id ASC";
        $result = $this->db->select($query);
        return $result;
    }
    
    /**
     * Lấy loại sản phẩm theo danh mục cho submenu
     */
    public function getSubCategoriesByCategory($danhmuc_id) {
        $query = "SELECT loaisanpham_id, loaisanpham_ten 
                 FROM tbl_loaisanpham 
                 WHERE danhmuc_id = '$danhmuc_id' 
                 ORDER BY loaisanpham_id ASC";
        $result = $this->db->select($query);
        return $result;
    }
    
    public function get_loaisanpham($loaisanpham_id){
    $query = "SELECT tbl_sanpham.*, tbl_danhmuc.danhmuc_ten,tbl_loaisanpham.loaisanpham_ten
    FROM tbl_sanpham INNER JOIN tbl_danhmuc ON tbl_sanpham.danhmuc_id = tbl_danhmuc.danhmuc_id
    INNER JOIN tbl_loaisanpham ON tbl_sanpham.loaisanpham_id = tbl_loaisanpham.loaisanpham_id
    WHERE tbl_sanpham.loaisanpham_id = '$loaisanpham_id'
    ORDER BY tbl_sanpham.sanpham_id DESC  ";
    $result = $this -> db ->select($query);
    return $result;
    }
    /**
     * Lấy giỏ hàng mini theo session
     */
    // public function getCartItems($session_id) {
    //     $query = "SELECT cart_id, sanpham_anh, sanpham_tieude, sanpham_size, quantitys, sanpham_gia
    //              FROM tbl_cart 
    //              WHERE session_idA = '$session_id' 
    //              ORDER BY cart_id DESC";
    //     $result = $this->db->select($query);
    //     return $result;
    // }
    /**
 * Lấy giỏ hàng (mini) theo session: JOIN biến thể để có size & color_anh
 */
public function getCartItems($session_id) {
    // escape an toàn
    $session_id = $this->db->link->real_escape_string($session_id);

    $query = "
        SELECT 
            c.cart_id,
            c.sanpham_anh,
            c.sanpham_tieude,
            c.sanpham_gia,
            c.quantitys,
            c.bienthe_id,
            sz.sanpham_size,
            col.color_anh
        FROM tbl_cart c
        INNER JOIN tbl_sanpham_bienthe bt ON c.bienthe_id = bt.bienthe_id
        INNER JOIN tbl_sanpham_size sz     ON bt.sanpham_size_id = sz.sanpham_size_id
        INNER JOIN tbl_color col           ON bt.color_id = col.color_id
        WHERE c.session_idA = '{$session_id}'
        ORDER BY c.cart_id DESC
    ";

    return $this->db->select($query);
}

    
    /**
     * Đếm tổng số lượng sản phẩm trong giỏ hàng
     */
    public function getCartCount($session_id) {
        $query = "SELECT SUM(quantitys) as total_quantity 
                 FROM tbl_cart 
                 WHERE session_idA = '$session_id'";
        $result = $this->db->select($query);
        
        if ($result) {
            $data = $result->fetch_assoc();
            return $data['total_quantity'] ?? 0;
        }
        return 0;
    }
    
    /**
     * Tính tổng tiền giỏ hàng
     */
    public function getCartTotal($session_id) {
        $query = "SELECT SUM(sanpham_gia * quantitys) as total_price 
                 FROM tbl_cart 
                 WHERE session_idA = '$session_id'";
        $result = $this->db->select($query);
        
        if ($result) {
            $data = $result->fetch_assoc();
            return $data['total_price'] ?? 0;
        }
        return 0;
    }
    
    /**
     * Tìm kiếm sản phẩm (cho search box)
     */
    public function searchProducts($keyword) {
        $keyword = $this->db->link->real_escape_string($keyword);
        $query = "SELECT sanpham_id, sanpham_tieude, sanpham_anh 
                 FROM tbl_sanpham 
                 WHERE sanpham_tieude LIKE '%$keyword%' 
                 OR sanpham_mota LIKE '%$keyword%'
                 LIMIT 5";
        $result = $this->db->select($query);
        return $result;
    }
}
?>