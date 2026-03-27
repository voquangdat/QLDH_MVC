<?php
    include "header.php";
?>
<link rel="stylesheet" href="css/lienhe.css">
<section class="contact-section">
        <div class="container">
            <div class="row">
                <!-- Phần thông tin sinh viên bên trái -->
                <div class="student-info">
                    <h2>THÔNG TIN SINH VIÊN</h2>
    
                    <div class="student">
                        <img src="image/avt1.jpg" alt="Võ Hoài Nam" class="student-image">
                        <h3>Võ Hoài Nam</h3>
                        <p><b>Ngày sinh:</b> 01/01/2004</p>
                        <p><b>Mã số sinh viên:</b> 2251150061</p>
                        <p><b>Lớp:</b> KM22B</p>
                    </div>
    
                    <div class="student">
                        <img src="image/avt2.jpg" alt="Võ Quang Đạt" class="student-image">
                        <h3>Võ Quang Đạt</h3>
                        <p><b>Ngày sinh:</b> 03/07/2004</p>
                        <p><b>Mã số sinh viên:</b> 2251330009</p>
                        <p><b>Lớp:</b> HT22</p>
                    </div>
                </div>
    
                <!-- Form liên hệ bên phải -->
                <div class="contact-form">
                    <h2>LIÊN HỆ</h2>
                    <form>
                        <div class="form-group">
                            <input type="text" placeholder="Họ tên" required>
                            <input type="text" placeholder="Số điện thoại" required>
                        </div>
                        <div class="form-group">
                            <input type="text" placeholder="Địa chỉ" required>
                            <input type="email" placeholder="Email" required>
                        </div>
                        <input type="text" placeholder="Chủ đề" required>
                        <textarea placeholder="Nội dung" rows="5" required></textarea>
                        <button type="submit">Gửi</button>
                        <button type="reset">Nhập lại</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <?php
     include "footer.php"
    ?>