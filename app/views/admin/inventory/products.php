<?php
// filepath: e:\QLDH_MVC\app\views\admin\inventory\products.php
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Sản phẩm Tồn kho'; ?></title>
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
        
        .management-header h2 {
            color: #333;
            margin-bottom: 10px;
        }
        
        .management-header p {
            color: #666;
            margin-bottom: 20px;
        }
        
        .btn {
            padding: 12px 25px;
            border-radius: 25px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        
        .btn-secondary:hover {
            background: #5a6268;
            color: white;
            text-decoration: none;
            transform: translateY(-2px);
        }
        
        .data-table {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .table {
            width: 100%;
            margin: 0;
        }
        
        .table thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .table th,
        .table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
            vertical-align: middle;
        }
        
        .table th {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }
        
        .table tbody tr:hover {
            background-color: #f8f9fa;
        }
        
        .product-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid #dee2e6;
        }
        
        .product-title {
            max-width: 200px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .status-badge {
            padding: 6px 12px;
            border-radius: 15px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .status-success {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
        }
        
        .status-warning {
            background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
            color: #212529;
        }
        
        .status-danger {
            background: linear-gradient(135deg, #dc3545 0%, #e83e8c 100%);
            color: white;
        }
        
        .action-btn {
            padding: 8px 15px;
            border-radius: 20px;
            text-decoration: none;
            font-size: 0.85rem;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            margin-right: 5px;
            margin-bottom: 5px;
            transition: all 0.3s ease;
        }
        
        .btn-view {
            background: #17a2b8;
            color: white;
        }
        
        .btn-view:hover {
            background: #138496;
            color: white;
            text-decoration: none;
            transform: translateY(-1px);
        }
        
        .no-data {
            text-align: center;
            padding: 50px;
            color: #666;
        }
        
        .no-data i {
            font-size: 4rem;
            margin-bottom: 20px;
            opacity: 0.3;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 15px;
            text-align: center;
        }
        
        .stat-item {
            padding: 10px;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 8px;
        }
        
        .stat-value {
            font-size: 1.5rem;
            font-weight: bold;
            color: #495057;
        }
        
        .stat-label {
            font-size: 0.85rem;
            color: #6c757d;
            margin-top: 5px;
        }
        
        .sku-code {
            background: #f8f9fa;
            padding: 4px 8px;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
            font-size: 0.85rem;
            color: #495057;
        }
    </style>
</head>
<body>
    <!-- Admin Header -->
    <div class="admin-header">
        <div class="container">
            <h1><i class="fas fa-box"></i> Sản phẩm Tồn kho</h1>
            <div class="admin-user-info">
                <span>Xin chào, <strong><?php echo htmlspecialchars($admin_name ?? 'Admin'); ?></strong></span>
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
            <h2>Sản phẩm có Tồn kho</h2>
            <p>Tổng quan về tất cả sản phẩm có biến thể và tồn kho trong hệ thống</p>

            <div>
                <a href="index.php?page=admin_inventory" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
            </div>
        </div>

        <!-- Data Table -->
        <div class="data-table">
            <?php if ($products && mysqli_num_rows($products) > 0): ?>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Ảnh SP</th>
                                <th>Tên sản phẩm</th>
                                <th>Mã SP</th>
                                <th>Danh mục</th>
                                <th>Số biến thể</th>
                                <th>Tổng tồn kho</th>
                                <th>Tổng đặt hàng</th>
                                <th>Có thể bán</th>
                                <th>Trạng thái</th>
                                <th style="text-align: center;">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $stt = 1;
                            while ($product = mysqli_fetch_assoc($products)): 
                            ?>
                            <tr>
                                <td><?php echo $stt++; ?></td>
                                <td>
                                    <?php if (!empty($product['sanpham_anh'])): ?>
                                        <img src="public/uploads/<?php echo htmlspecialchars($product['sanpham_anh']); ?>" 
                                             class="product-image" 
                                             alt="<?php echo htmlspecialchars($product['sanpham_tieude']); ?>"
                                             onerror="this.src='public/images/no-image.png'">
                                    <?php else: ?>
                                        <div class="product-image" style="background: #f8f9fa; display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-image" style="color: #ccc;"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="product-title" title="<?php echo htmlspecialchars($product['sanpham_tieude']); ?>">
                                        <strong><?php echo htmlspecialchars($product['sanpham_tieude']); ?></strong>
                                    </div>
                                    <small style="color: #6c757d;">
                                        <?php echo htmlspecialchars($product['danhmuc_ten'] ?? ''); ?> - 
                                        <?php echo htmlspecialchars($product['loaisanpham_ten'] ?? ''); ?>
                                    </small>
                                </td>
                                <td>
                                    <span class="sku-code">
                                        <?php echo htmlspecialchars($product['sanpham_ma']); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php echo htmlspecialchars($product['danhmuc_ten'] ?? 'N/A'); ?>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-info">
                                        <?php echo $product['tong_bienthe']; ?> biến thể
                                    </span>
                                </td>
                                <td class="text-center">
                                    <strong style="color: #28a745;">
                                        <?php echo number_format($product['tong_ton_kho']); ?>
                                    </strong>
                                </td>
                                <td class="text-center">
                                    <strong style="color: #dc3545;">
                                        <?php echo number_format($product['tong_dat_hang']); ?>
                                    </strong>
                                </td>
                                <td class="text-center">
                                    <strong style="color: #17a2b8;">
                                        <?php echo number_format($product['tong_co_the_ban']); ?>
                                    </strong>
                                </td>
                                <td>
                                    <?php if ($product['trang_thai'] == 'danger'): ?>
                                        <span class="status-badge status-danger">Hết hàng</span>
                                    <?php elseif ($product['trang_thai'] == 'warning'): ?>
                                        <span class="status-badge status-warning">Sắp hết</span>
                                    <?php else: ?>
                                        <span class="status-badge status-success">Còn hàng</span>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align: center;">
                                    <a href="index.php?page=admin_inventory&section=variants&action=detail&id=<?php echo $product['sanpham_id']; ?>" 
                                       class="action-btn btn-view" 
                                       title="Xem chi tiết tồn kho">
                                        <i class="fas fa-warehouse"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

            <?php else: ?>
                <div class="no-data">
                    <i class="fas fa-box-open"></i>
                    <h3>Chưa có sản phẩm nào có tồn kho</h3>
                    <p>Hãy thêm sản phẩm và tạo biến thể để bắt đầu quản lý tồn kho</p>
                    <a href="index.php?page=admin_product&section=products&action=add" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Thêm sản phẩm mới
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>