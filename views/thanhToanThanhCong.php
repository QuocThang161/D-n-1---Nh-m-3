<?php require_once 'layout/header.php'; ?>
<main class="container section-padding" style="margin-top: 80px; margin-bottom: 80px;">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center shadow-lg p-5 bg-white" style="border-radius: 20px;">
            
            <div class="success-checkmark mb-4">
                <div class="check-icon" style="width: 80px; height: 80px; position: relative; border-radius: 50%; background: #28a745; margin: 0 auto; display: flex; align-items: center; justify-content: center;">
                    <span style="color: white; font-size: 50px; font-weight: bold;">&#10003;</span>
                </div>
            </div>
            
            <h2 class="text-success mb-3" style="font-weight: 700;">Thanh toán thành công!</h2>
            <p class="lead text-muted">Cảm ơn bạn đã tin tưởng <strong>TBT Store</strong>.</p>
            
            <div class="card my-4 border-0" style="background: #f1f8f3; border-left: 5px solid #28a745;">
                <div class="card-body py-3">
                    <p class="mb-1 text-dark">Mã đơn hàng: <span class="badge badge-dark" style="background: #333;">#<?= $donHang['ma_don_hang'] ?></span></p>
                    <p class="mb-0 text-dark">Tổng thanh toán: <strong class="text-danger" style="font-size: 1.2rem;"><?= number_format($donHang['tong_tien']) ?>đ</strong></p>
                </div>
            </div>

            <p class="mb-4" style="color: #666;">Thông tin xác nhận đã được gửi vào email của bạn. Vui lòng kiểm tra điện thoại khi shipper liên hệ.</p>

            <div class="d-flex justify-content-center gap-3">
                <a href="<?= BASE_URL ?>" class="btn btn-outline-secondary btn-lg px-4 mr-2" style="border-radius: 30px;">
                    <i class="fas fa-shopping-cart"></i> Tiếp tục mua sắm
                </a>
                <a href="<?= BASE_URL . '?act=chi-tiet-mua-hang&id=' . $donHang['id'] ?>" class="btn btn-success btn-lg px-4" style="border-radius: 30px; background: #28a745;">
                    Xem đơn hàng
                </a>
            </div>
        </div>
    </div>
</main>
<?php require_once 'layout/footer.php'; ?>