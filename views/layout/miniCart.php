<!-- offcanvas mini cart start -->
<?php $cartSummary = getCartSummary(); ?>
<div class="offcanvas-minicart-wrapper">
    <div class="minicart-inner">
        <div class="offcanvas-overlay"></div>
        <div class="minicart-inner-content">
            <div class="minicart-close">
                <i class="pe-7s-close"></i>
            </div>
            <div class="minicart-content-box">
                <div class="minicart-item-wrapper">
                    <?php if (empty($cartSummary['items'])) : ?>
                    <div class="text-center" style="padding: 20px;">Giỏ hàng của bạn đang trống.</div>
                    <?php else : ?>
                    <ul>
                        <?php foreach ($cartSummary['items'] as $item) :
                                $unitPrice = !empty($item['gia_khuyen_mai']) ? $item['gia_khuyen_mai'] : $item['gia_san_pham'];
                                $itemTotal = $unitPrice * $item['so_luong_gio'];
                            ?>
                        <li class="minicart-item">
                            <div class="minicart-thumb">
                                <a
                                    href="<?= BASE_URL . '?act=chi-tiet-san-pham&id_san_pham=' . $item['san_pham_id'] ?>">
                                    <img src="<?= BASE_URL . $item['hinh_anh'] ?>"
                                        alt="<?= htmlspecialchars($item['ten_san_pham']) ?>">
                                </a>
                            </div>
                            <div class="minicart-content">
                                <h3 class="product-name">
                                    <a
                                        href="<?= BASE_URL . '?act=chi-tiet-san-pham&id_san_pham=' . $item['san_pham_id'] ?>"><?= htmlspecialchars($item['ten_san_pham']) ?></a>
                                </h3>
                                <p>
                                    <span class="cart-quantity"><?= intval($item['so_luong_gio']) ?>
                                        <strong>&times;</strong></span>
                                    <span class="cart-price"><?= formatPrice($itemTotal) ?> đ</span>
                                </p>
                            </div>
                            <a class="minicart-remove"
                                href="<?= BASE_URL . '?act=xoa-san-pham-gio-hang&id_san_pham=' . $item['san_pham_id'] ?>"
                                onclick="return confirm('Bạn có chắc muốn xoá sản phẩm này khỏi giỏ hàng?');"><i
                                    class="pe-7s-close"></i></a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <?php endif; ?>
                </div>

                <div class="minicart-pricing-box">
                    <ul>
                        <li>
                            <span>Tổng tiền</span>
                            <span><strong><?= formatPrice($cartSummary['subtotal']) ?> đ</strong></span>
                        </li>
                        <li>
                            <span>Phí vận chuyển</span>
                            <span><strong>30.000 đ</strong></span>
                        </li>
                        <li class="total">
                            <span>Thanh toán</span>
                            <span><strong><?= formatPrice($cartSummary['subtotal'] + 30000) ?> đ</strong></span>
                        </li>
                    </ul>
                </div>

                <div class="minicart-button">
                    <a href="<?= BASE_URL . '?act=gio-hang' ?>"><i class="fa fa-shopping-cart"></i> Xem giỏ hàng</a>
                    <a href="<?= BASE_URL . '?act=thanh-toan' ?>"><i class="fa fa-share"></i> Thanh toán</a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- offcanvas mini cart end -->