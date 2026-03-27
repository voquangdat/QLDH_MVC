<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Danh sách Đơn hàng'; ?></title>
    <script src="https://kit.fontawesome.com/54f0cb7e4a.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="/public/css/admin-dashboard.css">
    <style>
        .management-container {
            max-width: 1400px;
            margin: 20px auto;
            padding: 0 20px;
        }
        
        .management-header {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 30px;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: #fff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
            border-left: 5px solid;
        }
        
        .stat-card.total { border-left-color: #667eea; }
        .stat-card.pending { border-left-color: #ffc107; }
        .stat-card.confirmed { border-left-color: #17a2b8; }
        .stat-card.processing { border-left-color: #007bff; }
        .stat-card.delivered { border-left-color: #28a745; }
        .stat-card.cancelled { border-left-color: #dc3545; }
        
        .stat-card h3 {
            margin: 0 0 10px 0;
            font-size: 0.9rem;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .stat-card .number {
            font-size: 2.5rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }
        
        .filters-section {
            background: #fff;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .filters-form {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            align-items: end;
        }
        
        .filter-group {
            display: flex;
            flex-direction: column;
        }
        
        .filter-group label {
            margin-bottom: 8px;
            font-size: 0.9rem;
            font-weight: 600;
            color: #333;
        }
        
        .filter-group input,
        .filter-group select {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 0.9rem;
        }
        
        .filter-group input:focus,
        .filter-group select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            font-size: 0.9rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        
        .data-table {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .table-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .table-responsive {
            overflow-x: auto;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
        }
        
        .table th,
        .table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
            vertical-align: middle;
        }
        
        .table th {
            background: #f8f9fa;
            font-weight: 600;
            color: #333;
            font-size: 0.9rem;
        }
        
        .table tbody tr:hover {
            background-color: #f8f9fa;
        }
        
        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .status-pending {
            background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
            color: #212529;
        }
        
        .status-confirmed {
            background: linear-gradient(135deg, #17a2b8 0%, #20c997 100%);
            color: white;
        }
        
        .status-processing {
            background: linear-gradient(135deg, #007bff 0%, #6610f2 100%);
            color: white;
        }
        
        .status-delivered {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
        }
        
        .status-cancelled {
            background: linear-gradient(135deg, #dc3545 0%, #e83e8c 100%);
            color: white;
        }
        
        .payment-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.7rem;
            font-weight: 500;
        }
        
        .payment-pending {
            background: #fff3cd;
            color: #856404;
        }
        
        .payment-processing {
            background: #cce7ff;
            color: #004085;
        }
        
        .payment-completed {
            background: #d4edda;
            color: #155724;
        }
        
        .payment-failed {
            background: #f8d7da;
            color: #721c24;
        }
        
        .action-buttons {
            display: flex;
            gap: 5px;
        }
        
        .btn-sm {
            padding: 6px 12px;
            font-size: 0.8rem;
            border-radius: 15px;
        }
        
        .btn-info {
            background: #17a2b8;
            color: white;
        }
        
        .btn-success {
            background: #28a745;
            color: white;
        }
        
        .btn-warning {
            background: #ffc107;
            color: #212529;
        }
        
        .btn-danger {
            background: #dc3545;
            color: white;
        }
        
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            gap: 10px;
        }
        
        .pagination a,
        .pagination span {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            text-decoration: none;
            color: #333;
        }
        
        .pagination a:hover {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }
        
        .pagination .current {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }
        
        .alert {
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <!-- Admin Header -->
    <div class="admin-header">
        <div class="container">
            <h1><i class="fas fa-shopping-cart"></i> Danh sách Đơn hàng</h1>
            <div class="admin-user-info">
                <span class="admin-name">
                    <i class="fas fa-user"></i> <?php echo htmlspecialchars($admin_name ?? 'Admin'); ?>
                </span>
                <a href="index.php?page=admin&action=logout" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i> Đăng xuất
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="management-container">
        <!-- Header Section -->
        <div class="management-header">
            <h2>Quản lý Đơn hàng</h2>
            <p>Theo dõi và xử lý tất cả đơn hàng trong hệ thống</p>
            
            <div style="margin-top: 20px;">
                <a href="index.php?page=admin_order" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
            </div>
        </div>

        <!-- Hiển thị thông báo -->
        <?php if (isset($_SESSION['flash_success'])): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <?php echo $_SESSION['flash_success']; unset($_SESSION['flash_success']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['flash_error'])): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo $_SESSION['flash_error']; unset($_SESSION['flash_error']); ?>
            </div>
        <?php endif; ?>

        <!-- Thống kê -->
        <?php if (isset($statistics) && $statistics): ?>
        <div class="stats-grid">
            <div class="stat-card total">
                <h3>Tổng đơn hàng</h3>
                <div class="number"><?php echo number_format($statistics['total_orders'] ?? 0); ?></div>
            </div>
            <div class="stat-card pending">
                <h3>Chờ xác nhận</h3>
                <div class="number"><?php echo number_format($statistics['pending_orders'] ?? 0); ?></div>
            </div>
            <div class="stat-card confirmed">
                <h3>Đã xác nhận</h3>
                <div class="number"><?php echo number_format($statistics['confirmed_orders'] ?? 0); ?></div>
            </div>
            <div class="stat-card processing">
                <h3>Đang xử lý</h3>
                <div class="number"><?php echo number_format($statistics['processing_orders'] ?? 0); ?></div>
            </div>
            <div class="stat-card delivered">
                <h3>Đã giao</h3>
                <div class="number"><?php echo number_format($statistics['delivered_orders'] ?? 0); ?></div>
            </div>
            <div class="stat-card cancelled">
                <h3>Đã hủy</h3>
                <div class="number"><?php echo number_format($statistics['cancelled_orders'] ?? 0); ?></div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Bộ lọc -->
        <div class="filters-section">
            <form method="GET" action="index.php" class="filters-form">
                <input type="hidden" name="page" value="admin_order">
                <input type="hidden" name="action" value="order_management">
                
                <div class="filter-group">
                    <label for="search">Tìm kiếm</label>
                    <input type="text" 
                           id="search" 
                           name="search" 
                           placeholder="Mã đơn hàng, tên khách hàng, SĐT..."
                           value="<?php echo htmlspecialchars($filters['search'] ?? ''); ?>">
                </div>
                
                <div class="filter-group">
                    <label for="order_status">Trạng thái đơn hàng</label>
                    <select id="order_status" name="order_status">
                        <option value="">Tất cả</option>
                        <option value="pending" <?php echo ($filters['order_status'] ?? '') === 'pending' ? 'selected' : ''; ?>>Chờ xác nhận</option>
                        <option value="confirmed" <?php echo ($filters['order_status'] ?? '') === 'confirmed' ? 'selected' : ''; ?>>Đã xác nhận</option>
                        <option value="processing" <?php echo ($filters['order_status'] ?? '') === 'processing' ? 'selected' : ''; ?>>Đang xử lý</option>
                        <option value="delivered" <?php echo ($filters['order_status'] ?? '') === 'delivered' ? 'selected' : ''; ?>>Đã giao</option>
                        <option value="cancelled" <?php echo ($filters['order_status'] ?? '') === 'cancelled' ? 'selected' : ''; ?>>Đã hủy</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label for="payment_status">Trạng thái thanh toán</label>
                    <select id="payment_status" name="payment_status">
                        <option value="">Tất cả</option>
                        <option value="pending" <?php echo ($filters['payment_status'] ?? '') === 'pending' ? 'selected' : ''; ?>>Chờ thanh toán</option>
                        <option value="completed" <?php echo ($filters['payment_status'] ?? '') === 'completed' ? 'selected' : ''; ?>>Đã thanh toán</option>
                        <option value="failed" <?php echo ($filters['payment_status'] ?? '') === 'failed' ? 'selected' : ''; ?>>Thanh toán thất bại</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label for="date_from">Từ ngày</label>
                    <input type="date" 
                           id="date_from" 
                           name="date_from" 
                           value="<?php echo htmlspecialchars($filters['date_from'] ?? ''); ?>">
                </div>
                
                <div class="filter-group">
                    <label for="date_to">Đến ngày</label>
                    <input type="date" 
                           id="date_to" 
                           name="date_to" 
                           value="<?php echo htmlspecialchars($filters['date_to'] ?? ''); ?>">
                </div>
                
                <div class="filter-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Lọc
                    </button>
                </div>
            </form>
        </div>

        <!-- Bảng đơn hàng -->
        <div class="data-table">
            <div class="table-header">
                <h5 style="margin: 0;">
                    <i class="fas fa-list-alt"></i> 
                    Danh sách đơn hàng 
                    (<?php echo number_format($totalOrders ?? 0); ?> đơn)
                </h5>
            </div>
            
            <div class="table-responsive">
                <?php if (isset($orders) && $orders && mysqli_num_rows($orders) > 0): ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Mã đơn hàng</th>
                            <th>Khách hàng</th>
                            <th>Ngày đặt</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th>Thanh toán</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($order = mysqli_fetch_assoc($orders)): ?>
                        <tr>
                            <td>
                                <strong><?php echo htmlspecialchars($order['order_code']); ?></strong>
                            </td>
                            <td>
                                <div>
                                    <strong><?php echo htmlspecialchars($order['customer_name']); ?></strong><br>
                                    <small class="text-muted"><?php echo htmlspecialchars($order['customer_phone']); ?></small>
                                </div>
                            </td>
                            <td>
                                <?php echo date('d/m/Y H:i', strtotime($order['order_date'])); ?>
                            </td>
                            <td>
                                <strong><?php echo number_format($order['total_amount']); ?>đ</strong>
                            </td>
                            <td>
                                <span class="status-badge status-<?php echo $order['order_status']; ?>">
                                    <?php 
                                    $statusLabels = [
                                        'pending' => 'Chờ xác nhận',
                                        'confirmed' => 'Đã xác nhận',
                                        'processing' => 'Đang xử lý',
                                        'delivered' => 'Đã giao',
                                        'cancelled' => 'Đã hủy'
                                    ];
                                    echo $statusLabels[$order['order_status']] ?? $order['order_status'];
                                    ?>
                                </span>
                            </td>
                            <td>
                                <span class="payment-badge payment-<?php echo $order['payment_status']; ?>">
                                    <?php 
                                    // Hiển thị payment status phù hợp với từng phương thức
                                    if ($order['payment_method'] === 'Thu tiền tận nơi') {
                                        if ($order['payment_status'] === 'pending') {
                                            echo 'Chưa thanh toán (COD)';
                                        } else if ($order['payment_status'] === 'completed') {
                                            echo 'Đã thanh toán (COD)';
                                        } else {
                                            echo 'COD - ' . ($order['payment_status'] ?? 'N/A');
                                        }
                                    } else {
                                        // Online payment (OnePay, MoMo, etc.) - chung trạng thái
                                        $paymentLabels = [
                                            'pending' => 'Chờ thanh toán',
                                            'processing' => 'Đang xử lý',
                                            'completed' => 'Đã thanh toán',
                                            'failed' => 'Thanh toán thất bại'
                                        ];
                                        $statusText = $paymentLabels[$order['payment_status']] ?? $order['payment_status'];
                                        
                                        // Hiển thị thêm tên phương thức thanh toán
                                        if (strpos($order['payment_method'], 'OnePay') !== false) {
                                            echo $statusText . ' (OnePay)';
                                        } else if ($order['payment_method'] === 'Thanh toán Momo') {
                                            echo $statusText . ' ';
                                        } else {
                                            echo $statusText;
                                        }
                                    }
                                    ?>
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="index.php?page=admin_order&section=detail&action=view&id=<?php echo $order['order_id']; ?>" 
                                       class="btn btn-info btn-sm" 
                                       title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    <?php if ($order['order_status'] === 'pending'): ?>
                                    <form method="POST" action="index.php?page=admin_order&action=confirm" style="display: inline;">
                                        <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                                        <button type="submit" class="btn btn-success btn-sm" title="Xác nhận đơn hàng">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    <?php endif; ?>
                                    
                                    <?php if ($order['order_status'] === 'confirmed'): ?>
                                    <form method="POST" action="index.php?page=admin_order&action=process" style="display: inline;">
                                        <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                                        <button type="submit" class="btn btn-warning btn-sm" title="Chuyển sang xử lý">
                                            <i class="fas fa-cogs"></i>
                                        </button>
                                    </form>
                                    <?php endif; ?>
                                    
                                    <?php if (in_array($order['order_status'], ['confirmed', 'processing'])): ?>
                                    <form method="POST" action="index.php?page=admin_order&action=deliver" style="display: inline;">
                                        <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                                        <button type="submit" class="btn btn-success btn-sm" title="Hoàn tất giao hàng">
                                            <i class="fas fa-truck"></i>
                                        </button>
                                    </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <?php else: ?>
                <div style="text-align: center; padding: 50px; color: #666;">
                    <i class="fas fa-shopping-cart" style="font-size: 4rem; margin-bottom: 20px; opacity: 0.3;"></i>
                    <h3>Không có đơn hàng nào</h3>
                    <p>Chưa có đơn hàng nào trong hệ thống hoặc không khớp với bộ lọc hiện tại.</p>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Phân trang -->
            <?php if (isset($totalPages) && $totalPages > 1): ?>
            <div class="pagination">
                <?php if ($currentPage > 1): ?>
                    <a href="?page=admin_order&action=order_management&p=<?php echo $currentPage - 1; ?>&<?php echo http_build_query($filters); ?>">
                        <i class="fas fa-chevron-left"></i> Trước
                    </a>
                <?php endif; ?>
                
                <?php for ($i = max(1, $currentPage - 2); $i <= min($totalPages, $currentPage + 2); $i++): ?>
                    <?php if ($i == $currentPage): ?>
                        <span class="current"><?php echo $i; ?></span>
                    <?php else: ?>
                        <a href="?page=admin_order&action=order_management&p=<?php echo $i; ?>&<?php echo http_build_query($filters); ?>"><?php echo $i; ?></a>
                    <?php endif; ?>
                <?php endfor; ?>
                
                <?php if ($currentPage < $totalPages): ?>
                    <a href="?page=admin_order&action=order_management&p=<?php echo $currentPage + 1; ?>&<?php echo http_build_query($filters); ?>">
                        Sau <i class="fas fa-chevron-right"></i>
                    </a>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>