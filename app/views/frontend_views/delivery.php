<?php
// app/views/frontend_views/delivery.php

// Lấy flash messages
$flashSuccess = Session::get('flash_success');
$flashError = Session::get('flash_error');
// if ($flashSuccess) Session::unset('flash_success');
// if ($flashError) Session::unset('flash_error');

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

<!-- -----------------------DELIVERY---------------------------------------------- -->
<section class="delivery">
    <div class="container">
        <div class="delivery-top-wrap">
             <div class="delivery-top">
                 <div class="delivery-top-delivery delivery-top-item">
                     <i class="fas fa-shopping-cart"></i>
                 </div>
                 <div class="delivery-top-adress delivery-top-item">
                     <i class="fas fa-map-marker-alt"></i>
                 </div>
                 <div class="delivery-top-payment delivery-top-item">
                     <i class="fas fa-money-check-alt"></i>
                 </div>
             </div>
        </div>
     </div>
     
     <div class="container">
         <?php if($cartItems && $cartItems->num_rows > 0): ?>
             <div class="delivery-content row">
                 <div class="delivery-content-left">
                    <form action="index.php?page=delivery&action=create" method="post">
                        <p>Vui lòng chọn địa chỉ giao hàng</p>
                        <div class="delivery-content-left-dangnhap row">
                            <i class="fas fa-sign-in-alt"></i>
                            <p>Đăng nhập (Nếu bạn đã có tài khoản của VOXFOOTBALL)</p>
                        </div>
                        <br>
                        
                        <input style="float: left;margin-right:12px" checked="checked" name="loaikhach" type="radio" value="khachle">
                        <p><span style="font-weight: bold;">Khách lẻ</span> (Nếu bạn không muốn lưu lại thông tin)</p>
                       <br>
                        <input style="float: left;margin-right:12px" class="register-input" name="loaikhach" value="dangky" type="radio">
                        <p><span style="font-weight: bold;">Đăng ký</span> (Tạo mới tài khoản với thông tin bên dưới)</p>
                        <br>
                        
                        <div class="delivery-content-left-input-top row">
                            <div class="delivery-content-left-input-top-item">
                                <label for="">Họ tên <span style="color: red;">*</span></label>
                                <input name="customer_name" required type="text">
                            </div>
                            <div class="delivery-content-left-input-top-item">
                                <label for="">Điện thoại <span style="color: red;">*</span></label>
                                <input name="customer_phone" required type="text">
                            </div>
                            <div class="delivery-content-left-input-top-item">
                                <label for="">Tỉnh/Tp <span style="color: red;">*</span></label>
                                <select name="customer_tinh" id="tinh_tp" required>
                                    <option value="">Chọn Tỉnh/Tp</option>
                                    <?php if($provinces && $provinces->num_rows > 0): ?>
                                        <?php while($province = $provinces->fetch_assoc()): ?>
                                            <option value="<?php echo $province['ma_tinh'] ?>">
                                                <?php echo htmlspecialchars($province['tinh_tp']) ?>
                                            </option>
                                        <?php endwhile; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="delivery-content-left-input-top-item">
                                <label for="">Quận/Huyện <span style="color: red;">*</span></label>
                                <select name="customer_huyen" id="quan_huyen" required>
                                    <option value="">Chọn Quận/Huyện</option>                           
                                </select>
                            </div>
                        </div>
                        
                        <div class="delivery-content-left-input-bottom">
                                <label for="">Phường/Xã <span style="color: red;">*</span></label>
                                <select name="customer_xa" id="phuong_xa" required>
                                    <option value="">Chọn Phường/Xã</option>
                                </select>
                        </div>
                        
                        <div class="delivery-content-left-input-bottom">
                                <label for="">Địa chỉ <span style="color: red;">*</span></label>
                                <input name="customer_diachi" required type="text">
                        </div>
                        
                        <div class="delivery-content-left-input-top row register" style="display: none;">
                            <div class="delivery-content-left-input-top-item">
                                <label for="">Mật khẩu<span style="color: red;">*</span></label>
                                <input name="password" type="password">
                            </div>
                            <div class="delivery-content-left-input-top-item">
                                <label for="">Nhập lại mật khẩu <span style="color: red;">*</span></label>
                                <input name="confirm_password" type="password">
                            </div>
                        </div>
                        
                        <div class="delivery-content-left-button row">
                            <a href="index.php?page=cart&id=live"><span> &#8810;</span><p>Quay lại giỏ hàng</p></a>
                            <button type="submit"><p style="font-weight: bold;">THANH TOÁN VÀ GIAO HÀNG</p></button>
                        </div>
                    </form>
                 </div>
                 
                <div class="delivery-content-right">
                    <table>
                        <tr>
                            <th>Tên sản phẩm</th>
                            <th>Đơn giá</th>
                            <th>Số lượng</th>
                            <th>Thành tiền</th>
                        </tr>
                        <?php
                           $cartItems->data_seek(0); // Reset cursor
                           $totalAmount = 0;
                           while($item = $cartItems->fetch_assoc()): 
                               $subtotal = $item['sanpham_gia'] * $item['quantitys'];
                               $totalAmount += $subtotal;
                        ?> 
                        <tr>
                            <td><?php echo htmlspecialchars($item['sanpham_tieude']) ?></td>
                            <td><?php echo number_format($item['sanpham_gia']) ?>đ</td>
                            <td><?php echo $item['quantitys'] ?></td>
                            <td><?php echo number_format($subtotal) ?>đ</td>                          
                        </tr>
                       <?php endwhile; ?>
                       
                        <tr style="border-top: 2px solid red">
                            <td style="font-weight: bold;border-top: 2px solid #dddddd" colspan="3">Tổng</td>
                            <td style="font-weight: bold;border-top: 2px solid #dddddd">
                                <?php echo number_format($cartTotal) ?>đ
                            </td>                          
                        </tr>
                    </table>
                </div>
             </div>
         <?php else: ?>
             <div class="cart-empty">
                 <p>Bạn vẫn chưa thêm sản phẩm nào vào giỏ hàng, Vui lòng chọn sản phẩm nhé!</p>
                 <a href="index.php" class="continue-shopping">Tiếp tục mua sắm</a>
             </div>
         <?php endif; ?>
     </div>
