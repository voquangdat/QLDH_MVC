<?php
/**
 * Sidebar/Category Navigation View
 * Data được truyền từ Controller thông qua loadView()
 */

if (!isset($categories)) {
    $categories = [];
}
if (!isset($breadcrumbInfo)) {
    $breadcrumbInfo = null;
}
if (!isset($loaisanpham_id)) {
    $loaisanpham_id = null;
}
?>
    -----------------------CARTEGPRY----------------------------------------------
    <section class="cartegory">
        <div class="container">
            <div class="cartegory-top row">
                <p><a style="color:#000000;" href="index.php">Trang chủ</a></p> <span>&#8594;</span> 
                <p><?php echo isset($breadcrumbInfo['danhmuc_ten']) ? $breadcrumbInfo['danhmuc_ten'] : 'Danh mục'; ?></p>
                <span>&#8594;</span>
                <p><?php echo isset($breadcrumbInfo['loaisanpham_ten']) ? $breadcrumbInfo['loaisanpham_ten'] : 'Loại sản phẩm'; ?></p>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="cartegory-left">
                    <ul>
                    <?php
                        if(is_array($categories) && count($categories) > 0) {
                            foreach($categories as $category) {
                        ?>
                        <li class="cartegory-left-li"><a href="#"><?php echo htmlspecialchars($category['danhmuc_ten']); ?></a>
                            <ul>
                                    <?php
                                      if(isset($category['subCategories']) && is_array($category['subCategories']) && count($category['subCategories']) > 0) {
                                          foreach($category['subCategories'] as $subCategory) {
                                    ?>
                                    <li><a href="index.php?page=category&loaisanpham_id=<?php echo htmlspecialchars($subCategory['loaisanpham_id']); ?>"><?php echo htmlspecialchars($subCategory['loaisanpham_ten']); ?></a></li>
                                    <?php
                                          }
                                      }
                                    ?>
                            </ul>
                        
                        </li>    
                        <?php
                            }
                        }
                        ?>                  
                    </ul>
                </div>  <!-- End cartegory-left -->