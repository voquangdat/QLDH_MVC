<?php
// filepath: e:\QLDH_MVC\app\views\admin\inventory\add_variant.php
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Thêm biến thể'; ?></title>
    <script src="https://kit.fontawesome.com/54f0cb7e4a.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="/public/css/admin-dashboard.css">
    <style>
        .management-container {
            max-width: 1000px;
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
        
        .form-container {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .form-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            text-align: center;
        }
        
        .form-body {
            padding: 30px;
        }
        
        .product-info {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
            border-left: 4px solid #667eea;
        }
        
        .product-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid #dee2e6;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #495057;
        }
        
        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            background: #fff;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: #667eea;
            outline: none;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
            background: #fff;
        }
        
        .form-select {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 16px 12px;
            appearance: none;
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
            font-size: 0.95rem;
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
        
        .btn-success {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
        }
        
        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4);
        }
        
        .form-actions {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
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
        
        .preview-section {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
            border: 2px dashed #dee2e6;
        }
        
        .preview-title {
            color: #495057;
            font-weight: 600;
            margin-bottom: 15px;
            text-align: center;
        }
        
        .variant-preview {
            background: white;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            border: 1px solid #dee2e6;
        }
        
        .sku-preview {
            background: #667eea;
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            font-family: 'Courier New', monospace;
            font-weight: 600;
            display: inline-block;
            margin-top: 10px;
        }
        
        .color-preview {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin: 10px 0;
        }
        
        .color-swatch {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            border: 2px solid #dee2e6;
        }
        
        .size-preview {
            background: #6c757d;
            color: white;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 0.85rem;
            display: inline-block;
        }
        
        .quantity-preview {
            background: linear-gradient(135deg, #17a2b8 0%, #20c997 100%);
            color: white;
            padding: 8px 15px;
            border-radius: 15px;
            font-weight: 600;
            margin-top: 10px;
            display: inline-block;
        }
        
        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .form-actions {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <!-- Admin Header -->
    <div class="admin-header">
        <div class="container">
            <h1><i class="fas fa-plus-circle"></i> Thêm Biến thể</h1>
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
            <h2>Thêm Biến thể mới</h2>
            <p>Tạo biến thể mới cho sản phẩm với màu sắc và kích thước khác nhau</p>
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

        <!-- Form Container -->
        <div class="form-container">
            <div class="form-header">
                <h4 style="margin: 0;">
                    <i class="fas fa-tags"></i> Thông tin Biến thể
                </h4>
            </div>
            
            <div class="form-body">
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
                                    <i class="fas fa-image" style="color: #ccc; font-size: 1.5rem;"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-10">
                            <h5 style="color: #333; margin-bottom: 5px;"><?php echo htmlspecialchars($product['sanpham_tieude']); ?></h5>
                            <p style="color: #6c757d; margin-bottom: 5px;">
                                <strong>Danh mục:</strong> <?php echo htmlspecialchars($product['danhmuc_ten']); ?> - 
                                <?php echo htmlspecialchars($product['loaisanpham_ten']); ?>
                            </p>
                            <p style="color: #6c757d; margin: 0;">
                                <strong>Mã sản phẩm:</strong> 
                                <span style="background: #e9ecef; padding: 2px 6px; border-radius: 4px; font-family: monospace;">
                                    <?php echo htmlspecialchars($product['sanpham_ma']); ?>
                                </span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Form thêm biến thể -->
                <form method="POST" action="index.php?page=admin_inventory&section=detail&action=add_variant" id="addVariantForm">
                    <input type="hidden" name="sanpham_id" value="<?php echo $product['sanpham_id']; ?>">
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="color_id" class="form-label">
                                <i class="fas fa-palette"></i> Màu sắc *
                            </label>
                            <select name="color_id" id="color_id" class="form-control form-select" required>
                                <option value="">-- Chọn màu sắc --</option>
                                <?php if ($availableColors && mysqli_num_rows($availableColors) > 0): ?>
                                    <?php while ($color = mysqli_fetch_assoc($availableColors)): ?>
                                        <option value="<?php echo $color['color_id']; ?>" 
                                                data-color-name="<?php echo htmlspecialchars($color['color_ten']); ?>"
                                                data-color-image="<?php echo htmlspecialchars($color['color_anh'] ?? ''); ?>">
                                            <?php echo htmlspecialchars($color['color_ten']); ?>
                                        </option>
                                    <?php endwhile; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="size_id" class="form-label">
                                <i class="fas fa-ruler"></i> Kích thước *
                            </label>
                            <select name="size_id" id="size_id" class="form-control form-select" required>
                                <option value="">-- Chọn kích thước --</option>
                                <?php if ($availableSizes && mysqli_num_rows($availableSizes) > 0): ?>
                                    <?php while ($size = mysqli_fetch_assoc($availableSizes)): ?>
                                        <option value="<?php echo $size['sanpham_size_id']; ?>"
                                                data-size-name="<?php echo htmlspecialchars($size['sanpham_size']); ?>">
                                            <?php echo htmlspecialchars($size['sanpham_size']); ?>
                                        </option>
                                    <?php endwhile; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="soluong" class="form-label">
                                <i class="fas fa-boxes"></i> Số lượng ban đầu
                            </label>
                            <input type="number" name="soluong" id="soluong" class="form-control" 
                                   value="0" min="0" max="999999"
                                   placeholder="Nhập số lượng tồn kho ban đầu">
                        </div>
                        
                        <div class="form-group">
                            <label for="muc_canh_bao" class="form-label">
                                <i class="fas fa-exclamation-triangle"></i> Mức cảnh báo
                            </label>
                            <input type="number" name="muc_canh_bao" id="muc_canh_bao" class="form-control" 
                                   value="10" min="1" max="999"
                                   placeholder="Mức cảnh báo khi sắp hết hàng">
                        </div>
                    </div>

                    <!-- Preview Section -->
                    <div class="preview-section" id="variantPreview" style="display: none;">
                        <div class="preview-title">
                            <i class="fas fa-eye"></i> Xem trước Biến thể
                        </div>
                        <div class="variant-preview">
                            <div class="color-preview" id="colorPreview"></div>
                            <div class="size-preview" id="sizePreview"></div>
                            <div class="sku-preview" id="skuPreview"></div>
                            <div class="quantity-preview" id="quantityPreview">Số lượng: 0</div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="form-actions">
                        <a href="index.php?page=admin_inventory&section=detail&action=detail&id=<?php echo $product['sanpham_id']; ?>" 
                           class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Quay lại
                        </a>
                        <button type="submit" class="btn btn-success" id="submitBtn">
                            <i class="fas fa-plus"></i> Thêm Biến thể
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const colorSelect = document.getElementById('color_id');
            const sizeSelect = document.getElementById('size_id');
            const quantityInput = document.getElementById('soluong');
            const previewSection = document.getElementById('variantPreview');
            const colorPreview = document.getElementById('colorPreview');
            const sizePreview = document.getElementById('sizePreview');
            const skuPreview = document.getElementById('skuPreview');
            const quantityPreview = document.getElementById('quantityPreview');
            const form = document.getElementById('addVariantForm');
            const submitBtn = document.getElementById('submitBtn');

            // Cập nhật preview khi thay đổi
            function updatePreview() {
                const colorOption = colorSelect.options[colorSelect.selectedIndex];
                const sizeOption = sizeSelect.options[sizeSelect.selectedIndex];
                const quantity = quantityInput.value || 0;

                if (colorSelect.value && sizeSelect.value) {
                    previewSection.style.display = 'block';
                    
                    // Color preview
                    const colorName = colorOption.dataset.colorName;
                    const colorImage = colorOption.dataset.colorImage;
                    
                    colorPreview.innerHTML = `
                        ${colorImage ? 
                            `<img src="public/uploads/${colorImage}" class="color-swatch" alt="${colorName}">` :
                            `<div class="color-swatch" style="background-color: #${Math.random().toString(16).substr(2, 6)};"></div>`
                        }
                        <strong>${colorName}</strong>
                    `;
                    
                    // Size preview
                    sizePreview.textContent = sizeOption.dataset.sizeName;
                    
                    // SKU preview
                    const productCode = '<?php echo $product['sanpham_ma']; ?>';
                    const colorCode = colorName.substring(0, 2).toUpperCase();
                    const sizeCode = sizeOption.dataset.sizeName;
                    skuPreview.textContent = `${productCode}-${colorCode}-${sizeCode}`;
                    
                    // Quantity preview
                    quantityPreview.innerHTML = `<i class="fas fa-boxes"></i> Số lượng: ${quantity}`;
                } else {
                    previewSection.style.display = 'none';
                }
            }

            // Event listeners
            colorSelect.addEventListener('change', updatePreview);
            sizeSelect.addEventListener('change', updatePreview);
            quantityInput.addEventListener('input', updatePreview);

            // Form validation
            form.addEventListener('submit', function(e) {
                if (!colorSelect.value || !sizeSelect.value) {
                    e.preventDefault();
                    alert('Vui lòng chọn đầy đủ màu sắc và kích thước!');
                    return;
                }

                // Disable submit button to prevent double submission
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang thêm...';
            });

            // Check for existing variant (optional AJAX check)
            function checkExistingVariant() {
                if (colorSelect.value && sizeSelect.value) {
                    // You can add AJAX call here to check if variant already exists
                    // This is optional but provides better UX
                }
            }

            colorSelect.addEventListener('change', checkExistingVariant);
            sizeSelect.addEventListener('change', checkExistingVariant);
        });
    </script>
</body>
</html>