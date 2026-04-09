<?php require_once 'layout/header.php'; ?>
<?php require_once 'layout/menu.php'; ?>

<main>
    <div class="breadcrumb-area">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <div class="breadcrumb-wrap">
                        <nav aria-label="breadcrumb">
                            <ul class="breadcrumb justify-content-center">
                                <li class="breadcrumb-item"><a href="<?= BASE_URL ?>"><i class="fa fa-home"></i></a></li>
                                <li class="breadcrumb-item active">Hoàn tất thanh toán</li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="checkout-page-wrapper section-padding">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="order-summary-details text-center shadow-sm p-5" style="border-radius: 15px; background: #fff;">
                        <h4 class="checkout-title mb-4" style="color: #c29958;">🎉 Đặt hàng thành công!</h4>
                        <p class="mb-4">Cảm ơn <strong><?= $donHang['ten_nguoi_nhan'] ?></strong> đã tin tưởng TBT Store. <br> Vui lòng thực hiện thanh toán để chúng tôi sớm gửi hàng cho bạn.</p>

                        <div class="row align-items-center">
                            <div class="col-md-6 mb-4">
                                <div class="qr-wrapper p-3 border" style="border-radius: 10px;">
                                    <h6 class="mb-3">Quét mã QR để thanh toán</h6>
                                    <img src="https://img.vietqr.io/image/mbbank-2255926102006-compact.jpg?amount=<?= $donHang['tong_tien'] ?>&addInfo=TBT%20<?= $donHang['ma_don_hang'] ?>&accountName=NGUYEN%20XUAN%20BAC" 
                                         alt="QR Thanh Toan" class="img-fluid" style="max-width: 250px;">
                                    <p class="mt-2 small text-muted">Hỗ trợ mọi ứng dụng Ngân hàng & Ví điện tử</p>
                                </div>
                            </div>

                            <div class="col-md-6 text-left">
                                <div class="bank-info bg-light p-4" style="border-radius: 10px; border-left: 5px solid #c29958;">
                                    <ul class="list-unstyled">
                                        <li class="mb-2">Ngân hàng: <strong>MB BANK (Quân Đội)</strong></li>
                                        <li class="mb-2">Số tài khoản: <strong class="text-danger" style="font-size: 1.2rem;">2255926102006</strong></li>
                                        <li class="mb-2">Chủ tài khoản: <strong>KIEU XUAN BAC</strong></li>
                                        <li class="mb-2">Số tiền: <strong class="text-primary"><?= number_format($donHang['tong_tien'], 0, ',', '.') ?>đ</strong></li>
                                        <li class="mb-2">Nội dung chuyển khoản: <strong class="text-danger"><?= $donHang['ma_don_hang'] ?></strong></li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-warning mt-4 text-left" role="alert">
                            <i class="fa fa-info-circle"></i> <strong>Lưu ý:</strong> Sau khi chuyển khoản thành công, hệ thống sẽ xác nhận đơn hàng trong vòng 5-15 phút. Bạn có thể theo dõi trạng thái tại trang lịch sử mua hàng.
                        </div>

                        <div class="group-button mt-4">
                            <a href="<?= BASE_URL ?>" class="btn btn-secondary mr-2">Quay lại trang chủ</a>
                            <a href="<?= BASE_URL . '?act=lich-su-mua-hang' ?>" class="btn btn-sqr">Xem lịch sử đơn hàng</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require_once 'layout/footer.php'; ?>