<?php
// app/views/frontend_views/customer/order-detail.php
?>
<style>
/* Main wrapper để tránh bị che khuất bởi header */
.main-wrapper {
    margin-top: 80px;
    background: #f8f9fa;
    min-height: calc(100vh - 80px);
}

.customer-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 30px 20px;
}

/* Breadcrumb navigation */
.breadcrumb {
    background: white;
    padding: 15px 30px;
    border-radius: 8px;
    margin-bottom: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.breadcrumb a {
    color: #007bff;
    text-decoration: none;
}

.breadcrumb a:hover {
    text-decoration: underline;
}

.order-detail-header {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    color: #333;
    padding: 30px;
    border-radius: 12px;
    margin-bottom: 30px;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1), 0 5px 15px rgba(0, 0, 0, 0.08);
    border-left: 4px solid #ff5722;
    backdrop-filter: blur(10px);
}

.order-detail-header h1 {
    margin: 0 0 15px 0;
    font-size: 2.2em;
    font-weight: bold;
    color: #333;
}

.order-meta {
    display: flex;
    gap: 30px;
    font-size: 1.1em;
    opacity: 0.9;
}

.back-button {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: linear-gradient(135deg, #ff5722, #e64a19);
    color: white;
    padding: 10px 20px;
    border-radius: 8px;
    text-decoration: none;
    transition: all 0.3s ease;
    margin-bottom: 20px;
    box-shadow: 0 4px 12px rgba(255, 87, 34, 0.3);
}

.back-button:hover {
    background: linear-gradient(135deg, #e64a19, #d84315);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(255, 87, 34, 0.4);
}

.order-info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 30px;
}

.info-card {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1), 0 5px 15px rgba(0, 0, 0, 0.08);
    backdrop-filter: blur(10px);
}

.info-card h3 {
    margin: 0 0 20px 0;
    color: #333;
    font-size: 1.3em;
    padding-bottom: 15px;
    border-bottom: 2px solid #e9ecef;
}

.info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 0;
    border-bottom: 1px solid #f8f9fa;
}

.info-item:last-child {
    border-bottom: none;
}

.info-label {
    color: #666;
    font-weight: 500;
}

.info-value {
    color: #333;
    font-weight: 600;
}

.order-status {
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 0.9em;
    font-weight: 500;
    text-transform: uppercase;
}

.status-pending {
    background: #fff3cd;
    color: #856404;
}

.status-confirmed {
    background: #cce5ff;
    color: #0056b3;
}

.status-processing {
    background: #e1ecf4;
    color: #0c5460;
}

.status-shipped {
    background: #d4edda;
    color: #155724;
}

.status-delivered {
    background: #d1ecf1;
    color: #0c5460;
}

.status-cancelled {
    background: #f8d7da;
    color: #721c24;
}

.order-items {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1), 0 5px 15px rgba(0, 0, 0, 0.08);
    margin-bottom: 20px;
    backdrop-filter: blur(10px);
}

.order-items h3 {
    margin: 0 0 25px 0;
    color: #333;
    font-size: 1.5em;
    padding-bottom: 15px;
    border-bottom: 2px solid #e9ecef;
}

.item-card {
    display: flex;
    align-items: center;
    gap: 20px;
    padding: 20px;
    border: 1px solid #e9ecef;
    border-radius: 12px;
    margin-bottom: 15px;
    transition: all 0.3s ease;
}

.item-card:hover {
    border-color: #ff5722;
    box-shadow: 0 8px 25px rgba(255, 87, 34, 0.15);
    transform: translateY(-2px);
}

.item-image {
    width: 80px;
    height: 80px;
    border-radius: 8px;
    object-fit: cover;
    border: 1px solid #e9ecef;
}

.item-details {
    flex: 1;
}

.item-name {
    font-size: 1.1em;
    font-weight: 600;
    color: #333;
    margin: 0 0 8px 0;
}

.item-specs {
    display: flex;
    gap: 20px;
    font-size: 0.9em;
    color: #666;
    margin-bottom: 5px;
}

.item-price {
    font-size: 1.2em;
    font-weight: bold;
    color: #007bff;
}

.item-quantity {
    font-size: 1.1em;
    font-weight: 600;
    color: #333;
    min-width: 100px;
    text-align: center;
}

