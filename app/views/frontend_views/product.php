<?php
?>
<section class="product">
    <div class="container">
        <div class="product-top row">
            <?php if(isset($productData)): ?>
            <p><a href="index.php">Trang chủ</a></p> 
            <span>&#8594;</span> 
            <p><?php echo $productData['danhmuc_ten'] ?></p>
            <span>&#8594;</span>
            <p><?php echo $productData['loaisanpham_ten'] ?></p>
            <span>&#8594;</span>
            <p><?php echo $productData['sanpham_tieude'] ?></p>
            <?php endif; ?>
        </div>
        
        <div class="product-content row">
            <?php if(isset($productData)): ?>
            <div class="product-content-left row">
                <div class="product-content-left-big-img">
                    <img class="sanpham_anh" src="/public/uploads/<?php echo $productData['sanpham_anh'] ?>" alt="">
                </div>
                <div class="product-content-left-small-img">
                    <?php if (!empty($productImages) && $productImages->num_rows > 0): ?>
                        <?php while($imageRow = $productImages->fetch_assoc()): ?>
                            <img src="/public/uploads/<?php echo $imageRow['sanpham_anh'] ?>" alt="">
                        <?php endwhile; ?>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="product-content-right">
                <div class="product-content-right-product-name">
                    <input class="session_id" type="hidden" value="<?php echo session_id() ?>">
                    <input class="sanpham_id" type="hidden" value="<?php echo $productData['sanpham_id'] ?>">
                    <h1 class="sanpham_tieude"><?php echo $productData['sanpham_tieude'] ?></h1>
                    <p><?php echo $productData['sanpham_ma'] ?></p>
                </div>
                
                <div class="product-content-right-product-price">
                    <p><span><?php echo number_format($productData['sanpham_gia']) ?></span><sup>đ</sup></p>
                    <input class="sanpham_gia" type="hidden" value="<?php echo $productData['sanpham_gia'] ?>">
                </div>
                
                <div class="product-content-right-product-color">
                    <p><span style="font-weight: bold;">Màu sắc</span>: <?php echo $productData['color_ten'] ?> <span style="color: red;">*</span></p>
                    <div class="product-content-right-product-color-IMG">
                        <img class="color_anh" src="/public/uploads/<?php echo $productData['color_anh'] ?>" alt="">
                    </div>
                </div>
                
                <div class="product-content-right-product-size">
                    <p style="font-weight: bold">Size:</p>
                    <div class="size">
                        <?php if (!empty($variants)): ?>
                            <?php foreach ($variants as $v): ?>
                                <div class="size-item">
                                    <input class="size-item-input" 
                                           name="size-item" 
                                           type="radio"
                                           value="<?php echo htmlspecialchars($v['sanpham_size']); ?>"
                                           data-bienthe-id="<?php echo (int)$v['bienthe_id']; ?>"
                                           data-stock="<?php echo (int)($v['soluong_co_the_ban'] ?? 0); ?>">
                                    <span><?php echo htmlspecialchars($v['sanpham_size']); ?></span>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>Chưa thiết lập biến thể cho sản phẩm này.</p>
                        <?php endif; ?>
                    </div>
                    
                    <div class="quantity">
                        <p style="font-weight: bold">Số lượng:</p>
                        <input class="quantitys" type="number" min="1" value="1">
                    </div>
                    <p class="size-alert" style="color: red;"></p>
                    <p class="stock-hint" style="margin-top:6px;color:#555;"></p>
                </div>
                
                <div class="product-content-right-product-button">
                    <button class="add-cart-btn">
                        <i class="fas fa-shopping-cart"></i> 
                        <p>MUA HÀNG</p>
                    </button>
                    <button><p>TÌM TẠI CỬA HÀNG</p></button>
                </div>
                
                <div class="product-content-right-product-icon">
                    <div class="product-content-right-product-icon-item">
                        <i class="fas fa-phone-alt"></i> <p>Hotline</p>
                    </div>
                    <div class="product-content-right-product-icon-item">
                        <i class="far fa-comments"></i> <p>Chat</p>
                    </div>
                    <div class="product-content-right-product-icon-item">
                        <i class="far fa-envelope"></i> <p>Mail</p>
                    </div>
                </div>
                
                <div class="product-content-right-product-QR">
                    <img src="/public/image/qrcode2.png" alt="">
                </div>
                
                <div class="product-content-right-bottom">
                    <div class="product-content-right-bottom-top">&#8744;</div>
                    <div class="product-content-right-bottom-content-big">
                        <div class="product-content-right-bottom-title">
                            <div class="product-content-right-bottom-title-item chitiet">
                                <p>Chi tiết</p>
                            </div>
                            <div class="product-content-right-bottom-title-item baoquan">
                                <p>Bảo quản</p>
                            </div>
                            <div class="product-content-right-bottom-title-item">
                                <p>Tham khảo size</p>
                            </div>
                        </div>
                        <div class="product-content-right-bottom-content">
                            <div class="product-content-right-bottom-content-chitiet">
                                <?php echo $productData['sanpham_chitiet'] ?>
                            </div>
                            <div class="product-content-right-bottom-content-baoquan">
                                <?php echo $productData['sanpham_baoquan'] ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="product-related">
    <div class="container">
        <div class="product-related-title">
            <p>SẢN PHẨM LIÊN QUAN</p>
        </div>
        <div class="row justify-between">
            <?php if(!empty($relatedProducts) && $relatedProducts->num_rows > 0): ?>
                <?php while($relatedProduct = $relatedProducts->fetch_assoc()): ?>
                <div class="product-related-item">
                    <a href="index.php?page=product&sanpham_id=<?php echo $relatedProduct['sanpham_id']?>">
                        <img src="/public/uploads/<?php echo $relatedProduct['sanpham_anh']?>" alt="">
                    </a>
                    <a href="index.php?page=product&sanpham_id=<?php echo $relatedProduct['sanpham_id']?>">
                        <h1><?php echo $relatedProduct['sanpham_tieude']?></h1>
                    </a>
                    <p><?php echo number_format($relatedProduct['sanpham_gia']); ?><sup>đ</sup></p>
                    <span>_new_</span>
                </div>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<script>
