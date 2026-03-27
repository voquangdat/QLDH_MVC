<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Báo cáo Doanh thu'; ?></title>
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
            background: #4facfe;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
            transition: background 0.3s;
        }
        
        .filter-btn:hover {
            background: #3d8bfe;
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
        
        .stat-card.revenue { --card-color: #4facfe; }
        .stat-card.orders { --card-color: #667eea; }
        .stat-card.avg { --card-color: #6f42c1; }
        .stat-card.max { --card-color: #28a745; }
        .stat-card.min { --card-color: #ffc107; }
        
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
            border-bottom: 3px solid #4facfe;
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
        
        .comparison-card {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-top: 20px;
        }
        
        .comparison-item {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .comparison-item.current {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
        }
        
        .comparison-item.previous {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .comparison-item h4 {
            margin-bottom: 15px;
        }
        
        .comparison-value {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .growth-indicator {
            font-size: 1.2rem;
            font-weight: bold;
            margin-top: 15px;
        }
        
        .growth-up {
            color: #28a745;
        }
        
        .growth-down {
            color: #dc3545;
        }
        
        .growth-neutral {
            color: #6c757d;
        }
        
        .payment-method-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        
        .payment-method-item {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .payment-method-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: #4facfe;
        }
        
        .payment-method-item h4 {
            color: #333;
            margin-bottom: 15px;
        }
        
        .payment-stats {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
            font-size: 0.9rem;
        }
        
        .payment-stats span {
            color: #666;
        }
        
        .payment-stats strong {
            color: #333;
        }
        
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
        
        .trend-indicator {
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        
        .trend-up {
            color: #28a745;
        }
        
        .trend-down {
            color: #dc3545;
        }
        
        .trend-neutral {
            color: #6c757d;
        }
    </style>
</head>
<body>
    <!-- Admin Header -->
    <div class="admin-header">
        <div class="container">
            <h1><i class="fas fa-chart-bar"></i> Báo cáo Doanh thu</h1>
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
            <h2><i class="fas fa-dollar-sign"></i> Báo cáo Doanh thu Chi tiết</h2>
            <p>Phân tích doanh thu tổng thể, xu hướng và hiệu suất kinh doanh</p>
        </div>
        
        <!-- Bộ lọc -->
        <div class="filter-section">
            <form method="GET" action="" class="filter-form">
                <input type="hidden" name="page" value="admin_reports">
                <input type="hidden" name="action" value="revenue">
                
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
        
        <!-- Thống kê doanh thu tổng quan -->
        <?php if ($revenueStats): ?>
        <div class="stats-grid">
            <div class="stat-card revenue">
                <i class="fas fa-dollar-sign"></i>
                <h3><?php echo number_format($revenueStats['total_revenue'] ?? 0, 0, ',', '.'); ?>₫</h3>
                <p>Tổng doanh thu</p>
            </div>
            
            <div class="stat-card orders">
                <i class="fas fa-shopping-cart"></i>
                <h3><?php echo number_format($revenueStats['total_orders'] ?? 0); ?></h3>
                <p>Tổng đơn hàng</p>
            </div>
            
            <div class="stat-card avg">
                <i class="fas fa-calculator"></i>
                <h3><?php echo number_format($revenueStats['avg_order_value'] ?? 0, 0, ',', '.'); ?>₫</h3>
                <p>Giá trị TB/đơn</p>
            </div>
            
            <div class="stat-card max">
                <i class="fas fa-arrow-up"></i>
                <h3><?php echo number_format($revenueStats['max_order_value'] ?? 0, 0, ',', '.'); ?>₫</h3>
                <p>Đơn hàng cao nhất</p>
            </div>
            
            <div class="stat-card min">
                <i class="fas fa-arrow-down"></i>
                <h3><?php echo number_format($revenueStats['min_order_value'] ?? 0, 0, ',', '.'); ?>₫</h3>
                <p>Đơn hàng thấp nhất</p>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- So sánh với kỳ trước -->
        <?php if ($comparison && count($comparison) >= 2): ?>
        <div class="report-section">
            <h3 class="section-title">
                <i class="fas fa-balance-scale"></i>
                So sánh với Kỳ trước
            </h3>
            
            <div class="comparison-card">
                <?php 
                $current = null;
                $previous = null;
                foreach ($comparison as $period) {
                    if ($period['period'] == 'current') {
                        $current = $period;
                    } else if ($period['period'] == 'previous') {
                        $previous = $period;
                    }
                }
                
                $revenueGrowth = 0;
                $orderGrowth = 0;
                
                if ($previous && $previous['revenue'] > 0) {
                    $revenueGrowth = round((($current['revenue'] - $previous['revenue']) / $previous['revenue']) * 100, 1);
                }
                
                if ($previous && $previous['orders'] > 0) {
                    $orderGrowth = round((($current['orders'] - $previous['orders']) / $previous['orders']) * 100, 1);
                }
                ?>
                
                <div class="comparison-item current">
                    <h4>Kỳ hiện tại</h4>
                    <div class="comparison-value">
                        <?php echo number_format($current['revenue'] ?? 0, 0, ',', '.'); ?>₫
                    </div>
                    <p><?php echo number_format($current['orders'] ?? 0); ?> đơn hàng</p>
                    
                    <div class="growth-indicator">
                        <?php if ($revenueGrowth > 0): ?>
                            <span class="growth-up">
                                <i class="fas fa-arrow-up"></i> +<?php echo $revenueGrowth; ?>%
                            </span>
                        <?php elseif ($revenueGrowth < 0): ?>
                            <span class="growth-down">
                                <i class="fas fa-arrow-down"></i> <?php echo $revenueGrowth; ?>%
                            </span>
                        <?php else: ?>
                            <span class="growth-neutral">
                                <i class="fas fa-minus"></i> 0%
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="comparison-item previous">
                    <h4>Kỳ trước</h4>
                    <div class="comparison-value">
                        <?php echo number_format($previous['revenue'] ?? 0, 0, ',', '.'); ?>₫
                    </div>
                    <p><?php echo number_format($previous['orders'] ?? 0); ?> đơn hàng</p>
                    
                    <div style="margin-top: 15px; color: rgba(255,255,255,0.8);">
                        <i class="fas fa-info-circle"></i> Tham chiếu
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Doanh thu theo phương thức thanh toán -->
        <div class="report-section">
            <h3 class="section-title">
                <i class="fas fa-credit-card"></i>
                Doanh thu theo Phương thức Thanh toán
            </h3>
            
            <?php if ($paymentMethodStats && count($paymentMethodStats) > 0): ?>
                <div class="payment-method-grid">
                    <?php foreach ($paymentMethodStats as $method): ?>
                    <div class="payment-method-item">
                        <h4>
                            <?php 
                            $methodLabels = [
                                'Thu tiền tận nơi' => 'COD',
                                'Thanh toán bằng thẻ tín dụng(OnePay)' => 'OnePay Credit',
                                'Thanh toán bằng thẻ ATM(OnePay)' => 'OnePay ATM',
                                'Thanh toán Momo' => 'MoMo'
                            ];
                            echo $methodLabels[$method['payment_method']] ?? $method['payment_method'];
                            ?>
                        </h4>
                        
                        <div style="font-size: 1.8rem; font-weight: bold; color: #4facfe; margin: 15px 0;">
                            <?php echo number_format($method['revenue'] ?? 0, 0, ',', '.'); ?>₫
                        </div>
                        
                        <div class="payment-stats">
                            <div>
                                <span>Đơn hàng:</span><br>
                                <strong><?php echo number_format($method['order_count']); ?></strong>
                            </div>
                            <div>
                                <span>TB/đơn:</span><br>
                                <strong><?php echo number_format($method['avg_order_value'] ?? 0, 0, ',', '.'); ?>₫</strong>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="no-data">
                    <i class="fas fa-credit-card"></i>
                    <p>Không có dữ liệu phương thức thanh toán trong khoảng thời gian được chọn</p>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Xu hướng doanh thu theo thời gian -->
        <div class="report-section">
            <h3 class="section-title">
                <i class="fas fa-chart-line"></i>
                Xu hướng Doanh thu theo Thời gian
            </h3>
            
            <?php if ($revenueTrend && count($revenueTrend) > 0): ?>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Thời gian</th>
                            <th>Doanh thu</th>
                            <th>Số đơn hàng</th>
                            <th>Giá trị TB/đơn</th>
                            <th>Xu hướng</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $previousRevenue = 0;
                        foreach ($revenueTrend as $index => $trend): 
                            $growth = 0;
                            if ($index > 0 && $previousRevenue > 0) {
                                $growth = round((($trend['revenue'] - $previousRevenue) / $previousRevenue) * 100, 1);
                            }
                            $previousRevenue = $trend['revenue'];
                        ?>
                        <tr>
                            <td>
                                <i class="fas fa-calendar" style="color: #4facfe; margin-right: 5px;"></i>
                                <?php echo htmlspecialchars($trend['period']); ?>
                            </td>
                            <td>
                                <strong><?php echo number_format($trend['revenue'] ?? 0, 0, ',', '.'); ?>₫</strong>
                            </td>
                            <td><?php echo number_format($trend['order_count']); ?></td>
                            <td><?php echo number_format($trend['avg_order_value'] ?? 0, 0, ',', '.'); ?>₫</td>
                            <td>
                                <?php if ($growth > 0): ?>
                                    <span class="trend-indicator trend-up">
                                        <i class="fas fa-arrow-up"></i> +<?php echo $growth; ?>%
                                    </span>
                                <?php elseif ($growth < 0): ?>
                                    <span class="trend-indicator trend-down">
                                        <i class="fas fa-arrow-down"></i> <?php echo $growth; ?>%
                                    </span>
                                <?php else: ?>
                                    <span class="trend-indicator trend-neutral">
                                        <i class="fas fa-minus"></i> -
                                    </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="no-data">
                    <i class="fas fa-chart-line"></i>
                    <p>Không có dữ liệu xu hướng trong khoảng thời gian được chọn</p>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Tóm tắt báo cáo -->
        <div class="report-section">
            <h3 class="section-title">
                <i class="fas fa-clipboard-list"></i>
                Tóm tắt Báo cáo
            </h3>
            
            <div style="background: #f8f9fa; padding: 20px; border-radius: 10px; color: #333;">
                <h4 style="margin-bottom: 15px; color: #4facfe;">
                    <i class="fas fa-info-circle"></i> Kết quả Phân tích
                </h4>
                
                <ul style="list-style: none; padding: 0;">
                    <li style="margin-bottom: 10px;">
                        <i class="fas fa-check" style="color: #28a745; margin-right: 10px;"></i>
                        Khoảng thời gian: <strong><?php echo $dateFrom; ?></strong> đến <strong><?php echo $dateTo; ?></strong>
                    </li>
                    
                    <?php if ($revenueStats): ?>
                    <li style="margin-bottom: 10px;">
                        <i class="fas fa-check" style="color: #28a745; margin-right: 10px;"></i>
                        Tổng doanh thu đạt được: <strong><?php echo number_format($revenueStats['total_revenue'] ?? 0, 0, ',', '.'); ?>₫</strong>
                    </li>
                    
                    <li style="margin-bottom: 10px;">
                        <i class="fas fa-check" style="color: #28a745; margin-right: 10px;"></i>
                        Tổng số đơn hàng: <strong><?php echo number_format($revenueStats['total_orders'] ?? 0); ?></strong> đơn
                    </li>
                    
                    <li style="margin-bottom: 10px;">
                        <i class="fas fa-check" style="color: #28a745; margin-right: 10px;"></i>
                        Giá trị trung bình mỗi đơn hàng: <strong><?php echo number_format($revenueStats['avg_order_value'] ?? 0, 0, ',', '.'); ?>₫</strong>
                    </li>
                    <?php endif; ?>
                    
                    <?php if ($comparison && count($comparison) >= 2): ?>
                    <li style="margin-bottom: 10px;">
                        <i class="fas fa-<?php echo $revenueGrowth > 0 ? 'arrow-up' : ($revenueGrowth < 0 ? 'arrow-down' : 'minus'); ?>" 
                           style="color: <?php echo $revenueGrowth > 0 ? '#28a745' : ($revenueGrowth < 0 ? '#dc3545' : '#6c757d'); ?>; margin-right: 10px;"></i>
                        So với kỳ trước: 
                        <strong style="color: <?php echo $revenueGrowth > 0 ? '#28a745' : ($revenueGrowth < 0 ? '#dc3545' : '#6c757d'); ?>;">
                            <?php echo $revenueGrowth > 0 ? '+' : ''; ?><?php echo $revenueGrowth; ?>%
                        </strong>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>