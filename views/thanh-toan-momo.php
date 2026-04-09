<?php require_once 'layout/header.php'; ?>
<main class="container section-padding text-center">
    <div class="row justify-content-center">
        <div class="col-md-5 shadow-lg p-4" style="border-radius: 20px; background: #ae1d6e; color: white;">
            <img src="https://cdn.haitrieu.com/wp-content/uploads/2022/10/Logo-MoMo-Square.png" width="80" class="mb-3">
            <h4>Thanh toán qua ví MoMo</h4>
            <hr style="border-color: rgba(255,255,255,0.2)">
            
            <div class="bg-white p-3 mb-3" style="border-radius: 10px;">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=MoMo_Payment_For_Order_<?= $donHang['ma_don_hang'] ?>" alt="QR MoMo">
                <p>Số tiền: <strong><?= isset($donHang['tong_tien']) ? number_format($donHang['tong_tien']) : 0 ?>đ</strong></p>
            </div>

            <p>Vui lòng quét mã để hoàn tất đơn hàng #<?= $donHang['ma_don_hang'] ?? 'N/A' ?></p>
            <a href="<?= BASE_URL . '?act=thanh-toan-thanh-cong&id=' . $donHang['id'] ?>" class="btn btn-light w-300 mt-3" style="color: #150f13; font-weight: bold;">Xác nhận đã thanh toán</a>
        </div>
    </div>
</main>
<?php require_once 'layout/footer.php'; ?>