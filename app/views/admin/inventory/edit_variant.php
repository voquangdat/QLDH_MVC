<?php
// filepath: e:\QLDH_MVC\app\views\admin\inventory\edit_variant.php
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Chỉnh sửa biến thể'; ?></title>
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
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            color: white;
            padding: 20px;
            text-align: center;
        }
        
        .form-body {
            padding: 30px;
        }
        
        .variant-info {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
            border-left: 4px solid #17a2b8;
        }
        
        .product-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid #dee2e6;
        }
        
        .current-variant {
            background: white;
            border-radius: 8px;
            padding: 15px;
            margin-top: 15px;
            border: 1px solid #dee2e6;
        }
        
        .variant-display {
            display: flex;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
        }
        
        .color-display {
            display: flex;
            align-items: center;
            gap: 8px;
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
            font-size: 0.85rem;
            font-weight: 600;
        }
        
        .sku-code {
            background: #17a2b8;
            color: white;
            padding: 6px 12px;
            border-radius: 15px;
            font-family: 'Courier New', monospace;
            font-weight: 600;
            font-size: 0.85rem;
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
            border-color: #17a2b8;
            outline: none;
            box-shadow: 0 0 0 0.2rem rgba(23, 162, 184, 0.25);
            background: #fff;
        }
        
        .form-control:disabled {
            background: #e9ecef;
            opacity: 1;
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
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            color: white;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(23, 162, 184, 0.4);
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
        
        .btn-danger {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
        }
        
        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(220, 53, 69, 0.4);
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
        
        .stock-summary {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-top: 20px;
        }
        
        .stock-item {
            text-align: center;
            padding: 15px;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 10px;
            border: 1px solid #dee2e6;
        }
        
        .stock-value {
            font-size: 1.5rem;
            font-weight: bold;
            color: #495057;
            display: block;
        }
        
        .stock-label {
            font-size: 0.85rem;
            color: #6c757d;
            margin-top: 5px;
        }
        
        .available-calc {
            background: linear-gradient(135deg, #17a2b8 0%, #20c997 100%);
            color: white;
        }
        
        .validation-error {
            border-color: #dc3545 !important;
            background-color: #f8d7da !important;
        }
        
        .validation-message {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 5px;
        }
        
        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .stock-summary {
                grid-template-columns: 1fr;
            }
            
            .variant-display {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
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
            <h1><i class="fas fa-edit"></i> Chỉnh sửa Biến thể</h1>
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
            <h2>Chỉnh sửa Biến thể</h2>
            <p>Cập nhật thông tin tồn kho và cài đặt cho biến thể sản phẩm</p>
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
                    <i class="fas fa-cog"></i> Cập nhật Biến thể
                </h4>
            </div>
            
            <div class="form-body">
                <!-- Thông tin biến thể hiện tại -->
                <div class="variant-info">
                    <div class="row align-items-center">
                        <div class="col-md-2">
                            <?php if (!empty($variant['sanpham_anh'])): ?>
                                <img src="public/uploads/<?php echo htmlspecialchars($variant['sanpham_anh']); ?>" 
                                     class="product-image" 
                                     alt="<?php echo htmlspecialchars($variant['sanpham_tieude']); ?>"
                                     onerror="this.src='public/images/no-image.png'">
                            <?php else: ?>
                                <div class="product-image" style="background: #f8f9fa; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-image" style="color: #ccc; font-size: 1.5rem;"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-10">
                            <h5 style="color: #333; margin-bottom: 5px;"><?php echo htmlspecialchars($variant['sanpham_tieude']); ?></h5>
                            <p style="color: #6c757d; margin-bottom: 10px;">
                                <strong>Sản phẩm ID:</strong> #<?php echo $variant['sanpham_id']; ?>
                            </p>
                            
                            <!-- Hiển thị thông tin biến thể hiện tại -->
                            <div class="current-variant">
                                <h6 style="margin-bottom: 10px; color: #495057;">
                                    <i class="fas fa-tag"></i> Thông tin Biến thể hiện tại
                                </h6>
                                <div class="variant-display">
                                    <div class="color-display">
                                        <?php if (!empty($variant['color_anh'])): ?>
                                            <img src="public/uploads/<?php echo htmlspecialchars($variant['color_anh']); ?>" 
                                                 class="color-swatch" 
                                                 title="<?php echo htmlspecialchars($variant['color_ten']); ?>">
                                        <?php else: ?>
                                            <div class="color-swatch" style="background-color: #<?php echo substr(md5($variant['color_ten']), 0, 6); ?>"></div>
                                        <?php endif; ?>
                                        <strong><?php echo htmlspecialchars($variant['color_ten']); ?></strong>
                                    </div>
                                    
                                    <span class="size-badge"><?php echo htmlspecialchars($variant['sanpham_size']); ?></span>
                                    
                                    <span class="sku-code"><?php echo htmlspecialchars($variant['bienthe_ma']); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form chỉnh sửa -->
                <form method="POST" action="index.php?page=admin_inventory&section=detail&action=edit_variant" id="editVariantForm">
                    <input type="hidden" name="bienthe_id" value="<?php echo $variant['bienthe_id']; ?>">
                    
                    <!-- Thông tin cơ bản (readonly) -->
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-barcode"></i> Mã SKU
                            </label>
                            <input type="text" class="form-control" 
                                   value="<?php echo htmlspecialchars($variant['bienthe_ma']); ?>" 
                                   disabled>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-calendar-plus"></i> Ngày tạo
                            </label>
                            <input type="text" class="form-control" 
                                   value="<?php echo date('d/m/Y H:i', strtotime($variant['created_at'] ?? 'now')); ?>" 
                                   disabled>
                        </div>
                    </div>
                    
                    <!-- Thông tin có thể chỉnh sửa -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="soluong_ton" class="form-label">
                                <i class="fas fa-boxes"></i> Số lượng tồn kho *
                            </label>
                            <input type="number" name="soluong_ton" id="soluong_ton" class="form-control" 
                                   value="<?php echo $variant['soluong_ton']; ?>" 
                                   min="0" max="999999" required
                                   placeholder="Nhập số lượng tồn kho">
                            <div class="validation-message" id="soluong_ton_error"></div>
                        </div>
                        
                        <div class="form-group">
                            <label for="soluong_dat" class="form-label">
                                <i class="fas fa-shopping-cart"></i> Số lượng đã đặt
                            </label>
                            <input type="number" name="soluong_dat" id="soluong_dat" class="form-control" 
                                   value="<?php echo $variant['soluong_dat']; ?>" 
                                   min="0" max="999999"
                                   placeholder="Số lượng khách đã đặt">
                            <div class="validation-message" id="soluong_dat_error"></div>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="muc_canh_bao" class="form-label">
                                <i class="fas fa-exclamation-triangle"></i> Mức cảnh báo *
                            </label>
                            <input type="number" name="muc_canh_bao" id="muc_canh_bao" class="form-control" 
                                   value="<?php echo $variant['muc_canh_bao']; ?>" 
                                   min="1" max="999" required
                                   placeholder="Mức cảnh báo khi sắp hết">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-calculator"></i> Có thể bán
                            </label>
                            <input type="text" class="form-control available-calc" 
                                   id="soluong_co_the_ban"
                                   value="<?php echo ($variant['soluong_ton'] - $variant['soluong_dat']); ?>" 
                                   disabled>
                        </div>
                    </div>

                    <!-- Tóm tắt trạng thái -->
                    <div class="stock-summary">
                        <div class="stock-item">
                            <span class="stock-value" id="display_ton"><?php echo number_format($variant['soluong_ton']); ?></span>
                            <div class="stock-label">Tồn kho</div>
                        </div>
                        <div class="stock-item">
                            <span class="stock-value" id="display_dat"><?php echo number_format($variant['soluong_dat']); ?></span>
                            <div class="stock-label">Đã đặt</div>
                        </div>
                        <div class="stock-item available-calc">
                            <span class="stock-value" id="display_available"><?php echo number_format($variant['soluong_ton'] - $variant['soluong_dat']); ?></span>
                            <div class="stock-label">Có thể bán</div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="form-actions">
                        <a href="index.php?page=admin_inventory&section=detail&action=detail&id=<?php echo $variant['sanpham_id']; ?>" 
                           class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Quay lại
                        </a>
                        <button type="submit" class="btn btn-success" id="submitBtn">
                            <i class="fas fa-save"></i> Cập nhật
                        </button>
                        <a href="index.php?page=admin_inventory&section=detail&action=delete_variant&variant_id=<?php echo $variant['bienthe_id']; ?>&product_id=<?php echo $variant['sanpham_id']; ?>" 
                           class="btn btn-danger" 
                           onclick="return confirm('Bạn có chắc muốn xóa biến thể này? Hành động này không thể hoàn tác!')">
                            <i class="fas fa-trash"></i> Xóa
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tonInput = document.getElementById('soluong_ton');
            const datInput = document.getElementById('soluong_dat');
            const availableInput = document.getElementById('soluong_co_the_ban');
            const form = document.getElementById('editVariantForm');
            const submitBtn = document.getElementById('submitBtn');
            
            // Display elements
            const displayTon = document.getElementById('display_ton');
            const displayDat = document.getElementById('display_dat');
            const displayAvailable = document.getElementById('display_available');

            // Cập nhật số lượng có thể bán
            function updateAvailable() {
                const ton = parseInt(tonInput.value) || 0;
                const dat = parseInt(datInput.value) || 0;
                const available = Math.max(0, ton - dat);
                
                availableInput.value = available;
                
                // Update display
                displayTon.textContent = ton.toLocaleString();
                displayDat.textContent = dat.toLocaleString();
                displayAvailable.textContent = available.toLocaleString();
                
                // Validation
                validateInputs(ton, dat);
            }

            // Validation function
            function validateInputs(ton, dat) {
                const tonError = document.getElementById('soluong_ton_error');
                const datError = document.getElementById('soluong_dat_error');
                
                // Clear previous errors
                tonInput.classList.remove('validation-error');
                datInput.classList.remove('validation-error');
                tonError.textContent = '';
                datError.textContent = '';
                
                let isValid = true;
                
                // Validate số lượng đặt không được lớn hơn tồn kho
                if (dat > ton) {
                    datInput.classList.add('validation-error');
                    datError.textContent = 'Số lượng đặt không thể lớn hơn số lượng tồn kho!';
                    isValid = false;
                }
                
                // Update submit button
                submitBtn.disabled = !isValid;
                if (!isValid) {
                    submitBtn.style.opacity = '0.6';
                } else {
                    submitBtn.style.opacity = '1';
                }
                
                return isValid;
            }

            // Event listeners
            tonInput.addEventListener('input', updateAvailable);
            datInput.addEventListener('input', updateAvailable);

            // Form validation on submit
            form.addEventListener('submit', function(e) {
                const ton = parseInt(tonInput.value) || 0;
                const dat = parseInt(datInput.value) || 0;
                
                if (!validateInputs(ton, dat)) {
                    e.preventDefault();
                    alert('Vui lòng sửa các lỗi trong form trước khi gửi!');
                    return;
                }
                
                // Confirm if reducing stock significantly
                const currentTon = <?php echo $variant['soluong_ton']; ?>;
                if (ton < currentTon && (currentTon - ton) > 10) {
                    if (!confirm(`Bạn đang giảm tồn kho từ ${currentTon} xuống ${ton}. Bạn có chắc chắn?`)) {
                        e.preventDefault();
                        return;
                    }
                }

                // Disable submit button to prevent double submission
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang cập nhật...';
            });

            // Initial validation
            updateAvailable();
        });
    </script>
</body>
</html>