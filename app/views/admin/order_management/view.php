<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Chi tiết Đơn hàng'; ?></title>
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
        
        .order-info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .info-card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 20px;
            font-weight: 600;
        }
        
        .card-body {
            padding: 20px;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        
        .info-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }
        
        .info-label {
            font-weight: 600;
            color: #666;
        }
        
        .info-value {
            color: #333;
            text-align: right;
        }
        
        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .status-pending {
            background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
            color: #212529;
        }
        
        .status-confirmed {
            background: linear-gradient(135deg, #17a2b8 0%, #20c997 100%);
            color: white;
        }
        
        .status-processing {
            background: linear-gradient(135deg, #007bff 0%, #6610f2 100%);
            color: white;
        }
        
        .status-delivered {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
        }
        
        .status-cancelled {
            background: linear-gradient(135deg, #dc3545 0%, #e83e8c 100%);
            color: white;
        }
        
        .payment-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 500;
        }
        
        .payment-pending {
            background: #fff3cd;
            color: #856404;
        }
        
        .payment-processing {
            background: #cce7ff;
            color: #004085;
        }
        
        .payment-completed {
            background: #d4edda;
            color: #155724;
        }
        
        .payment-failed {
            background: #f8d7da;
            color: #721c24;
        }
        
        .actions-section {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 25px;
            margin-bottom: 20px;
            text-align: center;
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
            margin: 0 5px;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        
        .btn-success {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
        }
        
        .btn-warning {
            background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
            color: #212529;
        }
        
        .btn-danger {
            background: linear-gradient(135deg, #dc3545 0%, #e83e8c 100%);
            color: white;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            text-decoration: none;
            color: inherit;
        }
        
        .products-section {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
            margin-bottom: 20px;
        }
        
        .table-responsive {
            overflow-x: auto;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
        }
        
        .table th,
        .table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
            vertical-align: middle;
        }
        
        .table th {
            background: #f8f9fa;
            font-weight: 600;
            color: #333;
            font-size: 0.9rem;
        }
        
        .product-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .product-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid #eee;
        }
        
        .product-details h6 {
            margin: 0 0 5px 0;
            font-weight: 600;
            color: #333;
        }
        
        .product-variant {
            font-size: 0.85rem;
            color: #666;
        }
        
        .color-swatch {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            border: 2px solid #ddd;
            display: inline-block;
            margin-right: 5px;
        }
        
        .size-badge {
            background: #f8f9fa;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 0.75rem;
            color: #666;
        }
        
        .history-section {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .timeline {
            padding: 20px;
        }
        
        .timeline-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 20px;
            position: relative;
        }
        
        .timeline-item:last-child {
            margin-bottom: 0;
        }
        
        .timeline-item:not(:last-child)::after {
            content: '';
            position: absolute;
            left: 15px;
            top: 35px;
            width: 2px;
            height: calc(100% + 10px);
            background: #eee;
        }
        
        .timeline-icon {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 0.8rem;
            flex-shrink: 0;
        }
        
        .timeline-icon.success {
            background: #28a745;
            color: white;
        }
        
        .timeline-icon.warning {
            background: #ffc107;
            color: #212529;
        }
        
        .timeline-icon.info {
            background: #17a2b8;
            color: white;
        }
        
        .timeline-content {
            flex-grow: 1;
        }
        
        .timeline-content h6 {
            margin: 0 0 5px 0;
            font-weight: 600;
            color: #333;
        }
        
        .timeline-content p {
            margin: 0;
            color: #666;
            font-size: 0.9rem;
        }
        
        .timeline-time {
            font-size: 0.8rem;
            color: #999;
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
        
        .alert-warning {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }
        
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }
        
        .modal-content {
            background-color: #fff;
            margin: 15% auto;
            padding: 20px;
            border-radius: 10px;
            width: 90%;
            max-width: 500px;
        }
        
        .close {
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
        }
        
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 0.9rem;
        }
        
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
    </style>
</head>
<body>
    <!-- Admin Header -->
    <div class="admin-header">
        <div class="container">
            <h1><i class="fas fa-shopping-cart"></i> Chi tiết Đơn hàng</h1>
            <div class="admin-user-info">
                <span class="admin-name">
                    <i class="fas fa-user"></i> <?php echo htmlspecialchars($admin_name ?? 'Admin'); ?>
                </span>
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
            <h2>Chi tiết Đơn hàng <?php echo htmlspecialchars($order['order_code'] ?? ''); ?></h2>
            <p>Xem thông tin chi tiết và quản lý trạng thái đơn hàng</p>
            
            <div style="margin-top: 20px;">
                <a href="index.php?page=admin_order&action=order_management" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Quay lại danh sách
                </a>
            </div>
        </div>

        <!-- Hiển thị thông báo -->
        <?php if (isset($_SESSION['flash_success'])): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <?php echo $_SESSION['flash_success']; unset($_SESSION['flash_success']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['flash_error'])): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo $_SESSION['flash_error']; unset($_SESSION['flash_error']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($order)): ?>
        <!-- Thông tin đơn hàng -->
        <div class="order-info-grid">
            <!-- Thông tin khách hàng -->
            <div class="info-card">
                <div class="card-header">
                    <i class="fas fa-user"></i> Thông tin Khách hàng
                </div>
                <div class="card-body">
                    <div class="info-row">
                        <span class="info-label">Họ tên:</span>
                        <span class="info-value"><?php echo htmlspecialchars($order['customer_name']); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Số điện thoại:</span>
                        <span class="info-value"><?php echo htmlspecialchars($order['customer_phone']); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Địa chỉ:</span>
                        <span class="info-value">
                            <?php echo htmlspecialchars($order['customer_diachi']); ?><br>
                            <?php echo htmlspecialchars($order['xa_name'] ?? ''); ?>, 
                            <?php echo htmlspecialchars($order['huyen_name'] ?? ''); ?>, 
                            <?php echo htmlspecialchars($order['tinh_name'] ?? ''); ?>
                        </span>
                    </div>
                    <?php if (!empty($order['customer_note'])): ?>
                    <div class="info-row">
                        <span class="info-label">Ghi chú:</span>
                        <span class="info-value"><?php echo htmlspecialchars($order['customer_note']); ?></span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Thông tin đơn hàng -->
            <div class="info-card">
                <div class="card-header">
                    <i class="fas fa-shopping-bag"></i> Thông tin Đơn hàng
                </div>
                <div class="card-body">
                    <div class="info-row">
                        <span class="info-label">Mã đơn hàng:</span>
                        <span class="info-value"><strong><?php echo htmlspecialchars($order['order_code']); ?></strong></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Ngày đặt:</span>
                        <span class="info-value"><?php echo date('d/m/Y H:i', strtotime($order['order_date'])); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Trạng thái:</span>
                        <span class="info-value">
                            <span class="status-badge status-<?php echo $order['order_status']; ?>">
                                <?php 
                                $statusLabels = [
                                    'pending' => 'Chờ xác nhận',
                                    'confirmed' => 'Đã xác nhận',
                                    'processing' => 'Đang xử lý',
                                    'delivered' => 'Đã giao',
                                    'cancelled' => 'Đã hủy'
                                ];
                                echo $statusLabels[$order['order_status']] ?? $order['order_status'];
                                ?>
                            </span>
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Tổng tiền:</span>
                        <span class="info-value"><strong style="color: #dc3545; font-size: 1.2rem;"><?php echo number_format($order['total_amount']); ?>đ</strong></span>
                    </div>
                </div>
            </div>

            <!-- Thông tin thanh toán -->
            <?php if (isset($payment) && $payment): ?>
            <div class="info-card">
                <div class="card-header">
                    <i class="fas fa-credit-card"></i> Thông tin Thanh toán
                </div>
                <div class="card-body">
                    <div class="info-row">
                        <span class="info-label">Phương thức:</span>
                        <span class="info-value"><?php echo htmlspecialchars($payment['payment_method']); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Trạng thái:</span>
                        <span class="info-value">
                            <span class="payment-badge payment-<?php echo $payment['payment_status']; ?>">
                                <?php 
                                // Hiển thị trạng thái thanh toán phù hợp với từng phương thức
                                if ($payment['payment_method'] === 'Thu tiền tận nơi') {
                                    if ($payment['payment_status'] === 'pending') {
                                        echo 'Chưa thanh toán (COD)';
                                    } else if ($payment['payment_status'] === 'completed') {
                                        echo 'Đã thanh toán (COD)';
                                    } else {
                                        echo 'COD - ' . ($payment['payment_status'] ?? 'N/A');
                                    }
                                } else {
                                    // Online payment (OnePay, MoMo, etc.) - chung trạng thái
                                    $paymentLabels = [
                                        'pending' => 'Chờ thanh toán',
                                        'processing' => 'Đang xử lý',
                                        'completed' => 'Đã thanh toán',
                                        'failed' => 'Thanh toán thất bại'
                                    ];
                                    $statusText = $paymentLabels[$payment['payment_status']] ?? $payment['payment_status'];
                                    
                                    // Hiển thị thêm tên phương thức thanh toán
                                    if (strpos($payment['payment_method'], 'OnePay') !== false) {
                                        echo $statusText . ' (OnePay)';
                                    } else if ($payment['payment_method'] === 'Thanh toán Momo') {
                                        echo $statusText . ' (MoMo)';
                                    } else {
                                        echo $statusText;
                                    }
                                }
                                ?>
                            </span>
                        </span>
                    </div>
                    <?php if (!empty($payment['transaction_id'])): ?>
                    <div class="info-row">
                        <span class="info-label">Mã giao dịch:</span>
                        <span class="info-value"><?php echo htmlspecialchars($payment['transaction_id']); ?></span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Thao tác với đơn hàng -->
        <div class="actions-section">
            <h5 style="margin-bottom: 20px;">Thao tác với đơn hàng</h5>
            
            <?php if ($order['order_status'] === 'pending'): ?>
                <form method="POST" action="index.php?page=admin_order&action=confirm" style="display: inline;">
                    <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                    <button type="submit" class="btn btn-success" onclick="return confirm('Xác nhận đơn hàng này?')">
                        <i class="fas fa-check"></i> Xác nhận đơn hàng
                    </button>
                </form>
                
                <button type="button" class="btn btn-danger" onclick="showCancelModal()">
                    <i class="fas fa-times"></i> Hủy đơn hàng
                </button>
            <?php endif; ?>
            
            <?php if ($order['order_status'] === 'confirmed'): ?>
                <form method="POST" action="index.php?page=admin_order&action=process" style="display: inline;">
                    <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                    <button type="submit" class="btn btn-warning" onclick="return confirm('Chuyển đơn hàng sang trạng thái xử lý?')">
                        <i class="fas fa-cogs"></i> Chuyển sang xử lý
                    </button>
                </form>
                
                <form method="POST" action="index.php?page=admin_order&action=deliver" style="display: inline;">
                    <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                    <button type="submit" class="btn btn-success" onclick="return confirm('Hoàn tất giao hàng?')">
                        <i class="fas fa-truck"></i> Hoàn tất giao hàng
                    </button>
                </form>
                
                <button type="button" class="btn btn-danger" onclick="showCancelModal()">
                    <i class="fas fa-times"></i> Hủy đơn hàng
                </button>
            <?php endif; ?>
            
            <?php if ($order['order_status'] === 'processing'): ?>
                <form method="POST" action="index.php?page=admin_order&action=order_management&subaction=deliver" style="display: inline;">
                    <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                    <button type="submit" class="btn btn-success" onclick="return confirm('Hoàn tất giao hàng?')">
                        <i class="fas fa-truck"></i> Hoàn tất giao hàng
                    </button>
                </form>
                
                <button type="button" class="btn btn-danger" onclick="showCancelModal()">
                    <i class="fas fa-times"></i> Hủy đơn hàng
                </button>
            <?php endif; ?>
            
            <?php if (isset($payment) && $payment && $payment['payment_status'] === 'pending'): ?>
                <button type="button" class="btn btn-primary" onclick="showPaymentModal()">
                    <i class="fas fa-credit-card"></i> Cập nhật thanh toán
                </button>
            <?php endif; ?>
        </div>

        <!-- Danh sách sản phẩm -->
        <div class="products-section">
            <div class="card-header">
                <i class="fas fa-box"></i> Sản phẩm trong đơn hàng
            </div>
            
            <div class="table-responsive">
                <?php if (isset($details) && $details && mysqli_num_rows($details) > 0): ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Sản phẩm</th>
                            <th>Biến thể</th>
                            <th>Đơn giá</th>
                            <th>Số lượng</th>
                            <th>Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($detail = mysqli_fetch_assoc($details)): ?>
                        <tr>
                            <td>
                                <div class="product-info">
                                    <?php if (!empty($detail['sanpham_anh'])): ?>
                                        <img src="/public/uploads/<?php echo htmlspecialchars($detail['sanpham_anh']); ?>" 
                                             class="product-image" 
                                             alt="<?php echo htmlspecialchars($detail['sanpham_tieude']); ?>">
                                    <?php else: ?>
                                        <div class="product-image" style="background: #f8f9fa; display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-image" style="color: #ccc;"></i>
                                        </div>  
                                    <?php endif; ?>
                                    <div class="product-details">
                                        <h6><?php echo htmlspecialchars($detail['sanpham_tieude']); ?></h6>
                                        <div class="product-variant">
                                            SKU: <?php echo htmlspecialchars($detail['bienthe_ma'] ?? 'N/A'); ?>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="product-variant">
                                    <?php if (!empty($detail['color_ten'])): ?>
                                        <div style="margin-bottom: 5px;">
                                            <?php if (!empty($detail['color_anh'])): ?>
                                                <img src="/public/uploads/<?php echo $detail['color_anh']; ?>" class="color-swatch">
                                            <?php else: ?>
                                                <span class="color-swatch" style="background-color: #<?php echo substr(md5($detail['color_ten']), 0, 6); ?>"></span>
                                            <?php endif; ?>
                                            <?php echo htmlspecialchars($detail['color_ten']); ?>
                                        </div>
                                    <?php endif; ?>
                                    <?php if (!empty($detail['sanpham_size'])): ?>
                                        <span class="size-badge">Size: <?php echo htmlspecialchars($detail['sanpham_size']); ?></span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <strong><?php echo number_format($detail['sanpham_gia']); ?>đ</strong>
                            </td>
                            <td>
                                <strong><?php echo number_format($detail['quantity']); ?></strong>
                            </td>
                            <td>
                                <strong style="color: #dc3545;"><?php echo number_format($detail['subtotal']); ?>đ</strong>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <?php else: ?>
                <div style="text-align: center; padding: 30px; color: #666;">
                    <i class="fas fa-box-open" style="font-size: 3rem; margin-bottom: 15px; opacity: 0.3;"></i>
                    <p>Không có sản phẩm nào trong đơn hàng này.</p>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Lịch sử trạng thái -->
        <div class="history-section">
            <div class="card-header">
                <i class="fas fa-history"></i> Lịch sử trạng thái
            </div>
            
            <div class="timeline">
                <?php if (isset($history) && $history && mysqli_num_rows($history) > 0): ?>
                    <?php while ($historyItem = mysqli_fetch_assoc($history)): ?>
                    <div class="timeline-item">
                        <div class="timeline-icon <?php echo ($historyItem['new_status'] === 'delivered') ? 'success' : (($historyItem['new_status'] === 'cancelled') ? 'warning' : 'info'); ?>">
                            <i class="fas fa-<?php echo ($historyItem['new_status'] === 'delivered') ? 'check' : (($historyItem['new_status'] === 'cancelled') ? 'times' : 'clock'); ?>"></i>
                        </div>
                        <div class="timeline-content">
                            <h6>
                                <?php 
                                $statusLabels = [
                                    'pending' => 'Chờ xác nhận',
                                    'confirmed' => 'Đã xác nhận',
                                    'processing' => 'Đang xử lý',
                                    'delivered' => 'Đã giao',
                                    'cancelled' => 'Đã hủy'
                                ];
                                echo $statusLabels[$historyItem['new_status']] ?? $historyItem['new_status'];
                                ?>
                            </h6>
                            <?php if (!empty($historyItem['note'])): ?>
                                <p><?php echo htmlspecialchars($historyItem['note']); ?></p>
                            <?php endif; ?>
                            <div class="timeline-time">
                                <?php echo date('d/m/Y H:i', strtotime($historyItem['created_at'])); ?>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div style="text-align: center; padding: 30px; color: #666;">
                        <i class="fas fa-history" style="font-size: 3rem; margin-bottom: 15px; opacity: 0.3;"></i>
                        <p>Chưa có lịch sử thay đổi trạng thái.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php else: ?>
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i>
            Không tìm thấy thông tin đơn hàng!
        </div>
        <?php endif; ?>
    </div>

    <!-- Modal hủy đơn hàng -->
    <div id="cancelModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeCancelModal()">&times;</span>
            <h3>Hủy đơn hàng</h3>
            <form method="POST" action="index.php?page=admin_order&action=cancel">
                <input type="hidden" name="order_id" value="<?php echo $order['order_id'] ?? ''; ?>">
                <div class="form-group">
                    <label for="cancel_note">Lý do hủy đơn hàng:</label>
                    <textarea id="cancel_note" name="cancel_note" rows="4" placeholder="Nhập lý do hủy đơn hàng..." required></textarea>
                </div>
                <div style="text-align: right;">
                    <button type="button" class="btn btn-secondary" onclick="closeCancelModal()">Hủy</button>
                    <button type="submit" class="btn btn-danger">Xác nhận hủy</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal cập nhật thanh toán -->
    <div id="paymentModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closePaymentModal()">&times;</span>
            <h3>Cập nhật trạng thái thanh toán</h3>
            <form method="POST" action="index.php?page=admin_order&action=order_management&subaction=updatePaymentStatus">
                <input type="hidden" name="order_id" value="<?php echo $order['order_id'] ?? ''; ?>">
                <div class="form-group">
                    <label for="payment_status">Trạng thái thanh toán:</label>
                    <select id="payment_status" name="payment_status" required>
                        <option value="pending">Chờ thanh toán</option>
                        <option value="completed">Đã thanh toán</option>
                        <option value="failed">Thanh toán thất bại</option>
                    </select>
                </div>
                <div style="text-align: right;">
                    <button type="button" class="btn btn-secondary" onclick="closePaymentModal()">Hủy</button>
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function showCancelModal() {
            document.getElementById('cancelModal').style.display = 'block';
        }

        function closeCancelModal() {
            document.getElementById('cancelModal').style.display = 'none';
        }

        function showPaymentModal() {
            document.getElementById('paymentModal').style.display = 'block';
        }

        function closePaymentModal() {
            document.getElementById('paymentModal').style.display = 'none';
        }

        // Đóng modal khi click bên ngoài
        window.onclick = function(event) {
            const cancelModal = document.getElementById('cancelModal');
            const paymentModal = document.getElementById('paymentModal');
            
            if (event.target === cancelModal) {
                cancelModal.style.display = 'none';
            }
            if (event.target === paymentModal) {
                paymentModal.style.display = 'none';
            }
        }
    </script>
</body>
</html>