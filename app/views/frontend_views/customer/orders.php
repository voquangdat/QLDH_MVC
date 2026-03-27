<?php
// app/views/frontend_views/customer/orders.php
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

/* Breadcrumb navigation - đồng bộ với website */
.breadcrumb {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    padding: 15px 30px;
    border-radius: 12px;
    margin-bottom: 20px;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1), 0 5px 15px rgba(0, 0, 0, 0.08);
    font-family: Arial, Helvetica, sans-serif;
    font-size: 14px;
    font-weight: 500;
}

.breadcrumb a {
    color: #ff5722;
    text-decoration: none;
    transition: all 0.3s ease;
}

.breadcrumb a:hover {
    color: #ff7043;
    text-decoration: none;
}

.orders-header {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    color: #333;
    padding: 30px;
    border-radius: 12px;
    text-align: center;
    margin-bottom: 30px;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1), 0 5px 15px rgba(0, 0, 0, 0.08);
    border-left: 4px solid #ff5722;
    backdrop-filter: blur(10px);
}

.orders-header h1 {
    margin: 0 0 15px 0;
    font-size: 2.2em;
    font-weight: bold;
    color: #333;
    font-family: Arial, Helvetica, sans-serif;
}

.orders-stats {
    display: flex;
    justify-content: center;
    gap: 30px;
    margin-top: 20px;
}

.stat-item {
    text-align: center;
    transition: all 0.3s ease;
}

.stat-item:hover {
    transform: translateY(-2px);
}

.stat-item .number {
    font-size: 2em;
    font-weight: bold;
    display: block;
    color: #ff5722;
}

.stat-item .label {
    font-size: 0.9em;
    opacity: 0.8;
    color: #333;
    font-weight: 500;
}

.orders-container {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1), 0 5px 15px rgba(0, 0, 0, 0.08);
    backdrop-filter: blur(10px);
}

.order-card {
    border: 2px solid #e9ecef;
    border-radius: 12px;
    margin-bottom: 20px;
    transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    overflow: hidden;
    background: #ffffff;
}

.order-card:hover {
    border-color: #ff5722;
    box-shadow: 0 15px 35px rgba(255, 87, 34, 0.1), 0 5px 15px rgba(255, 87, 34, 0.08);
    transform: translateY(-2px);
}

.order-header {
    background: linear-gradient(135deg, #ff5722, #e64a19);
    color: white;
    padding: 20px;
    border-radius: 12px 12px 0 0;
    border-bottom: none;
    box-shadow: 0 5px 15px rgba(255, 87, 34, 0.3);
}

.order-header-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
}

.order-id {
    font-size: 1.2em;
    font-weight: bold;
    color: white;
    text-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
}

