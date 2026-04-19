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
                                <li class="breadcrumb-item"><a href="<?= BASE_URL?>"><i class="fa fa-home"></i></a></li>
                                <li class="breadcrumb-item"><a href="<?= BASE_URL . '?act=cua-hang' ?>">Cửa hàng</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Giỏ hàng</li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- breadcrumb area end -->

    <!-- cart main wrapper start -->
    <div class="cart-main-wrapper section-padding">
        <div class="container">
            <div class="section-bg-color">
                <div class="row">
                    <div class="col-12">
                        <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger">
                            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                        </div>
                        <?php endif; ?>

                        <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success">
                            <?= $_SESSION['success']; unset($_SESSION['success']); ?>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="col-lg-12">
                        <!-- Cart Table Area -->
                        <div class="cart-table table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="pro-thumbnail">Ảnh sản phẩm</th>
                                        <th class="pro-title">Tên sản phẩm</th>
                                        <th class="pro-title">Phiên bản</th>
                                        <th class="pro-price">Giá tiền</th>
                                        <th class="pro-quantity">Số lượng</th>
                                        <th class="pro-subtotal">Tổng tiền</th>
                                        <th class="pro-remove">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                            $tongGioHang = 0; 
                                            foreach($chiTietGioHang as $key=>$sanPham): 
                                        ?>
                                    <tr class="cart-item-row">
                                        <td class="pro-thumbnail"><a href="#"><img class="img-fluid"
                                                    src="<?= BASE_URL . $sanPham['hinh_anh'] ?>" alt="Product" /></a>
                                        </td>
                                        <td class="pro-title"><a href="#"><?= $sanPham['ten_san_pham'] ?></a></td>
                                        <td class="pro-title">
                                            <?php 
                                            $variantInfo = '';
                                            if (!empty($sanPham['mau_sac'])) {
                                                $variantInfo .= 'Màu: ' . htmlspecialchars($sanPham['mau_sac']);
                                            }
                                            if (!empty($sanPham['bien_the_size'])) {
                                                $variantInfo .= (!empty($variantInfo) ? ' / ' : '') . 'Size: ' . htmlspecialchars($sanPham['bien_the_size']);
                                            }
                                            echo !empty($variantInfo) ? $variantInfo : 'Không có biến thể';
                                            ?>
                                        </td>
                                        <td class="pro-price"><span>
                                                <?php 
                                                $price = $sanPham['gia_khuyen_mai'] ?? $sanPham['gia_san_pham'];
                                                echo formatPrice($price) . ' đ';
                                                ?>
                                            </span></td>
                                        <td class="pro-quantity">
                                            <form action="<?= BASE_URL . '?act=update-so-luong-gio-hang' ?>"
                                                method="POST">
                                                <input type="hidden" name="id_san_pham"
                                                    value="<?= $sanPham['san_pham_id'] ?>">
                                                <input type="hidden" name="id_bien_the"
                                                    value="<?= $sanPham['san_pham_bien_the_id'] ?? '' ?>">
                                                <div
                                                    class="quantity-control d-flex align-items-center justify-content-center">
                                                    <!-- Nút giảm số lượng: thuần PHP, không background, icon đen -->
                                                    <button type="submit" name="so_luong"
                                                        value="<?= $sanPham['so_luong_gio'] - 1 ?>"
                                                        style="border: none; background: none; color: black; cursor: pointer; padding: 5px;">
                                                        <i class="fa fa-minus" style="font-size: 12px;"></i>
                                                    </button>

                                                    <!-- Hiển thị số lượng hiện tại ở giữa -->
                                                    <span class="mx-3 fw-bold"
                                                        style="min-width: 20px; display: inline-block; text-align: center;">
                                                        <?= $sanPham['so_luong_gio'] ?>
                                                    </span>

                                                    <!-- Nút tăng số lượng: thuần PHP, không background, icon đen -->
                                                    <button type="submit" name="so_luong"
                                                        value="<?= $sanPham['so_luong_gio'] + 1 ?>"
                                                        style="border: none; background: none; color: black; cursor: pointer; padding: 5px;">
                                                        <i class="fa fa-plus" style="font-size: 12px;"></i>
                                                    </button>
                                                </div>
                                            </form>
                                        </td>
                                        <td class="pro-subtotal"><span class="item-subtotal">
                                                <?php 
                                                    $tongTien = $price * $sanPham['so_luong_gio'];
                                                    $tongGioHang += $tongTien;
                                                    echo formatPrice($tongTien) . ' đ';
                                                ?>
                                            </span></td>
                                        <td class="pro-remove">
                                            <a href="<?= BASE_URL . '?act=xoa-san-pham-gio-hang&id_san_pham=' . $sanPham['san_pham_id'] . '&id_bien_the=' . ($sanPham['san_pham_bien_the_id'] ?? '') ?>"
                                                onclick="return confirm('Bạn có muốn xoá sản phẩm này không?')">
                                                <i class="fa fa-trash-o"></i></a>
                                        </td>
                                    </tr>
                                    <?php endforeach ?>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-5 ml-auto">
                        <!-- Cart Calculation Area -->
                        <div class="cart-calculator-wrapper">
                            <div class="cart-calculate-items">
                                <h6>Tổng đơn hàng</h6>
                                <div class="table-responsive">
                                    <table class="table">
                                        <tr>
                                            <td>Tổng tiền sản phẩm</td>
                                            <td class="cart-total-amount"><?= formatPrice($tongGioHang) . 'đ' ?></td>
                                        </tr>
                                        <tr>
                                            <td>Vận chuyển</td>
                                            <td>30.000 đ</td>
                                        </tr>
                                        <tr class="total">
                                            <td>Tổng thanh toán</td>
                                            <td class="grand-total-amount">
                                                <?= formatPrice($tongGioHang + 30000) . 'đ' ?></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <a href="<?= BASE_URL . '?act=thanh-toan' ?>" class="btn btn-sqr d-block">Tiến hành đặt
                                hàng</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- cart main wrapper end -->
</main>


<?php require_once 'layout/miniCart.php'; ?>
<?php require_once 'layout/footer.php'; ?>