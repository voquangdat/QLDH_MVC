<!-- Order Detail Page -->
<section class="detail">
    <div class="container">
        <div class="detail-top">
            <p>CHI TIẾT ĐƠN HÀNG</p>
        </div>
        
        <?php if (isset($orderInfo) && $orderInfo): ?>
            <h1>Mã đơn hàng: <span style="font-size: 20px; color: #378000;"><?php echo htmlspecialchars($orderCode); ?></span></h1>
            <div class="detail-text">
                <div class="detail-text-left-content">
                    <p><span style="font-weight: bold; color:red">Thông tin giao hàng</span></p>
                    <br>
                    
                    <p><span style="font-weight: bold;">Họ và tên</span>: <?php echo htmlspecialchars($orderInfo['customer_name']); ?></p>
                    <p><span style="font-weight: bold;">Số ĐT</span>: <?php echo htmlspecialchars($orderInfo['customer_phone']); ?></p>
                    <p><span style="font-weight: bold;">Địa chỉ</span>: <?php 
                        $addressParts = [];
                        if (!empty($orderInfo['customer_diachi'])) $addressParts[] = $orderInfo['customer_diachi'];
                        if (!empty($orderInfo['xa_name'])) $addressParts[] = $orderInfo['xa_name'];
                        if (!empty($orderInfo['huyen_name'])) $addressParts[] = $orderInfo['huyen_name'];
                        if (!empty($orderInfo['tinh_name'])) $addressParts[] = $orderInfo['tinh_name'];
                        echo htmlspecialchars(implode(', ', $addressParts));
                    ?></p>
                    
                    <?php if ($paymentInfo): ?>
                        <p><span style="font-weight: bold;">Phương thức giao hàng</span>: <?php echo htmlspecialchars($paymentInfo['delivery_method'] ?? 'Chưa cập nhật'); ?></p>
                        <p><span style="font-weight: bold;">Phương thức thanh toán</span>: <?php echo htmlspecialchars($paymentInfo['payment_method'] ?? 'Chưa cập nhật'); ?></p>
                        <p><span style="font-weight: bold;">Trạng thái thanh toán</span>: 
                            <span style="color: <?php echo ($paymentInfo['payment_status'] == 'completed') ? '#378000' : '#f39c12'; ?>">
                                <?php 
                                    switch(strtolower($paymentInfo['payment_status'] ?? '')) {
                                        case 'pending':
                                            echo 'Chờ thanh toán';
                                            break;
                                        case 'completed':
                                            echo 'Đã thanh toán';
                                            break;
                                        case 'failed':
                                            echo 'Thanh toán thất bại';
                                            break;
                                        case 'refunded':
                                            echo 'Đã hoàn tiền';
                                            break;
                                        default:
                                            echo 'Chưa cập nhật';
                                    }
                                ?>
                            </span>
                        </p>
                    <?php endif; ?>
                    
                    <p><span style="font-weight: bold;">Trạng thái đơn hàng</span>: 
                        <span style="color: <?php 
                            $statusColor = '#f39c12'; // Default: orange
                            switch(strtolower($orderInfo['order_status'])) {
                                case 'delivered':
                                    $statusColor = '#378000'; // Green
                                    break;
                                case 'cancelled':
                                    $statusColor = '#e74c3c'; // Red
                                    break;
                                case 'confirmed':
                                case 'processing':
                                    $statusColor = '#3498db'; // Blue
                                    break;
                            }
                            echo $statusColor;
                        ?>">
                            <?php 
                                switch(strtolower($orderInfo['order_status'])) {
                                    case 'pending':
                                        echo 'Đang chờ xử lý';
                                        break;
                                    case 'confirmed':
                                        echo 'Đã xác nhận';
                                        break;
                                    case 'processing':
                                        echo 'Đang xử lý';
                                        break;
                                    case 'shipped':
                                        echo 'Đang giao hàng';
                                        break;
                                    case 'delivered':
                                        echo 'Đã giao hàng';
                                        break;
                                    case 'cancelled':
                                        echo 'Đã hủy';
                                        break;
                                    default:
                                        echo ucfirst($orderInfo['order_status']);
                                }
                            ?>
                        </span>
                    </p>
                    <p><span style="font-weight: bold;">Ngày đặt hàng</span>: <?php echo date('d/m/Y H:i', strtotime($orderInfo['order_date'] ?? $orderInfo['created_at'] ?? 'now')); ?></p>
                </div>
                
                <div class="detail-text-right-content">
                    <p><span style="font-weight: bold;color:red">Thông tin đơn hàng</span></p>
                    <br>
                    
                    <div class="detail-text-right">
                        <table>
                            <tr>
                                <th>Sản phẩm</th>
                                <th>Tên sản phẩm</th>
                                <th>Màu</th>
                                <th>Size</th>
                                <th>SL</th>
                                <th>Giá</th>
                            </tr>
                            
                            <?php if ($orderItems && $orderItems->num_rows > 0): ?>
                                <?php while($item = $orderItems->fetch_assoc()): 
                                    // Xử lý cả trường hợp từ order_details và cart
                                    $quantity = $item['quantity'] ?? $item['quantitys'] ?? 0;
                                    $productTitle = $item['sanpham_tieude'];
                                    $productImage = $item['sanpham_anh'];
                                    $productPrice = $item['sanpham_gia'];
                                    
                                    /// Lấy size
                                    $productSize = $item['sanpham_size'] ?? 'N/A';

                                    // Lấy thông tin màu
                                    $colorName = $item['color_ten'] ?? 'N/A';
                                    $colorFile = $item['color_anh'] ?? ''; // thường chỉ là tên file, không có path

                                    // Dựng URL ảnh màu (nếu có)
                                    $colorImgUrl = '';
                                    if (!empty($colorFile)) {
                                        // đảm bảo có /public/uploads/
                                        $colorImgUrl = '/public/uploads/' . ltrim($colorFile, '/');
                                    }

                                    // HTML hiển thị màu: ưu tiên ảnh, nếu không có ảnh thì hiển thị tên màu
                                    if (!empty($colorImgUrl)) {
                                        $colorDisplay = '<img src="' . htmlspecialchars($colorImgUrl) . '" '.
                                                        'alt="' . htmlspecialchars($colorName) . '" '.
                                                        'style="width:30px;height:30px;border-radius:50%;object-fit:cover;" '.
                                                        // fallback nếu ảnh lỗi -> hiện badge tên màu
                                                        'onerror="this.outerHTML = \'<span style=&quot;font-size:12px&quot;>' . htmlspecialchars($colorName) . '</span>\';" '.
                                                        'title="' . htmlspecialchars($colorName) . '">';
                                    } else {
                                        // không có ảnh -> nhãn tên màu
                                        $colorDisplay = '<span style="font-size:12px;">' . htmlspecialchars($colorName) . '</span>';
                                    }
                                ?>               
                                <tr>
                                    <td><img src="<?php echo htmlspecialchars($productImage); ?>" alt="<?php echo htmlspecialchars($productTitle); ?>" style="width: 60px; height: 60px; object-fit: cover;"></td>
                                    <td><p><?php echo htmlspecialchars($productTitle); ?></p></td>
                                    <td><?php echo $colorDisplay; ?></td>
                                    <td><p><?php echo htmlspecialchars($productSize); ?></p></td>
                                    <td><span><?php echo $quantity; ?></span></td>
                                    <td><p><?php echo number_format($productPrice); ?><sup>đ</sup></p></td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" style="text-align: center; padding: 20px;">Không có sản phẩm nào trong đơn hàng</td>
                                </tr>
                            <?php endif; ?>
                        </table>
                    </div>
                    <div class="detail-content-right-bottom">
                        <table>
                            <tr>
                                <th colspan="2"><p>TỔNG TIỀN ĐƠN HÀNG</p></th>
                            </tr>
                            <tr>
                                <td>TỔNG SẢN PHẨM</td>
                                <td><?php echo number_format($totalQuantity); ?></td>
                            </tr>
                            <tr>
                                <td>TỔNG TIỀN HÀNG</td>
                                <td><p><?php echo number_format($totalAmount); ?><sup>đ</sup></p></td>
                            </tr>
                            <?php if (isset($orderInfo['shipping_fee']) && $orderInfo['shipping_fee'] > 0): ?>
                            <tr>
                                <td>PHÍ VẬN CHUYỂN</td>
                                <td><p><?php echo number_format($orderInfo['shipping_fee']); ?><sup>đ</sup></p></td>
                            </tr>
                            <?php endif; ?>
                            <tr>
                                <td>THÀNH TIỀN</td>
                                <td><p><?php echo number_format($totalAmount + ($orderInfo['shipping_fee'] ?? 0)); ?><sup>đ</sup></p></td>
                            </tr>
                            <tr style="border-top: 2px solid #e74c3c;">
                                <td style="font-weight: bold;">TẠM TÍNH</td>
                                <td><p style="font-weight: bold; color: #e74c3c; font-size: 18px;"><?php echo number_format($totalAmount + ($orderInfo['shipping_fee'] ?? 0)); ?><sup>đ</sup></p></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            
        <?php else: ?>
            <!-- Không tìm thấy đơn hàng -->
            <div class="no-order-found" style="text-align: center; padding: 50px 0;">
                <h2>Không tìm thấy thông tin đơn hàng</h2>
                <p style="margin: 20px 0;">Vui lòng kiểm tra lại mã đơn hàng hoặc liên hệ với chúng tôi để được hỗ trợ.</p>
                <div class="success-button">
                    <a href="index.php"><button>VỀ TRANG CHỦ</button></a>
                </div>
            </div>
        <?php endif; ?>
        
        <div class="success-button" style="text-align: center; margin-top: 30px;">
            <a href="index.php"><button>TIẾP TỤC MUA SẮM</button></a>
            <?php if (isset($orderInfo) && $orderInfo): ?>
                <a href="index.php?page=orders"><button>XEM DANH SÁCH ĐƠN HÀNG</button></a>
            <?php endif; ?>
        </div>
        
        <br>
        <p style="text-align: center; margin-top: 20px;">Mọi thắc mắc quý khách vui lòng liên hệ hotline <span style="font-size: 20px; color: red; font-weight: bold;">0973 999 949</span> hoặc chat với kênh hỗ trợ trên website để được hỗ trợ nhanh nhất.</p>
    </div>
</section>