</section>

<script>
$(document).ready(function(){
    // Toggle password fields
    $('input[name="loaikhach"]').change(function(){
        if ($(this).val() === 'dangky') {
            $('.register').show();
            $('input[name="password"]').attr('required', true);
            $('input[name="confirm_password"]').attr('required', true);
        } else {
            $('.register').hide();
            $('input[name="password"]').attr('required', false);
            $('input[name="confirm_password"]').attr('required', false);
        }
    });

    // Load districts when province changes
    $("#tinh_tp").change(function(){
        var provinceId = $(this).val();
        console.log("Province selected: " + provinceId); // Debug
        
        if (provinceId && provinceId !== '#') {
            var ajaxUrl = "index.php?page=delivery&action=districts&tinh_id=" + provinceId;
            console.log("AJAX URL: " + ajaxUrl); // Debug
            
            $.get(ajaxUrl, function(data) {
                console.log("Districts response:", data); // Debug
                $("#quan_huyen").html(data);
                $("#phuong_xa").html('<option value="">Chọn Phường/Xã</option>');
            }).fail(function(xhr, status, error) {
                console.error("AJAX error:", status, error); // Debug
                console.error("Response:", xhr.responseText); // Debug
            });
        } else {
            $("#quan_huyen").html('<option value="">Chọn Quận/Huyện</option>');
            $("#phuong_xa").html('<option value="">Chọn Phường/Xã</option>');
        }
    });

    // Load wards when district changes  
    $("#quan_huyen").change(function(){
        var districtId = $(this).val();
        console.log("District selected: " + districtId); // Debug
        
        if (districtId && districtId !== '#') {
            var ajaxUrl = "index.php?page=delivery&action=wards&quan_huyen_id=" + districtId;
            console.log("AJAX URL: " + ajaxUrl); // Debug
            
            $.get(ajaxUrl, function(data) {
                console.log("Wards response:", data); // Debug
                $("#phuong_xa").html(data);
            }).fail(function(xhr, status, error) {
                console.error("AJAX error:", status, error); // Debug
                console.error("Response:", xhr.responseText); // Debug
            });
        } else {
            $("#phuong_xa").html('<option value="">Chọn Phường/Xã</option>');
        }
    });
});
</script>