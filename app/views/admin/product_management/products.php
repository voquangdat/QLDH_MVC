<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Quản lý Sản phẩm'; ?></title>
    <script src="https://kit.fontawesome.com/54f0cb7e4a.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="/public/css/admin-dashboard.css">
    <style>
        .management-container {
            max-width: 1200px;
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
        
        .action-btn {
            padding: 8px 15px;
            border-radius: 20px;
            text-decoration: none;
            font-size: 0.85rem;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            margin-right: 5px;
            transition: all 0.3s ease;
        }
        
        .btn-edit {
            background: #28a745;
            color: white;
        }
        
        .btn-edit:hover {
            background: #218838;
            color: white;
            text-decoration: none;
            transform: translateY(-1px);
        }
        
        .btn-delete {
            background: #dc3545;
            color: white;
        }
        
        .btn-delete:hover {
            background: #c82333;
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
        
        .product-detail {
            max-width: 150px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .price-badge {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 5px 12px;
            border-radius: 15px;
            font-weight: 600;
            font-size: 0.85rem;
        }
        
        .category-badge {
            background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
            color: #8b4513;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .product-type-badge {
            background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
            color: #6c757d;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
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
        
        .btn-images {
            background: #6f42c1;
            color: white;
        }
        
        .btn-images:hover {
            background: #5a32a3;
            color: white;
            text-decoration: none;
            transform: translateY(-1px);
        }
        
        .btn-sizes {
            background: #fd7e14;
            color: white;
        }
        
        .btn-sizes:hover {
            background: #e8610f;
            color: white;
            text-decoration: none;
            transform: translateY(-1px);
        }
        
        .table th {
            white-space: nowrap;
        }
        
        .table-responsive {
            overflow-x: auto;
        }
    </style>
</head>
<body>
    <!-- Admin Header -->
    <div class="admin-header">
        <div class="container">
            <h1><i class="fas fa-list"></i> Quản lý sản phẩm</h1>
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
            <h2>Quản lý Sản phẩm</h2>
            <p>Thêm, sửa, xóa và quản lý toàn bộ sản phẩm trong hệ thống</p>

            <div class="action-buttons">
                <a href="index.php?page=admin_product&section=products&action=add" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Thêm Sản phẩm
                </a>
                <a href="index.php?page=admin_product" class="btn btn-secondary">
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

        <!-- Data Table -->
        <div class="data-table">
            <?php if ($products && $products->num_rows > 0): ?>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>ID</th>
                                <th>Tiêu đề</th>
                                <th>Mã SP</th>
                                <th>Danh mục</th>
                                <th>Loại SP</th>
                                <th>Màu sắc</th>   
                                <th>Giá</th> 
                                <th>Chi tiết</th> 
                                <th>Bảo quản</th>   
                                <th>Ảnh chính</th>   
                                <th>Ảnh SP</th>     
                                <th>Size SP</th>
                                <th style="text-align: center;">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $stt = 1;
                            while ($product = $products->fetch_assoc()): 
                            ?>
                                <tr>
                                    <td><?php echo $stt++; ?></td>
                                    <td>
                                        <strong style="color: #667eea;">#<?php echo htmlspecialchars($product['sanpham_id']); ?></strong>
                                    </td>
                                    <td>
                                        <div class="product-title" title="<?php echo htmlspecialchars($product['sanpham_tieude']); ?>">
                                            <strong><?php echo htmlspecialchars($product['sanpham_tieude']); ?></strong>
                                        </div>
                                    </td>
                                    <td>
                                        <code style="background: #f8f9fa; padding: 3px 6px; border-radius: 4px;">
                                            <?php echo htmlspecialchars($product['sanpham_ma']); ?>
                                        </code>
                                    </td>
                                    <td>
                                        <span class="category-badge">
                                            <?php echo htmlspecialchars($product['danhmuc_ten'] ?? 'N/A'); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="product-type-badge">
                                            <?php echo htmlspecialchars($product['loaisanpham_ten'] ?? 'N/A'); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="color-display">
                                            <?php if (!empty($product['color_anh'])): ?>
                                                <img src="/public/uploads/<?php echo htmlspecialchars($product['color_anh']); ?>" 
                                                     class="color-swatch" 
                                                     alt="<?php echo htmlspecialchars($product['color_ten']); ?>"
                                                     title="<?php echo htmlspecialchars($product['color_ten']); ?>">
                                            <?php else: ?>
                                                <div class="color-swatch" style="background: #ccc;"></div>
                                            <?php endif; ?>
                                            <small><?php echo htmlspecialchars($product['color_ten'] ?? 'N/A'); ?></small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="price-badge">
                                            <?php echo number_format($product['sanpham_gia'], 0, ',', '.'); ?>₫
                                        </span>
                                        <?php if (!empty($product['sanpham_giakhuyenmai']) && $product['sanpham_giakhuyenmai'] > 0): ?>
                                            <br><small style="color: #6c757d; text-decoration: line-through;">
                                                <?php echo number_format($product['sanpham_giakhuyenmai'], 0, ',', '.'); ?>₫
                                            </small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="product-detail" title="<?php echo htmlspecialchars($product['sanpham_chitiet']); ?>">
                                            <?php echo htmlspecialchars(mb_substr($product['sanpham_chitiet'], 0, 50) . '...'); ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="product-detail" title="<?php echo htmlspecialchars($product['sanpham_baoquan']); ?>">
                                            <?php echo htmlspecialchars(mb_substr($product['sanpham_baoquan'], 0, 30) . '...'); ?>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if (!empty($product['sanpham_anh'])): ?>
                                            <img src="/public/uploads/<?php echo htmlspecialchars($product['sanpham_anh']); ?>" 
                                                 class="product-image" 
                                                 alt="<?php echo htmlspecialchars($product['sanpham_tieude']); ?>">
                                        <?php else: ?>
                                            <div class="product-image" style="background: #f8f9fa; display: flex; align-items: center; justify-content: center;">
                                                <i class="fas fa-image" style="color: #ccc;"></i>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td style="text-align: center;">
                                        <a href="index.php?page=admin_product&section=product_images&sanpham_id=<?php echo $product['sanpham_id']; ?>" 
                                           class="action-btn btn-images" 
                                           title="Quản lý ảnh">
                                            <i class="fas fa-images"></i>
                                        </a>
                                    </td>
                                    <td style="text-align: center;">
                                        <a href="index.php?page=admin_product&section=product_sizes&sanpham_id=<?php echo $product['sanpham_id']; ?>" 
                                           class="action-btn btn-sizes" 
                                           title="Quản lý size">
                                            <i class="fas fa-ruler"></i>
                                        </a>
                                    </td>
                                    <td style="text-align: center;">
                                        <a href="index.php?page=product&sanpham_id=<?php echo $product['sanpham_id']; ?>" 
                                           class="action-btn btn-view" 
                                           target="_blank"
                                           title="Xem sản phẩm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <br><br>
                                        <a href="index.php?page=admin_product&section=products&action=edit&id=<?php echo $product['sanpham_id']; ?>" 
                                           class="action-btn btn-edit" 
                                           title="Sửa sản phẩm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <br><br>
                                        <a href="index.php?page=admin_product&section=products&action=delete&id=<?php echo $product['sanpham_id']; ?>" 
                                           class="action-btn btn-delete"
                                           title="Xóa sản phẩm"
                                           onclick="return confirm('⚠️ CẢNH BÁO: Bạn có chắc chắn muốn xóa sản phẩm này?\n\n- Sản phẩm: <?php echo addslashes($product['sanpham_tieude']); ?>\n- Mã: <?php echo addslashes($product['sanpham_ma']); ?>\n\nViệc xóa sẽ:\n- Xóa vĩnh viễn sản phẩm\n- Xóa tất cả ảnh liên quan\n- Xóa tất cả size liên quan\n\nHành động này KHÔNG THỂ HOÀN TÁC!')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="no-data">
                    <i class="fas fa-box"></i>
                    <h3>Chưa có sản phẩm nào</h3>
                    <p>Hãy thêm sản phẩm đầu tiên cho cửa hàng của bạn</p>
                    <a href="index.php?page=admin_product&section=products&action=add" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Thêm sản phẩm ngay
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>