.order-summary {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1), 0 5px 15px rgba(0, 0, 0, 0.08);
    backdrop-filter: blur(10px);
}

.summary-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 0;
    border-bottom: 1px solid #f8f9fa;
}

.summary-row:last-child {
    border-bottom: none;
    border-top: 2px solid #ff5722;
    margin-top: 15px;
    padding-top: 20px;
    font-size: 1.3em;
    font-weight: bold;
    color: #ff5722;
}

.order-actions {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1), 0 5px 15px rgba(0, 0, 0, 0.08);
    margin-top: 20px;
    text-align: center;
    backdrop-filter: blur(10px);
}

.btn {
    padding: 12px 30px;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    margin: 0 10px;
}

.btn-danger {
    background: #dc3545;
    color: white;
}

.btn-danger:hover {
    background: #c82333;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
}

.btn-primary {
    background: #007bff;
    color: white;
}

.btn-primary:hover {
    background: #0056b3;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
}

@media (max-width: 768px) {
    .customer-container {
        padding: 10px;
    }
    
    .order-info-grid {
        grid-template-columns: 1fr;
    }
    
    .order-meta {
        flex-direction: column;
        gap: 10px;
    }
    
    .item-card {
        flex-direction: column;
        text-align: center;
    }
    
    .item-specs {
        justify-content: center;
    }
    
    .summary-row {
        font-size: 0.9em;
    }
}
</style>

