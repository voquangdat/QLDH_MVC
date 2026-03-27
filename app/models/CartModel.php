<?php
// filepath: app/models/CartModel.php (fixed stock columns)
require_once __DIR__ . '/../../config/database.php';

class CartModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    private function _getVariantStock($bienthe_id) {
        $bienthe_id = (int)$bienthe_id;
        $q = "SELECT 
                COALESCE(soluong_co_the_ban, (COALESCE(soluong_ton,0) - COALESCE(soluong_dat,0))) AS stock_can_sell,
                COALESCE(soluong_ton,0) AS soluong_ton,
                COALESCE(soluong_dat,0) AS soluong_dat,
                COALESCE(soluong_co_the_ban,0) AS soluong_co_the_ban
              FROM tbl_tonkho
              WHERE bienthe_id = {$bienthe_id}
              LIMIT 1";
        $rs = $this->db->select($q);
        if ($rs && $rs->num_rows > 0) {
            return $rs->fetch_assoc();
        }
        // Mặc định: không có bản ghi tồn kho → coi như 0
        return ['stock_can_sell' => 0, 'soluong_ton' => 0, 'soluong_dat' => 0, 'soluong_co_the_ban' => 0];
    }

    // Thêm vào giỏ dựa trên bienthe_id; gộp dòng nếu đã có
    public function addToCart($session_id, $sanpham_id, $bienthe_id, $quantity, $fallbackTitle, $fallbackImage, $fallbackPrice) {
        $session_id = $this->db->link->real_escape_string($session_id);
        $sanpham_id = (int)$sanpham_id;
        $bienthe_id = (int)$bienthe_id;
        $quantity   = max(1, (int)$quantity);

        // 1) Kiểm tra tồn kho (dùng stock_can_sell)
        $stockRow = $this->_getVariantStock($bienthe_id);
        $stock = (int)$stockRow['stock_can_sell'];

        if ($stock <= 0) {
            return ['success' => false, 'error' => 'Hết hàng'];
        }

        // 2) Nếu cart đã có cùng bienthe_id + session → cộng dồn
        $sqlFind = "SELECT cart_id, quantitys FROM tbl_cart 
                    WHERE session_idA='{$session_id}' AND bienthe_id={$bienthe_id}
                    LIMIT 1";
        $rsFind = $this->db->select($sqlFind);

        if ($rsFind && $rsFind->num_rows > 0) {
            $row = $rsFind->fetch_assoc();
            $newQty = (int)$row['quantitys'] + $quantity;
            if ($newQty > $stock) {
                return ['success' => false, 'error' => 'Vượt quá tồn kho', 'max' => $stock];
            }
            $cart_id = (int)$row['cart_id'];
            $ok = $this->db->update("UPDATE tbl_cart SET quantitys={$newQty} WHERE cart_id={$cart_id}");
            return ['success' => (bool)$ok];
        }

        // 3) Lần đầu thêm
        if ($quantity > $stock) {
            return ['success' => false, 'error' => 'Vượt quá tồn kho', 'max' => $stock];
        }

        $sanpham_tieude = $this->db->link->real_escape_string($fallbackTitle);
        $sanpham_anh    = $this->db->link->real_escape_string($fallbackImage);
        $sanpham_gia    = (float)$fallbackPrice;

        $sqlIns = "INSERT INTO tbl_cart (sanpham_anh, session_idA, sanpham_id, bienthe_id, sanpham_tieude, sanpham_gia, quantitys)
                   VALUES ('{$sanpham_anh}', '{$session_id}', {$sanpham_id}, {$bienthe_id}, '{$sanpham_tieude}', {$sanpham_gia}, {$quantity})";
        $ok = $this->db->insert($sqlIns);
        return ['success' => (bool)$ok];
    }

    public function getCartItems($session_id) {
        $session_id = $this->db->link->real_escape_string($session_id);
        $query = "
            SELECT c.*,
                   sz.sanpham_size,
                   col.color_anh
            FROM tbl_cart c
            INNER JOIN tbl_sanpham_bienthe bt ON c.bienthe_id = bt.bienthe_id
            INNER JOIN tbl_sanpham_size sz     ON bt.sanpham_size_id = sz.sanpham_size_id
            INNER JOIN tbl_color col           ON bt.color_id = col.color_id
            WHERE c.session_idA = '{$session_id}'
            ORDER BY c.cart_id DESC";
        return $this->db->select($query);
    }

    public function getMiniCartItems($session_id) {
        return $this->getCartItems($session_id);
    }

    public function removeFromCart($cart_id) {
        $cart_id = (int)$cart_id;
        $result = $this->db->delete("DELETE FROM tbl_cart WHERE cart_id = {$cart_id}");
        if ($result) {
            $check = $this->db->select("SELECT cart_id FROM tbl_cart LIMIT 1");
            if ($check == null) {
                Session::set('SL', null);
            }
        }
        return $result;
    }

    public function getTotalQuantity($session_id) {
        $cartItems = $this->getCartItems($session_id);
        $total = 0;
        if ($cartItems) {
            while ($item = $cartItems->fetch_assoc()) {
                $total += (int)$item['quantitys'];
            }
        }
        return $total;
    }

    public function getTotalAmount($session_id) {
        $cartItems = $this->getCartItems($session_id);
        $sum = 0;
        if ($cartItems) {
            while ($item = $cartItems->fetch_assoc()) {
                $sum += ((float)$item['sanpham_gia'] * (int)$item['quantitys']);
            }
        }
        return $sum;
    }

    public function isCartEmpty($session_id) {
        $cartItems = $this->getCartItems($session_id);
        return ($cartItems == null || $cartItems->num_rows == 0);
    }

    public function updateCartQuantity($cart_id, $quantity) {
        $cart_id = (int)$cart_id;
        $quantity = max(1, (int)$quantity);

        // Lấy bienthe_id từ cart
        $rs = $this->db->select("SELECT bienthe_id FROM tbl_cart WHERE cart_id={$cart_id} LIMIT 1");
        if (!$rs || $rs->num_rows == 0) return false;
        $bienthe_id = (int)$rs->fetch_assoc()['bienthe_id'];

        // Kiểm tra stock (dùng stock_can_sell)
        $stockRow = $this->_getVariantStock($bienthe_id);
        $stock = (int)$stockRow['stock_can_sell'];
        if ($quantity > $stock) {
            $quantity = $stock;
        }
        return $this->db->update("UPDATE tbl_cart SET quantitys={$quantity} WHERE cart_id={$cart_id}");
    }

    public function countCartItems($session_id) {
        $session_id = $this->db->link->real_escape_string($session_id);
        $result = $this->db->select("SELECT COUNT(*) as total FROM tbl_cart WHERE session_idA = '{$session_id}'");
        if ($result) {
            $row = $result->fetch_assoc();
            return (int)$row['total'];
        }
        return 0;
    }

    public function clearCart($session_id) {
        $session_id = $this->db->link->real_escape_string($session_id);
        $result = $this->db->delete("DELETE FROM tbl_cart WHERE session_idA = '{$session_id}'");
        if ($result) {
            Session::set('SL', null);
        }
        return $result;
    }

    public function getCartTotal($session_id) {
        $session_id = $this->db->link->real_escape_string($session_id);
        $result = $this->db->select("SELECT SUM(sanpham_gia * quantitys) as total FROM tbl_cart WHERE session_idA = '{$session_id}'");
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return (float)($row['total'] ?? 0);
        }
        return 0;
    }
}
