<?php
// app/controllers/admin/ReportsController.php
require_once __DIR__ . '/../../models/OrderModel.php';
require_once __DIR__ . '/../../models/ProductModel.php';
require_once __DIR__ . '/../../models/PaymentModel.php';

class ReportsController {
    private $orderModel;
    private $productModel;
    private $paymentModel;
    private $db;
    
    public function __construct() {
        $this->orderModel = new OrderModel();
        $this->productModel = new ProductModel();
        $this->paymentModel = new PaymentModel();
        
        // require_once __DIR__ . '/../../config/database.php';
        $this->db = new Database();
        
        if (!$this->isAdminLoggedIn()) {
            header('Location: index.php?page=admin');
            exit;
        }
    }
    
    public function index() {
        $action = $_GET['action'] ?? 'overview';
        
        switch ($action) {
            case 'orders':
                $this->orderReports();
                break;
            case 'products':
                $this->productReports();
                break;
            case 'revenue':
                $this->revenueReports();
                break;
            case 'overview':
            default:
                $this->overviewReports();
                break;
        }
    }
    
    /**
     * Tổng quan báo cáo
     */
    private function overviewReports() {
        $data = [
            'pageTitle' => 'Báo cáo Thống kê - VoxFootball Admin',
            'admin_name' => $_SESSION['admin_name'] ?? 'Admin'
        ];
        
        $this->loadView('reports/overview', $data);
    }
    
    /**
     * Báo cáo đơn hàng
     */
    private function orderReports() {
        // Lấy tham số thời gian
        $dateFrom = $_GET['date_from'] ?? date('Y-m-01'); // Đầu tháng hiện tại
        $dateTo = $_GET['date_to'] ?? date('Y-m-d'); // Hôm nay
        $groupBy = $_GET['group_by'] ?? 'day'; // day, week, month, year
        
        // Thống kê tổng quan
        $overallStats = $this->getOrderOverallStats($dateFrom, $dateTo);
        
        // Thống kê theo trạng thái
        $statusStats = $this->getOrderStatusStats($dateFrom, $dateTo);
        
        // Thống kê theo khu vực
        $regionStats = $this->getOrderRegionStats($dateFrom, $dateTo);
        
        // Tỷ lệ thành công/thất bại
        $successRateStats = $this->getOrderSuccessRateStats($dateFrom, $dateTo);
        
        // Xu hướng đơn hàng theo thời gian
        $trendStats = $this->getOrderTrendStats($dateFrom, $dateTo, $groupBy);
        
        $data = [
            'pageTitle' => 'Báo cáo Đơn hàng - VoxFootball Admin',
            'admin_name' => $_SESSION['admin_name'] ?? 'Admin',
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'groupBy' => $groupBy,
            'overallStats' => $overallStats,
            'statusStats' => $statusStats,
            'regionStats' => $regionStats,
            'successRateStats' => $successRateStats,
            'trendStats' => $trendStats
        ];
        
        $this->loadView('reports/orders', $data);
    }
    
    /**
     * Báo cáo sản phẩm & tồn kho
     */
    private function productReports() {
        // Debug: Test database connection
        try {
            $testQuery = "SELECT COUNT(*) as total FROM tbl_order_details";
            $testResult = $this->db->select($testQuery);
            if (!$testResult) {
                throw new Exception("Cannot connect to database or query failed");
            }
        } catch (Exception $e) {
            die("Database Error: " . $e->getMessage());
        }
        
        // Lấy tham số thời gian
        $dateFrom = $_GET['date_from'] ?? date('Y-m-01');
        $dateTo = $_GET['date_to'] ?? date('Y-m-d');
        
        // Top sản phẩm bán chạy
        $topProducts = $this->getTopSellingProducts($dateFrom, $dateTo, 20);
        
        // Sản phẩm tồn kho
        $stockStats = $this->getStockStats();
        
        // Sản phẩm sắp hết hàng
        $lowStockProducts = $this->getLowStockProducts(10);
        
        // Thống kê theo danh mục
        $categoryStats = $this->getProductCategoryStats($dateFrom, $dateTo);
        
        $data = [
            'pageTitle' => 'Báo cáo Sản phẩm & Tồn kho - VoxFootball Admin',
            'admin_name' => $_SESSION['admin_name'] ?? 'Admin',
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'topProducts' => $topProducts,
            'stockStats' => $stockStats,
            'lowStockProducts' => $lowStockProducts,
            'categoryStats' => $categoryStats
        ];
        
        $this->loadView('reports/products', $data);
    }
    
