<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Báo cáo Đơn hàng'; ?></title>
    <script src="https://kit.fontawesome.com/54f0cb7e4a.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="/public/css/admin-dashboard.css">
    <style>
        .reports-container {
            max-width: 1400px;
            margin: 20px auto;
            padding: 0 20px;
        }
        
        .reports-header {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 30px;
            margin-bottom: 20px;
        }
        
        .filter-section {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .filter-form {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            align-items: end;
        }
        
        .filter-group {
            display: flex;
            flex-direction: column;
            min-width: 150px;
        }
        
        .filter-group label {
            font-weight: 600;
            margin-bottom: 5px;
            color: #333;
        }
        
        .filter-group input,
        .filter-group select {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }
        
        .filter-btn {
            background: #667eea;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
            transition: background 0.3s;
        }
        
        .filter-btn:hover {
            background: #5a6fd8;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: #fff;
            border-radius: 10px;
            padding: 25px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: relative;
            overflow: hidden;
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--card-color);
        }
        
        .stat-card.total { --card-color: #667eea; }
        .stat-card.delivered { --card-color: #28a745; }
        .stat-card.cancelled { --card-color: #dc3545; }
        .stat-card.returned { --card-color: #ffc107; }
        .stat-card.revenue { --card-color: #17a2b8; }
        .stat-card.avg { --card-color: #6f42c1; }
        
        .stat-card i {
            font-size: 2.5rem;
            color: var(--card-color);
            margin-bottom: 15px;
        }
        
        .stat-card h3 {
            font-size: 2rem;
            margin-bottom: 5px;
            color: #333;
        }
        
        .stat-card p {
            color: #666;
            margin: 0;
            font-weight: 600;
        }
        
        .report-section {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 25px;
            margin-bottom: 20px;
        }
        
        .section-title {
            color: #333;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 3px solid #667eea;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        
        .data-table th,
        .data-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        
        .data-table th {
            background: #f8f9fa;
            font-weight: 600;
            color: #333;
        }
        
        .data-table tr:hover {
            background: #f8f9fa;
        }
        
        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .status-pending { background: #fff3cd; color: #856404; }
        .status-processing { background: #d1ecf1; color: #0c5460; }
        .status-delivered { background: #d4edda; color: #155724; }
        .status-cancelled { background: #f8d7da; color: #721c24; }
        .status-returned { background: #ffeaa7; color: #856404; }
        
        .percentage-bar {
            background: #e9ecef;
            border-radius: 10px;
            height: 8px;
            margin-top: 5px;
            overflow: hidden;
        }
        
        .percentage-fill {
            height: 100%;
            background: var(--card-color);
            transition: width 0.3s ease;
        }
        
        .success-rate {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-top: 20px;
        }
        
        .rate-item {
            text-align: center;
            flex: 1;
        }
        
        .rate-circle {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: bold;
            margin: 0 auto 10px;
            color: white;
        }
        
        .rate-success { background: #28a745; }
        .rate-failed { background: #dc3545; }
        
        .back-to-reports {
            background: #6c757d;
            color: white;
            padding: 12px 25px;
            border-radius: 25px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }
        
        .back-to-reports:hover {
            background: #5a6268;
            color: white;
            text-decoration: none;
            transform: translateY(-2px);
        }
        
        .no-data {
            text-align: center;
            padding: 40px;
            color: #666;
        }
        
        .no-data i {
            font-size: 3rem;
            margin-bottom: 15px;
            opacity: 0.3;
        }
    </style>
</head>
<body>
    <!-- Admin Header -->
    <div class="admin-header">
        <div class="container">
            <h1><i class="fas fa-shopping-cart"></i> Báo cáo Đơn hàng</h1>
            <div class="admin-user-info">
                <span>Xin chào, <strong><?php echo htmlspecialchars($admin_name ?? 'Admin'); ?></strong></span>
                <a href="index.php?page=admin&action=logout" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i> Đăng xuất
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="reports-container">
        <a href="index.php?page=admin_reports" class="back-to-reports">
            <i class="fas fa-arrow-left"></i> Quay lại Báo cáo
        </a>
        
        <div class="reports-header">
            <h2><i class="fas fa-chart-line"></i> Báo cáo Đơn hàng Chi tiết</h2>
            <p>Phân tích tổng quan đơn hàng, trạng thái và hiệu suất kinh doanh</p>
        </div>
        
        <!-- Bộ lọc -->
        <div class="filter-section">
            <form method="GET" action="" class="filter-form">
                <input type="hidden" name="page" value="admin_reports">
                <input type="hidden" name="action" value="orders">
                
                <div class="filter-group">
                    <label>Từ ngày:</label>
                    <input type="date" name="date_from" value="<?php echo $dateFrom; ?>">
                </div>
                
                <div class="filter-group">
                    <label>Đến ngày:</label>
                    <input type="date" name="date_to" value="<?php echo $dateTo; ?>">
                </div>
                
                <div class="filter-group">
                    <label>Nhóm theo:</label>
                    <select name="group_by">
                        <option value="day" <?php echo $groupBy == 'day' ? 'selected' : ''; ?>>Ngày</option>
                        <option value="week" <?php echo $groupBy == 'week' ? 'selected' : ''; ?>>Tuần</option>
                        <option value="month" <?php echo $groupBy == 'month' ? 'selected' : ''; ?>>Tháng</option>
                        <option value="year" <?php echo $groupBy == 'year' ? 'selected' : ''; ?>>Năm</option>
                    </select>
                </div>
                
                <button type="submit" class="filter-btn">
                    <i class="fas fa-filter"></i> Lọc
                </button>
            </form>
        </div>
        
        <!-- Thống kê tổng quan -->
        <?php if ($overallStats): ?>
        <div class="stats-grid">
            <div class="stat-card total">
                <i class="fas fa-shopping-cart"></i>
                <h3><?php echo number_format($overallStats['total_orders'] ?? 0); ?></h3>
                <p>Tổng đơn hàng</p>
            </div>
            
            <div class="stat-card delivered">
                <i class="fas fa-check-circle"></i>
                <h3><?php echo number_format($overallStats['delivered_orders'] ?? 0); ?></h3>
                <p>Đã giao hàng</p>
            </div>
            
            <div class="stat-card cancelled">
                <i class="fas fa-times-circle"></i>
                <h3><?php echo number_format($overallStats['cancelled_orders'] ?? 0); ?></h3>
                <p>Đã hủy</p>
            </div>
            
            <div class="stat-card returned">
                <i class="fas fa-undo"></i>
                <h3><?php echo number_format($overallStats['returned_orders'] ?? 0); ?></h3>
                <p>Đổi trả</p>
            </div>
            
            <div class="stat-card revenue">
                <i class="fas fa-dollar-sign"></i>
                <h3><?php echo number_format($overallStats['total_revenue'] ?? 0, 0, ',', '.'); ?>₫</h3>
                <p>Tổng doanh thu</p>
            </div>
            
            <div class="stat-card avg">
                <i class="fas fa-calculator"></i>
                <h3><?php echo number_format($overallStats['avg_order_value'] ?? 0, 0, ',', '.'); ?>₫</h3>
                <p>Giá trị TB/đơn</p>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Thống kê theo trạng thái -->
        <div class="report-section">
            <h3 class="section-title">
                <i class="fas fa-list-alt"></i>
                Thống kê theo Trạng thái Đơn hàng
            </h3>
            
            <?php if ($statusStats && count($statusStats) > 0): ?>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Trạng thái</th>
                            <th>Số lượng</th>
                            <th>Tỷ lệ</th>
                            <th>Doanh thu</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($statusStats as $status): ?>
                        <tr>
                            <td>
                                <span class="status-badge status-<?php echo $status['order_status']; ?>">
                                    <?php 
                                    $statusLabels = [
                                        'pending' => 'Chờ xác nhận',
                                        'processing' => 'Đang xử lý',
                                        'shipping' => 'Đang giao',
                                        'delivered' => 'Đã giao',
                                        'completed' => 'Hoàn thành',
                                        'cancelled' => 'Đã hủy',
                                        'returned' => 'Đổi trả'
                                    ];
                                    echo $statusLabels[$status['order_status']] ?? $status['order_status'];
                                    ?>
                                </span>
                            </td>
                            <td><strong><?php echo number_format($status['count']); ?></strong></td>
                            <td>
                                <?php echo $status['percentage']; ?>%
                                <div class="percentage-bar">
                                    <div class="percentage-fill" style="width: <?php echo $status['percentage']; ?>%; --card-color: #667eea;"></div>
                                </div>
                            </td>
                            <td><?php echo number_format($status['revenue'] ?? 0, 0, ',', '.'); ?>₫</td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="no-data">
                    <i class="fas fa-chart-bar"></i>
                    <p>Không có dữ liệu trong khoảng thời gian được chọn</p>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Thống kê theo khu vực -->
        <div class="report-section">
            <h3 class="section-title">
                <i class="fas fa-map-marker-alt"></i>
                Thống kê theo Khu vực Địa lý
            </h3>
            
            <?php if ($regionStats && count($regionStats) > 0): ?>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Tỉnh/Thành phố</th>
                            <th>Số đơn hàng</th>
                            <th>Doanh thu</th>
                            <th>Giá trị TB/đơn</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($regionStats as $region): ?>
                        <tr>
                            <td>
                                <i class="fas fa-map-pin" style="color: #667eea; margin-right: 5px;"></i>
                                <?php echo htmlspecialchars($region['province_name'] ?? 'Không xác định'); ?>
                            </td>
                            <td><strong><?php echo number_format($region['order_count']); ?></strong></td>
                            <td><?php echo number_format($region['revenue'] ?? 0, 0, ',', '.'); ?>₫</td>
                            <td><?php echo number_format($region['avg_order_value'] ?? 0, 0, ',', '.'); ?>₫</td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="no-data">
                    <i class="fas fa-map"></i>
                    <p>Không có dữ liệu khu vực trong khoảng thời gian được chọn</p>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Tỷ lệ thành công/thất bại -->
        <?php if ($successRateStats): ?>
        <div class="report-section">
            <h3 class="section-title">
                <i class="fas fa-percentage"></i>
                Tỷ lệ Thành công - Thất bại
            </h3>
            
            <div class="success-rate">
                <?php 
                $totalCount = $successRateStats['total_count'] ?? 1;
                $successCount = $successRateStats['success_count'] ?? 0;
                $failedCount = $successRateStats['failed_count'] ?? 0;
                $successRate = $totalCount > 0 ? round(($successCount / $totalCount) * 100, 1) : 0;
                $failedRate = $totalCount > 0 ? round(($failedCount / $totalCount) * 100, 1) : 0;
                ?>
                
                <div class="rate-item">
                    <div class="rate-circle rate-success">
                        <?php echo $successRate; ?>%
                    </div>
                    <h4>Thành công</h4>
                    <p><?php echo number_format($successCount); ?> đơn hàng</p>
                </div>
                
                <div class="rate-item">
                    <div class="rate-circle rate-failed">
                        <?php echo $failedRate; ?>%
                    </div>
                    <h4>Thất bại</h4>
                    <p><?php echo number_format($failedCount); ?> đơn hàng</p>
                </div>
                
                <div class="rate-item">
                    <div style="text-align: center;">
                        <h2 style="color: #333; margin-bottom: 10px;"><?php echo number_format($totalCount); ?></h2>
                        <h4>Tổng đơn hàng</h4>
                        <p>Trong khoảng thời gian được chọn</p>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Xu hướng theo thời gian -->
        <?php if ($trendStats && count($trendStats) > 0): ?>
        <div class="report-section">
            <h3 class="section-title">
                <i class="fas fa-chart-line"></i>
                Xu hướng Đơn hàng theo Thời gian
            </h3>
            
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Thời gian</th>
                        <th>Số đơn hàng</th>
                        <th>Doanh thu</th>
                        <th>Tăng trưởng</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $previousRevenue = 0;
                    foreach ($trendStats as $index => $trend): 
                        $growth = 0;
                        if ($index > 0 && $previousRevenue > 0) {
                            $growth = round((($trend['revenue'] - $previousRevenue) / $previousRevenue) * 100, 1);
                        }
                        $previousRevenue = $trend['revenue'];
                    ?>
                    <tr>
                        <td>
                            <i class="fas fa-calendar" style="color: #667eea; margin-right: 5px;"></i>
                            <?php echo htmlspecialchars($trend['period']); ?>
                        </td>
                        <td><strong><?php echo number_format($trend['order_count']); ?></strong></td>
                        <td><?php echo number_format($trend['revenue'] ?? 0, 0, ',', '.'); ?>₫</td>
                        <td>
                            <?php if ($growth > 0): ?>
                                <span style="color: #28a745;">
                                    <i class="fas fa-arrow-up"></i> +<?php echo $growth; ?>%
                                </span>
                            <?php elseif ($growth < 0): ?>
                                <span style="color: #dc3545;">
                                    <i class="fas fa-arrow-down"></i> <?php echo $growth; ?>%
                                </span>
                            <?php else: ?>
                                <span style="color: #6c757d;">-</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>