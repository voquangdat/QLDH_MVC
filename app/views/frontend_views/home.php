<?php
require_once __DIR__ . '/../../controllers/frontend/HomeController.php';
?>
<link rel="stylesheet" href="/public/css/index-effects.css">

<!-- -------------Trình bày sản phẩm nổi bật ----------------- -->
<section class="product-display" id="product">
    <div class="container">
        <div class="section-header">
            <h1><i class="fas fa-fire"></i> Sản Phẩm Bán Chạy</h1>
            <p>Những sản phẩm được yêu thích nhất tại VoxFootball</p>
        </div>
        
        <div class="product-grid">
            <?php if (!empty($featuredProducts)): ?>
                <?php foreach ($featuredProducts as $product): ?>
                    <div class="product-card" data-product-id="<?php echo $product['id']; ?>">
                        <div class="product-image-container">
                            <div class="product-badge">
                                <span class="discount-badge">-<?php echo $product['discount']; ?>%</span>
                                <div class="product-actions">
                                    <button class="action-btn wishlist-btn" title="Yêu thích">
                                        <i class="far fa-heart"></i>
                                    </button>
                                    <button class="action-btn quick-view-btn" title="Xem nhanh">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="action-btn compare-btn" title="So sánh">
                                        <i class="fas fa-exchange-alt"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="product-images">
                                <img src="/public/uploads/<?php echo htmlspecialchars($product['image_main']); ?>" 
                                     alt="<?php echo htmlspecialchars($product['name']); ?>" 
                                     class="main-image">
                                <img src="/public/uploads/<?php echo htmlspecialchars($product['image_hover']); ?>" 
                                     alt="<?php echo htmlspecialchars($product['name']); ?>" 
                                     class="hover-image">
                            </div>
                            
                            <div class="size-options">
                                <span class="size-label">Size:</span>
                                <div class="size-list">
                                    <button class="size-btn" data-size="S">S</button>
                                    <button class="size-btn active" data-size="M">M</button>
                                    <button class="size-btn" data-size="L">L</button>
                                    <button class="size-btn" data-size="XL">XL</button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="product-info">
                            <div class="product-rating">
                                <div class="stars">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star-half-alt"></i>
                                </div>
                                <span class="rating-count">(4.8)</span>
                            </div>
                            
                            <h3 class="product-name">
                                <a href="?page=product&sanpham_id=<?php echo $product['id']; ?>">
                                    <?php echo htmlspecialchars($product['name']); ?>
                                </a>
                            </h3>
                            
                            <div class="product-price">
                                <span class="current-price"><?php echo number_format($product['price']); ?>đ</span>
                                <span class="old-price"><?php echo number_format($product['old_price']); ?>đ</span>
                            </div>
                            
                            <div class="product-buttons">
                                <button class="btn btn-cart" data-product-id="<?php echo $product['id']; ?>">
                                    <i class="fas fa-shopping-cart"></i>
                                    Thêm vào giỏ
                                </button>
                                <a href="?page=product&sanpham_id=<?php echo $product['id']; ?>" class="btn btn-buy" data-product-id="<?php echo $product['id']; ?>">
                                    <i class="fas fa-bolt"></i>
                                    Mua ngay
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="no-products">Không có sản phẩm nào.</p>
            <?php endif; ?>
        </div>
        
        <div class="section-footer">
            <a href="?page=category" class="view-all-btn">
                <i class="fas fa-th-large"></i>
                Xem tất cả sản phẩm
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</section>



<script src="/public/js/home.js"></script>