$(function(){
  var selectedVariantId = null;

  $(document).on('change', '.size-item-input', function(){
    selectedVariantId = $(this).data('bienthe-id');
    var stock = $(this).data('stock');
    $('.size-alert').text('');
    $('.stock-hint').text('Còn lại: ' + (typeof stock === 'number' ? stock : 0));
  });

  $('.add-cart-btn').on('click', function(){
    if (!selectedVariantId) {
      $('.size-alert').text('Vui lòng chọn size*');
      return;
    }
    var $root = $(this).closest('.product-content-right');
    var sanpham_tieude = $root.find('.sanpham_tieude').text();
    var session_id     = $root.find('.session_id').val();
    var sanpham_id     = $root.find('.sanpham_id').val();
    var sanpham_anh    = $('.product-content-left-big-img .sanpham_anh').attr('src');
    var sanpham_gia    = $root.find('.sanpham_gia').val();
    var quantitys      = Math.max(1, parseInt($root.find('.quantitys').val() || '1', 10));

    $('.add-cart-btn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Đang thêm...');

    $.ajax({
      url: "index.php?page=product&action=addToCart",
      method: "POST",
      dataType: "json",
      data: {
        session_id: session_id,
        sanpham_id: sanpham_id,
        bienthe_id: selectedVariantId,
        quantitys: quantitys,
        sanpham_tieude: sanpham_tieude,
        sanpham_anh: sanpham_anh,
        sanpham_gia: sanpham_gia
      },
      success: function(resp){
        $('.add-cart-btn').prop('disabled', false).html('<i class="fas fa-shopping-cart"></i> <p>MUA HÀNG</p>');
        if (resp && resp.success) {
          // Chuyển thẳng đến giỏ hàng
          window.location.href = 'index.php?page=cart&id=live';   // hoặc 'index.php?page=cart'
        } else {
          var msg = (resp && resp.message) ? resp.message : 'Không thêm được vào giỏ';
          if (resp && resp.max) msg += '. Tối đa: ' + resp.max;
          alert('Lỗi: ' + msg);
        }
      },
      error: function(xhr){
        $('.add-cart-btn').prop('disabled', false).html('<i class="fas fa-shopping-cart"></i> <p>MUA HÀNG</p>');
        alert('Có lỗi: ' + (xhr.statusText || xhr.status));
      }
    });
  });
});
</script>