    /**
     * Báo cáo doanh thu
     */
    private function revenueReports() {
        // Lấy tham số thời gian
        $dateFrom = $_GET['date_from'] ?? date('Y-m-01');
        $dateTo = $_GET['date_to'] ?? date('Y-m-d');
        $groupBy = $_GET['group_by'] ?? 'day';
        
        // Doanh thu tổng thể
        $revenueStats = $this->getRevenueStats($dateFrom, $dateTo);
        
        // Doanh thu theo thời gian
        $revenueTrend = $this->getRevenueTrend($dateFrom, $dateTo, $groupBy);
        
        // Doanh thu theo phương thức thanh toán
        $paymentMethodStats = $this->getRevenueByPaymentMethod($dateFrom, $dateTo);
        
        // So sánh với kỳ trước
        $comparison = $this->getRevenueComparison($dateFrom, $dateTo);
        
        $data = [
            'pageTitle' => 'Báo cáo Doanh thu - VoxFootball Admin',
            'admin_name' => $_SESSION['admin_name'] ?? 'Admin',
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'groupBy' => $groupBy,
            'revenueStats' => $revenueStats,
            'revenueTrend' => $revenueTrend,
            'paymentMethodStats' => $paymentMethodStats,
            'comparison' => $comparison
        ];
        
        $this->loadView('reports/revenue', $data);
    }
    
    // ==================== HELPER METHODS ====================
    
    /**
     * Thống kê tổng quan đơn hàng
     */
    private function getOrderOverallStats($dateFrom, $dateTo) {
        $query = "SELECT 
                    COUNT(*) as total_orders,
                    SUM(CASE WHEN order_status = 'delivered' THEN 1 ELSE 0 END) as delivered_orders,
                    SUM(CASE WHEN order_status = 'cancelled' THEN 1 ELSE 0 END) as cancelled_orders,
                    SUM(CASE WHEN order_status = 'returned' THEN 1 ELSE 0 END) as returned_orders,
                    SUM(total_amount) as total_revenue,
                    AVG(total_amount) as avg_order_value
                  FROM tbl_order 
                  WHERE DATE(created_at) BETWEEN '$dateFrom' AND '$dateTo'";
        
        $result = $this->db->select($query);
        return $result ? $result->fetch_assoc() : null;
    }
    
    /**
     * Thống kê theo trạng thái đơn hàng
     */
    private function getOrderStatusStats($dateFrom, $dateTo) {
        $query = "SELECT 
                    order_status,
                    COUNT(*) as count,
                    SUM(total_amount) as revenue,
                    ROUND((COUNT(*) * 100.0 / (SELECT COUNT(*) FROM tbl_order WHERE DATE(created_at) BETWEEN '$dateFrom' AND '$dateTo')), 2) as percentage
                  FROM tbl_order 
                  WHERE DATE(created_at) BETWEEN '$dateFrom' AND '$dateTo'
                  GROUP BY order_status
                  ORDER BY count DESC";
        
        $result = $this->db->select($query);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }
    
    /**
     * Thống kê theo khu vực địa lý
     */
    private function getOrderRegionStats($dateFrom, $dateTo) {
        $query = "SELECT 
                    t.tinh_tp as province_name,
                    COUNT(o.order_id) as order_count,
                    SUM(o.total_amount) as revenue,
                    ROUND(AVG(o.total_amount), 0) as avg_order_value
                  FROM tbl_order o
                  LEFT JOIN (SELECT DISTINCT ma_tinh, tinh_tp FROM tbl_diachi) t 
                      ON o.customer_tinh = t.ma_tinh
                  WHERE DATE(o.created_at) BETWEEN '$dateFrom' AND '$dateTo'
                  GROUP BY o.customer_tinh, t.tinh_tp
                  ORDER BY order_count DESC
                  LIMIT 20";
        
        $result = $this->db->select($query);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }
    
