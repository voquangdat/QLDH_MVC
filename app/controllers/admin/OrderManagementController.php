<?php
// app/controllers/admin/OrderManagementController.php
require_once __DIR__ . '/../../models/OrderModel.php';

class OrderManagementController {
    private $orderModel;
    
    public function __construct() {
        $this->orderModel = new OrderModel();
        
        if (!$this->isAdminLoggedIn()) {
            header('Location: index.php?page=admin');
            exit;
        }
    }
    
    public function index() {
        $section = $_GET['section'] ?? 'index';
        $action = $_GET['action'] ?? 'list';
        $subaction = $_GET['subaction'] ?? '';
        
        // XỬ LÝ PATTERN CŨ: page=admin_order&action=order_management&subaction=...
        if ($action === 'order_management') {
            switch ($subaction) {
                case 'view':
                    $this->viewOrder();
                    return;
                default:
                    $this->listOrders();
                    return;
            }
        }
        
        // XỬ LÝ CÁC ACTION KHÁC
        if (in_array($action, ['confirm', 'process', 'deliver', 'cancel', 'update_payment'])) {
            $method = $action . 'Order';
            if ($action === 'update_payment') {
                $this->updatePaymentStatus();
            } else {
                $this->$method();
            }
            return;
        }
        
        // XỬ LÝ PATTERN MỚI: page=admin_order&section=...
        switch ($section) {
            case 'index':
                $this->showOrderIndex();
                break;
                
            case 'list':
                $this->listOrders();
                break;
                
            case 'detail':
                switch ($action) {
                    case 'view':
                        $this->viewOrder();
                        break;
                    case 'confirm':
                        $this->confirmOrder();
                        break;
                    case 'process':
                        $this->processOrder();
                        break;
                    case 'deliver':
                        $this->deliverOrder();
                        break;
                    case 'cancel':
                        $this->cancelOrder();
                        break;
                    case 'update_payment':
                        $this->updatePaymentStatus();
                        break;
                    default:
                        $this->viewOrder();
                        break;
                }
                break;
                
            default:
                $this->listOrders();
                break;
        }
    }
    
    private function showOrderIndex() {
        $data = [
            'pageTitle' => 'Quản lý Đơn hàng - VoxFootball Admin',
            'admin_name' => $_SESSION['admin_name'] ?? 'Admin'
        ];
        
        $this->loadView('order_management/index', $data);
    }
    
    private function listOrders() {
        $page = isset($_GET['p']) ? (int)$_GET['p'] : 1;
        $limit = 20;
        $offset = ($page - 1) * $limit;
        
        $filters = [
            'order_status' => $_GET['order_status'] ?? '',
            'payment_status' => $_GET['payment_status'] ?? '',
            'search' => $_GET['search'] ?? '',
            'date_from' => $_GET['date_from'] ?? '',
            'date_to' => $_GET['date_to'] ?? ''
        ];
        
        $orders = $this->orderModel->getAllOrders($limit, $offset, $filters);
        $totalOrders = $this->orderModel->countOrders($filters);
        $totalPages = ceil($totalOrders / $limit);
        $statistics = $this->orderModel->getOrderStatistics();
        
        $data = [
            'orders' => $orders,
            'totalOrders' => $totalOrders,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'filters' => $filters,
            'statistics' => $statistics,
            'pageTitle' => 'Quản lý Đơn hàng',
            'admin_name' => $_SESSION['admin_name'] ?? 'Admin'
        ];
        
        $this->loadView('order_management/list', $data);
    }
    
    private function viewOrder() {
        $orderId = $_GET['id'] ?? null;
        
        if (!$orderId) {
            $this->setFlashMessage('error', 'Không tìm thấy đơn hàng');
            header('Location: index.php?page=admin_order&section=list');
            exit;
        }
        
        $orderData = $this->orderModel->getOrderDetailsForAdmin($orderId);
        
        if (!$orderData) {
            $this->setFlashMessage('error', 'Không tìm thấy đơn hàng');
            header('Location: index.php?page=admin_order&section=list');
            exit;
        }
        
        $stockCheck = $this->orderModel->checkStockAvailability($orderId);
        
        $data = [
            'order' => $orderData['order'],
            'details' => $orderData['details'],
            'history' => $orderData['history'],
            'payment' => $orderData['payment'],
            'stockCheck' => $stockCheck,
            'pageTitle' => 'Chi tiết Đơn hàng #' . $orderId,
            'admin_name' => $_SESSION['admin_name'] ?? 'Admin'
        ];
        
        $this->loadView('order_management/view', $data);
    }
    
