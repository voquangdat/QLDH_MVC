<?php
// Sử dụng CategoryController để lấy dữ liệu
$categoryController = new CategoryController();
$categories = $categoryController->getMenuData();

$loaisanpham_id = isset($_GET['loaisanpham_id']) ? $_GET['loaisanpham_id'] : null;

// Lấy thông tin breadcrumb nếu có loaisanpham_id
$breadcrumbInfo = null;
if ($loaisanpham_id) {
    $categoryModel = new CategoryModel();
    $breadcrumbResult = $categoryModel->getCategoryInfo($loaisanpham_id);
    if ($breadcrumbResult) {
        $breadcrumbInfo = $breadcrumbResult->fetch_assoc();
    }
}
?>
    <!-- -----------------------CARTEGPRY---------------------------------------------- -->
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
                        if($categories && $categories->num_rows > 0) {
                            while($category = $categories->fetch_assoc()) {
                        ?>
                        <li class="cartegory-left-li"><a href="#"><?php echo $category['danhmuc_ten'] ?></a>
                            <ul>
                                    <?php
                                      $danhmuc_id = $category['danhmuc_id'];
                                      $subCategories = $categoryController->getSubCategories($danhmuc_id);
                                      if($subCategories && $subCategories->num_rows > 0) {
                                          while($subCategory = $subCategories->fetch_assoc()) {
                                    ?>
                                    <li><a href="index.php?page=category&loaisanpham_id=<?php echo $subCategory['loaisanpham_id'] ?>"><?php echo $subCategory['loaisanpham_ten'] ?></a></li>
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
                </div>