.order-status {
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 0.9em;
    font-weight: 500;
    text-transform: uppercase;
    backdrop-filter: blur(5px);
    border: 1px solid rgba(255,255,255,0.1);
    background: rgba(255,255,255,0.2);
    color: white;
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

.order-info {
    display: flex;
    gap: 30px;
    font-size: 0.9em;
    color: rgba(255,255,255,0.9);
    margin-top: 10px;
}

.order-body {
    padding: 20px;
}

.order-summary {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.order-total {
    font-size: 1.3em;
    font-weight: bold;
    color: #ff5722;
}

.order-actions {
    display: flex;
    gap: 10px;
}

.btn {
    padding: 10px 20px;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-primary {
    background: linear-gradient(135deg, #ff5722, #e64a19);
    color: white;
    box-shadow: 0 4px 12px rgba(255, 87, 34, 0.3);
}

.btn-primary:hover {
    background: linear-gradient(135deg, #e64a19, #d84315);
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(255, 87, 34, 0.4);
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

.btn-outline {
    background: white;
    color: #ff5722;
    border: 2px solid #ff5722;
}

.btn-outline:hover {
    background: #ff5722;
    color: white;
    box-shadow: 0 4px 12px rgba(255, 87, 34, 0.3);
}

.no-orders {
    text-align: center;
    padding: 60px 20px;
    color: #666;
}

.no-orders i {
    font-size: 4em;
    color: #ccc;
    margin-bottom: 20px;
}

.no-orders h3 {
    margin: 0 0 10px 0;
    color: #333;
}

.pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 10px;
    margin-top: 30px;
}

.pagination a, .pagination span {
    padding: 10px 15px;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    text-decoration: none;
    color: #ff5722;
    transition: all 0.3s ease;
}

.pagination a:hover {
    background: #ff5722;
    color: white;
    border-color: #ff5722;
    box-shadow: 0 4px 12px rgba(255, 87, 34, 0.3);
}

.pagination .current {
    background: #ff5722;
    color: white;
    border-color: #ff5722;
    box-shadow: 0 4px 12px rgba(255, 87, 34, 0.3);
}

/* Cancel Order Modal */
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
    background-color: white;
    margin: 10% auto;
    padding: 30px;
    border-radius: 15px;
    width: 90%;
    max-width: 500px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.3);
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 1px solid #e9ecef;
}

.modal-title {
    margin: 0;
    color: #333;
}

.close {
    background: none;
    border: none;
    font-size: 2em;
    cursor: pointer;
    color: #999;
}

.close:hover {
    color: #333;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
    color: #333;
}

.form-control {
    width: 100%;
    padding: 12px 15px;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    font-size: 16px;
    transition: border-color 0.3s ease;
    box-sizing: border-box;
}

.form-control:focus {
    outline: none;
    border-color: #ff5722;
    box-shadow: 0 0 0 3px rgba(255, 87, 34, 0.1);
}

@media (max-width: 768px) {
    .customer-container {
        padding: 10px;
    }
    
    .orders-stats {
        flex-direction: column;
        gap: 15px;
    }
    
    .order-header-top {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }
    
    .order-info {
        flex-direction: column;
        gap: 10px;
    }
    
    .order-summary {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }
    
    .order-actions {
        width: 100%;
        flex-direction: column;
    }
}
</style>

<div class="main-wrapper">
    <div class="customer-container">
        <!-- Breadcrumb -->
        <div class="breadcrumb">
            <a href="index.php"><i class="fas fa-home"></i> Trang chủ</a> / 
            <span>Đơn hàng của tôi</span>
        </div>

        <!-- Orders Header -->
        <div class="orders-header">
            <h1><i class="fas fa-shopping-basket"></i> Đơn hàng của tôi</h1>
            <!-- <div class="orders-stats">
                <div class="stat-item">
                    <span class="number"><?php //echo $totalOrders;?></span>
                    <span class="label">Tổng đơn hàng</span>
                </div>
                <div class="stat-item">
                    <span class="number"><?php //echo $currentPage; ?></span>
                    <span class="label">Trang hiện tại</span>
                </div>
                <div class="stat-item">
                    <span class="number"><?php //echo $totalPages; ?></span>
                    <span class="label">Tổng trang</span>
                </div>
            </div> -->
        </div>

    <!-- Orders Container -->
    <div class="orders-container">
        <?php if (!empty($orders)): ?>
            <?php foreach ($orders as $order): ?>
                <div class="order-card">
                    <div class="order-header">
                        <div class="order-header-top">
                            <div class="order-id">
                                <i class="fas fa-receipt"></i> Đơn hàng :<?php echo $order['order_code']; ?>
                            </div>
                            <div class="order-status status-<?php echo $order['order_status']; ?>">
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
                            </div>
                        </div>
                        <div class="order-info">
                            <div><i class="fas fa-calendar"></i> <?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></div>
                            <div><i class="fas fa-box"></i> <?php echo $order['total_items']; ?> sản phẩm</div>
                            <div><i class="fas fa-map-marker-alt"></i> 
                                <?php 
                                $address = [];
                                if ($order['xa_name']) $address[] = $order['xa_name'];
                                if ($order['huyen_name']) $address[] = $order['huyen_name'];
                                if ($order['tinh_name']) $address[] = $order['tinh_name'];
                                echo implode(', ', $address);
                                ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="order-body">
                        <div class="order-summary">
                            <div class="order-total">
                                <i class="fas fa-money-bill-wave"></i> 
                                <?php echo number_format($order['total_amount'], 0, ',', '.'); ?>đ
                            </div>
                            <div class="order-actions">
                                <a href="index.php?page=customer&action=orderDetail&order_id=<?php echo $order['order_id']; ?>" 
                                   class="btn btn-outline">
                                    <i class="fas fa-eye"></i> Xem chi tiết
                                </a>
                                
                                <?php if (in_array($order['order_status'], ['pending', 'confirmed'])): ?>
                                    <button onclick="showCancelModal(<?php echo $order['order_id']; ?>)" 
                                            class="btn btn-danger">
                                        <i class="fas fa-times"></i> Hủy đơn
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            
            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
                <div class="pagination">
                    <?php if ($currentPage > 1): ?>
                        <a href="index.php?page=orders&p=<?php echo $currentPage - 1; ?>">
                            <i class="fas fa-chevron-left"></i> Trước
                        </a>
                    <?php endif; ?>
                    
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <?php if ($i == $currentPage): ?>
                            <span class="current"><?php echo $i; ?></span>
                        <?php else: ?>
                            <a href="index.php?page=orders&p=<?php echo $i; ?>"><?php echo $i; ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>
                    
                    <?php if ($currentPage < $totalPages): ?>
                        <a href="index.php?page=orders&p=<?php echo $currentPage + 1; ?>">
                            Sau <i class="fas fa-chevron-right"></i>
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
        <?php else: ?>
            <div class="no-orders">
                <i class="fas fa-shopping-cart"></i>
                <h3>Chưa có đơn hàng nào</h3>
                <p>Bạn chưa có đơn hàng nào. Hãy khám phá và mua sắm ngay!</p>
                <a href="index.php" class="btn btn-primary">
                    <i class="fas fa-shopping-bag"></i> Mua sắm ngay
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Cancel Order Modal -->
<div id="cancelModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Hủy đơn hàng</h3>
            <button class="close" onclick="closeCancelModal()">&times;</button>
        </div>
        <form id="cancelForm">
            <input type="hidden" id="cancelOrderId" name="order_id">
            <div class="form-group">
                <label for="cancel_reason">Lý do hủy đơn hàng *</label>
                <textarea id="cancel_reason" name="cancel_reason" class="form-control" 
                         rows="4" placeholder="Vui lòng cho biết lý do hủy đơn hàng..." required></textarea>
            </div>
            <div style="display: flex; gap: 10px; justify-content: flex-end;">
                <button type="button" onclick="closeCancelModal()" class="btn btn-outline">
                    Đóng
                </button>
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-times"></i> Xác nhận hủy
                </button>
            </div>
        </form>
    </div>
</div>
    </div>
</div>

<script>
// Cancel modal functions
function showCancelModal(orderId) {
    document.getElementById('cancelOrderId').value = orderId;
    document.getElementById('cancelModal').style.display = 'block';
}

function closeCancelModal() {
    document.getElementById('cancelModal').style.display = 'none';
    document.getElementById('cancelForm').reset();
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('cancelModal');
    if (event.target == modal) {
        closeCancelModal();
    }
}

// Cancel form submission
document.getElementById('cancelForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    if (!confirm('Bạn có chắc chắn muốn hủy đơn hàng này?')) {
        return;
    }
    
    const formData = new FormData(this);
    const button = this.querySelector('button[type="submit"]');
    const originalText = button.innerHTML;
    
    button.disabled = true;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang hủy...';
    
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
    })
    .finally(() => {
        button.disabled = false;
        button.innerHTML = originalText;
        closeCancelModal();
    });
});
</script>