    private function confirmOrder() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=admin_order&section=list');
            exit;
        }
        
        $orderId = $_POST['order_id'] ?? null;
        
        if (!$orderId) {
            $this->setFlashMessage('error', 'Không tìm thấy đơn hàng');
            header('Location: index.php?page=admin_order&section=list');
            exit;
        }
        
        $adminId = $_SESSION['admin_id'] ?? null;
        $result = $this->orderModel->confirmOrder($orderId, $adminId);
        
        if ($result['success']) {
            $this->setFlashMessage('success', $result['message']);
        } else {
            $this->setFlashMessage('error', $result['message']);
        }
        
        header('Location: index.php?page=admin_order&action=order_management&subaction=view&id=' . $orderId);
        exit;
    }
    
    private function processOrder() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=admin_order&section=list');
            exit;
        }
        
        $orderId = $_POST['order_id'] ?? null;
        
        if (!$orderId) {
            $this->setFlashMessage('error', 'Không tìm thấy đơn hàng');
            header('Location: index.php?page=admin_order&section=list');
            exit;
        }
        
        $adminId = $_SESSION['admin_id'] ?? null;
        $result = $this->orderModel->processOrder($orderId, $adminId);
        
        if ($result['success']) {
            $this->setFlashMessage('success', $result['message']);
        } else {
            $this->setFlashMessage('error', $result['message']);
        }
        
        header('Location: index.php?page=admin_order&action=order_management&subaction=view&id=' . $orderId);
        exit;
    }
    
    private function deliverOrder() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=admin_order&section=list');
            exit;
        }
        
        $orderId = $_POST['order_id'] ?? null;
        
        if (!$orderId) {
            $this->setFlashMessage('error', 'Không tìm thấy đơn hàng');
            header('Location: index.php?page=admin_order&section=list');
            exit;
        }
        
        $adminId = $_SESSION['admin_id'] ?? null;
        $result = $this->orderModel->deliverOrder($orderId, $adminId);
        
        if ($result['success']) {
            $this->setFlashMessage('success', $result['message']);
        } else {
            $this->setFlashMessage('error', $result['message']);
        }
        
        header('Location: index.php?page=admin_order&action=order_management&subaction=view&id=' . $orderId);
        exit;
    }
    
    private function cancelOrder() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=admin_order&section=list');
            exit;
        }
        
        $orderId = $_POST['order_id'] ?? null;
        $note = $_POST['cancel_note'] ?? '';
        
        if (!$orderId) {
            $this->setFlashMessage('error', 'Không tìm thấy đơn hàng');
            header('Location: index.php?page=admin_order&section=list');
            exit;
        }
        
        $adminId = $_SESSION['admin_id'] ?? null;
        $result = $this->orderModel->cancelOrder($orderId, $note, $adminId);
        
        if ($result['success']) {
            $this->setFlashMessage('success', $result['message']);
        } else {
            $this->setFlashMessage('error', $result['message']);
        }
        
        header('Location: index.php?page=admin_order&action=order_management&subaction=view&id=' . $orderId);
        exit;
    }
    
    private function updatePaymentStatus() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=admin_order&section=list');
            exit;
        }
        
        $orderId = $_POST['order_id'] ?? null;
        $paymentStatus = $_POST['payment_status'] ?? null;
        
        if (!$orderId || !$paymentStatus) {
            $this->setFlashMessage('error', 'Dữ liệu không hợp lệ');
            header('Location: index.php?page=admin_order&section=list');
            exit;
        }
        
        $result = $this->orderModel->updatePaymentStatus($orderId, $paymentStatus);
        
        if ($result) {
            $this->setFlashMessage('success', 'Cập nhật trạng thái thanh toán thành công');
        } else {
            $this->setFlashMessage('error', 'Lỗi cập nhật trạng thái thanh toán');
        }
        
        header('Location: index.php?page=admin_order&action=order_management&subaction=view&id=' . $orderId);
        exit;
    }
    
    private function setFlashMessage($type, $message) {
        $_SESSION['flash_' . $type] = $message;
    }
    
    private function isAdminLoggedIn() {
        return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
    }
    
    private function loadView($view, $data = []) {
        extract($data);
        include APP_PATH . '/views/admin/' . $view . '.php';
    }
}
?>