<?php
// filepath: e:\QLDH_MVC\app\views\admin\inventory\detail.php
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Chi tiết Tồn kho'; ?></title>
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
            border: none;
            cursor: pointer;
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
        
        .product-info {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .product-image {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 10px;
            border: 2px solid #dee2e6;
        }
        
        .variant-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 20px;
        }
        
        .variant-card {
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 10px;
            padding: 20px;
            transition: all 0.3s ease;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .variant-card:hover {
            box-shadow: 0 5px 15px rgba(0,0,0,0.15);
            transform: translateY(-2px);
        }
        
        .variant-header {
            display: flex;
            justify-content: between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        
        .variant-info {
            flex-grow: 1;
        }
        
        .color-display {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 10px;
        }
        
        .color-swatch {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            border: 2px solid #dee2e6;
        }
        
        .size-badge {
            background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
            color: white;
            padding: 6px 12px;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .sku-code {
            background: #f8f9fa;
            padding: 4px 8px;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
            font-size: 0.85rem;
            color: #495057;
            margin-top: 5px;
        }
        
        .variant-actions {
            display: flex;
            gap: 5px;
        }
        
        .action-btn {
            padding: 6px 10px;
            border-radius: 15px;
            text-decoration: none;
            font-size: 0.75rem;
            display: inline-flex;
            align-items: center;
            gap: 3px;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }
        
        .btn-edit {
            background: #17a2b8;
            color: white;
        }
        
        .btn-edit:hover {
            background: #138496;
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
        
        .stock-controls {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin: 15px 0;
        }
        
        .stock-group {
            text-align: center;
        }
        
        .stock-label {
            font-size: 0.75rem;
            color: #6c757d;
            margin-bottom: 5px;
            font-weight: 600;
        }
        
        .editable-stock {
            border: 1px solid #dee2e6;
            background: #f8f9fa;
            width: 100%;
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
        
        .available-display {
            text-align: center;
            margin: 15px 0;
        }
        
        .available-qty {
            background: linear-gradient(135deg, #17a2b8 0%, #20c997 100%);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
        }
        
        .save-section {
            text-align: center;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #eee;
        }
        
        .btn-save {
            background: #28a745;
            color: white;
            padding: 10px 20px;
            border-radius: 20px;
            font-size: 0.85rem;
        }
        
        .btn-save:hover {
            background: #218838;
            color: white;
            text-decoration: none;
            transform: translateY(-1px);
        }
        
        .status-section {
            text-align: center;
            margin-top: 10px;
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
        
        .modal {
            z-index: 1050;
        }
        
        .modal-backdrop {
            z-index: 1040;
        }
        
        .form-control:focus {
            border-color: #667eea;
            outline: none;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
    </style>
</head>
<body>
    <!-- Admin Header -->
    <div class="admin-header">
        <div class="container">
            <h1><i class="fas fa-warehouse"></i> Chi tiết Tồn kho</h1>
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
            <h2>Chi tiết Tồn kho Sản phẩm</h2>
            <p>Quản lý biến thể và số lượng tồn kho của sản phẩm</p>

            <div class="action-buttons">
                <a href="index.php?page=admin_inventory&section=list_inventory" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
                <a href="index.php?page=admin_inventory&section=detail&action=show_add_variant&id=<?php echo $product['sanpham_id']; ?>" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Thêm biến thể
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

        <!-- Thông tin sản phẩm -->
        <div class="product-info">
            <div class="row align-items-center">
                <div class="col-md-2">
                    <?php if (!empty($product['sanpham_anh'])): ?>
                        <img src="public/uploads/<?php echo htmlspecialchars($product['sanpham_anh']); ?>" 
                             class="product-image" 
                             alt="<?php echo htmlspecialchars($product['sanpham_tieude']); ?>"
                             onerror="this.src='public/images/no-image.png'">
                    <?php else: ?>
                        <div class="product-image" style="background: #f8f9fa; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-image" style="color: #ccc; font-size: 2rem;"></i>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="col-md-10">
                    <h3 style="color: #333; margin-bottom: 10px;"><?php echo htmlspecialchars($product['sanpham_tieude']); ?></h3>
                    <p style="color: #6c757d; margin-bottom: 8px;">
                        <strong>Danh mục:</strong> <?php echo htmlspecialchars($product['danhmuc_ten']); ?> - 
                        <?php echo htmlspecialchars($product['loaisanpham_ten']); ?>
                    </p>
                    <p style="color: #6c757d; margin-bottom: 8px;">
                        <strong>Mã sản phẩm:</strong> 
                        <span class="sku-code"><?php echo htmlspecialchars($product['sanpham_ma']); ?></span>
                    </p>
                    <p style="color: #6c757d; margin-bottom: 0;">
                        <strong>Giá:</strong> 
                        <span style="color: #dc3545; font-weight: bold; font-size: 1.1rem;">
                            <?php echo number_format($product['sanpham_gia']); ?>₫
                        </span>
                        <?php if ($product['sanpham_giakhuyenmai'] > 0): ?>
                            <span style="text-decoration: line-through; color: #6c757d; margin-left: 10px;">
                                <?php echo number_format($product['sanpham_giakhuyenmai']); ?>₫
                            </span>
                        <?php endif; ?>
                    </p>
                </div>
            </div>
        </div>

        <!-- Danh sách biến thể -->
        <div class="data-table">
            <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 15px; border-radius: 10px 10px 0 0;">
                <h5 style="margin: 0; text-align: center;">Danh sách Biến thể</h5>
            </div>
            <div style="background: #fff; padding: 20px; border-radius: 0 0 10px 10px;">
                <?php if ($variants && mysqli_num_rows($variants) > 0): ?>
                    <div class="variant-grid">
                        <?php while ($variant = mysqli_fetch_assoc($variants)): ?>
                        <div class="variant-card">
                            <div class="variant-header">
                                <div class="variant-info">
                                    <div class="color-display">
                                        <?php if (!empty($variant['color_anh'])): ?>
                                            <img src="public/uploads/<?php echo htmlspecialchars($variant['color_anh']); ?>" 
                                                 class="color-swatch" 
                                                 title="<?php echo htmlspecialchars($variant['color_ten']); ?>">
                                        <?php else: ?>
                                            <div class="color-swatch" style="background-color: #<?php echo substr(md5($variant['color_ten']), 0, 6); ?>"></div>
                                        <?php endif; ?>
                                        <strong style="color: #333;"><?php echo htmlspecialchars($variant['color_ten']); ?></strong>
                                    </div>
                                    <div>
                                        <span class="size-badge"><?php echo htmlspecialchars($variant['sanpham_size']); ?></span>
                                    </div>
                                    <div class="sku-code" style="margin-top: 8px;">
                                        SKU: <?php echo htmlspecialchars($variant['bienthe_ma']); ?>
                                    </div>
                                </div>
                                <div class="variant-actions">
                                    <a href="index.php?page=admin_inventory&section=detail&action=show_edit_variant&variant_id=<?php echo $variant['bienthe_id']; ?>" 
                                    class="action-btn btn-edit" 
                                    title="Chỉnh sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="index.php?page=admin_inventory&section=detail&action=delete_variant&variant_id=<?php echo $variant['bienthe_id']; ?>&product_id=<?php echo $product['sanpham_id']; ?>" 
                                    class="action-btn btn-delete" 
                                    onclick="return confirm('Bạn có chắc muốn xóa biến thể này?')"
                                    title="Xóa">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </div>
                            
                            <div class="stock-controls">
                                <div class="stock-group">
                                    <div class="stock-label">Tồn kho</div>
                                    <input type="number" 
                                           class="editable-stock stock-input" 
                                           data-field="soluong_ton"
                                           data-variant-id="<?php echo $variant['bienthe_id']; ?>"
                                           value="<?php echo $variant['soluong_ton']; ?>" 
                                           min="0">
                                </div>
                                <div class="stock-group">
                                    <div class="stock-label">Đã đặt</div>
                                    <input type="number" 
                                           class="editable-stock stock-input" 
                                           data-field="soluong_dat"
                                           data-variant-id="<?php echo $variant['bienthe_id']; ?>"
                                           value="<?php echo $variant['soluong_dat']; ?>" 
                                           min="0">
                                </div>
                                <div class="stock-group">
                                    <div class="stock-label">Cảnh báo</div>
                                    <input type="number" 
                                           class="editable-stock stock-input" 
                                           data-field="muc_canh_bao"
                                           data-variant-id="<?php echo $variant['bienthe_id']; ?>"
                                           value="<?php echo $variant['muc_canh_bao']; ?>" 
                                           min="1">
                                </div>
                            </div>
                            
                            <div class="available-display">
                                <span class="available-qty" id="available-<?php echo $variant['bienthe_id']; ?>">
                                    Có thể bán: <?php echo ($variant['soluong_ton'] - $variant['soluong_dat']); ?>
                                </span>
                            </div>
                            
                            <div class="save-section">
                                <button type="button" 
                                        class="btn btn-save save-variant-btn" 
                                        data-variant-id="<?php echo $variant['bienthe_id']; ?>">
                                    <i class="fas fa-save"></i> Lưu thay đổi
                                </button>
                            </div>
                            
                            <div class="status-section">
                                <?php if ($variant['soluong_ton'] == 0): ?>
                                    <span class="status-badge status-danger">Hết hàng</span>
                                <?php elseif ($variant['soluong_ton'] <= $variant['muc_canh_bao']): ?>
                                    <span class="status-badge status-warning">Sắp hết</span>
                                <?php else: ?>
                                    <span class="status-badge status-success">Còn hàng</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <div class="no-data">
                        <i class="fas fa-box-open"></i>
                        <h3>Chưa có biến thể nào</h3>
                        <p>Hãy thêm biến thể đầu tiên cho sản phẩm này.</p>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addVariantModal">
                            <i class="fas fa-plus"></i> Thêm biến thể
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
    const saveButtons = document.querySelectorAll('.save-variant-btn');
    const stockInputs = document.querySelectorAll('.stock-input');

    // Xử lý nút lưu biến thể
    saveButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const variantId = this.dataset.variantId;
            updateVariantStock(variantId, this);
        });
    });

    // Highlight thay đổi
    stockInputs.forEach(input => {
        input.addEventListener('change', function() {
            const variantId = this.dataset.variantId;
            const saveBtn = document.querySelector(`[data-variant-id="${variantId}"].save-variant-btn`);
            if (saveBtn) {
                saveBtn.style.background = '#ffc107';
                saveBtn.style.color = '#212529';
                saveBtn.innerHTML = '<i class="fas fa-save"></i> Lưu thay đổi*';
            }
        });
    });
});

function updateVariantStock(variantId, button) {
    const inputs = document.querySelectorAll(`[data-variant-id="${variantId}"].stock-input`);
    const formData = new FormData();
    
    formData.append('bienthe_id', variantId);
    
    inputs.forEach(input => {
        formData.append(input.dataset.field, input.value);
    });

    // Disable button during request
    button.disabled = true;
    const originalHtml = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang lưu...';

    fetch('index.php?page=admin_inventory&section=detail&action=update_stock', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update available quantity
            const availableSpan = document.getElementById(`available-${variantId}`);
            if (availableSpan && data.data) {
                availableSpan.textContent = `Có thể bán: ${data.data.soluong_co_the_ban}`;
            }
            
            // Reset button
            button.style.background = '#28a745';
            button.style.color = 'white';
            button.innerHTML = '<i class="fas fa-save"></i> Lưu thay đổi';
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
    // Remove existing alerts
    const existingAlerts = document.querySelectorAll('.alert');
    existingAlerts.forEach(alert => alert.remove());

    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type === 'success' ? 'success' : 'error'}`;
    alertDiv.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
        ${message}
    `;
    
    // Insert at top of main content
    const container = document.querySelector('.management-container');
    const firstChild = container.children[0];
    container.insertBefore(alertDiv, firstChild);
    
    // Auto dismiss after 3 seconds
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 3000);
}

// Auto calculate available quantity on input change
document.addEventListener('input', function(e) {
    if (e.target.classList.contains('stock-input')) {
        const variantId = e.target.dataset.variantId;
        const tonInput = document.querySelector(`[data-variant-id="${variantId}"][data-field="soluong_ton"]`);
        const datInput = document.querySelector(`[data-variant-id="${variantId}"][data-field="soluong_dat"]`);
        const availableSpan = document.getElementById(`available-${variantId}`);
        
        if (tonInput && datInput && availableSpan) {
            const tonValue = parseInt(tonInput.value) || 0;
            const datValue = parseInt(datInput.value) || 0;
            const available = Math.max(0, tonValue - datValue);
            
            availableSpan.textContent = `Có thể bán: ${available}`;
            
            // Validate
            if (datValue > tonValue) {
                datInput.style.borderColor = '#dc3545';
                datInput.style.backgroundColor = '#f8d7da';
                showAlert('error', 'Số lượng đặt không thể lớn hơn số lượng tồn kho!');
            } else {
                datInput.style.borderColor = '#dee2e6';
                datInput.style.backgroundColor = '#f8f9fa';
            }
        }
    }
});
    </script>
</body>
</html>