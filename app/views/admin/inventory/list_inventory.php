<?php
// filepath: e:\QLDH_MVC\app\views\admin\inventory\list_inventory.php
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Quản lý Tồn kho'; ?></title>
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
        
        .action-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
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
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
            color: white;
            text-decoration: none;
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
        
        .btn-warning {
            background: #ffc107;
            color: #212529;
        }
        
        .btn-warning:hover {
            background: #e0a800;
            color: #212529;
            text-decoration: none;
            transform: translateY(-2px);
        }
        
        .filter-section {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .stats-section {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }
        
        .stat-card {
            padding: 20px;
            border-radius: 10px;
            color: white;
            text-align: center;
        }
        
        .stat-card.total { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .stat-card.stock { background: linear-gradient(135deg, #28a745 0%, #20c997 100%); }
        .stat-card.warning { background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%); }
        .stat-card.danger { background: linear-gradient(135deg, #dc3545 0%, #e83e8c 100%); }
        
        .stat-card h3 {
            margin: 0 0 10px 0;
            font-size: 2rem;
        }
        
        .stat-card p {
            margin: 0;
            opacity: 0.9;
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
        
        .table tbody tr.stock-warning {
            background-color: #fff3cd;
        }
        
        .table tbody tr.stock-danger {
            background-color: #f8d7da;
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
        
        .btn-save {
            background: #28a745;
            color: white;
        }
        
        .btn-save:hover {
            background: #218838;
            color: white;
            text-decoration: none;
            transform: translateY(-1px);
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
        
        .color-display {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .color-swatch {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            border: 2px solid #dee2e6;
        }
        
        .size-badge {
            background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
            color: white;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
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
        
        .editable-stock {
            border: 1px solid #dee2e6;
            background: #f8f9fa;
            width: 70px;
            text-align: center;
            padding: 8px;
            border-radius: 6px;
            font-weight: 600;
        }
        
        .editable-stock:focus {
            background: #fff;
            border-color: #667eea;
            outline: none;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .sku-code {
            background: #f8f9fa;
            padding: 4px 8px;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
            font-size: 0.85rem;
            color: #495057;
        }
        
        .table-responsive {
            overflow-x: auto;
        }
        
        .filter-form {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            align-items: end;
        }
        
        .form-group {
            display: flex;
            flex-direction: column;
        }
        
        .form-group label {
            margin-bottom: 5px;
            font-weight: 600;
            color: #495057;
        }
        
        .form-control {
            padding: 10px 15px;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            background: #fff;
        }
        
        .form-control:focus {
            border-color: #667eea;
            outline: none;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .quantity-display {
            text-align: center;
            font-weight: 600;
        }
        
        .available-qty {
            background: linear-gradient(135deg, #17a2b8 0%, #20c997 100%);
            color: white;
            padding: 6px 12px;
            border-radius: 15px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        /* ================= Pagination ================ */
        .pagination {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        list-style: none;
        padding: 0;
        margin: 18px 0 6px;
        justify-content: center;
        }

        .page-item { display: inline-flex; }

        .page-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 40px;
        height: 40px;
        padding: 0 12px;
        border-radius: 10px;
        border: 1px solid #e6e6f0;
        background: #fff;
        color: #4a4a6a;
        font-weight: 600;
        text-decoration: none;
        box-shadow: 0 2px 6px rgba(0,0,0,.06);
        transition: transform .15s ease, box-shadow .15s ease, background .2s ease, color .2s ease;
        }

        .page-link:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 18px rgba(102,126,234,.22);
        color: #4b57df;
        border-color: #d7dbff;
        background: #f8f9ff;
        }

        /* Active page */
        .page-item.active .page-link {
        cursor: default;
        color: #fff;
        border-color: transparent;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        box-shadow: 0 10px 22px rgba(118,75,162,.35);
        }

        /* Disabled (nếu có) */
        .page-item.disabled .page-link {
        opacity: .45;
        cursor: not-allowed;
        pointer-events: none;
        background: #f3f4f6;
        color: #9aa0a6;
        border-color: #ececf3;
        box-shadow: none;
        }

        /* “Đầu/Trước/Sau/Cuối” có icon mũi tên đẹp hơn (tuỳ chọn) */
        .page-link::first-letter { text-transform: uppercase; }

        /* Kích thước nhỏ hơn trên mobile */
        @media (max-width: 576px) {
        .page-link {
            min-width: 34px;
            height: 34px;
            padding: 0 10px;
            border-radius: 8px;
            font-size: 13px;
        }
        .pagination { gap: 6px; }
        }

        /* Hỗ trợ high-contrast khi focus */
        .page-link:focus-visible {
        outline: 3px solid rgba(102,126,234,.45);
        outline-offset: 2px;
        }
    </style>
</head>
<body>
    <!-- Admin Header -->
    <div class="admin-header">
        <div class="container">
            <h1><i class="fas fa-warehouse"></i> Quản lý Tồn kho</h1>
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
            <h2>Quản lý Tồn kho</h2>
            <p>Theo dõi và quản lý số lượng tồn kho của tất cả sản phẩm trong hệ thống</p>

            <div class="action-buttons">
                <button onclick="refreshPage()" class="btn btn-warning">
                    <i class="fas fa-sync-alt"></i> Làm mới
                </button>
                <a href="index.php?page=admin_inventory" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
            </div>
        </div>

        <!-- Hiển thị thông báo -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <?php 
                echo $_SESSION['success']; 
                unset($_SESSION['success']);
                ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <?php 
                echo $_SESSION['error']; 
                unset($_SESSION['error']);
                ?>
            </div>
        <?php endif; ?>

        <!-- Thống kê -->
        <?php if ($stats): ?>
        <div class="stats-section">
            <h3 style="margin-bottom: 20px; text-align: center; color: #333;">Thống kê Tồn kho</h3>
            <div class="stats-grid">
                <div class="stat-card total">
                    <i class="fas fa-cubes fa-2x" style="margin-bottom: 10px;"></i>
                    <h3><?php echo number_format($stats['tong_bienthe']); ?></h3>
                    <p>Tổng biến thể</p>
                </div>

                <div class="stat-card stock">
                    <i class="fas fa-boxes fa-2x" style="margin-bottom: 10px;"></i>
                    <h3><?php echo number_format($stats['tong_ton_kho']); ?></h3>
                    <p>Tổng tồn kho</p>
                </div>

                <div class="stat-card warning">
                    <i class="fas fa-exclamation-triangle fa-2x" style="margin-bottom: 10px;"></i>
                    <h3><?php echo number_format($stats['canh_bao']); ?></h3>
                    <p>Sắp hết hàng</p>
                </div>

                <div class="stat-card danger">
                    <i class="fas fa-times-circle fa-2x" style="margin-bottom: 10px;"></i>
                    <h3><?php echo number_format($stats['het_hang']); ?></h3>
                    <p>Hết hàng</p>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Bộ lọc và tìm kiếm -->
        <div class="filter-section">
            <form method="GET" action="index.php" class="filter-form">
                <input type="hidden" name="page" value="admin_inventory">
                <input type="hidden" name="section" value="list_inventory">
                
                <div class="form-group">
                    <label>Tìm kiếm</label>
                    <input type="text" class="form-control" name="search" 
                           value="<?php echo htmlspecialchars($filters['search'] ?? ''); ?>" 
                           placeholder="Tên sản phẩm hoặc SKU...">
                </div>
                
                <div class="form-group">
                    <label>Danh mục</label>
                    <select name="category" class="form-control">
                        <option value="">Tất cả danh mục</option>
                        <?php if ($categories && mysqli_num_rows($categories) > 0): ?>
                            <?php while ($category = mysqli_fetch_assoc($categories)): ?>
                                <option value="<?php echo $category['danhmuc_id']; ?>" 
                                        <?php echo (($filters['category'] ?? '') == $category['danhmuc_id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($category['danhmuc_ten']); ?>
                                </option>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Tìm kiếm
                    </button>
                    <a href="index.php?page=admin_inventory&section=list_inventory" class="btn btn-secondary" style="margin-top: 10px;">
                        <i class="fas fa-undo"></i> Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Data Table -->
        <div class="data-table">
            <?php if ($inventoryItems && mysqli_num_rows($inventoryItems) > 0): ?>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Ảnh SP</th>
                                <th>Tên sản phẩm</th>
                                <th>SKU</th>
                                <th>Size</th>
                                <th>Màu sắc</th>
                                <th>SL Tồn</th>
                                <th>SL Đặt</th>
                                <th>SL Có thể bán</th>
                                <th>Mức cảnh báo</th>
                                <th>Trạng thái</th>
                                <th style="text-align: center;">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $stt = 1;
                            while ($item = mysqli_fetch_assoc($inventoryItems)): 
                            $rowClass = '';
                            if ($item['trang_thai_ton'] == 'warning') $rowClass = 'stock-warning';
                            elseif ($item['trang_thai_ton'] == 'danger') $rowClass = 'stock-danger';
                            ?>
                            <tr class="<?php echo $rowClass; ?>">
                                <td><?php echo $stt++; ?></td>
                                <td>
                                    <?php if (!empty($item['sanpham_anh'])): ?>
                                        <img src="public/uploads/<?php echo htmlspecialchars($item['sanpham_anh']); ?>" 
                                             class="product-image" 
                                             alt="<?php echo htmlspecialchars($item['ten_sanpham']); ?>"
                                             onerror="this.src='public/images/no-image.png'">
                                    <?php else: ?>
                                        <div class="product-image" style="background: #f8f9fa; display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-image" style="color: #ccc;"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="product-title" title="<?php echo htmlspecialchars($item['ten_sanpham']); ?>">
                                        <strong><?php echo htmlspecialchars($item['ten_sanpham']); ?></strong>
                                    </div>
                                    <small style="color: #6c757d;">
                                        <?php echo htmlspecialchars($item['danhmuc_ten'] ?? ''); ?>
                                    </small>
                                </td>
                                <td>
                                    <span class="sku-code">
                                        <?php echo htmlspecialchars($item['sku']); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="size-badge">
                                        <?php echo htmlspecialchars($item['size']); ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="color-display">
                                        <?php if (!empty($item['mau_anh'])): ?>
                                            <img src="public/uploads/<?php echo htmlspecialchars($item['mau_anh']); ?>" 
                                                 class="color-swatch" 
                                                 title="<?php echo htmlspecialchars($item['mau_sac']); ?>">
                                        <?php else: ?>
                                            <div class="color-swatch" style="background-color: #<?php echo substr(md5($item['mau_sac']), 0, 6); ?>"></div>
                                        <?php endif; ?>
                                        <small><?php echo htmlspecialchars($item['mau_sac']); ?></small>
                                    </div>
                                </td>
                                <td class="quantity-display">
                                    <input type="number" 
                                           class="editable-stock stock-input" 
                                           data-field="soluong_ton"
                                           data-variant-id="<?php echo $item['bienthe_id']; ?>"
                                           value="<?php echo $item['soluong_ton']; ?>" 
                                           min="0">
                                </td>
                                <td class="quantity-display">
                                    <input type="number" 
                                           class="editable-stock stock-input" 
                                           data-field="soluong_dat"
                                           data-variant-id="<?php echo $item['bienthe_id']; ?>"
                                           value="<?php echo $item['soluong_dat']; ?>" 
                                           min="0">
                                </td>
                                <td class="quantity-display">
                                    <span class="available-qty" id="available-<?php echo $item['bienthe_id']; ?>">
                                        <?php echo $item['soluong_co_the_ban']; ?>
                                    </span>
                                </td>
                                <td class="quantity-display">
                                    <input type="number" 
                                           class="editable-stock stock-input" 
                                           data-field="muc_canh_bao"
                                           data-variant-id="<?php echo $item['bienthe_id']; ?>"
                                           value="<?php echo $item['muc_canh_bao']; ?>" 
                                           min="1">
                                </td>
                                <td>
                                    <?php if ($item['trang_thai_ton'] == 'danger'): ?>
                                        <span class="status-badge status-danger">Hết hàng</span>
                                    <?php elseif ($item['trang_thai_ton'] == 'warning'): ?>
                                        <span class="status-badge status-warning">Sắp hết</span>
                                    <?php else: ?>
                                        <span class="status-badge status-success">Còn hàng</span>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align: center;">
                                    <a href="index.php?page=admin_inventory&section=detail&action=detail&id=<?php echo $item['sanpham_id']; ?>" 
                                        class="action-btn btn-view" 
                                        title="Chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <br>
                                    <button type="button" 
                                            class="action-btn btn-save save-stock-btn" 
                                            data-variant-id="<?php echo $item['bienthe_id']; ?>"
                                            title="Lưu thay đổi">
                                        <i class="fas fa-save"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Phân trang -->
                <?php if (($pagination['total_pages'] ?? 1) > 1): ?>
                <div style="margin-top: 20px;">
                    <nav aria-label="Inventory pagination">
                        <ul class="pagination justify-content-center">
                            <?php if ($pagination['current_page'] > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?php echo buildPaginationUrl(1, $filters ?? []); ?>">Đầu</a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" href="<?php echo buildPaginationUrl($pagination['current_page'] - 1, $filters ?? []); ?>">Trước</a>
                                </li>
                            <?php endif; ?>

                            <?php 
                            $start = max(1, $pagination['current_page'] - 2);
                            $end = min($pagination['total_pages'], $pagination['current_page'] + 2);
                            ?>
                            
                            <?php for ($i = $start; $i <= $end; $i++): ?>
                                <li class="page-item <?php echo ($i == $pagination['current_page']) ? 'active' : ''; ?>">
                                    <a class="page-link" href="<?php echo buildPaginationUrl($i, $filters ?? []); ?>"><?php echo $i; ?></a>
                                </li>
                            <?php endfor; ?>

                            <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?php echo buildPaginationUrl($pagination['current_page'] + 1, $filters ?? []); ?>">Sau</a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" href="<?php echo buildPaginationUrl($pagination['total_pages'], $filters ?? []); ?>">Cuối</a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>

                    <div class="text-center">
                        <small style="color: #6c757d;">
                            Hiển thị <?php echo (($pagination['current_page'] - 1) * $pagination['limit'] + 1); ?> - 
                            <?php echo min($pagination['current_page'] * $pagination['limit'], $pagination['total_items']); ?> 
                            trong tổng số <?php echo $pagination['total_items']; ?> mục
                        </small>
                    </div>
                </div>
                <?php endif; ?>

            <?php else: ?>
                <div class="no-data">
                    <i class="fas fa-warehouse"></i>
                    <h3>Không có dữ liệu tồn kho</h3>
                    <p>Hãy thêm biến thể sản phẩm để bắt đầu quản lý tồn kho</p>
                    <a href="index.php?page=admin_product" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Quản lý sản phẩm
                    </a>
                </div>
            <?php endif; ?>
        </div>

        <!-- Sản phẩm sắp hết hàng -->
        <?php if (isset($lowStockItems) && $lowStockItems && mysqli_num_rows($lowStockItems) > 0): ?>
        <div style="background: #fff; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); padding: 20px; margin-top: 20px;">
            <h3 style="color: #dc3545; margin-bottom: 20px;">
                <i class="fas fa-exclamation-triangle"></i> Sản phẩm sắp hết hàng
            </h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 15px;">
                <?php while ($item = mysqli_fetch_assoc($lowStockItems)): ?>
                <div style="background: #fff3cd; border: 1px solid #ffeaa7; border-radius: 8px; padding: 15px;">
                    <div style="display: flex; align-items: center; gap: 15px;">
                        <img src="public/uploads/<?php echo htmlspecialchars($item['sanpham_anh']); ?>" 
                             style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;"
                             alt="<?php echo htmlspecialchars($item['ten_sanpham']); ?>">
                        <div style="flex-grow: 1;">
                            <h6 style="margin: 0 0 5px 0; color: #333;">
                                <?php echo htmlspecialchars($item['ten_sanpham']); ?>
                            </h6>
                            <small style="color: #6c757d;">
                                <?php echo htmlspecialchars($item['mau_sac']); ?> - <?php echo htmlspecialchars($item['size']); ?>
                            </small>
                            <div style="margin-top: 8px;">
                                <span class="status-badge status-warning">
                                    Còn <?php echo $item['soluong_ton']; ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <script>
        // Auto save khi thay đổi stock
        document.addEventListener('DOMContentLoaded', function() {
            const stockInputs = document.querySelectorAll('.stock-input');
            const saveButtons = document.querySelectorAll('.save-stock-btn');

            // Lắng nghe sự kiện thay đổi input
            stockInputs.forEach(input => {
                input.addEventListener('change', function() {
                    const variantId = this.dataset.variantId;
                    const saveBtn = document.querySelector(`[data-variant-id="${variantId}"].save-stock-btn`);
                    if (saveBtn) {
                        saveBtn.classList.add('btn-warning');
                        saveBtn.classList.remove('btn-save');
                        saveBtn.style.background = '#ffc107';
                    }
                });
            });

            // Xử lý lưu thay đổi
            saveButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    const variantId = this.dataset.variantId;
                    updateStock(variantId, this);
                });
            });
        });

        function updateStock(variantId, button) {
            const inputs = document.querySelectorAll(`[data-variant-id="${variantId}"].stock-input`);
            const formData = new FormData();
            
            formData.append('bienthe_id', variantId);
            
            inputs.forEach(input => {
                formData.append(input.dataset.field, input.value);
            });

            // Disable button during request
            button.disabled = true;
            const originalHtml = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

            fetch('index.php?page=admin_inventory&action=update_stock', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update available quantity
                    const availableSpan = document.getElementById(`available-${variantId}`);
                    if (availableSpan && data.data) {
                        availableSpan.textContent = data.data.soluong_co_the_ban;
                    }
                    
                    // Reset button
                    button.classList.remove('btn-warning');
                    button.classList.add('btn-save');
                    button.style.background = '#28a745';
                    
                    showAlert('success', data.message || 'Cập nhật thành công!');
                } else {
                    showAlert('error', data.message || 'Có lỗi xảy ra!');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('error', 'Có lỗi xảy ra khi cập nhật!');
            })
            .finally(() => {
                button.disabled = false;
                button.innerHTML = originalHtml;
            });
        }

        function showAlert(type, message) {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type === 'success' ? 'success' : 'error'}`;
            alertDiv.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
                ${message}
            `;
            
            // Insert at top of main content
            const container = document.querySelector('.management-container');
            container.insertBefore(alertDiv, container.firstChild);
            
            // Auto dismiss after 3 seconds
            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.remove();
                }
            }, 3000);
        }

        function refreshPage() {
            window.location.reload();
        }

        // PHP function to build pagination URLs
        function buildPaginationUrl(page) {
            const params = new URLSearchParams();
            params.set('page', 'admin_inventory');
            params.set('section', 'list_inventory');
            params.set('page_num', page);
            
            const search = document.querySelector('input[name="search"]');
            const category = document.querySelector('select[name="category"]');
            
            if (search && search.value) params.set('search', search.value);
            if (category && category.value) params.set('category', category.value);
            
            return 'index.php?' + params.toString();
        }
    </script>
</body>
</html>

<?php
// PHP function to build pagination URLs (for server-side rendering)
function buildPaginationUrl($page, $filters) {
    $params = [
        'page' => 'admin_inventory',
        'section' => 'list_inventory',
        'page_num' => $page
    ];
    
    if (!empty($filters['search'])) {
        $params['search'] = $filters['search'];
    }
    
    if (!empty($filters['category'])) {
        $params['category'] = $filters['category'];
    }
    
    return 'index.php?' . http_build_query($params);
}
?>