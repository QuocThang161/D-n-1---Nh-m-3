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
                                <li class="breadcrumb-item"><a href="index.html"><i class="fa fa-home"></i></a></li>
                                <li class="breadcrumb-item"><a href="shop.html">shop</a></li>
                                <li class="breadcrumb-item active" aria-current="page">cart</li>
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
                    <div class="col-lg-12">
                        <!-- Cart Table Area -->
                        <div class="cart-table table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="pro-thumbnail">Ảnh sản phẩm</th>
                                        <th class="pro-title">Tên sản phẩm</th>
                                        <th class="pro-price">Giá</th>
                                        <th class="pro-quantity">Số lượng</th>
                                        <th class="pro-subtotal">Tổng cộng</th>
                                        <th class="pro-remove">Xóa</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php
                                    $tongGioHang = 0;
                                    if (!isset($chiTietGioHang) || !is_array($chiTietGioHang) || count($chiTietGioHang) === 0):
                                        echo '<tr><td colspan="6" class="text-center">Giỏ hàng của bạn hiện đang trống.</td></tr>';
                                    else:
                                        foreach ($chiTietGioHang as $key => $sanPham): ?>
                                            <tr class="cart-item">
                                                <td class="pro-thumbnail"><a href="#"><img class="img-fluid" src="<?= BASE_URL . $sanPham['hinh_anh'] ?>" alt="Product" /></a></td>
                                                <td class="pro-title"><a href="#"><?= $sanPham['ten_san_pham'] ?></a></td>

                                                <td class="pro-price">
                                                    <span class="price-value" data-price="<?= $sanPham['gia_khuyen_mai'] > 0 ? $sanPham['gia_khuyen_mai'] : $sanPham['gia_san_pham'] ?>">
                                                        <?php if ($sanPham['gia_khuyen_mai'] > 0): ?>
                                                            <del><?= number_format($sanPham['gia_san_pham'], 0, ',', '.') . 'đ' ?></del>
                                                            <?= number_format($sanPham['gia_khuyen_mai'], 0, ',', '.') . 'đ' ?>
                                                        <?php else: ?>
                                                            <?= number_format($sanPham['gia_san_pham'], 0, ',', '.') . 'đ' ?>
                                                        <?php endif; ?>
                                                    </span>
                                                </td>

                                                <td class="pro-quantity">
                                                    <div class="pro-qty"><input type="text" class="quantity-input" data-id="<?= $sanPham['san_pham_id'] ?>" value="<?= $sanPham['so_luong'] ?>"></div>
                                                </td>

                                                <td class="pro-subtotal"><span class="row-total">
                                                        <?php
                                                        $currentPrice = $sanPham['gia_khuyen_mai'] > 0 ? $sanPham['gia_khuyen_mai'] : $sanPham['gia_san_pham'];
                                                        $tongTien = $currentPrice * $sanPham['so_luong'];
                                                        $tongGioHang += $tongTien;
                                                        echo number_format($tongTien, 0, ',', '.') . 'đ';
                                                        ?>
                                                    </span></td>
                                                <td class="pro-remove"><a href="#"><i class="fa fa-trash-o"></i></a></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- Cart Update Option -->
                        <div class="cart-update-option d-block d-md-flex justify-content-between">
                            <div class="apply-coupon-wrapper">
                                <form action="#" method="post" class=" d-block d-md-flex">
                                    <input type="text" placeholder="Enter Your Coupon Code" required />
                                    <button class="btn btn-sqr">Apply Coupon</button>
                                </form>
                            </div>
                            <div class="cart-update">
                                <!-- <a href="#" class="btn btn-sqr">Update Cart</a> -->
                            </div>
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
                                            <td class="sub-total-display"><?= number_format($tongGioHang, 0, ',', '.') . ' đ' ?></td>
                                        </tr>
                                        <tr>
                                            <td>Vận chuyển</td>
                                            <td><?= number_format(30000, 0, ',', '.') . ' đ' ?></td>
                                        </tr>
                                        <tr class="total">
                                            <td>Tổng thanh toán</td>
                                            <td class="total-amount final-total-display"><?= number_format($tongGioHang + 30000, 0, ',', '.') . ' đ' ?></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <a href="<?= BASE_URL . '?act=thanh-toan' ?>" class="btn btn-sqr d-block">Tiến hành đặt hàng</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- cart main wrapper end -->
</main>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const shippingFee = 30000;

        // 1. Hàm định dạng tiền VNĐ
        function formatVNĐ(num) {
            return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.') + ' đ';
        }

        // 2. Hàm AJAX gửi dữ liệu lên Server (Để riêng bên ngoài)
        function updateQuantityAjax(productId, newQty) {
            fetch('<?= BASE_URL . "?act=update-so-luong-gio-hang" ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'product_id=' + productId + '&quantity=' + newQty
            })
            .then(response => response.json())
            .then(data => {
                if(data.status === 'success') {
                    console.log('Đã đồng bộ ID ' + productId + ' với số lượng ' + newQty);
                }
            })
            .catch(error => console.error('Lỗi kết nối:', error));
        }

        // 3. Hàm cập nhật giao diện và gọi AJAX
        function updateCart() {
            let totalCart = 0;

            document.querySelectorAll('.cart-item').forEach(function(row) {
                let priceElement = row.querySelector('.price-value');
                let qtyElement = row.querySelector('.quantity-input');
                let rowTotalElement = row.querySelector('.row-total');

                if (priceElement && qtyElement) {
                    let price = parseInt(priceElement.getAttribute('data-price'));
                    let quantity = parseInt(qtyElement.value);
                    let productId = qtyElement.getAttribute('data-id');

                    if (isNaN(quantity) || quantity < 1) {
                        quantity = 1;
                        qtyElement.value = 1;
                    }

                    // Tính tiền từng dòng
                    let rowTotal = price * quantity;
                    rowTotalElement.innerText = formatVNĐ(rowTotal);
                    totalCart += rowTotal;

                    // GỌI AJAX ĐỂ LƯU VÀO SESSION
                    updateQuantityAjax(productId, quantity);
                }
            });

            // Cập nhật bảng tổng tiền phía dưới
            const subTotalBox = document.querySelector('.sub-total-display');
            const finalTotalBox = document.querySelector('.final-total-display');

            if (subTotalBox) subTotalBox.innerText = formatVNĐ(totalCart);
            if (finalTotalBox) finalTotalBox.innerText = formatVNĐ(totalCart + shippingFee);
        }

        // 4. Sự kiện Click nút + - của Template
        document.querySelector('.cart-table').addEventListener('click', function(e) {
            if (e.target.classList.contains('qtybtn')) {
                // Đợi template cập nhật số vào input rồi mới tính
                setTimeout(updateCart, 50);
            }
        });

        // 5. Sự kiện khi tự nhập số vào ô input
        document.querySelectorAll('.quantity-input').forEach(function(input) {
            input.addEventListener('change', updateCart);
        });
    });
</script>

<?php require_once 'layout/miniCart.php' ?>

<?php

require_once 'layout/footer.php'

?>