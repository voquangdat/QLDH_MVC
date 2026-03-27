<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Sửa Sản phẩm'; ?></title>
    <script src="https://kit.fontawesome.com/54f0cb7e4a.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="/public/css/admin-dashboard.css">
    <style>
        .form-container {
            max-width: 1000px;
            margin: 20px auto;
            padding: 0 20px;
        }
        
        .form-header {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 30px;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .form-header h2 {
            color: #333;
            margin-bottom: 10px;
        }
        
        .form-wrapper {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 40px;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 25px;
        }
        
        .form-row.full-width {
            grid-template-columns: 1fr;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }
        
        .form-group .required {
            color: #dc3545;
        }
        
        .form-control {
            width: 100%;
            padding: 15px;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            font-size: 16px;
            transition: all 0.3s ease;
            box-sizing: border-box;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .form-control textarea {
            min-height: 120px;
            resize: vertical;
        }
        
        .file-input-wrapper {
            position: relative;
            display: block;
            width: 100%;
        }
        
        .file-input {
            width: 100%;
            padding: 15px;
            border: 2px dashed #e9ecef;
            border-radius: 10px;
            text-align: center;
            background: #f8f9fa;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            min-height: 120px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        
        .file-input:hover {
            border-color: #667eea;
            background: #f0f8ff;
        }
        
        .file-input input[type="file"] {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
            z-index: 2;
        }
        
        .file-input-content {
            pointer-events: none;
            z-index: 1;
        }
        
        .preview-container {
            margin-top: 15px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        .preview-image {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid #dee2e6;
        }
        
        .current-image-container {
            margin-bottom: 15px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
            border: 1px solid #dee2e6;
        }
        
        .current-image-container h4 {
            margin: 0 0 10px 0;
            color: #495057;
            font-size: 14px;
            font-weight: 600;
        }
        
        .current-image {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid #fff;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .btn {
            padding: 15px 30px;
            border: none;
            border-radius: 25px;
            font-size: 16px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
            cursor: pointer;
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
        
        .btn-secondary:hover {
            background: #5a6268;
            text-decoration: none;
            color: white;
            transform: translateY(-2px);
        }
        
        .form-actions {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 40px;
            padding-top: 30px;
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
        
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .back-link {
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
        
        .back-link:hover {
            background: #5a6268;
            color: white;
            text-decoration: none;
            transform: translateY(-2px);
        }
        
        .form-help {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 25px;
        }
        
        .form-help i {
            margin-right: 8px;
        }
        
        .checkbox-wrapper {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 10px;
        }
        
        .checkbox-wrapper input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }
        
        .checkbox-wrapper label {
            margin: 0;
            cursor: pointer;
            font-weight: 500;
        }
        
        .price-group {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        
        .category-chain {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #dee2e6;
            margin-top: 10px;
        }
        
        .category-chain .chain-item {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-right: 15px;
            color: #6c757d;
            font-size: 14px;
        }
        
        .product-info-box {
            background: #e8f4fd;
            border: 1px solid #b3d4fc;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 25px;
        }
        
        .product-info-box h3 {
            margin: 0 0 15px 0;
            color: #004085;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .product-info-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #cce7f0;
        }
        
        .product-info-item:last-child {
            border-bottom: none;
        }
        
        .product-info-label {
            font-weight: 600;
            color: #495057;
        }
        
        .product-info-value {
            color: #6c757d;
        }
        
        .multiple-images-wrapper {
            margin-top: 10px;
        }
        
        .multiple-preview-container {
            margin-top: 20px;
        }
        
        .preview-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }
        
        .preview-item {
            position: relative;
            border: 2px solid #dee2e6;
            border-radius: 8px;
            overflow: hidden;
            background: #f8f9fa;
        }
        
        .preview-item img {
            width: 100%;
            height: 120px;
            object-fit: cover;
            display: block;
        }
        
        .preview-item .remove-btn {
            position: absolute;
            top: 5px;
            right: 5px;
            background: #dc3545;
            color: white;
            border: none;
            border-radius: 50%;
            width: 25px;
            height: 25px;
            font-size: 12px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }
        
        .preview-item .remove-btn:hover {
            background: #c82333;
            transform: scale(1.1);
        }
        
        .preview-item .image-name {
            padding: 8px;
            font-size: 12px;
            color: #666;
            text-align: center;
            word-break: break-all;
            background: #fff;
        }
        
        .existing-item {
            border-color: #28a745;
        }
        
        .existing-item .remove-btn {
            background: #ffc107;
            color: #212529;
        }
        
        .existing-item .remove-btn:hover {
            background: #e0a800;
        }
        
        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .price-group {
                grid-template-columns: 1fr;
            }
            
            .preview-grid {
                grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
                gap: 10px;
            }
            
            .preview-item img {
                height: 100px;
            }
        }
    </style>
</head>
<body>
    <!-- Admin Header -->
    <div class="admin-header">
        <div class="container">
            <h1><i class="fas fa-edit"></i> Sửa Sản phẩm</h1>
            <div class="admin-user-info">
                <span>Xin chào, <strong><?php echo htmlspecialchars($admin_name ?? 'Admin'); ?></strong></span>
                <a href="index.php?page=admin&action=logout" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i> Đăng xuất
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="form-container">
        <a href="index.php?page=admin_product&section=products" class="back-link">
            <i class="fas fa-arrow-left"></i> Quay lại danh sách
        </a>
        
        <div class="form-header">
            <h2>Sửa Thông tin Sản phẩm</h2>
            <p>Chỉnh sửa thông tin sản phẩm hiện có</p>
        </div>

        <!-- Hiển thị thông báo -->
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <?php 
                echo $_SESSION['error']; 
                unset($_SESSION['error']);
                ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <?php 
                echo $_SESSION['success']; 
                unset($_SESSION['success']);
                ?>
            </div>
        <?php endif; ?>

        <?php if ($product): ?>
        <!-- Thông tin sản phẩm hiện tại -->
        <div class="product-info-box">
            <h3>
                <i class="fas fa-info-circle"></i>
                Thông tin hiện tại
            </h3>
            <div class="product-info-item">
                <span class="product-info-label">ID sản phẩm:</span>
                <span class="product-info-value">#<?php echo $product['sanpham_id']; ?></span>
            </div>
            <div class="product-info-item">
                <span class="product-info-label">Mã sản phẩm:</span>
                <span class="product-info-value"><?php echo htmlspecialchars($product['sanpham_ma']); ?></span>
            </div>
            <div class="product-info-item">
                <span class="product-info-label">Ngày tạo:</span>
                <span class="product-info-value"><?php echo date('d/m/Y H:i', strtotime($product['sanpham_ngaytao'] ?? 'now')); ?></span>
            </div>
            <div class="product-info-item">
                <span class="product-info-label">Trạng thái HOT:</span>
                <span class="product-info-value">
                    <?php if ($product['sanpham_hot']): ?>
                        <i class="fas fa-fire" style="color: #dc3545;"></i> Có
                    <?php else: ?>
                        <i class="fas fa-circle" style="color: #6c757d;"></i> Không
                    <?php endif; ?>
                </span>
            </div>
        </div>

        <div class="form-wrapper">
            <div class="form-help">
                <i class="fas fa-edit"></i>
                <strong>Chỉnh sửa:</strong> Thay đổi thông tin sản phẩm theo nhu cầu. 
                Các trường có dấu (*) là bắt buộc. Nếu không thay đổi ảnh, hãy để trống trường upload ảnh.
            </div>
            
            <form method="POST" action="" enctype="multipart/form-data" id="productForm">
                <input type="hidden" name="sanpham_id" value="<?php echo $product['sanpham_id']; ?>">
                
                <!-- Thông tin cơ bản -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="sanpham_tieude">
                            Tên sản phẩm <span class="required">*</span>
                        </label>
                        <input type="text" 
                               id="sanpham_tieude" 
                               name="sanpham_tieude" 
                               class="form-control" 
                               placeholder="Nhập tên sản phẩm..."
                               value="<?php echo htmlspecialchars($product['sanpham_tieude']); ?>"
                               required>
                    </div>
                    
                    <div class="form-group">
                        <label for="sanpham_ma">
                            Mã sản phẩm <span class="required">*</span>
                        </label>
                        <input type="text" 
                               id="sanpham_ma" 
                               name="sanpham_ma" 
                               class="form-control" 
                               placeholder="Nhập mã sản phẩm (VD: SP001)..."
                               value="<?php echo htmlspecialchars($product['sanpham_ma']); ?>"
                               required>
                    </div>
                </div>

                <!-- Danh mục và loại sản phẩm -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="danhmuc_id">
                            Danh mục <span class="required">*</span>
                        </label>
                        <select id="danhmuc_id" 
                                name="danhmuc_id" 
                                class="form-control" 
                                required>
                            <option value="">-- Chọn danh mục --</option>
                            <?php if ($categories && $categories->num_rows > 0): ?>
                                <?php while ($category = $categories->fetch_assoc()): ?>
                                    <option value="<?php echo $category['danhmuc_id']; ?>"
                                            <?php echo ($product['danhmuc_id'] == $category['danhmuc_id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($category['danhmuc_ten']); ?>
                                    </option>
                                <?php endwhile; ?>
                            <?php endif; ?>
                        </select>
                        <div class="category-chain" id="categoryChain">
                            <div class="chain-item">
                                <i class="fas fa-folder"></i>
                                <span id="selectedCategory"><?php echo htmlspecialchars($product['danhmuc_ten'] ?? 'Chưa chọn danh mục'); ?></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="loaisanpham_id">
                            Loại sản phẩm <span class="required">*</span>
                        </label>
                        <select id="loaisanpham_id" 
                                name="loaisanpham_id" 
                                class="form-control" 
                                required>
                            <option value="">-- Chọn loại sản phẩm --</option>
                            <?php if ($productTypes && $productTypes->num_rows > 0): ?>
                                <?php while ($type = $productTypes->fetch_assoc()): ?>
                                    <option value="<?php echo $type['loaisanpham_id']; ?>"
                                            <?php echo ($product['loaisanpham_id'] == $type['loaisanpham_id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($type['loaisanpham_ten']); ?>
                                    </option>
                                <?php endwhile; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>

                <!-- Màu sắc và giá -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="color_id">
                            Màu sắc <span class="required">*</span>
                        </label>
                        <select id="color_id" 
                                name="color_id" 
                                class="form-control" 
                                required>
                            <option value="">-- Chọn màu sắc --</option>
                            <?php if ($colors && $colors->num_rows > 0): ?>
                                <?php while ($color = $colors->fetch_assoc()): ?>
                                    <option value="<?php echo $color['color_id']; ?>"
                                            <?php echo ($product['color_id'] == $color['color_id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($color['color_ten']); ?>
                                    </option>
                                <?php endwhile; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Giá sản phẩm <span class="required">*</span></label>
                        <div class="price-group">
                            <div>
                                <label for="sanpham_gia" style="font-size: 14px; color: #666;">Giá bán (₫)</label>
                                <input type="number" 
                                       id="sanpham_gia" 
                                       name="sanpham_gia" 
                                       class="form-control" 
                                       placeholder="0"
                                       min="0"
                                       value="<?php echo $product['sanpham_gia']; ?>"
                                       required>
                            </div>
                            <div>
                                <label for="sanpham_giakhuyenmai" style="font-size: 14px; color: #666;">Giá gốc (₫)</label>
                                <input type="number" 
                                       id="sanpham_giakhuyenmai" 
                                       name="sanpham_giakhuyenmai" 
                                       class="form-control" 
                                       placeholder="0"
                                       min="0"
                                       value="<?php echo $product['sanpham_giakhuyenmai']; ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chọn size sản phẩm -->
                <div class="form-row full-width">
                    <div class="form-group">
                        <label for="">Chọn Size sản phẩm <span class="required">*</span></label>
                        <div class="sanpham-size" style="display: flex; gap: 20px; align-items: center; margin-top: 10px; flex-wrap: wrap;">
                            <?php 
                            $availableSizes = ['S', 'M', 'L', 'XL', 'XXL'];
                            foreach ($availableSizes as $size): 
                                $checked = in_array($size, $currentSizes) ? 'checked' : '';
                            ?>
                            <div style="display: flex; align-items: center; gap: 5px;">
                                <input type="checkbox" id="size_<?php echo strtolower($size); ?>" name="sanpham-size[]" value="<?php echo $size; ?>" <?php echo $checked; ?> style="width: 18px; height: 18px;">
                                <label for="size_<?php echo strtolower($size); ?>" style="margin: 0; font-weight: 500; cursor: pointer;"><?php echo $size; ?></label>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Mô tả sản phẩm -->
                <div class="form-row full-width">
                    <div class="form-group">
                        <label for="sanpham_chitiet">
                            Chi tiết sản phẩm <span class="required">*</span>
                        </label>
                        <textarea id="sanpham_chitiet" 
                                  name="sanpham_chitiet" 
                                  class="form-control" 
                                  placeholder="Nhập chi tiết mô tả sản phẩm..."
                                  rows="5"
                                  required><?php echo htmlspecialchars($product['sanpham_chitiet']); ?></textarea>
                    </div>
                </div>

                <div class="form-row full-width">
                    <div class="form-group">
                        <label for="sanpham_baoquan">
                            Hướng dẫn bảo quản <span class="required">*</span>
                        </label>
                        <textarea id="sanpham_baoquan" 
                                  name="sanpham_baoquan" 
                                  class="form-control" 
                                  placeholder="Nhập hướng dẫn bảo quản sản phẩm..."
                                  rows="4"
                                  required><?php echo htmlspecialchars($product['sanpham_baoquan']); ?></textarea>
                    </div>
                </div>

                <!-- Upload ảnh -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="sanpham_anh">
                            Ảnh chính sản phẩm
                        </label>
                        
                        <?php if (!empty($product['sanpham_anh'])): ?>
                        <div class="current-image-container">
                            <h4>Ảnh hiện tại:</h4>
                            <img src="/public/uploads/<?php echo htmlspecialchars($product['sanpham_anh']); ?>" 
                                 alt="Ảnh sản phẩm hiện tại" 
                                 class="current-image">
                        </div>
                        <?php endif; ?>
                        
                        <div class="file-input-wrapper">
                            <div class="file-input" onclick="document.getElementById('sanpham_anh').click();">
                                <div class="file-input-content">
                                    <i class="fas fa-cloud-upload-alt" style="font-size: 2rem; color: #667eea; margin-bottom: 10px; display: block;"></i>
                                    <p style="margin: 10px 0;">Thay đổi ảnh chính <strong>(tùy chọn)</strong></p>
                                    <small style="color: #666;">Hỗ trợ: JPG, PNG, GIF (tối đa 5MB)</small>
                                </div>
                                <input type="file" 
                                       id="sanpham_anh" 
                                       name="sanpham_anh" 
                                       accept="image/*">
                            </div>
                            <div class="preview-container" id="mainPreview"></div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="sanpham_anhkhac">
                            Ảnh phụ sản phẩm
                        </label>
                        
                        <?php if (!empty($product['sanpham_anhkhac'])): ?>
                        <div class="current-image-container">
                            <h4>Ảnh phụ hiện tại:</h4>
                            <img src="/public/uploads/<?php echo htmlspecialchars($product['sanpham_anhkhac']); ?>" 
                                 alt="Ảnh phụ hiện tại" 
                                 class="current-image">
                        </div>
                        <?php endif; ?>
                        
                        <div class="file-input-wrapper">
                            <div class="file-input" onclick="document.getElementById('sanpham_anhkhac').click();">
                                <div class="file-input-content">
                                    <i class="fas fa-images" style="font-size: 2rem; color: #28a745; margin-bottom: 10px; display: block;"></i>
                                    <p style="margin: 10px 0;">Thay đổi ảnh phụ <strong>(tùy chọn)</strong></p>
                                    <small style="color: #666;">Hỗ trợ: JPG, PNG, GIF (tối đa 5MB)</small>
                                </div>
                                <input type="file" 
                                       id="sanpham_anhkhac" 
                                       name="sanpham_anhkhac" 
                                       accept="image/*">
                            </div>
                            <div class="preview-container" id="subPreview"></div>
                        </div>
                    </div>
                </div>

                <!-- Upload nhiều ảnh sản phẩm -->
                <div class="form-row full-width">
                    <div class="form-group">
                        <label for="multiple_images">
                            <i class="fas fa-images"></i> Thêm nhiều ảnh sản phẩm mới (tùy chọn)
                        </label>
                        <div class="multiple-images-wrapper">
                            <!-- Hiển thị ảnh hiện có -->
                            <?php if ($existingImages && mysqli_num_rows($existingImages) > 0): ?>
                            <div class="existing-images">
                                <h6 style="margin-bottom: 15px; color: #666;">
                                    <i class="fas fa-folder-open"></i> Ảnh đã có:
                                </h6>
                                <div class="preview-grid">
                                    <?php while ($img = mysqli_fetch_assoc($existingImages)): ?>
                                    <div class="preview-item existing-item">
                                        <img src="/public/uploads/<?php echo $img['sanpham_anh']; ?>" alt="Existing image">
                                        <button type="button" class="remove-btn" onclick="removeExistingImage(<?php echo $img['sanpham_anh_id']; ?>, this)">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        <div class="image-name"><?php echo $img['sanpham_anh']; ?></div>
                                    </div>
                                    <?php endwhile; ?>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <div class="file-input-wrapper" style="margin-top: 20px;">
                                <div class="file-input" onclick="document.getElementById('multiple_images').click();">
                                    <div class="file-input-content">
                                        <i class="fas fa-plus-circle" style="font-size: 2rem; color: #17a2b8; margin-bottom: 10px; display: block;"></i>
                                        <p style="margin: 10px 0;"><strong>Chọn nhiều ảnh mới để thêm</strong></p>
                                        <small style="color: #666;">Có thể chọn tối đa 10 ảnh (mỗi ảnh tối đa 5MB)</small>
                                    </div>
                                    <input type="file" 
                                           id="multiple_images" 
                                           name="multiple_images[]" 
                                           accept="image/*"
                                           multiple>
                                </div>
                            </div>
                            <div class="multiple-preview-container" id="multiplePreview">
                                <div class="preview-grid" id="previewGrid"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tùy chọn -->
                <div class="form-row full-width">
                    <div class="form-group">
                        <div class="checkbox-wrapper">
                            <input type="checkbox" 
                                   id="sanpham_hot" 
                                   name="sanpham_hot" 
                                   value="1"
                                   <?php echo ($product['sanpham_hot']) ? 'checked' : ''; ?>>
                            <label for="sanpham_hot">
                                <i class="fas fa-fire" style="color: #dc3545;"></i>
                                Đánh dấu là sản phẩm HOT (hiển thị trên trang chủ)
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Cập nhật Sản phẩm
                    </button>
                    <a href="index.php?page=admin_product&section=products" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Hủy bỏ
                    </a>
                </div>
            </form>
        </div>
        
        <?php else: ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-triangle"></i>
                Không tìm thấy sản phẩm cần sửa!
            </div>
        <?php endif; ?>
    </div>

    <script>
        // Product types data - dynamic from database
        const productTypesData = <?php echo $categoryProductTypeMap; ?>;
        
        console.log('Product types data loaded:', productTypesData);
        
        // Auto focus vào input đầu tiên
        document.getElementById('sanpham_tieude').focus();
        
        // Tự động load product types cho category hiện tại khi trang tải
        document.addEventListener('DOMContentLoaded', function() {
            const danhmucSelect = document.getElementById('danhmuc_id');
            if (danhmucSelect.value) {
                // Trigger change event để load product types
                danhmucSelect.dispatchEvent(new Event('change'));
            }
        });
        
        // Load loại sản phẩm khi chọn danh mục
        document.getElementById('danhmuc_id').addEventListener('change', function() {
            const danhmucId = this.value;
            const loaisanphamSelect = document.getElementById('loaisanpham_id');
            const categoryChain = document.getElementById('categoryChain');
            const selectedCategory = document.getElementById('selectedCategory');
            
            // Reset loại sản phẩm
            loaisanphamSelect.innerHTML = '<option value="">-- Chọn loại sản phẩm --</option>';
            
            if (danhmucId && productTypesData[danhmucId]) {
                // Hiển thị danh mục đã chọn
                categoryChain.style.display = 'block';
                selectedCategory.textContent = this.options[this.selectedIndex].text;
                
                console.log('Loading product types for category:', danhmucId);
                
                // Load loại sản phẩm từ data có sẵn
                const productTypes = productTypesData[danhmucId];
                
                if (productTypes && productTypes.length > 0) {
                    productTypes.forEach(item => {
                        const option = document.createElement('option');
                        option.value = item.id;
                        option.textContent = item.name;
                        
                        // Giữ lại lựa chọn hiện tại
                        if (item.id == '<?php echo $product['loaisanpham_id'] ?? ''; ?>') {
                            option.selected = true;
                        }
                        
                        loaisanphamSelect.appendChild(option);
                    });
                    console.log('Added', productTypes.length, 'product types to select');
                } else {
                    console.log('No product types found for category:', danhmucId);
                    const option = document.createElement('option');
                    option.value = '';
                    option.textContent = '-- Không có loại sản phẩm nào --';
                    option.disabled = true;
                    loaisanphamSelect.appendChild(option);
                }
            } else {
                categoryChain.style.display = 'none';
                console.log('No category selected or no data for category:', danhmucId);
            }
        });
        
        // Preview ảnh khi upload và drag & drop
        function setupImagePreview(inputId, previewId) {
            const input = document.getElementById(inputId);
            const preview = document.getElementById(previewId);
            const fileInputDiv = input.parentElement;
            
            // Handle file input change
            input.addEventListener('change', function(e) {
                handleFileSelect(e.target.files[0], preview, input);
            });
            
            // Handle drag and drop
            fileInputDiv.addEventListener('dragover', function(e) {
                e.preventDefault();
                e.stopPropagation();
                this.style.borderColor = '#667eea';
                this.style.backgroundColor = '#f0f8ff';
            });
            
            fileInputDiv.addEventListener('dragleave', function(e) {
                e.preventDefault();
                e.stopPropagation();
                this.style.borderColor = '#e9ecef';
                this.style.backgroundColor = '#f8f9fa';
            });
            
            fileInputDiv.addEventListener('drop', function(e) {
                e.preventDefault();
                e.stopPropagation();
                this.style.borderColor = '#e9ecef';
                this.style.backgroundColor = '#f8f9fa';
                
                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    input.files = files;
                    handleFileSelect(files[0], preview, input);
                }
            });
        }
        
        function handleFileSelect(file, preview, input) {
            preview.innerHTML = '';
            
            if (!file) return;
            
            // Validate file type
            if (!file.type.startsWith('image/')) {
                alert('Vui lòng chọn file ảnh!');
                input.value = '';
                return;
            }
            
            // Validate file size
            if (file.size > 5 * 1024 * 1024) {
                alert('File ảnh quá lớn! Vui lòng chọn file nhỏ hơn 5MB.');
                input.value = '';
                return;
            }
            
            // Show preview
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'preview-image';
                img.title = file.name;
                preview.appendChild(img);
            };
            reader.readAsDataURL(file);
        }
        
        setupImagePreview('sanpham_anh', 'mainPreview');
        setupImagePreview('sanpham_anhkhac', 'subPreview');
        setupMultipleImagePreview('multiple_images', 'previewGrid');
        
        // Xử lý multiple image preview
        function setupMultipleImagePreview(inputId, previewId) {
            const input = document.getElementById(inputId);
            const previewGrid = document.getElementById(previewId);
            let selectedFiles = [];
            
            input.addEventListener('change', function(e) {
                const files = Array.from(e.target.files);
                
                // Validate số lượng file
                if (files.length > 10) {
                    alert('Chỉ được chọn tối đa 10 ảnh!');
                    input.value = '';
                    return;
                }
                
                // Validate từng file
                const validFiles = [];
                for (let file of files) {
                    if (!file.type.startsWith('image/')) {
                        alert(`File "${file.name}" không phải là ảnh!`);
                        continue;
                    }
                    
                    if (file.size > 5 * 1024 * 1024) {
                        alert(`File "${file.name}" quá lớn! Vui lòng chọn file nhỏ hơn 5MB.`);
                        continue;
                    }
                    
                    validFiles.push(file);
                }
                
                selectedFiles = validFiles;
                displayMultiplePreview(selectedFiles, previewGrid, input);
            });
        }
        
        function displayMultiplePreview(files, previewGrid, input) {
            previewGrid.innerHTML = '';
            
            files.forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const previewItem = document.createElement('div');
                    previewItem.className = 'preview-item';
                    
                    previewItem.innerHTML = `
                        <img src="${e.target.result}" alt="Preview ${index + 1}">
                        <button type="button" class="remove-btn" onclick="removeMultipleImage(${index}, '${input.id}', '${previewGrid.id}')">
                            <i class="fas fa-times"></i>
                        </button>
                        <div class="image-name">${file.name}</div>
                    `;
                    
                    previewGrid.appendChild(previewItem);
                };
                reader.readAsDataURL(file);
            });
        }
        
        function removeMultipleImage(index, inputId, previewGridId) {
            const input = document.getElementById(inputId);
            const previewGrid = document.getElementById(previewGridId);
            
            // Tạo DataTransfer object để cập nhật files
            const dt = new DataTransfer();
            const files = Array.from(input.files);
            
            files.forEach((file, i) => {
                if (i !== index) {
                    dt.items.add(file);
                }
            });
            
            input.files = dt.files;
            
            // Refresh preview
            const remainingFiles = Array.from(dt.files);
            displayMultiplePreview(remainingFiles, previewGrid, input);
        }
        
        function removeExistingImage(imageId, buttonElement) {
            if (confirm('Bạn có chắc muốn xóa ảnh này không?')) {
                fetch('index.php?page=admin_product&section=ajax&action=delete_product_image', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'image_id=' + imageId
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        buttonElement.closest('.preview-item').remove();
                        alert('Đã xóa ảnh thành công!');
                    } else {
                        alert('Có lỗi xảy ra khi xóa ảnh: ' + (data.message || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Có lỗi xảy ra khi xóa ảnh!');
                });
            }
        }
        
        // Validation form
        document.getElementById('productForm').addEventListener('submit', function(e) {
            const sanphamTieude = document.getElementById('sanpham_tieude').value.trim();
            const sanphamMa = document.getElementById('sanpham_ma').value.trim();
            const danhmucId = document.getElementById('danhmuc_id').value;
            const loaisanphamId = document.getElementById('loaisanpham_id').value;
            const colorId = document.getElementById('color_id').value;
            const sanphamGia = document.getElementById('sanpham_gia').value;
            const sanphamChitiet = document.getElementById('sanpham_chitiet').value.trim();
            const sanphamBaoquan = document.getElementById('sanpham_baoquan').value.trim();
            const sanphamSizes = document.querySelectorAll('input[name="sanpham-size[]"]:checked');
            
            if (!sanphamTieude || !sanphamMa || !danhmucId || !loaisanphamId || 
                !colorId || !sanphamGia || !sanphamChitiet || !sanphamBaoquan) {
                e.preventDefault();
                alert('Vui lòng điền đầy đủ thông tin bắt buộc!');
                return false;
            }
            
            if (sanphamSizes.length === 0) {
                e.preventDefault();
                alert('Vui lòng chọn ít nhất một size sản phẩm!');
                return false;
            }
            
            if (sanphamTieude.length < 5) {
                e.preventDefault();
                alert('Tên sản phẩm phải có ít nhất 5 ký tự!');
                document.getElementById('sanpham_tieude').focus();
                return false;
            }
            
            if (sanphamMa.length < 3) {
                e.preventDefault();
                alert('Mã sản phẩm phải có ít nhất 3 ký tự!');
                document.getElementById('sanpham_ma').focus();
                return false;
            }
            
            if (parseInt(sanphamGia) <= 0) {
                e.preventDefault();
                alert('Giá sản phẩm phải lớn hơn 0!');
                document.getElementById('sanpham_gia').focus();
                return false;
            }
        });
    </script>
</body>
</html>