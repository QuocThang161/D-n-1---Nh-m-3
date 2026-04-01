<div class="offcanvas-minicart-wrapper">
    <div class="minicart-inner">
        <div class="offcanvas-overlay"></div>
        <div class="minicart-inner-content">
            <div class="minicart-close">
                <i class="pe-7s-close"></i>
            </div>
            <div class="minicart-content-box">
                <div class="minicart-item-wrapper">
                    <ul>
                        <?php 
                        $tongTien = 0;
                        if (isset($chiTietGioHang) && !empty($chiTietGioHang)): 
                            foreach ($chiTietGioHang as $item): 
                                $giaSanPham = $item['gia_khuyen_mai'] ?? $item['gia_san_pham'];
                                $thanhTien = $giaSanPham * $item['so_luong'];
                                $tongTien += $thanhTien;
                        ?>
                            <li class="minicart-item">
                                <div class="minicart-thumb">
                                    <a href="<?= BASE_URL . '?act=chi-tiet-san-pham&id_san_pham=' . $item['san_pham_id'] ?>">
                                        <img src="<?= BASE_URL . $item['hinh_anh'] ?>" alt="product">
                                    </a>
                                </div>
                                <div class="minicart-content">
                                    <h3 class="product-name">
                                        <a href="<?= BASE_URL . '?act=chi-tiet-san-pham&id_san_pham=' . $item['san_pham_id'] ?>"><?= $item['ten_san_pham'] ?></a>
                                    </h3>
                                    <p>
                                        <span class="cart-quantity"><?= $item['so_luong'] ?> <strong>&times;</strong></span>
                                        <span class="cart-price"><?= formatPrice($giaSanPham) ?>đ</span>
                                    </p>
                                </div>
                                <button class="minicart-remove"><i class="pe-7s-close"></i></button>
                            </li>
                        <?php 
                            endforeach; 
                        else: 
                        ?>
                            <li class="text-center">Giỏ hàng trống</li>
                        <?php endif; ?>
                    </ul>
                </div>

                <div class="minicart-pricing-box">
                    <ul>
                        <li>
                            <span>Tạm tính</span>
                            <span><strong><?= formatPrice($tongTien) ?>đ</strong></span>
                        </li>
                        <li>
                            <span>VAT (0%)</span>
                            <span><strong>0đ</strong></span>
                        </li>
                        <li class="total">
                            <span>Tổng giá</span>
                            <span><strong><?= formatPrice($tongTien) ?>đ</strong></span>
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