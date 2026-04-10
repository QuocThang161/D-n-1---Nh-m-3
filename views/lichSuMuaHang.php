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
                                <li class="breadcrumb-item">
                                    <a href="<?= BASE_URL ?>"><i class="fa fa-home"></i></a>
                                </li>
                                <li class="breadcrumb-item">Shop</li>
                                <li class="breadcrumb-item active">Lịch sử mua hàng</li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- cart main wrapper start -->
    <div class="cart-main-wrapper section-padding">
        <div class="container">
            <div class="section-bg-color">
                <div class="row">
                    <div class="col-lg-12">

                        <div class="cart-table table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Mã đơn hàng</th>
                                        <th>Ngày đặt</th>
                                        <th>Tổng tiền</th>
                                        <th>Thanh toán</th>
                                        <th>Trạng thái</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php if (!empty($donHangs)) : ?>
                                    <?php foreach ($donHangs as $donHang) : ?>
                                    <tr>
                                        <td class="text-center"><?= $donHang['ma_don_hang']; ?></td>
                                        <td><?= $donHang['ngay_dat']; ?></td>
                                        <td><?= formatPrice($donHang['tong_tien']) . 'đ'; ?></td>

                                        <td>
                                            <?= $phuongThucThanhToan[$donHang['phuong_thuc_thanh_toan_id']] ?? '' ?>
                                        </td>

                                        <td>
                                            <?= $trangThaiDonHang[$donHang['trang_thai_id']] ?? '' ?>
                                        </td>

                                        <td>
                                            <a href="<?= BASE_URL ?>?act=chi-tiet-mua-hang&id=<?= $donHang['id'] ?>"
                                                class="btn btn-sqr">
                                                Chi tiết đơn hàng
                                            </a>

                                            <?php if ($donHang['trang_thai_id'] == 1): ?>
                                            <a href="<?= BASE_URL ?>?act=huy-don-hang&id=<?= $donHang['id'] ?>"
                                                class="btn btn-sqr"
                                                onclick="return confirm('Bạn có chắc chắn muốn huỷ đơn hàng này?')">
                                                Huỷ
                                            </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>

                                    <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center">
                                            Chưa có đơn hàng nào
                                        </td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>

                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require_once 'layout/miniCart.php'; ?>
<?php require_once 'layout/footer.php'; ?>