<div class="main-wrapper">
    <div class="customer-container">
        <!-- Breadcrumb -->
        <div class="breadcrumb">
            <a href="index.php"><i class="fas fa-home"></i> Trang chủ</a> / 
            <a href="index.php?page=orders">Đơn hàng của tôi</a> / 
            <span>Chi tiết đơn hàng #<?php echo $order['order_id']; ?></span>
        </div>

        <a href="index.php?page=orders" class="back-button">
            <i class="fas fa-arrow-left"></i> Quay lại danh sách đơn hàng
        </a>

        <!-- Order Detail Header -->
        <div class="order-detail-header">
            <h1><i class="fas fa-receipt"></i> Chi tiết đơn hàng: <?php echo $order['order_code']; ?></h1>
            <div class="order-meta">
                <div><i class="fas fa-calendar"></i> <?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></div>
                <div>
                    <span class="order-status status-<?php echo $order['order_status']; ?>">
                        <?php 
                        $statusText = [
                            'pending' => 'Chờ xác nhận',
                            'confirmed' => 'Đã xác nhận',
                            'processing' => 'Đang xử lý',
                            'shipped' => 'Đang giao',
                            'delivered' => 'Đã giao',
                            'cancelled' => 'Đã hủy'
                        ];
                        echo $statusText[$order['order_status']] ?? $order['order_status'];
                        ?>
                    </span>
                </div>
            </div>
        </div>

    <!-- Order Information Grid -->
    <div class="order-info-grid">
        <!-- Order Info -->
        <div class="info-card">
            <h3><i class="fas fa-info-circle"></i> Thông tin đơn hàng</h3>
            <div class="info-item">
                <span class="info-label">Mã đơn hàng:</span>
                <span class="info-value"><?php echo $order['order_code']; ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">Trạng thái:</span>
                <span class="info-value">
                    <span class="order-status status-<?php echo $order['order_status']; ?>">
                        <?php echo $statusText[$order['order_status']] ?? $order['order_status']; ?>
                    </span>
                </span>
            </div>
            <div class="info-item">
                <span class="info-label">Ngày đặt:</span>
                <span class="info-value"><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">Thanh toán:</span>
                <span class="info-value">
                    <?php 
                    $paymentText = [
                        'unpaid' => 'Chưa thanh toán',
                        'paid' => 'Đã thanh toán',
                        'refunded' => 'Đã hoàn tiền'
                    ];
                    echo $paymentText[$order['payment_status']] ?? $order['payment_status'];
                    ?>
                </span>
            </div>
        </div>

        <!-- Delivery Info -->
        <div class="info-card">
            <h3><i class="fas fa-shipping-fast"></i> Thông tin giao hàng</h3>
            <div class="info-item">
                <span class="info-label">Tên người nhận:</span>
                <span class="info-value"><?php echo htmlspecialchars($order['customer_name']); ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">Số điện thoại:</span>
                <span class="info-value"><?php echo htmlspecialchars($order['customer_phone']); ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">Địa chỉ:</span>
                <span class="info-value"><?php echo htmlspecialchars($order['customer_diachi']); ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">Khu vực:</span>
                <span class="info-value">
                    <?php 
                    $address = [];
                    if ($order['xa_name']) $address[] = $order['xa_name'];
                    if ($order['huyen_name']) $address[] = $order['huyen_name'];
                    if ($order['tinh_name']) $address[] = $order['tinh_name'];
                    echo implode(', ', $address);
                    ?>
                </span>
            </div>
        </div>
    </div>

    <!-- Order Items -->
    <div class="order-items">
        <h3><i class="fas fa-box-open"></i> Sản phẩm đã đặt</h3>
        
        <?php if (!empty($orderDetails)): ?>
            <?php foreach ($orderDetails as $item): ?>
                <div class="item-card">
                    <img src="<?php echo htmlspecialchars($item['sanpham_anh'] ?? '/public/uploads/default-product.png'); ?>" 
                         alt="<?php echo htmlspecialchars($item['sanpham_tieude']); ?>" 
                         class="item-image">
                    
                    <div class="item-details">
                        <div class="item-name"><?php echo htmlspecialchars($item['sanpham_tieude']); ?></div>
                        <div class="item-specs">
                            <?php if (isset($item['color_ten']) && $item['color_ten']): ?>
                                <span><i class="fas fa-palette"></i> Màu: <?php echo htmlspecialchars($item['color_ten']); ?></span>
                            <?php endif; ?>
                            <?php if (isset($item['sanpham_size']) && $item['sanpham_size']): ?>
                                <span><i class="fas fa-expand-arrows-alt"></i> Size: <?php echo htmlspecialchars($item['sanpham_size']); ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="item-price"><?php echo number_format($item['sanpham_gia'], 0, ',', '.'); ?>đ</div>
                    </div>
                    
                    <div class="item-quantity">
                        <strong>SL: <?php echo $item['quantity']; ?></strong>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div style="text-align: center; padding: 40px; color: #666;">
                <i class="fas fa-box" style="font-size: 3em; margin-bottom: 15px;"></i>
                <p>Không có sản phẩm nào trong đơn hàng này.</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Order Summary -->
    <div class="order-summary">
        <h3><i class="fas fa-calculator"></i> Tổng kết đơn hàng</h3>
        
        <div class="summary-row">
            <span>Tạm tính:</span>
            <span><?php echo number_format(($order['total_amount'] ?? 0) - ($order['shipping_fee'] ?? 0), 0, ',', '.'); ?>đ</span>
        </div>
        
        <div class="summary-row">
            <span>Phí vận chuyển:</span>
            <span><?php echo number_format($order['shipping_fee'] ?? 0, 0, ',', '.'); ?>đ</span>
        </div>
        
        <div class="summary-row">
            <span>Tổng cộng:</span>
            <span><?php echo number_format($order['total_amount'] ?? 0, 0, ',', '.'); ?>đ</span>
        </div>
    </div>

    <!-- Order Actions -->
    <div class="order-actions">
        <h3><i class="fas fa-cogs"></i> Thao tác</h3>
        
        <?php if (in_array($order['order_status'], ['pending', 'confirmed'])): ?>
            <button onclick="cancelOrder(<?php echo $order['order_id']; ?>)" class="btn btn-danger">
                <i class="fas fa-times"></i> Hủy đơn hàng
            </button>
        <?php endif; ?>
        
        <a href="index.php?page=orders" class="btn btn-primary">
            <i class="fas fa-list"></i> Danh sách đơn hàng
        </a>
    </div>
    </div>
</div>

<script>
function cancelOrder(orderId) {
    const reason = prompt('Vui lòng cho biết lý do hủy đơn hàng:');
    
    if (!reason || reason.trim() === '') {
        return;
    }
    
    if (!confirm('Bạn có chắc chắn muốn hủy đơn hàng này?')) {
        return;
    }
    
    const formData = new FormData();
    formData.append('order_id', orderId);
    formData.append('cancel_reason', reason.trim());
    
    fetch('index.php?page=customer&action=cancelOrder', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload(); // Reload to update order status
        } else {
            alert('Lỗi: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra khi hủy đơn hàng');
    });
}
</script>