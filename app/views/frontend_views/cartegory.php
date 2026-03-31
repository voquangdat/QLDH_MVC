<?php
// File này được gọi từ CategoryController qua render method
// Không cần include header và leftside riêng biệt
// require_once __DIR__ . '/../../controllers/frontend/CategoryController.php';
?>
            <!-- <link rel="stylesheet" href="/public/css/footer.css"> -->
            <div class="cartegory-right">
                <div class="cartegory-right-top row">
                    <div class="cartegory-right-top-item">
                        <p><?php echo isset($categoryData['loaisanpham_ten']) ? $categoryData['loaisanpham_ten'] : 'Hiện tại chưa có loại sản phẩm nào'; ?></p>
                    </div>
                    <div class="cartegory-right-top-item">
                        <button><span>Bộ lọc</span><i class="fas fa-sort-down"></i></button>
                    </div>
                    <div class="cartegory-right-top-item">
                        <form method="GET" action="">
                            <input type="hidden" name="loaisanpham_id" value="<?php echo $loaisanpham_id; ?>">
                            <select name="sort" onchange="this.form.submit()">
                                <option value="">Sắp xếp</option>
                                <option value="price_high" <?php echo isset($_GET['sort']) && $_GET['sort'] == 'price_high' ? 'selected' : ''; ?>>Giá cao đến thấp</option>
                                <option value="price_low" <?php echo isset($_GET['sort']) && $_GET['sort'] == 'price_low' ? 'selected' : ''; ?>>Giá thấp đến cao</option>
                            </select>
                        </form>
                    </div>
                </div>
                
                <div class="cartegory-right-content row">
                    <?php
                    if($products && $products->num_rows > 0) {
                        while($product = $products->fetch_assoc()) {
                    ?>
                    <div class="cartegory-right-content-item">
                        <a href="index.php?page=product&sanpham_id=<?php echo $product['sanpham_id']?>">
                            <img src="/public/uploads/<?php echo $product['sanpham_anh']?>" alt="">
                        </a>
                        <a href="index.php?page=product&sanpham_id=<?php echo $product['sanpham_id']?>">
                            <h1><?php echo $product['sanpham_tieude']?></h1>
                        </a>
                        <p><?php echo number_format($product['sanpham_gia']); ?><sup>đ</sup></p>
                        <span>_new_</span>
                    </div>
                    <?php
                        }
                    } else {
                        echo "<p>Không có sản phẩm nào trong danh mục này.</p>";
                    }
                    ?>
                </div>
                
                <div class="cartegory-right-bottom row">
                    <div class="cartegory-right-bottom-items">
                        <p>Hiện thị <?php echo $products ? $products->num_rows : 0; ?> sản phẩm</p>
                    </div>
                    <div class="cartegory-right-bottom-items">
                        <p><span>&#171;</span> 1 2 3 4 5 <span>&#187;</span> Trang cuối</p>
                    </div>
                </div>
            </div>  <!-- End cartegory-right -->
            </div>  <!-- End row -->
        </div>  <!-- End container -->
    </section>  <!-- End section.cartegory -->