    /**
     * Tỷ lệ thành công/thất bại
     */
    private function getOrderSuccessRateStats($dateFrom, $dateTo) {
        $query = "SELECT 
                    SUM(CASE WHEN order_status IN ('delivered', 'completed') THEN 1 ELSE 0 END) as success_count,
                    SUM(CASE WHEN order_status IN ('cancelled', 'returned', 'failed') THEN 1 ELSE 0 END) as failed_count,
                    COUNT(*) as total_count
                  FROM tbl_order 
                  WHERE DATE(created_at) BETWEEN '$dateFrom' AND '$dateTo'";
        
        $result = $this->db->select($query);
        return $result ? $result->fetch_assoc() : null;
    }
    
    /**
     * Xu hướng đơn hàng theo thời gian
     */
    private function getOrderTrendStats($dateFrom, $dateTo, $groupBy) {
        $dateFormat = $this->getDateFormat($groupBy);
        
        $query = "SELECT 
                    DATE_FORMAT(created_at, '$dateFormat') as period,
                    COUNT(*) as order_count,
                    SUM(total_amount) as revenue
                  FROM tbl_order 
                  WHERE DATE(created_at) BETWEEN '$dateFrom' AND '$dateTo'
                  GROUP BY period
                  ORDER BY period";
        
        $result = $this->db->select($query);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }
    
    /**
     * Top sản phẩm bán chạy
     */
    private function getTopSellingProducts($dateFrom, $dateTo, $limit = 20) {
        $query = "SELECT 
                    sp.sanpham_tieude as sanpham_name,
                    SUM(oi.quantity) as total_sold,
                    SUM(oi.quantity * oi.sanpham_gia) as total_revenue,
                    AVG(oi.sanpham_gia) as avg_price,
                    COUNT(DISTINCT o.order_id) as order_count
                  FROM tbl_order o
                  JOIN tbl_order_details oi ON o.order_id = oi.order_id
                  JOIN tbl_sanpham sp ON oi.sanpham_id = sp.sanpham_id
                  WHERE DATE(o.created_at) BETWEEN '$dateFrom' AND '$dateTo'
                    AND o.order_status NOT IN ('cancelled', 'failed')
                  GROUP BY oi.sanpham_id, sp.sanpham_tieude
                  ORDER BY total_sold DESC
                  LIMIT $limit";
        
        $result = $this->db->select($query);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }
    
    /**
     * Thống kê tồn kho
     */
    private function getStockStats() {
        $query = "SELECT 
                    COUNT(*) as total_products,
                    SUM(CASE WHEN soluong > 0 THEN 1 ELSE 0 END) as in_stock,
                    SUM(CASE WHEN soluong = 0 THEN 1 ELSE 0 END) as out_of_stock,
                    SUM(CASE WHEN soluong > 0 AND soluong <= 10 THEN 1 ELSE 0 END) as low_stock,
                    SUM(soluong) as total_quantity,
                    AVG(soluong) as avg_quantity
                  FROM tbl_sanpham_bienthe";
        
        $result = $this->db->select($query);
        return $result ? $result->fetch_assoc() : null;
    }
    
    /**
     * Sản phẩm sắp hết hàng
     */
    private function getLowStockProducts($limit = 10) {
        $query = "SELECT 
                    sp.sanpham_tieude as sanpham_name,
                    c.color_ten as color_name,
                    sz.sanpham_size as size_name,
                    bt.soluong
                  FROM tbl_sanpham_bienthe bt
                  JOIN tbl_sanpham sp ON bt.sanpham_id = sp.sanpham_id
                  LEFT JOIN tbl_color c ON bt.color_id = c.color_id
                  LEFT JOIN tbl_sanpham_size sz ON bt.sanpham_size_id = sz.sanpham_size_id
                  WHERE bt.soluong > 0 AND bt.soluong <= 10
                  ORDER BY bt.soluong ASC
                  LIMIT $limit";
        
        $result = $this->db->select($query);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }
    
    /**
     * Thống kê theo danh mục sản phẩm
     */
    private function getProductCategoryStats($dateFrom, $dateTo) {
        $query = "SELECT 
                    dm.danhmuc_ten as danhmuc_name,
                    COUNT(DISTINCT sp.sanpham_id) as product_count,
                    SUM(oi.quantity) as total_sold,
                    SUM(oi.quantity * oi.sanpham_gia) as revenue
                  FROM tbl_danhmuc dm
                  LEFT JOIN tbl_sanpham sp ON dm.danhmuc_id = sp.danhmuc_id
                  LEFT JOIN tbl_order_details oi ON sp.sanpham_id = oi.sanpham_id
                  LEFT JOIN tbl_order o ON oi.order_id = o.order_id
                  WHERE (o.order_id IS NULL OR DATE(o.created_at) BETWEEN '$dateFrom' AND '$dateTo')
                    AND (o.order_id IS NULL OR o.order_status NOT IN ('cancelled', 'failed'))
                  GROUP BY dm.danhmuc_id, dm.danhmuc_ten
                  ORDER BY revenue DESC";
        
        $result = $this->db->select($query);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }
    
