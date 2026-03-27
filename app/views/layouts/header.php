<?php
// session_start();
require_once __DIR__ . '/../../controllers/frontend/HeaderController.php';

$headerController = new HeaderController();
$headerData = $headerController->getHeaderData();

// Kiểm tra trạng thái đăng nhập - chỉ cho frontend customer
$isLoggedIn = Session::get('customer_id') ? true : false;
$customerName = Session::get('customer_name');
$userRole = 'customer'; // Frontend chỉ hiển thị chức năng khách hàng
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website - Voxfootball</title>
    <script src="https://kit.fontawesome.com/54f0cb7e4a.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
    <link rel="stylesheet" href="/public/css/mainstyle.css">
    <script src="/public/js/header.js"></script>
</head>
<body>
    <div class="page-wrapper">
    <section class="top">
        <div class="container">
            <div class="row">
                <!-- Mobile Menu Bar -->
                <div class="menu-bar">
                    <i class="fas fa-bars"></i>
                </div>
                
                <!-- Logo -->
                <div class="top-logo">
                    <a href="index.php">
                        <img src="/public/image/logoShop.png" alt="Voxfootball Logo" height="40px" width="48px">
                    </a>
                </div>
                
                <!-- Navigation Menu -->
                <div class="top-menu-items">
                    <ul>
                        <li>
                            <a href="index.php">
                                <i class="fas fa-home"></i> Trang chủ
                            </a>
                        </li>
                        
                        <?php if (!empty($headerData['categories'])): ?>
                            <?php foreach ($headerData['categories'] as $category): ?>
                                <li>
                                    <a href="index.php?page=category&danhmuc_id=<?php echo $category['danhmuc_id']; ?>">
                                        <?php echo htmlspecialchars($category['danhmuc_ten']); ?>
                                        <?php if (!empty($category['subcategories'])): ?>
                                            <i class="fas fa-chevron-down"></i>
                                        <?php endif; ?>
                                    </a>
                                    
                                    <?php if (!empty($category['subcategories'])): ?>
                                        <ul class="top-menu-item">
                                            <?php foreach ($category['subcategories'] as $subCat): ?>
                                                <li>
                                                    <a href="index.php?page=category&loaisanpham_id=<?php echo $subCat['loaisanpham_id']; ?>">
                                                        <i class="fas fa-tag"></i>
                                                        <?php echo htmlspecialchars($subCat['loaisanpham_ten']); ?>
                                                    </a>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        
                        <li>
                            <a href="index.php?page=news">
                                <i class="fas fa-newspaper"></i> Tin tức
                            </a>
                        </li>
                        <li>
                            <a href="index.php?page=contact">
                                <i class="fas fa-phone"></i> Liên hệ
                            </a>
                        </li>
                    </ul>
                </div>
                
                <!-- Top Menu Icons -->
                <div class="top-menu-icons">
                    <ul>
                        <!-- Search -->
                        <li>
                            <input type="text" placeholder="Tìm kiếm sản phẩm..." id="search-input">
                            <i class="fas fa-search"></i>
                        </li>
                        
                        <!-- User Account Area -->
                        <li class="user-account">
                            <?php if ($isLoggedIn): ?>
                                <!-- Logged In User -->
                                <a href="#" class="user-dropdown-toggle" title="Tài khoản">
                                    <i class="fas fa-user-circle"></i>
                                    <span class="user-name">
                                        <?php echo htmlspecialchars($customerName ?? 'Khách hàng'); ?>
                                    </span>
                                    <i class="fas fa-chevron-down"></i>
                                </a>
                                
                                <!-- User Dropdown Menu - Chỉ hiển thị chức năng khách hàng -->
                                <div class="user-dropdown-menu">
                                    <a href="index.php?page=profile" class="dropdown-item">
                                        <i class="fas fa-user"></i> Thông tin cá nhân
                                    </a>
                                    <a href="index.php?page=orders" class="dropdown-item">
                                        <i class="fas fa-shopping-bag"></i> Đơn hàng của tôi
                                    </a>
                                    <a href="index.php?page=wishlist" class="dropdown-item">
                                        <i class="fas fa-heart"></i> Danh sách yêu thích
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    
                                    <a href="index.php?page=auth&action=logout" class="dropdown-item logout-link">
                                        <i class="fas fa-sign-out-alt"></i> Đăng xuất
                                    </a>
                                </div>
                            <?php else: ?>
                                <!-- Not Logged In -->
                                <div class="auth-buttons">
                                    <a href="index.php?page=login" class="auth-btn login-btn" title="Đăng nhập">
                                        <i class="fas fa-sign-in-alt"></i>
                                        <span>Đăng nhập</span>
                                    </a>
                                    <span class="auth-separator">|</span>
                                    <a href="index.php?page=register" class="auth-btn register-btn" title="Đăng ký">
                                        <i class="fas fa-user-plus"></i>
                                        <span>Đăng ký</span>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </li>
                        
                        <!-- Shopping Cart -->
                        <li>
                            <a href="index.php?page=cart&id=live" title="Giỏ hàng">
                                <i class="fas fa-shopping-cart"></i>
                                <span class="cart-count"><?php echo isset($headerData['cart_count']) ? $headerData['cart_count'] : 0; ?></span>
                            </a>
                            
                            <!-- Cart Mini Content -->
                            <div class="cart-content-mini">
                                <div class="cart-content-mini-top">
                                    <p><i class="fas fa-shopping-bag"></i> Giỏ hàng của bạn</p>
                                </div>
                                
                                <?php if (!empty($headerData['cart_items'])): ?>
                                    <?php foreach ($headerData['cart_items'] as $item): ?>
                                        <div class="cart-content-mini-item">
                                            <img src="<?php echo htmlspecialchars($item['sanpham_anh']); ?>" alt="<?php echo htmlspecialchars($item['sanpham_tieude']); ?>">
                                            <div class="cart-content-item-text">
                                                <h1><?php echo htmlspecialchars($item['sanpham_tieude']); ?></h1>
                                                <p><i class="fas fa-tag"></i> Size: <?php echo htmlspecialchars($item['sanpham_size']); ?></p>
                                                <p><i class="fas fa-sort-numeric-up"></i> SL: <?php echo $item['quantitys']; ?></p>
                                                <p><i class="fas fa-money-bill-wave"></i> <?php echo number_format($item['sanpham_gia']); ?>đ</p>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                    
                                    <div class="cart-content-mini-total">
                                        <p><strong><i class="fas fa-calculator"></i> Tổng: <?php echo number_format($headerData['cart_total'] ?? 0); ?>đ</strong></p>
                                    </div>
                                <?php else: ?>
                                    <div class="cart-content-mini-empty">
                                        <p><i class="fas fa-shopping-cart"></i> Giỏ hàng trống</p>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="cart-content-mini-bottom">
                                    <p><a href="index.php?page=cart&id=live"><i class="fas fa-eye"></i> Xem chi tiết</a></p>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- JavaScript for user dropdown -->
    <script>
    $(document).ready(function() {
        // Toggle user dropdown
        $('.user-dropdown-toggle').on('click', function(e) {
            e.preventDefault();
            $('.user-dropdown-menu').toggleClass('show');
        });
        
        // Close dropdown when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.user-account').length) {
                $('.user-dropdown-menu').removeClass('show');
            }
        });
        
        // Logout confirmation
        $('.logout-link').on('click', function(e) {
            if (!confirm('Bạn có chắc chắn muốn đăng xuất?')) {
                e.preventDefault();
            }
        });
    });
    </script>
</body>
</html>