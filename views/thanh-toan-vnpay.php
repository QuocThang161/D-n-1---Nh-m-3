<?php require_once 'layout/header.php'; ?>
<main class="container section-padding">
    <div class="row justify-content-center">
        <div class="col-md-8 shadow p-4 bg-white" style="border-top: 5px solid #005baa;">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <img src="https://vinadesign.vn/uploads/images/2023/05/vnpay-logo-vinadesign-25-12-57-55.jpg" width="120">
                <h5>Đơn hàng: #<?= $donHang['ma_don_hang'] ?></h5>
            </div>
            
            <div class="alert alert-info">
                Số tiền cần thanh toán: <strong><?= number_format($donHang['tong_tien']) ?>đ</strong>
            </div>

            <h6>Chọn phương thức thanh toán qua VNPAY:</h6>
            <div class="list-group mb-4">
                <label class="list-group-item d-flex align-items-center">
                    <input type="radio" name="bank" checked class="mr-3"> 
                    <span>Ứng dụng thanh toán hỗ trợ VNPAY-QR</span>
                </label>
                <label class="list-group-item d-flex align-items-center">
                    <input type="radio" name="bank" class="mr-3"> 
                    <span>Thẻ ATM và tài khoản ngân hàng</span>
                </label>
                <label class="list-group-item d-flex align-items-center">
                    <input type="radio" name="bank" class="mr-3"> 
                    <span>Thẻ thanh toán quốc tế (Visa, MasterCard, JCB)</span>
                </label>
            </div>

            <div class="text-center">
                <p>Hệ thống đang kết nối đến cổng thanh toán VNPAY...</p>
                <a href="<?= BASE_URL . '?act=thanh-toan-thanh-cong&id=' . $donHang['id'] ?>" class="btn btn-primary px-5">Tiếp tục thanh toán</a>
            </div>
        </div>
    </div>
</main>
<?php require_once 'layout/footer.php'; ?>