    /**
     * Thống kê doanh thu
     */
    private function getRevenueStats($dateFrom, $dateTo) {
        $query = "SELECT 
                    SUM(total_amount) as total_revenue,
                    COUNT(*) as total_orders,
                    AVG(total_amount) as avg_order_value,
                    MAX(total_amount) as max_order_value,
                    MIN(total_amount) as min_order_value
                  FROM tbl_order 
                  WHERE DATE(created_at) BETWEEN '$dateFrom' AND '$dateTo'
                    AND order_status NOT IN ('cancelled', 'failed')";
        
        $result = $this->db->select($query);
        return $result ? $result->fetch_assoc() : null;
    }
    
    /**
     * Xu hướng doanh thu
     */
    private function getRevenueTrend($dateFrom, $dateTo, $groupBy) {
        $dateFormat = $this->getDateFormat($groupBy);
        
        $query = "SELECT 
                    DATE_FORMAT(created_at, '$dateFormat') as period,
                    SUM(total_amount) as revenue,
                    COUNT(*) as order_count,
                    AVG(total_amount) as avg_order_value
                  FROM tbl_order 
                  WHERE DATE(created_at) BETWEEN '$dateFrom' AND '$dateTo'
                    AND order_status NOT IN ('cancelled', 'failed')
                  GROUP BY period
                  ORDER BY period";
        
        $result = $this->db->select($query);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }
    
    /**
     * Doanh thu theo phương thức thanh toán
     */
    private function getRevenueByPaymentMethod($dateFrom, $dateTo) {
        $query = "SELECT 
                    p.payment_method,
                    COUNT(*) as order_count,
                    SUM(o.total_amount) as revenue,
                    AVG(o.total_amount) as avg_order_value
                  FROM tbl_order o
                  JOIN tbl_payment p ON o.order_id = p.order_id
                  WHERE DATE(o.created_at) BETWEEN '$dateFrom' AND '$dateTo'
                    AND o.order_status NOT IN ('cancelled', 'failed')
                    AND p.payment_status = 'completed'
                  GROUP BY p.payment_method
                  ORDER BY revenue DESC";
        
        $result = $this->db->select($query);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }
    
    /**
     * So sánh doanh thu với kỳ trước
     */
    private function getRevenueComparison($dateFrom, $dateTo) {
        // Tính số ngày
        $days = (strtotime($dateTo) - strtotime($dateFrom)) / (60*60*24) + 1;
        $previousFrom = date('Y-m-d', strtotime($dateFrom) - ($days * 24*60*60));
        $previousTo = date('Y-m-d', strtotime($dateTo) - ($days * 24*60*60));
        
        $query = "SELECT 
                    'current' as period,
                    SUM(total_amount) as revenue,
                    COUNT(*) as orders
                  FROM tbl_order 
                  WHERE DATE(created_at) BETWEEN '$dateFrom' AND '$dateTo'
                    AND order_status NOT IN ('cancelled', 'failed')
                  UNION ALL
                  SELECT 
                    'previous' as period,
                    SUM(total_amount) as revenue,
                    COUNT(*) as orders
                  FROM tbl_order 
                  WHERE DATE(created_at) BETWEEN '$previousFrom' AND '$previousTo'
                    AND order_status NOT IN ('cancelled', 'failed')";
        
        $result = $this->db->select($query);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }
    
    /**
     * Lấy format ngày theo nhóm
     */
    private function getDateFormat($groupBy) {
        switch ($groupBy) {
            case 'week':
                return '%Y-%u'; // Year-Week
            case 'month':
                return '%Y-%m'; // Year-Month
            case 'year':
                return '%Y'; // Year
            case 'day':
            default:
                return '%Y-%m-%d'; // Year-Month-Day
        }
    }
    
    private function isAdminLoggedIn() {
        return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
    }
    
    private function loadView($view, $data = []) {
        extract($data);
        require_once __DIR__ . '/../../views/admin/' . $view . '.php';
    }
}
?>