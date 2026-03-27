<?php
// filepath: e:\QLDH_MVC\app\models\ProductModel.php
require_once __DIR__ . '/../../config/database.php';

class ProductModel {
    private $db;
    
    public function __construct() {
        $this->db = new Database();
    }
    
    // /**
    //  * Lấy sản phẩm nổi bật
    //  */
    public function getFeaturedProducts($limit = 6) {
        $query = "SELECT 
                    sanpham_id as id,
                    sanpham_tieude as name,
                    sanpham_gia as price,
                    sanpham_giakhuyenmai as old_price,
                    sanpham_anh as image_main,
                    sanpham_anhkhac as image_hover,
                    ROUND(((sanpham_giakhuyenmai - sanpham_gia) / sanpham_giakhuyenmai * 100)) as discount
                  FROM tbl_sanpham 
                  WHERE sanpham_hot = 1 
                  ORDER BY sanpham_id DESC
                  LIMIT $limit";
        
        $result = $this->db->select($query);
        $products = [];
        
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $products[] = $row;
            }
        }
        
        return $products;
    }
    
    public function getProductsByCategory($loaisanpham_id, $orderBy = 'sanpham_id DESC') {
        $query = "SELECT tbl_sanpham.*, tbl_danhmuc.danhmuc_ten, tbl_loaisanpham.loaisanpham_ten
                  FROM tbl_sanpham 
                  INNER JOIN tbl_danhmuc ON tbl_sanpham.danhmuc_id = tbl_danhmuc.danhmuc_id
                  INNER JOIN tbl_loaisanpham ON tbl_sanpham.loaisanpham_id = tbl_loaisanpham.loaisanpham_id
                  WHERE tbl_sanpham.loaisanpham_id = '$loaisanpham_id'
                  ORDER BY tbl_sanpham.$orderBy";
        return $this->db->select($query);
    }
    
    public function getProductById($sanpham_id) {
        $sanpham_id = (int)$sanpham_id;
        $query = "SELECT sp.*, dm.danhmuc_ten, lsp.loaisanpham_ten, c.color_ten, c.color_anh
                  FROM tbl_sanpham sp
                  LEFT JOIN tbl_danhmuc dm ON sp.danhmuc_id = dm.danhmuc_id
                  LEFT JOIN tbl_loaisanpham lsp ON sp.loaisanpham_id = lsp.loaisanpham_id
                  LEFT JOIN tbl_color c ON sp.color_id = c.color_id
                  WHERE sp.sanpham_id = {$sanpham_id}
                  LIMIT 1";
        $rs = $this->db->select($query);
        return ($rs && $rs->num_rows > 0) ? $rs->fetch_assoc() : null;
    }
    
    /**
     * Kiểm tra mã sản phẩm có tồn tại không
     */
    public function getProductByCode($sanpham_ma, $exclude_id = null) {
        $sanpham_ma = $this->db->link->real_escape_string($sanpham_ma);
        $query = "SELECT sanpham_id FROM tbl_sanpham WHERE sanpham_ma = '$sanpham_ma'";
        
        if ($exclude_id) {
            $query .= " AND sanpham_id != '$exclude_id'";
        }
        
        $result = $this->db->select($query);
        
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        
        return null;
    }
    
    public function getRelatedProducts($loaisanpham_id, $exclude_id, $limit = 8) {
        $loaisanpham_id = (int)$loaisanpham_id;
        $exclude_id = (int)$exclude_id;
        $limit = (int)$limit;
        $query = "SELECT sp.*, dm.danhmuc_ten, lsp.loaisanpham_ten
                  FROM tbl_sanpham sp
                  LEFT JOIN tbl_danhmuc dm ON sp.danhmuc_id = dm.danhmuc_id
                  LEFT JOIN tbl_loaisanpham lsp ON sp.loaisanpham_id = lsp.loaisanpham_id
                  WHERE sp.loaisanpham_id = {$loaisanpham_id} AND sp.sanpham_id <> {$exclude_id}
                  ORDER BY sp.sanpham_id DESC
                  LIMIT {$limit}";
        return $this->db->select($query);
    }
    
    public function getProductImages($sanpham_id) {
        $sanpham_id = (int)$sanpham_id;
        $query = "SELECT * FROM tbl_sanpham_anh WHERE sanpham_id = {$sanpham_id} ORDER BY sanpham_anh_id DESC";
        return $this->db->select($query);
    }
    
    // public function getProductSizes($sanpham_id) {
    //     $sanpham_id = (int)$sanpham_id;
    //     $query = "SELECT * FROM tbl_sanpham_size WHERE sanpham_id = {$sanpham_id} ORDER BY sanpham_size_id DESC";
    //     return $this->db->select($query);
    // }
    public function getProductSizes($sanpham_id, $onlyAvailable = false) {
        $sanpham_id = (int)$sanpham_id;

        // Gom các biến thể theo size, tính tổng tồn có thể bán của mỗi size
        $query = "
            SELECT 
                sz.sanpham_size_id,
                sz.sanpham_size,
                -- lấy 1 bienthe_id mẫu theo size (hữu ích nếu cần map nhanh)
                MIN(bt.bienthe_id) AS sample_bienthe_id,
                -- tổng tồn có thể bán cho size này (cộng tất cả biến thể màu thuộc size)
                SUM(
                COALESCE(tk.soluong_co_the_ban, 
                        COALESCE(tk.soluong_ton,0) - COALESCE(tk.soluong_dat,0))
                ) AS stock_available
            FROM tbl_sanpham_bienthe bt
            INNER JOIN tbl_sanpham_size sz ON bt.sanpham_size_id = sz.sanpham_size_id
            LEFT JOIN tbl_tonkho tk ON tk.bienthe_id = bt.bienthe_id
            WHERE bt.sanpham_id = {$sanpham_id}
            GROUP BY sz.sanpham_size_id, sz.sanpham_size
            ORDER BY sz.sanpham_size ASC
        ";

        if ($onlyAvailable) {
            // Lọc chỉ size có tồn khả dụng > 0
            $query = "
                SELECT * FROM (
                    {$query}
                ) AS t
                WHERE t.stock_available > 0
            ";
        }

        return $this->db->select($query);
    }


    public function getProductVariantsWithStock($sanpham_id) {
        $sanpham_id = (int)$sanpham_id;
        $query = "SELECT 
                    bt.bienthe_id,
                    bt.sanpham_id,
                    bt.color_id,
                    bt.sanpham_size_id,
                    sz.sanpham_size,
                    col.color_ten, 
                    col.color_anh,
                    COALESCE(tk.soluong_co_the_ban, (COALESCE(tk.soluong_ton,0) - COALESCE(tk.soluong_dat,0))) AS soluong_co_the_ban
                  FROM tbl_sanpham_bienthe bt
                  INNER JOIN tbl_sanpham_size sz ON bt.sanpham_size_id = sz.sanpham_size_id
                  INNER JOIN tbl_color col ON bt.color_id = col.color_id
                  LEFT JOIN tbl_tonkho tk ON tk.bienthe_id = bt.bienthe_id
                  WHERE bt.sanpham_id = {$sanpham_id}
                  ORDER BY sz.sanpham_size ASC, bt.bienthe_id ASC";
        return $this->db->select($query);
    }

    /** Lấy tồn kho theo biến thể */
    public function getVariantStock($bienthe_id) {
        $bienthe_id = (int)$bienthe_id;
        $query = "SELECT 
                    COALESCE(soluong_co_the_ban, (COALESCE(soluong_ton,0) - COALESCE(soluong_dat,0))) AS stock_can_sell,
                    COALESCE(soluong_ton,0) AS soluong_ton,
                    COALESCE(soluong_dat,0) AS soluong_dat,
                    COALESCE(soluong_co_the_ban,0) AS soluong_co_the_ban
                  FROM tbl_tonkho WHERE bienthe_id = {$bienthe_id} LIMIT 1";
        $rs = $this->db->select($query);
        return ($rs && $rs->num_rows > 0) ? $rs->fetch_assoc() : ['stock_can_sell' => 0, 'soluong_ton' => 0, 'soluong_dat' => 0, 'soluong_co_the_ban' => 0];
    }



    
    // ==================== ADMIN MANAGEMENT METHODS ====================
    
    /**
     * Lấy tất cả sản phẩm với đầy đủ thông tin
     */
    public function getAllProductsForAdmin() {
        $query = "SELECT sp.*, dm.danhmuc_ten, lsp.loaisanpham_ten, c.color_ten, c.color_anh
                  FROM tbl_sanpham sp 
                  LEFT JOIN tbl_danhmuc dm ON sp.danhmuc_id = dm.danhmuc_id 
                  LEFT JOIN tbl_loaisanpham lsp ON sp.loaisanpham_id = lsp.loaisanpham_id 
                  LEFT JOIN tbl_color c ON sp.color_id = c.color_id
                  ORDER BY sp.sanpham_id DESC";
        return $this->db->select($query);
    }
    
    /**
     * Thêm sản phẩm mới và tự động tạo biến thể
     */
    public function addProduct($data) {
        // Begin transaction
        $this->db->link->autocommit(FALSE);
        
        try {
            $sanpham_tieude = $this->db->link->real_escape_string($data['sanpham_tieude']);
            $sanpham_ma = $this->db->link->real_escape_string($data['sanpham_ma']);
            $danhmuc_id = $data['danhmuc_id'];
            $loaisanpham_id = $data['loaisanpham_id'];
            $color_id = $data['color_id'];
            $sanpham_gia = $data['sanpham_gia'];
            $sanpham_giakhuyenmai = $data['sanpham_giakhuyenmai'] ?? 0;
            $sanpham_chitiet = $this->db->link->real_escape_string($data['sanpham_chitiet']);
            $sanpham_baoquan = $this->db->link->real_escape_string($data['sanpham_baoquan']);
            $sanpham_anh = $this->db->link->real_escape_string($data['sanpham_anh']);
            $sanpham_anhkhac = $this->db->link->real_escape_string($data['sanpham_anhkhac'] ?? '');
            $sanpham_hot = $data['sanpham_hot'] ?? 0;
            
            // 1. Thêm sản phẩm
            $query = "INSERT INTO tbl_sanpham (
                        sanpham_tieude, sanpham_ma, danhmuc_id, loaisanpham_id, color_id,
                        sanpham_gia, sanpham_giakhuyenmai, sanpham_chitiet, sanpham_baoquan,
                        sanpham_anh, sanpham_anhkhac, sanpham_hot
                      ) VALUES (
                        '$sanpham_tieude', '$sanpham_ma', '$danhmuc_id', '$loaisanpham_id', '$color_id',
                        '$sanpham_gia', '$sanpham_giakhuyenmai', '$sanpham_chitiet', '$sanpham_baoquan',
                        '$sanpham_anh', '$sanpham_anhkhac', '$sanpham_hot'
                      )";
            
            $result = $this->db->insert($query);
            
            if (!$result) {
                throw new Exception('Không thể thêm sản phẩm');
            }
            
            $sanpham_id = $this->db->link->insert_id;
            
            // 2. Thêm sizes cho sản phẩm
            if (isset($data['sanpham_sizes']) && !empty($data['sanpham_sizes'])) {
                foreach ($data['sanpham_sizes'] as $size) {
                    $this->addProductSize($sanpham_id, $size);
                }
                
                // 3. Tự động tạo biến thể cho tất cả sizes với màu chính
                $this->createProductVariants($sanpham_id, $color_id, $data['sanpham_sizes']);
            }
            
            // Commit transaction
            $this->db->link->commit();
            $this->db->link->autocommit(TRUE);
            
            return $sanpham_id;
            
        } catch (Exception $e) {
            // Rollback transaction
            $this->db->link->rollback();
            $this->db->link->autocommit(TRUE);
            error_log("Error adding product: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Tự động tạo biến thể sản phẩm
     */
    private function createProductVariants($sanpham_id, $color_id, $sizes) {
        // Lấy thông tin sản phẩm và màu để tạo mã SKU
        $productInfo = $this->getProductBasicInfo($sanpham_id);
        $colorInfo = $this->getColorInfo($color_id);
        
        if (!$productInfo || !$colorInfo) {
            throw new Exception('Không thể lấy thông tin sản phẩm hoặc màu sắc');
        }
        
        $sanpham_ma = $productInfo['sanpham_ma'];
        $color_code = strtoupper(substr($colorInfo['color_ten'], 0, 2));
        
        // Lấy danh sách size IDs
        $sizeIds = $this->getProductSizeIds($sanpham_id, $sizes);
        
        foreach ($sizeIds as $sizeData) {
            $size_id = $sizeData['sanpham_size_id'];
            $size_code = $sizeData['sanpham_size'];
            
            // Tạo mã SKU unique
            $bienthe_ma = $sanpham_ma . '-' . $color_code . '-' . $size_code;
            
            // Thêm biến thể vào database
            $variantQuery = "INSERT INTO tbl_sanpham_bienthe (sanpham_id, color_id, sanpham_size_id, soluong, bienthe_ma, created_at, updated_at)
                           VALUES ('$sanpham_id', '$color_id', '$size_id', 0, '$bienthe_ma', NOW(), NOW())";
            
            $variantResult = $this->db->insert($variantQuery);
            
            if ($variantResult) {
                $bienthe_id = $this->db->link->insert_id;
                
                // Tạo record tồn kho với số lượng ban đầu = 0
                $inventoryQuery = "INSERT INTO tbl_tonkho (bienthe_id, soluong_ton, soluong_dat, muc_canh_bao, created_at, updated_at)
                                 VALUES ('$bienthe_id', 0, 0, 10, NOW(), NOW())";
                
                $this->db->insert($inventoryQuery);
            }
        }
    }
    
    /**
     * Lấy thông tin cơ bản của sản phẩm
     */
    private function getProductBasicInfo($sanpham_id) {
        $query = "SELECT sanpham_ma FROM tbl_sanpham WHERE sanpham_id = '$sanpham_id'";
        $result = $this->db->select($query);
        return $result ? $result->fetch_assoc() : null;
    }
    
    /**
     * Lấy thông tin màu sắc
     */
    private function getColorInfo($color_id) {
        $query = "SELECT color_ten FROM tbl_color WHERE color_id = '$color_id'";
        $result = $this->db->select($query);
        return $result ? $result->fetch_assoc() : null;
    }
    
    /**
     * Lấy danh sách Size IDs từ tên sizes
     */
    private function getProductSizeIds($sanpham_id, $sizes) {
        $sizeList = "'" . implode("','", array_map([$this->db->link, 'real_escape_string'], $sizes)) . "'";
        $query = "SELECT sanpham_size_id, sanpham_size FROM tbl_sanpham_size 
                  WHERE sanpham_id = '$sanpham_id' AND sanpham_size IN ($sizeList)";
        
        $result = $this->db->select($query);
        $sizeIds = [];
        
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $sizeIds[] = $row;
            }
        }
        
        return $sizeIds;
    }
    
    /**
     * Cập nhật sản phẩm
     */
    public function updateProduct($sanpham_id, $data) {
        $sanpham_tieude = $this->db->link->real_escape_string($data['sanpham_tieude']);
        $sanpham_ma = $this->db->link->real_escape_string($data['sanpham_ma']);
        $danhmuc_id = $data['danhmuc_id'];
        $loaisanpham_id = $data['loaisanpham_id'];
        $color_id = $data['color_id'];
        $sanpham_gia = $data['sanpham_gia'];
        $sanpham_giakhuyenmai = $data['sanpham_giakhuyenmai'] ?? 0;
        $sanpham_chitiet = $this->db->link->real_escape_string($data['sanpham_chitiet']);
        $sanpham_baoquan = $this->db->link->real_escape_string($data['sanpham_baoquan']);
        $sanpham_anh = $this->db->link->real_escape_string($data['sanpham_anh']);
        $sanpham_anhkhac = $this->db->link->real_escape_string($data['sanpham_anhkhac'] ?? '');
        $sanpham_hot = $data['sanpham_hot'] ?? 0;
        
        $query = "UPDATE tbl_sanpham SET 
                    sanpham_tieude = '$sanpham_tieude',
                    sanpham_ma = '$sanpham_ma',
                    danhmuc_id = '$danhmuc_id',
                    loaisanpham_id = '$loaisanpham_id',
                    color_id = '$color_id',
                    sanpham_gia = '$sanpham_gia',
                    sanpham_giakhuyenmai = '$sanpham_giakhuyenmai',
                    sanpham_chitiet = '$sanpham_chitiet',
                    sanpham_baoquan = '$sanpham_baoquan',
                    sanpham_anh = '$sanpham_anh',
                    sanpham_anhkhac = '$sanpham_anhkhac',
                    sanpham_hot = '$sanpham_hot'
                  WHERE sanpham_id = '$sanpham_id'";
        
        $result = $this->db->update($query);
        
        if ($result && isset($data['sanpham_sizes']) && !empty($data['sanpham_sizes'])) {
            // Xóa size cũ
            $this->deleteProductSizes($sanpham_id);
            // Thêm size mới
            foreach ($data['sanpham_sizes'] as $size) {
                $this->addProductSize($sanpham_id, $size);
            }
            
            // Tạo lại biến thể cho sizes mới (nếu cần)
            $this->updateProductVariants($sanpham_id, $color_id, $data['sanpham_sizes']);
        }
        
        return $result;
    }
    
    /**
     * Cập nhật biến thể khi thay đổi sizes
     */
    private function updateProductVariants($sanpham_id, $color_id, $new_sizes) {
        // Lấy danh sách biến thể hiện tại
        $existingVariants = $this->getExistingVariants($sanpham_id, $color_id);
        
        // Lấy sizes hiện tại
        $currentSizes = [];
        foreach ($existingVariants as $variant) {
            $currentSizes[] = $variant['sanpham_size'];
        }
        
        // Tìm sizes mới cần thêm
        $sizesToAdd = array_diff($new_sizes, $currentSizes);
        
        // Tạo biến thể cho sizes mới
        if (!empty($sizesToAdd)) {
            $this->createProductVariants($sanpham_id, $color_id, $sizesToAdd);
        }
        
        // Tìm sizes cần xóa
        $sizesToRemove = array_diff($currentSizes, $new_sizes);
        
        // Xóa biến thể cho sizes không còn sử dụng
        if (!empty($sizesToRemove)) {
            $this->removeVariantsBySizes($sanpham_id, $color_id, $sizesToRemove);
        }
    }
    
    /**
     * Lấy biến thể hiện tại của sản phẩm với màu cụ thể
     */
    private function getExistingVariants($sanpham_id, $color_id) {
        $query = "SELECT bt.*, sz.sanpham_size 
                  FROM tbl_sanpham_bienthe bt
                  INNER JOIN tbl_sanpham_size sz ON bt.sanpham_size_id = sz.sanpham_size_id
                  WHERE bt.sanpham_id = '$sanpham_id' AND bt.color_id = '$color_id'";
        
        $result = $this->db->select($query);
        $variants = [];
        
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $variants[] = $row;
            }
        }
        
        return $variants;
    }
    
    /**
     * Xóa biến thể theo sizes
     */
    private function removeVariantsBySizes($sanpham_id, $color_id, $sizes) {
        foreach ($sizes as $size) {
            $sizeInfo = $this->getSizeIdByName($sanpham_id, $size);
            if ($sizeInfo) {
                $size_id = $sizeInfo['sanpham_size_id'];
                
                // Lấy bienthe_id để xóa tồn kho
                $variantQuery = "SELECT bienthe_id FROM tbl_sanpham_bienthe 
                               WHERE sanpham_id = '$sanpham_id' AND color_id = '$color_id' AND sanpham_size_id = '$size_id'";
                $variantResult = $this->db->select($variantQuery);
                
                if ($variantResult && $variantResult->num_rows > 0) {
                    $variant = $variantResult->fetch_assoc();
                    $bienthe_id = $variant['bienthe_id'];
                    
                    // Xóa tồn kho
                    $this->db->delete("DELETE FROM tbl_tonkho WHERE bienthe_id = '$bienthe_id'");
                    
                    // Xóa biến thể
                    $this->db->delete("DELETE FROM tbl_sanpham_bienthe WHERE bienthe_id = '$bienthe_id'");
                }
            }
        }
    }
    
    /**
     * Lấy size ID theo tên size
     */
    private function getSizeIdByName($sanpham_id, $size_name) {
        $size_name = $this->db->link->real_escape_string($size_name);
        $query = "SELECT sanpham_size_id FROM tbl_sanpham_size 
                  WHERE sanpham_id = '$sanpham_id' AND sanpham_size = '$size_name'";
        $result = $this->db->select($query);
        return $result ? $result->fetch_assoc() : null;
    }
    
    /**
     * Xóa sản phẩm
     */
    public function deleteProduct($sanpham_id) {
        // Begin transaction
        $this->db->link->autocommit(FALSE);
        
        try {
            // Lấy danh sách biến thể để xóa tồn kho
            $variantsQuery = "SELECT bienthe_id FROM tbl_sanpham_bienthe WHERE sanpham_id = '$sanpham_id'";
            $variants = $this->db->select($variantsQuery);
            
            if ($variants) {
                while ($variant = $variants->fetch_assoc()) {
                    $bienthe_id = $variant['bienthe_id'];
                    // Xóa tồn kho
                    $this->db->delete("DELETE FROM tbl_tonkho WHERE bienthe_id = '$bienthe_id'");
                }
            }
            
            // Xóa biến thể
            $this->db->delete("DELETE FROM tbl_sanpham_bienthe WHERE sanpham_id = '$sanpham_id'");
            
            // Xóa ảnh sản phẩm
            $this->db->delete("DELETE FROM tbl_sanpham_anh WHERE sanpham_id = '$sanpham_id'");
            
            // Xóa size sản phẩm
            $this->db->delete("DELETE FROM tbl_sanpham_size WHERE sanpham_id = '$sanpham_id'");
            
            // Xóa sản phẩm
            $query = "DELETE FROM tbl_sanpham WHERE sanpham_id = '$sanpham_id'";
            $result = $this->db->delete($query);
            
            if (!$result) {
                throw new Exception('Không thể xóa sản phẩm');
            }
            
            // Commit transaction
            $this->db->link->commit();
            $this->db->link->autocommit(TRUE);
            
            return true;
            
        } catch (Exception $e) {
            // Rollback transaction
            $this->db->link->rollback();
            $this->db->link->autocommit(TRUE);
            error_log("Error deleting product: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Lấy sản phẩm theo ID cho admin
     */
    public function getProductByIdForAdmin($sanpham_id) {
        $query = "SELECT sp.*, dm.danhmuc_ten, lsp.loaisanpham_ten, c.color_ten, c.color_anh
                  FROM tbl_sanpham sp 
                  LEFT JOIN tbl_danhmuc dm ON sp.danhmuc_id = dm.danhmuc_id 
                  LEFT JOIN tbl_loaisanpham lsp ON sp.loaisanpham_id = lsp.loaisanpham_id 
                  LEFT JOIN tbl_color c ON sp.color_id = c.color_id
                  WHERE sp.sanpham_id = '$sanpham_id'";
        $result = $this->db->select($query);
        return $result ? $result->fetch_assoc() : null;
    }
    
    /**
     * Thêm ảnh sản phẩm
     */
    public function addProductImage($sanpham_id, $sanpham_anh) {
        $sanpham_anh = $this->db->link->real_escape_string($sanpham_anh);
        $query = "INSERT INTO tbl_sanpham_anh (sanpham_id, sanpham_anh) VALUES ('$sanpham_id', '$sanpham_anh')";
        return $this->db->insert($query);
    }
    
    /**
     * Xóa ảnh sản phẩm theo ID
     */
    public function deleteProductImageById($sanpham_anh_id) {
        $query = "DELETE FROM tbl_sanpham_anh WHERE sanpham_anh_id = '$sanpham_anh_id'";
        return $this->db->delete($query);
    }
    
    /**
     * Thêm size sản phẩm
     */
    public function addProductSize($sanpham_id, $sanpham_size) {
        $sanpham_size = $this->db->link->real_escape_string($sanpham_size);
        $query = "INSERT INTO tbl_sanpham_size (sanpham_id, sanpham_size) VALUES ('$sanpham_id', '$sanpham_size')";
        return $this->db->insert($query);
    }
    
    /**
     * Xóa size sản phẩm theo ID
     */
    public function deleteProductSizeById($sanpham_size_id) {
        $query = "DELETE FROM tbl_sanpham_size WHERE sanpham_size_id = '$sanpham_size_id'";
        return $this->db->delete($query);
    }
    
    /**
     * Xóa tất cả size của sản phẩm
     */
    public function deleteProductSizes($sanpham_id) {
        $query = "DELETE FROM tbl_sanpham_size WHERE sanpham_id = '$sanpham_id'";
        return $this->db->delete($query);
    }
    
    /**
     * Kiểm tra mã sản phẩm đã tồn tại chưa
     */
    public function isProductCodeExists($sanpham_ma, $exclude_id = null) {
        $query = "SELECT COUNT(*) as count FROM tbl_sanpham WHERE sanpham_ma = '$sanpham_ma'";
        if ($exclude_id) {
            $query .= " AND sanpham_id != '$exclude_id'";
        }
        $result = $this->db->select($query);
        if ($result) {
            $row = $result->fetch_assoc();
            return $row['count'] > 0;
        }
        return false;
    }
}
?>