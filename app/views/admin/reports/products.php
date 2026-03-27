<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Báo cáo Sản phẩm & Tồn kho'; ?></title>
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
        
        .filter-group input {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }
        
        .filter-btn {
            background: #f093fb;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
            transition: background 0.3s;
        }
        
        .filter-btn:hover {
            background: #e77ef1;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
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
        .stat-card.in-stock { --card-color: #28a745; }
        .stat-card.out-stock { --card-color: #dc3545; }
        .stat-card.low-stock { --card-color: #ffc107; }
        .stat-card.quantity { --card-color: #17a2b8; }
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
            border-bottom: 3px solid #f093fb;
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
        
        .rank-badge {
            background: linear-gradient(135deg, #ffd700, #ffed4e);
            color: #333;
            padding: 4px 8px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: bold;
            min-width: 25px;
            display: inline-block;
            text-align: center;
        }
        
        .rank-badge.top3 {
            background: linear-gradient(135deg, #ff6b6b, #ee5a52);
            color: white;
        }
        
        .stock-status {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .stock-in { background: #d4edda; color: #155724; }
        .stock-low { background: #fff3cd; color: #856404; }
        .stock-out { background: #f8d7da; color: #721c24; }
        
        .progress-bar {
            background: #e9ecef;
            border-radius: 10px;
            height: 8px;
            margin-top: 5px;
            overflow: hidden;
        }
        
        .progress-fill {
            height: 100%;
            background: var(--progress-color);
            transition: width 0.3s ease;
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
        
        .category-chart {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }
        
        .category-item {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .category-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: #f093fb;
        }
        
        .category-item h4 {
            color: #333;
            margin-bottom: 10px;
        }
        
        .category-stats {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
            font-size: 0.9rem;
        }
        
        .category-stats span {
            color: #666;
        }
        
        .category-stats strong {
            color: #333;
        }
    </style>
</head>
<body>
    <!-- Admin Header -->
    <div class="admin-header">
        <div class="container">
            <h1><i class="fas fa-box-open"></i> Báo cáo Sản phẩm & Tồn kho</h1>
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
            <h2><i class="fas fa-chart-pie"></i> Báo cáo Sản phẩm & Tồn kho Chi tiết</h2>
            <p>Phân tích sản phẩm bán chạy, tình trạng tồn kho và hiệu suất danh mục</p>
        </div>
        
        <!-- Bộ lọc -->
        <div class="filter-section">
            <form method="GET" action="" class="filter-form">
                <input type="hidden" name="page" value="admin_reports">
                <input type="hidden" name="action" value="products">
                
                <div class="filter-group">
                    <label>Từ ngày:</label>
                    <input type="date" name="date_from" value="<?php echo $dateFrom; ?>">
                </div>
                
                <div class="filter-group">
                    <label>Đến ngày:</label>
                    <input type="date" name="date_to" value="<?php echo $dateTo; ?>">
                </div>
                
                <button type="submit" class="filter-btn">
                    <i class="fas fa-filter"></i> Lọc
                </button>
            </form>
        </div>
        
        <!-- Thống kê tồn kho tổng quan -->
        <?php if ($stockStats): ?>
        <div class="stats-grid">
            <div class="stat-card total">
                <i class="fas fa-boxes"></i>
                <h3><?php echo number_format($stockStats['total_products'] ?? 0); ?></h3>
                <p>Tổng sản phẩm</p>
            </div>
            
            <div class="stat-card in-stock">
                <i class="fas fa-check-circle"></i>
                <h3><?php echo number_format($stockStats['in_stock'] ?? 0); ?></h3>
                <p>Còn hàng</p>
            </div>
            
            <div class="stat-card out-stock">
                <i class="fas fa-times-circle"></i>
                <h3><?php echo number_format($stockStats['out_of_stock'] ?? 0); ?></h3>
                <p>Hết hàng</p>
            </div>
            
            <div class="stat-card low-stock">
                <i class="fas fa-exclamation-triangle"></i>
                <h3><?php echo number_format($stockStats['low_stock'] ?? 0); ?></h3>
                <p>Sắp hết hàng</p>
            </div>
            
            <div class="stat-card quantity">
                <i class="fas fa-cubes"></i>
                <h3><?php echo number_format($stockStats['total_quantity'] ?? 0); ?></h3>
                <p>Tổng tồn kho</p>
            </div>
            
            <div class="stat-card avg">
                <i class="fas fa-calculator"></i>
                <h3><?php echo number_format($stockStats['avg_quantity'] ?? 0, 1); ?></h3>
                <p>TB/sản phẩm</p>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Top sản phẩm bán chạy -->
        <div class="report-section">
            <h3 class="section-title">
                <i class="fas fa-trophy"></i>
                Top Sản phẩm Bán chạy
            </h3>
            
            <?php if ($topProducts && count($topProducts) > 0): ?>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Xếp hạng</th>
                            <th>Tên sản phẩm</th>
                            <th>Đã bán</th>
                            <th>Doanh thu</th>
                            <th>Giá trung bình</th>
                            <th>Số đơn hàng</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($topProducts as $index => $product): ?>
                        <tr>
                            <td>
                                <span class="rank-badge <?php echo $index < 3 ? 'top3' : ''; ?>">
                                    #<?php echo $index + 1; ?>
                                </span>
                            </td>
                            <td>
                                <strong><?php echo htmlspecialchars($product['sanpham_name']); ?></strong>
                            </td>
                            <td>
                                <strong><?php echo number_format($product['total_sold']); ?></strong> sản phẩm
                            </td>
                            <td><?php echo number_format($product['total_revenue'] ?? 0, 0, ',', '.'); ?>₫</td>
                            <td><?php echo number_format($product['avg_price'] ?? 0, 0, ',', '.'); ?>₫</td>
                            <td><?php echo number_format($product['order_count']); ?> đơn</td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="no-data">
                    <i class="fas fa-trophy"></i>
                    <p>Không có dữ liệu sản phẩm trong khoảng thời gian được chọn</p>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Sản phẩm sắp hết hàng -->
        <div class="report-section">
            <h3 class="section-title">
                <i class="fas fa-exclamation-triangle"></i>
                Cảnh báo Sản phẩm Sắp hết hàng
            </h3>
            
            <?php if ($lowStockProducts && count($lowStockProducts) > 0): ?>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Sản phẩm</th>
                            <th>Màu sắc</th>
                            <th>Kích thước</th>
                            <th>Tồn kho</th>
                            <th>Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lowStockProducts as $product): ?>
                        <tr>
                            <td>
                                <strong><?php echo htmlspecialchars($product['sanpham_name']); ?></strong>
                            </td>
                            <td><?php echo htmlspecialchars($product['color_name'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($product['size_name'] ?? 'N/A'); ?></td>
                            <td>
                                <strong style="color: #dc3545;"><?php echo number_format($product['soluong']); ?></strong>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: <?php echo min(100, ($product['soluong'] / 10) * 100); ?>%; --progress-color: <?php echo $product['soluong'] <= 5 ? '#dc3545' : '#ffc107'; ?>;"></div>
                                </div>
                            </td>
                            <td>
                                <?php if ($product['soluong'] == 0): ?>
                                    <span class="stock-status stock-out">Hết hàng</span>
                                <?php elseif ($product['soluong'] <= 5): ?>
                                    <span class="stock-status stock-out">Rất ít</span>
                                <?php else: ?>
                                    <span class="stock-status stock-low">Sắp hết</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="no-data">
                    <i class="fas fa-check-circle"></i>
                    <p style="color: #28a745;">Tất cả sản phẩm đều có tồn kho ổn định</p>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Thống kê theo danh mục -->
        <div class="report-section">
            <h3 class="section-title">
                <i class="fas fa-layer-group"></i>
                Thống kê theo Danh mục Sản phẩm
            </h3>
            
            <?php if ($categoryStats && count($categoryStats) > 0): ?>
                <div class="category-chart">
                    <?php foreach ($categoryStats as $category): ?>
                    <div class="category-item">
                        <h4><?php echo htmlspecialchars($category['danhmuc_name']); ?></h4>
                        <div style="font-size: 2rem; font-weight: bold; color: #f093fb; margin: 10px 0;">
                            <?php echo number_format($category['product_count']); ?>
                        </div>
                        <p style="color: #666;">Sản phẩm</p>
                        
                        <div class="category-stats">
                            <div>
                                <span>Đã bán:</span><br>
                                <strong><?php echo number_format($category['total_sold'] ?? 0); ?></strong>
                            </div>
                            <div>
                                <span>Doanh thu:</span><br>
                                <strong><?php echo number_format($category['revenue'] ?? 0, 0, ',', '.'); ?>₫</strong>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="no-data">
                    <i class="fas fa-layer-group"></i>
                    <p>Không có dữ liệu danh mục</p>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Phân tích tồn kho chi tiết -->
        <?php if ($stockStats): ?>
        <div class="report-section">
            <h3 class="section-title">
                <i class="fas fa-chart-bar"></i>
                Phân tích Tồn kho Chi tiết
            </h3>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <i class="fas fa-percentage" style="color: #28a745;"></i>
                    <h3>
                        <?php 
                        $inStockRate = $stockStats['total_products'] > 0 ? 
                            round(($stockStats['in_stock'] / $stockStats['total_products']) * 100, 1) : 0;
                        echo $inStockRate;
                        ?>%
                    </h3>
                    <p>Tỷ lệ còn hàng</p>
                </div>
                
                <div class="stat-card">
                    <i class="fas fa-percentage" style="color: #dc3545;"></i>
                    <h3>
                        <?php 
                        $outStockRate = $stockStats['total_products'] > 0 ? 
                            round(($stockStats['out_of_stock'] / $stockStats['total_products']) * 100, 1) : 0;
                        echo $outStockRate;
                        ?>%
                    </h3>
                    <p>Tỷ lệ hết hàng</p>
                </div>
                
                <div class="stat-card">
                    <i class="fas fa-percentage" style="color: #ffc107;"></i>
                    <h3>
                        <?php 
                        $lowStockRate = $stockStats['total_products'] > 0 ? 
                            round(($stockStats['low_stock'] / $stockStats['total_products']) * 100, 1) : 0;
                        echo $lowStockRate;
                        ?>%
                    </h3>
                    <p>Tỷ lệ sắp hết</p>
                </div>
            </div>
            
            <div style="margin-top: 20px; text-align: center; color: #666;">
                <p><i class="fas fa-info-circle"></i> 
                Sản phẩm được coi là "sắp hết hàng" khi tồn kho ≤ 10 đơn vị</p>
            </div>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>