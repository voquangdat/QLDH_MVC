<?php
// Debug - thêm vào đầu file cart.php
// error_log("Cart Items: " . ($cartItems ? $cartItems->num_rows : 'null'));
// error_log("Session ID: " . session_id());
?>


<!-- -----------------------CART---------------------------------------------- -->
<section class="cart">
    <div class="container">
        <div class="cart-top-wrap">
            <div class="cart-top">
                <div class="cart-top-cart cart-top-item">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="cart-top-adress cart-top-item">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <div class="cart-top-payment cart-top-item">
                    <i class="fas fa-money-check-alt"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="container">
        <?php if($cartItems && $cartItems->num_rows > 0): ?>
            <div class="cart-content row">
                <div class="cart-content-left">
                    <table>
                        <tr>
                            <th>Sản phẩm</th>
                            <th>Tên sản phẩm</th>
                            <th>Màu</th>
                            <th>Size</th>
                            <th>SL</th>
                            <th>Giá</th>
                            <th>Xóa</th>
                        </tr>     
                        <?php
                        $SL = 0;
                        $TT = 0;
                        while($result = $cartItems->fetch_assoc()): 
                            $itemTotal = (int)$result['sanpham_gia'] * (int)$result['quantitys'];
                            $SL += $result['quantitys'];
                            $TT += $itemTotal;
                        ?>               
                        <tr>
                            <td><img src="<?php echo $result['sanpham_anh'] ?>" alt=""></td>
                            <td><p><?php echo htmlspecialchars($result['sanpham_tieude']) ?></p></td>
                            <td><img src="<?php echo $result['color_anh'] ?>" alt=""></td>
                            <td><p><?php echo htmlspecialchars($result['sanpham_size']) ?></p></td>
                            <td><span><?php echo $result['quantitys'] ?></span></td>
                            <td><p><?php echo number_format($result['sanpham_gia']) ?><sup>đ</sup></p></td>
                            <td>
                                <a href="index.php?page=cart&action=remove&cart_id=<?php echo $result['cart_id'] ?>" 
                                   onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này?')">
                                    <span>×</span>
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </table>
                </div>
                
                <div class="cart-content-right">
                    <table>
                        <tr>
                            <th colspan="2"><p>TỔNG TIỀN GIỎ HÀNG</p></th>
                        </tr>
                        <tr>
                            <td>TỔNG SẢN PHẨM</td>
                            <td><?php echo number_format($SL) ?></td>
                        </tr>
                        <tr>
                            <td>TỔNG TIỀN HÀNG</td>
                            <td><p><?php echo number_format($TT) ?><sup>đ</sup></p></td>
                        </tr>
                        <tr>
                            <td>THÀNH TIỀN</td>
                            <td><p><?php echo number_format($TT) ?><sup>đ</sup></p></td>
                        </tr>
                        <tr>
                            <td>TẠM TÍNH</td>
                            <td><p style="font-weight: bold; color: black;"><?php echo number_format($TT) ?><sup>đ</sup></p></td>
                        </tr>
                    </table>
                    
                    <div class="cart-content-right-text">
                        <p>Bạn sẽ được miễn phí ship khi đơn hàng của bạn có tổng giá trị trên 2,000,000<sup>đ</sup></p><br>
                        <?php if($TT >= 2000000): ?>
                            <p style="color: red;font-weight: bold;">Đơn hàng của bạn đủ điều kiện được <span style="font-size: 18px;">Free</span> ship</p>
                        <?php else: 
                            $remaining = 2000000 - $TT;
                        ?>
                            <p style="color: red;font-weight: bold;">Mua thêm <span style="font-size: 18px;"><?php echo number_format($remaining) ?><sup>đ</sup></span> để được miễn phí SHIP</p>
                        <?php endif; ?>
                    </div>
                    
                    <div class="cart-content-right-button">
                        <button onclick="window.location.href='index.php'">TIẾP TỤC MUA SẮM</button>
                        <a href="index.php?page=delivery"><button>THANH TOÁN</button></a>
                    </div>
                    
                    <div class="cart-content-right-dangnhap">
                        <p>TÀI KHOẢN Voxfootball</p> <br>
                        <p>Hãy <a href="index.php?page=login">đăng nhập</a> tài khoản của bạn để tích điểm thành viên.</p>
                    </div>
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