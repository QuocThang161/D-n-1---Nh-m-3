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
            $tongTienMini = 0;
            if (!empty($chiTietGioHangMini)): 
                foreach ($chiTietGioHangMini as $item): 
                    $giaHienTai = $item['gia_khuyen_mai'] > 0 ? $item['gia_khuyen_mai'] : $item['gia_san_pham'];
                    $thanhTien = $giaHienTai * $item['so_luong'];
                    $tongTienMini += $thanhTien;
            ?>
                <li class="minicart-item">
                    <div class="minicart-thumb">
                        <a href="?act=chi-tiet-san-pham&id_san_pham=<?= $item['san_pham_id'] ?>">
                            <img src="<?= BASE_URL . $item['hinh_anh'] ?>" alt="product">
                        </a>
                    </div>
                    <div class="minicart-content">
                        <h3 class="product-name">
                            <a href="?act=chi-tiet-san-pham&id_san_pham=<?= $item['san_pham_id'] ?>"><?= $item['ten_san_pham'] ?></a>
                        </h3>
                        <p>
                            <span class="cart-quantity"><?= $item['so_luong'] ?> <strong>&times;</strong></span>
                            <span class="cart-price"><?= number_format($giaHienTai, 0, ',', '.') ?>đ</span>
                        </p>
                    </div>
                    <a href="<?= BASE_URL . '?act=xoa-gio-hang&id_chi_tiet_gio_hang=' . $item['id'] ?>" 
                        onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này khỏi giỏ hàng không?')"
                        class="minicart-remove">
                        <i class="fa fa-trash-o"></i>
                    </a>
                </li>
            <?php 
                endforeach; 
            else: 
            ?>
                <li class="text-center">Giỏ hàng trống</li>
            <?php endif; ?>
        </ul>
    </div>

    <div class="minicart-pricing-bundle">
        <ul>
            <li><span>Tạm tính</span> <span><?= number_format($tongTienMini, 0, ',', '.') ?>đ</span></li>
            <li><span>Tổng cộng</span> <span class="total-amount"><?= number_format($tongTienMini, 0, ',', '.') ?>đ</span></li>
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