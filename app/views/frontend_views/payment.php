<?php
// app/views/frontend_views/payment.php

// Đảm bảo đã session_start() ở bootstrap
$flashSuccess = Session::get('flash_success');
$flashError   = Session::get('flash_error');
$flashInfo    = Session::get('flash_info');

if ($flashSuccess) unset($_SESSION['flash_success']);
if ($flashError)   unset($_SESSION['flash_error']);
if ($flashInfo)    unset($_SESSION['flash_info']);
$sessionId = session_id();
?>

<!-- Flash Messages -->
<?php if ($flashSuccess): ?>
<div class="alert alert-success">
    <?php echo htmlspecialchars($flashSuccess); ?>
</div>
<?php endif; ?>

<?php if ($flashError): ?>
<div class="alert alert-error">
    <?php echo htmlspecialchars($flashError); ?>
</div>
<?php endif; ?>

<?php if ($flashInfo): ?>
<div class="alert alert-info">
    <?php echo htmlspecialchars($flashInfo); ?>
</div>
<?php endif; ?>

<!-- -----------------------PAYMENT---------------------------------------------- -->
<section class="payment">
    <div class="container">
        <div class="payment-top-wrap">
             <div class="payment-top">
                 <div class="delivery-top-delivery payment-top-item">
                     <i class="fas fa-shopping-cart"></i>
                 </div>
                 <div class="delivery-top-adress payment-top-item">
                     <i class="fas fa-map-marker-alt"></i>
                 </div>
                 <div class="delivery-top-payment payment-top-item">
                     <i class="fas fa-money-check-alt"></i>
                 </div>
             </div>
        </div>
     </div>
     
     <div class="container">
        <!-- Kiểm tra có thông tin order không -->
        <?php if (isset($order) && !empty($order)): ?>
             <div class="payment-content row">
                <div class="payment-content-left">
                    <form action="index.php?page=payment&action=process" method="POST">
                        <div class="payment-content-left-method-delivery">
                            <p style="font-weight: bold;">Phương thức giao hàng</p> <br>
                            <div class="payment-content-left-method-delivery-item">
                                <input name="deliver-method" value="Giao hàng chuyển phát nhanh" type="radio" checked>
                                <label>Giao hàng chuyển phát nhanh</label>
                            </div>
                            <div class="payment-content-left-method-delivery-item">
                                <input name="deliver-method" value="Giao hàng tiêu chuẩn" type="radio">
                                <label>Giao hàng tiêu chuẩn</label>
                            </div>
                        </div>
                        <br>
                        
                        <div class="payment-content-left-method-payment">
                            <p style="font-weight: bold;">Phương thức thanh toán</p> <br>
                            
                            <div class="payment-content-left-method-payment-item">
                                <input value="COD" name="method-payment" checked type="radio">
                                <label>Thu tiền tận nơi (COD)</label>
                            </div>
                            <div class="payment-content-left-method-payment-item">
                                <input value="momo" name="method-payment" type="radio">
                                <label>Thanh toán Momo</label>
                            </div>
                        </div>
                        
                        <div class="payment-content-right-payment">
                            <button type="submit">HOÀN THÀNH THANH TOÁN</button>
                        </div>
                    </form>
                </div>
                
                <div class="payment-content-right">
                    <!-- Thông tin đơn hàng -->
                    <div class="order-info" style="background: #f8f9fa; padding: 15px; margin-bottom: 20px; border-radius: 5px;">
                        <h4>Thông tin đơn hàng #<?php echo htmlspecialchars($order['order_id']); ?></h4>
                        <p><strong>Khách hàng:</strong> <?php echo htmlspecialchars($order['customer_name']); ?></p>
                        <p><strong>Điện thoại:</strong> <?php echo htmlspecialchars($order['customer_phone']); ?></p>
                        <p><strong>Địa chỉ:</strong> <?php 
                            $addressParts = [];
                            if (!empty($order['customer_diachi'])) $addressParts[] = $order['customer_diachi'];
                            if (!empty($order['xa_name'])) $addressParts[] = $order['xa_name'];
                            if (!empty($order['huyen_name'])) $addressParts[] = $order['huyen_name'];
                            if (!empty($order['tinh_name'])) $addressParts[] = $order['tinh_name'];
                            echo htmlspecialchars(implode(', ', $addressParts));
                        ?></p>
                        <p><strong>Ngày đặt:</strong> <?php echo date('d/m/Y H:i', strtotime($order['order_date'])); ?></p>
                    </div>

                    <div class="payment-content-right-button">
                        <input type="text" placeholder="Mã giảm giá/Quà tặng">
                        <button><i class="fas fa-check"></i></button>
                    </div>
                    <div class="payment-content-right-button">
                        <input type="text" placeholder="Mã cộng tác viên">
                        <button><i class="fas fa-check"></i></button>
                    </div>
                    <div class="payment-content-right-mnv">
                        <select>
                            <option value="">Chọn mã nhân viên thân thiết</option>
                            <option value="D345">D345</option>
                            <option value="C333">C333</option>
                            <option value="T567">T567</option>
                            <option value="D333">D333</option>
                        </select>
                    </div>
                    <br>
                    
                    <table>
                        <tr>
                            <th>Tên sản phẩm</th>
                            <th>Đơn giá</th>
                            <th>Số lượng</th>
                            <th>Thành tiền</th>
                        </tr>
                        
                        <?php 
                        // Hiển thị items từ cart nếu có, nếu không thì hiển thị thông tin tổng quát
                        if ($cartItems && $cartItems->num_rows > 0): 
                            $cartItems->data_seek(0); // Reset cursor
                            $totalAmount = 0;
                            while($item = $cartItems->fetch_assoc()): 
                                $subtotal = $item['sanpham_gia'] * $item['quantitys'];
                                $totalAmount += $subtotal;
                                
                                // Thêm thông tin size và màu nếu có
                                $productInfo = htmlspecialchars($item['sanpham_tieude']);
                                $variantInfo = [];
                                if (!empty($item['sanpham_size'])) {
                                    $variantInfo[] = 'Size: ' . htmlspecialchars($item['sanpham_size']);
                                }
                                if (!empty($item['color_ten'])) {
                                    $variantInfo[] = 'Màu: ' . htmlspecialchars($item['color_ten']);
                                }
                                if (!empty($variantInfo)) {
                                    $productInfo .= '<br><small style="color: #666;">(' . implode(', ', $variantInfo) . ')</small>';
                                }
                        ?>
                        <tr>
                            <td><?php echo $productInfo; ?></td>
                            <td><?php echo number_format($item['sanpham_gia']); ?>đ</td>
                            <td><?php echo $item['quantitys']; ?></td>
                            <td><?php echo number_format($subtotal); ?>đ</td>                          
                        </tr>
                        <?php 
                            endwhile; 
                        else: 
                        ?>
                        <!-- Nếu không có cart items, hiển thị thông tin từ order -->
                        <tr>
                            <td colspan="3">Chi tiết sản phẩm đã được xử lý</td>
                            <td><?php echo number_format($cartTotal); ?>đ</td>
                        </tr>
                        <?php endif; ?>
                       
                        <tr style="border-top: 2px solid #dddddd">
                            <td style="font-weight: bold;" colspan="3">Tổng tiền hàng</td>
                            <td style="font-weight: bold;">
                                <?php echo number_format($cartTotal); ?>đ
                            </td>                          
                        </tr>
                        <?php if (isset($order['shipping_fee']) && $order['shipping_fee'] > 0): ?>
                        <tr>
                            <td colspan="3">Phí vận chuyển</td>
                            <td><?php echo number_format($order['shipping_fee']); ?>đ</td>
                        </tr>
                        <?php endif; ?>
                        <tr style="border-top: 2px solid red">
                            <td style="font-weight: bold; font-size: 16px;" colspan="3">TỔNG THANH TOÁN</td>
                            <td style="font-weight: bold; color: red; font-size: 16px;">
                                <?php 
                                $finalTotal = $cartTotal + ($order['shipping_fee'] ?? 0);
                                echo number_format($finalTotal); 
                                ?>đ
                            </td>                          
                        </tr>
                    </table>
                </div>
             </div>
         <?php else: ?>
             <!-- Nếu không có thông tin order -->
             <div class="cart-empty" style="text-align: center; padding: 50px 0;">
                 <p style="font-size: 18px; margin-bottom: 20px;">Không tìm thấy thông tin đơn hàng. Vui lòng quay lại trang giao hàng.</p>
                 <a href="index.php?page=delivery" class="continue-shopping" style="display: inline-block; padding: 10px 20px; background: #e53637; color: white; text-decoration: none; border-radius: 5px;">Quay lại trang giao hàng</a>
             </div>
         <?php endif; ?>
     </div>
