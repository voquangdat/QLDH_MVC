<?php
require_once __DIR__ . '/../../models/CartModel.php';
require_once __DIR__ . '/../../models/ProductModel.php';

class CartController {
    private $cartModel;
    private $productModel;

    public function __construct() {
        $this->cartModel = new CartModel();
        $this->productModel = new ProductModel();
    }

    // Trang giỏ hàng
    public function index() {
        if (!isset($_GET['id'])) {
            header("Location: index.php?page=cart&id=live");
            exit;
        }

        $session_id   = session_id();
        $cartItems    = $this->cartModel->getCartItems($session_id);
        $cartTotal    = $this->cartModel->getCartTotal($session_id);
        $cartCount    = $this->cartModel->countCartItems($session_id);
        $totalQuantity= $this->cartModel->getTotalQuantity($session_id);

        $data = [
            'cartItems'      => $cartItems,
            'cartTotal'      => $cartTotal,
            'cartCount'      => $cartCount,
            'totalQuantity'  => $totalQuantity,
            'pageTitle'      => 'Giỏ hàng - VoxFootball',
            'pageClass'      => 'cart'
        ];
        $this->loadView('cart', $data);
    }

    // AJAX: thêm vào giỏ
    public function addToCart() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $session_id       = $_POST['session_id'] ?? session_id();
            $sanpham_id       = $_POST['sanpham_id'];
            $bienthe_id       = $_POST['bienthe_id'];
            $quantitys        = $_POST['quantitys'] ?? 1;

            // Các thông tin dự phòng để lưu vào cart (đảm bảo hiển thị nhanh không cần join SP)
            $sanpham_tieude   = $_POST['sanpham_tieude'];
            $sanpham_anh      = $_POST['sanpham_anh'];
            $sanpham_gia      = $_POST['sanpham_gia'];

            $res = $this->cartModel->addToCart(
                $session_id,
                $sanpham_id,
                $bienthe_id,
                $quantitys,
                $sanpham_tieude,
                $sanpham_anh,
                $sanpham_gia
            );

            if ($res['success']) {
                $cartQty   = $this->cartModel->getTotalQuantity($session_id); // tổng quantity
                $cartTotal = $this->cartModel->getCartTotal($session_id);     // tổng tiền
                Session::set('SL', $cartQty);

                echo json_encode([
                    'success'    => true,
                    'message'    => 'Đã thêm vào giỏ hàng thành công',
                    'cart_qty'   => $cartQty,
                    'cart_total' => $cartTotal
                ]);
                return;
            }


            // Cập nhật session count
            $cartCount = $this->cartModel->countCartItems($session_id);
            Session::set('SL', $cartCount);
            echo json_encode(['success' => true, 'message' => 'Đã thêm vào giỏ hàng']);
        }
    }

    public function removeItem() {
        $cart_id = $_GET['cart_id'] ?? '';
        if ($cart_id) {
            $result = $this->cartModel->removeFromCart($cart_id);
            if ($result) {
                $session_id = session_id();
                $cartCount = $this->cartModel->countCartItems($session_id);
                Session::set('SL', $cartCount);
            }
        }
        header('Location: index.php?page=cart&id=live');
        exit;
    }

    public function updateQuantity() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $cart_id  = $_POST['cart_id'];
            $quantity = $_POST['quantity'];

            $ok = $this->cartModel->updateCartQuantity($cart_id, $quantity);
            if ($ok) {
                $session_id = session_id();
                $totalQuantity = $this->cartModel->getTotalQuantity($session_id);
                Session::set('SL', $totalQuantity);
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false]);
            }
        }
    }

    public function getMiniCartData($session_id) {
        return $this->cartModel->getMiniCartItems($session_id);
    }

    public function getCartCount($session_id) {
        return $this->cartModel->countCartItems($session_id);
    }

    public function clearCart() {
        $session_id = session_id();
        $result = $this->cartModel->clearCart($session_id);
        if ($result) {
            header('Location: cart.php?id=live');
        }
        return $result;
    }

    private function loadView($view, $data = []) {
        extract($data);
        include APP_PATH . '/views/layouts/header.php';
        include APP_PATH . '/views/frontend_views/' . $view . '.php';
        include APP_PATH . '/views/layouts/footer.php';
    }
}
