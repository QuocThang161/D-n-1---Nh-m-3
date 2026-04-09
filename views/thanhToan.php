<?php require_once 'layout/header.php'; ?>

<?php require_once 'layout/menu.php'; ?>

<main>
    <!-- breadcrumb area start -->
    <div class="breadcrumb-area">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="breadcrumb-wrap">
                        <nav aria-label="breadcrumb">
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="<?= BASE_URL ?>"><i class="fa fa-home"></i></a></li>
                                <li class="breadcrumb-item active" aria-current="page">Thanh toán</li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- breadcrumb area end -->

    <!-- checkout main wrapper start -->
    <div class="checkout-page-wrapper section-padding">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <!-- Checkout Login Coupon Accordion Start -->
                    <div class="checkoutaccordion" id="checkOutAccordion">


                        <div class="card">
                            <h6>Thêm mã giảm giá ? <span data-bs-toggle="collapse" data-bs-target="#couponaccordion">Click
                                    Nhập mã giảm giá</span></h6>
                            <div id="couponaccordion" class="collapse" data-parent="#checkOutAccordion">
                                <div class="card-body">
                                    <div class="cart-update-option">
                                        <div class="apply-coupon-wrapper">
                                            <form action="#" method="post" class=" d-block d-md-flex">
                                                <input type="text" placeholder="Enter Your Coupon Code" required />
                                                <button class="btn btn-sqr">Apply Coupon</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Checkout Login Coupon Accordion End -->
                </div>
            </div>
            <form action="<?= BASE_URL . '?act=xu-ly-thanh-toan' ?>" method="POST">
                <div class="row">
                    <!-- Checkout Billing Details -->
                    <div class="col-lg-6">
                        <div class="checkout-billing-details-wrap">
                            <h5 class="checkout-title">Thông tin người nhận</h5>
                            <div class="billing-form-wrap">

                                <div class="single-input-item">
                                    <label for="ten_nguoi_nhan" class="required">Tên người nhận</label>
                                    <input type="text" id="ten_nguoi_nhan" name="ten_nguoi_nhan" value="<?= $user['ho_ten'] ?>" placeholder="Tên người nhận" required />
                                </div>

                                <div class="single-input-item">
                                    <label for="email_nguoi_nhan" class="required">Địa chỉ email</label>
                                    <input type="email" id="email_nguoi_nhan" name="email_nguoi_nhan" value="<?= $user['email'] ?>" placeholder="Địa chỉ email" required />
                                </div>

                                <div class="single-input-item">
                                    <label for="sdt_nguoi_nhan" class="required">Số điện thoại</label>
                                    <input type="number" id="sdt_nguoi_nhan" name="sdt_nguoi_nhan" value="<?= $user['so_dien_thoai'] ?>" placeholder="Số điện thoại" required />
                                </div>

                                <div class="single-input-item">
                                    <label for="dia_chi_nguoi_nhan" class="required">Địa chỉ</label>
                                    <input type="text" id="dia_chi_nguoi_nhan" name="dia_chi_nguoi_nhan" value="<?= $user['dia_chi'] ?>" placeholder="Địa chỉ" required />
                                </div>


                                <div class="single-input-item">
                                    <label for="ghi_chu">Ghi chú</label>
                                    <textarea name="ghi_chu" id="ghi_chu" cols="30" rows="3" placeholder="Ghi chú đơn hàng của bạn"></textarea>
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- Order Summary Details -->
                    <div class="col-lg-6">
                        <div class="order-summary-details">
                            <h5 class="checkout-title">Thông tin sản phẩm</h5>
                            <div class="order-summary-content">
                                <!-- Order Summary Table -->
                                <div class="order-summary-table table-responsive text-center">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Sản phẩm</th>
                                                <th>Tổng</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php
                                            $tongGioHang = 0;
                                            if (!isset($chiTietGioHang) || !is_array($chiTietGioHang) || count($chiTietGioHang) === 0):
                                                echo '<tr><td colspan="6" class="text-center important">Giỏ hàng của bạn hiện đang trống.</td></tr>';
                                            else:
                                                foreach ($chiTietGioHang as $key => $sanPham):
                                            ?>
                                                    <tr>
                                                        <td><a href="">
                                                                <?= $sanPham['ten_san_pham'] ?><strong> <?= $sanPham['so_luong'] ?></strong>
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <?php
                                                            if ($sanPham['gia_khuyen_mai']) {
                                                                $tongTien = $sanPham['gia_khuyen_mai'] * $sanPham['so_luong'];
                                                            } else {
                                                                $tongTien = $sanPham['gia_san_pham'] * $sanPham['so_luong'];
                                                            }
                                                            $tongGioHang += $tongTien;
                                                            echo number_format($tongTien, 0, ',', '.') . 'đ';
                                                            ?>
                                                        </td>
                                                    </tr>
                                            <?php endforeach;
                                            endif; ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td>Tổng tiền sản phẩm</td>

                                                <td><strong><?= number_format($tongGioHang, 0, ',', '.') . 'đ' ?></strong></td>
                                            </tr>
                                            <tr>
                                                <td>Shipping</td>
                                                <td class="d-flex justify-content-center">
                                                    <strong>30.000đ</strong>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Tổng đơn hàng</td>
                                                <input type="hidden" name="tong_tien" value="<?= $tongGioHang + 30000 ?>">
                                                <td><strong><?= number_format($tongGioHang + 30000, 0, ',', '.') . 'đ' ?></strong></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <!-- Order Payment Method -->
                                <div class="order-payment-method">
                                    <div class="single-payment-method show">
                                        <div class="payment-method-name">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="cashon" value="1" name="phuong_thuc_thanh_toan_id" class="custom-control-input" checked />
                                                <label class="custom-control-label" for="cashon">
                                                    <i class="fa fa-truck text-primary mr-2"></i> Thanh toán khi nhận hàng (COD)
                                                </label>
                                            </div>
                                        </div>
                                        <div class="payment-method-details" data-method="cash">
                                            <p>Khách hàng thanh toán tiền mặt cho nhân viên giao hàng sau khi kiểm tra sản phẩm thành công.</p>
                                        </div>
                                    </div>

                                    <div class="single-payment-method">
                                        <div class="payment-method-name">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="directbank" name="phuong_thuc_thanh_toan_id" value="2" class="custom-control-input" />
                                                <label class="custom-control-label" for="directbank">
                                                    <i class="fa fa-university text-primary mr-4"></i> Chuyển khoản ngân hàng (QR Code)
                                                </label>
                                            </div>
                                        </div>
                                        <div class="payment-method-details" data-method="bank">
                                            <p>Vui lòng chuyển khoản vào số tài khoản sau:</p>
                                            <ul class="ml-3 mb-2" style="list-style-type: disc;">
                                                <li>Số tài khoản: <strong>2255926102006</strong></li>
                                                <li>Ngân hàng: <strong>MB Bank</strong></li>
                                                <li>Chủ TK: <strong>NGUYEN XUAN BAC</strong></li>
                                                <li>Nội dung: <strong>TBT <?= time() ?></strong> (Mã đơn tạm thời)</li>
                                            </ul>
                                            <p class="text-danger small">* Đơn hàng sẽ được xử lý sau khi nhận được tiền.</p>
                                        </div>
                                    </div>

                                    <div class="single-payment-method">
                                        <div class="payment-method-name">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="momo" name="phuong_thuc_thanh_toan_id" value="3" class="custom-control-input" />
                                                <label class="custom-control-label d-flex align-items-center" for="momo">
                                                    <img src="https://cdn.haitrieu.com/wp-content/uploads/2022/10/Logo-MoMo-Square.png" width="20" class="mr-2" alt="MoMo">
                                                    Thanh toán qua Ví MoMo
                                                </label>
                                            </div>
                                        </div>
                                        <div class="payment-method-details" data-method="momo">
                                            <p>Hệ thống sẽ kết nối với ứng dụng MoMo để bạn thực hiện thanh toán an toàn.</p>
                                        </div>
                                    </div>

                                    <div class="single-payment-method">
                                        <div class="payment-method-name">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="vnpay" name="phuong_thuc_thanh_toan_id" value="4" class="custom-control-input" />
                                                <label class="custom-control-label d-flex align-items-center" for="vnpay">
                                                    <img src="https://vinadesign.vn/uploads/images/2023/05/vnpay-logo-vinadesign-25-12-57-55.jpg" width="20" class="mr-2" alt="VNPAY">
                                                    Thanh toán qua VNPAY (ATM/Visa/MasterCard)
                                                </label>
                                            </div>
                                        </div>
                                        <div class="payment-method-details" data-method="vnpay">
                                            <p>Thanh toán cực nhanh qua ứng dụng ngân hàng hoặc thẻ quốc tế bằng cổng VNPAY.</p>
                                        </div>
                                    </div>

                                    <div class="summary-footer-area mt-4">
                                        <div class="custom-control custom-checkbox mb-20">
                                            <input type="checkbox" class="custom-control-input" id="terms" required />
                                            <label class="custom-control-label" for="terms">Tôi đã kiểm tra thông tin và xác nhận đặt hàng</label>
                                        </div>
                                        <button type="submit" class="btn btn-sqr w-100">Tiến hành đặt hàng</button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- checkout main wrapper end -->
</main>

<script>
$(document).ready(function () {
    // Ẩn tất cả các chi tiết thanh toán ngoại trừ cái đang được chọn (mặc định là COD)
    $('.payment-method-details').hide();
    $('.single-payment-method.show .payment-method-details').show();

    // Lắng nghe sự kiện click vào radio button
    $('input[name="phuong_thuc_thanh_toan_id"]').on('change', function () {
        // Ẩn toàn bộ các vùng chi tiết cũ
        $('.payment-method-details').slideUp();
        
        // Hiển thị vùng chi tiết của phương thức vừa chọn
        $(this).closest('.single-payment-method').find('.payment-method-details').slideDown();
        
        // Thêm/Xóa class 'show' để đồng bộ giao diện
        $('.single-payment-method').removeClass('show');
        $(this).closest('.single-payment-method').addClass('show');
    });
});
</script>



<?php require_once 'layout/miniCart.php'; ?>

<?php require_once 'layout/footer.php'; ?>