</section>

<script>
$(document).ready(function(){
    // Validate form before submit
    $('form').on('submit', function(e) {
        var deliveryMethod = $('input[name="deliver-method"]:checked').val();
        var paymentMethod = $('input[name="method-payment"]:checked').val();
        
        if (!deliveryMethod) {
            alert('Vui lòng chọn phương thức giao hàng');
            e.preventDefault();
            return false;
        }
        
        if (!paymentMethod) {
            alert('Vui lòng chọn phương thức thanh toán');
            e.preventDefault();
            return false;
        }
        
        // Confirm xác nhận thanh toán
        var confirmMsg = 'Xác nhận thanh toán với:\n' +
                        'Giao hàng: ' + deliveryMethod + '\n' +
                        'Thanh toán: ' + paymentMethod;
        
        if (!confirm(confirmMsg)) {
            e.preventDefault();
            return false;
        }
        
        // Show loading
        var $submitBtn = $(this).find('button[type="submit"]');
        $submitBtn.prop('disabled', true);
        $submitBtn.text('Đang xử lý...');
    });
    
    // Hiển thị thông tin khi chọn phương thức thanh toán
    $('input[name="method-payment"]').on('change', function() {
        var method = $(this).val();
        var infoText = '';
        
        if (method === 'Thu tiền tận nơi') {
            infoText = '<i class="fas fa-info-circle"></i> Bạn sẽ thanh toán bằng tiền mặt khi nhận hàng.';
        }
        
        if (infoText) {
            $('#payment-method-info').html('<p>' + infoText + '</p>').slideDown();
        } else {
            $('#payment-method-info').slideUp();
        }
    });
});
</script>

<style>
.alert {
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 4px;
    animation: slideDown 0.3s ease-out;
}
.alert-success {
    background-color: #d4edda;
    border-color: #c3e6cb;
    color: #155724;
}
.alert-error {
    background-color: #f8d7da;
    border-color: #f5c6cb;
    color: #721c24;
}
.alert-info {
    background-color: #d1ecf1;
    border-color: #bee5eb;
    color: #0c5460;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

#payment-method-info {
    padding: 12px;
    background: #e7f3ff;
    border-left: 4px solid #2196F3;
    border-radius: 4px;
}

#payment-method-info p {
    margin: 0;
    color: #0c5460;
}

#payment-method-info small {
    color: #666;
}

/* Loading spinner */
.fa-spinner {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>