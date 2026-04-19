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
                                <li class="breadcrumb-item"><a href="<?= BASE_URL ?>"><i class="fa fa-home"></i></a>
                                </li>
                                <li class="breadcrumb-item"><a href="<?= BASE_URL . '?act=cua-hang' ?>">Sản phẩm</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Chi tiết sản phẩm</li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- breadcrumb area end -->

    <!-- page main wrapper start -->
    <div class="shop-main-wrapper section-padding pb-0">
        <div class="container">
            <div class="row">
                <!-- product details wrapper start -->
                <div class="col-lg-12 order-1 order-lg-2">
                    <!-- product details inner end -->
                    <div class="product-details-inner">
                        <div class="row">
                            <div class="col-lg-5">
                                <div class="product-large-slider">
                                    <?php foreach ($listAnhSanPham as $key => $anhSanPham): ?>
                                    <div class="pro-large-img img-zoom">
                                        <img src="<?= BASE_URL . $anhSanPham['link_hinh_anh'] ?>"
                                            alt="product-details" />
                                    </div>
                                    <?php endforeach ?>
                                </div>
                                <div class="pro-nav slick-row-10 slick-arrow-style">
                                    <?php foreach ($listAnhSanPham as $key => $anhSanPham): ?>
                                    <div class="pro-nav-thumb">
                                        <img src="<?= BASE_URL . $anhSanPham['link_hinh_anh'] ?>"
                                            alt="product-details" />
                                    </div>
                                    <?php endforeach ?>
                                </div>
                            </div>
                            <div class="col-lg-7">
                                <div class="product-details-des">
                                    <div class="manufacturer-name">
                                        <a href="#"><?= $sanPham['ten_danh_muc'] ?></a>
                                    </div>
                                    <h3 class="product-name"><?= $sanPham['ten_san_pham'] ?></h3>
                                    <div class="ratings d-flex">
                                        <div class="pro-review">
                                            <?php $countComment = count($listBinhLuan); ?>
                                            <span><?= $countComment . ' bình luận' ?></span>
                                        </div>
                                    </div>
                                    <div class="price-box">
                                        <?php if ($sanPham['gia_khuyen_mai']) { ?>
                                        <span
                                            class="price-regular"><?= formatPrice($sanPham['gia_khuyen_mai']) . 'đ'; ?></span>
                                        <span
                                            class="price-old"><del><?= formatPrice($sanPham['gia_san_pham']) . 'đ'; ?></del></span>
                                        <?php } else { ?>
                                        <span
                                            class="price-regular"><?= formatPrice($sanPham['gia_san_pham']) . 'đ'; ?></span>
                                        <?php } ?>
                                    </div>
                                    <div class="availability">
                                        <i class="fa fa-check-circle"></i>
                                        <span>Trạng thái: </span>
                                        <?php if ($sanPham['trang_thai'] == 1 && $sanPham['so_luong_thuc_te'] > 0) : ?>
                                        <span class="text-success">Còn bán</span>
                                        <?php else : ?>
                                        <span
                                            class="text-danger"><?= $sanPham['trang_thai'] != 1 ? 'Dừng bán' : 'Hết hàng' ?></span>
                                        <?php endif; ?>

                                        <?php if (empty($listSanPhamBienThe)) : ?>
                                        <span> | <?= $sanPham['so_luong_thuc_te'] . ' sản phẩm trong kho' ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <p class="pro-desc"><?= $sanPham['mo_ta'] ?></p>
                                    <form action="<?= BASE_URL . '?act=them-gio-hang' ?>" method="post">
                                        <input type="hidden" name="san_pham_id" value="<?= $sanPham['id']; ?>">

                                        <?php if (!empty($listSanPhamBienThe)) : ?>
                                        <?php
                                            $colors = array_unique(array_map(fn($v) => $v['mau_sac'], $listSanPhamBienThe));
                                            $sizes = array_unique(array_map(fn($v) => $v['size'], $listSanPhamBienThe));
                                            ?>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <h6 class="option-title">Màu:</h6>
                                                <select id="variant_color" name="variant_color" class="form-control">
                                                    <option value="">-- Chọn màu --</option>
                                                    <?php foreach ($colors as $color): ?>
                                                    <option value="<?= htmlspecialchars($color) ?>">
                                                        <?= htmlspecialchars($color) ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <h6 class="option-title">Size:</h6>
                                                <select id="variant_size" name="variant_size" class="form-control">
                                                    <option value="">-- Chọn size --</option>
                                                </select>
                                            </div>
                                        </div>

                                        <input type="hidden" name="san_pham_bien_the_id" id="san_pham_bien_the_id"
                                            value="">
                                        <div id="variant_info" class="mb-2"></div>
                                        <div id="variant_error" class="text-danger mb-2"></div>

                                        <?php if (!empty($listSanPhamBienThe)) : ?>
                                        <script>
                                        const variants = <?= json_encode($listSanPhamBienThe) ?>;

                                        function normalize(val) {
                                            return (val || '').toString().trim().toLowerCase()
                                                .normalize('NFC');
                                        }

                                        function filterSizes(form) {
                                            const colorEl = form.querySelector('#variant_color');
                                            const sizeEl = form.querySelector('#variant_size');
                                            const hiddenEl = form.querySelector('#san_pham_bien_the_id');
                                            const infoEl = form.querySelector('#variant_info');
                                            const errorEl = form.querySelector('#variant_error');

                                            if (!colorEl || !sizeEl) return;

                                            sizeEl.innerHTML = '<option value="">-- Chọn size --</option>';
                                            hiddenEl.value = '';
                                            if (errorEl) errorEl.innerText = '';
                                            infoEl.innerHTML =
                                                '<span class="text-muted">Vui lòng chọn màu trước.</span>';

                                            const selectedColor = colorEl.value;
                                            console.log("🔥 Selected Color Value:", selectedColor);

                                            if (!selectedColor) {
                                                sizeEl.disabled = true;
                                                if (window.jQuery && jQuery.fn.niceSelect) {
                                                    jQuery(sizeEl).niceSelect('update');
                                                }
                                                return;
                                            }

                                            const normalizedColor = normalize(selectedColor);

                                            const filtered = variants.filter(v => {
                                                const mau = v.mau_sac || v.mauSac || v.color;
                                                return normalize(mau) === normalizedColor;
                                            });

                                            const uniqueSizes = [...new Set(filtered.map(v => v.size || v.Size).filter(
                                                s => s))];

                                            console.log("🔥 uniqueSizes:", uniqueSizes);

                                            if (uniqueSizes.length === 0) {
                                                infoEl.innerHTML =
                                                    '<span class="text-danger">Không có size cho màu này.</span>';
                                                sizeEl.disabled = true;
                                                if (window.jQuery && jQuery.fn.niceSelect) {
                                                    jQuery(sizeEl).niceSelect('update');
                                                }
                                                return;
                                            }

                                            uniqueSizes.forEach(currentSize => {
                                                const option = document.createElement('option');
                                                option.value = currentSize;
                                                option.textContent = currentSize;
                                                sizeEl.appendChild(option);
                                            });

                                            sizeEl.disabled = false;
                                            infoEl.innerHTML = '<span class="text-success">Chọn size phù hợp.</span>';

                                            // Cập nhật lại giao diện Nice Select sau khi thêm option mới
                                            if (window.jQuery && jQuery.fn.niceSelect) {
                                                jQuery(sizeEl).niceSelect('update');
                                            }
                                        }

                                        function updateVariantSelection(form) {
                                            const colorEl = form.querySelector('#variant_color');
                                            const sizeEl = form.querySelector('#variant_size');
                                            const hiddenEl = form.querySelector('#san_pham_bien_the_id');
                                            const infoEl = form.querySelector('#variant_info');
                                            const errorEl = form.querySelector('#variant_error');

                                            const colorValue = normalize(colorEl ? colorEl.value : '');
                                            const sizeValue = normalize(sizeEl ? sizeEl.value : '');

                                            if (!colorValue || !sizeValue) {
                                                hiddenEl.value = '';
                                                if (errorEl) errorEl.innerText = 'Vui lòng chọn cả màu và size.';
                                                infoEl.innerHTML = '';
                                                return;
                                            }

                                            const selected = variants.find(v => {
                                                const mau = normalize(v.mau_sac);
                                                const size = normalize(v.size);
                                                return mau === colorValue && size === sizeValue;
                                            });

                                            console.log('updateVariantSelection', {
                                                colorValue,
                                                sizeValue,
                                                selected
                                            });

                                            if (selected) {
                                                hiddenEl.value = selected.id;
                                                if (errorEl) errorEl.innerText = '';
                                                infoEl.innerHTML =
                                                    `<span class="text-success"><strong>Đã chọn:</strong> ${selected.mau_sac} - ${selected.size} | Còn: ${selected.so_luong_bien_the} sản phẩm</span>`;
                                            } else {
                                                hiddenEl.value = '';
                                                if (errorEl) errorEl.innerText = 'Biến thể không tồn tại.';
                                                infoEl.innerHTML = '';
                                            }
                                        }

                                        document.addEventListener('DOMContentLoaded', function() {
                                            const form = document.querySelector(
                                                'form[action*="them-gio-hang"]');
                                            if (!form) return;

                                            const colorEl = form.querySelector('#variant_color');
                                            const sizeEl = form.querySelector('#variant_size');
                                            const hiddenEl = form.querySelector('#san_pham_bien_the_id');
                                            const errorEl = form.querySelector('#variant_error');

                                            if (colorEl) {
                                                const handleColorChange = () => filterSizes(form);
                                                colorEl.addEventListener('change', handleColorChange);
                                                // Fallback cho các thư viện như nice-select sử dụng jQuery
                                                if (window.jQuery) jQuery(colorEl).on('change',
                                                    handleColorChange);
                                            }

                                            if (sizeEl) {
                                                const handleSizeChange = () => updateVariantSelection(form);
                                                sizeEl.addEventListener('change', handleSizeChange);
                                                if (window.jQuery) jQuery(sizeEl).on('change',
                                                    handleSizeChange);
                                            }

                                            // Nếu dùng Nice Select, cần cập nhật lại giao diện sau khi filter
                                            const refreshUI = () => {
                                                if (window.jQuery && jQuery.fn.niceSelect) jQuery('select')
                                                    .niceSelect('update');
                                            };

                                            filterSizes(form);
                                            refreshUI();

                                            form.addEventListener('submit', function(e) {
                                                if (!hiddenEl.value || !colorEl.value || !sizeEl
                                                    .value) {
                                                    e.preventDefault();
                                                    if (errorEl) errorEl.innerText =
                                                        'Vui lòng chọn đầy đủ Màu và Size hợp lệ.';
                                                    return false;
                                                }
                                            });
                                        });
                                        </script>
                                        <?php endif; ?>

                                        <?php else: ?>
                                        <input type="hidden" name="san_pham_bien_the_id" value="">
                                        <div class="pro-size mb-3">
                                            <h6 class="option-title">size :</h6>
                                            <select class="nice-select" name="size" disabled>
                                                <option>37</option>
                                                <option>38</option>
                                                <option>39</option>
                                                <option>40</option>
                                                <option>41</option>
                                                <option>42</option>
                                                <option>43</option>
                                            </select>
                                        </div>
                                        <?php endif; ?>

                                        <div class="quantity-cart-box d-flex align-items-center">
                                            <h6 class="option-title">Số lượng:</h6>
                                            <div class="quantity">
                                                <div class="pro-qty"><input type="text" value="1" name="so_luong"></div>
                                            </div>
                                            <div class="action_link">
                                                <?php if ($sanPham['trang_thai'] == 1 && $sanPham['so_luong_thuc_te'] > 0) : ?>
                                                <button type="submit" class="btn btn-cart2" id="add-to-cart-btn">Thêm
                                                    giỏ hàng</button>
                                                <?php else : ?>
                                                <button type="button" class="btn btn-cart2 disabled" disabled
                                                    style="background-color: #ccc; cursor: not-allowed;">Hết hàng / Dừng
                                                    bán</button>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </form>

                                    <?php if (isset($_SESSION['error'])): ?>
                                    <div class="alert alert-danger mt-3">
                                        <?php 
                                            if (is_array($_SESSION['error'])) {
                                                foreach ($_SESSION['error'] as $error) {
                                                    echo "<p class='mb-0'>$error</p>";
                                                }
                                            } else {
                                                echo $_SESSION['error'];
                                            }
                                        ?>
                                    </div>
                                    <?php unset($_SESSION['error']); ?>
                                    <?php endif ?>

                                    <?php if (isset($_SESSION['debug_message'])): ?>
                                    <div class="alert alert-info mt-3">
                                        <h4>Debug Information (Server-side):</h4>
                                        <pre><?php var_dump($_SESSION['debug_message']); ?></pre>
                                    </div>
                                    <?php unset($_SESSION['debug_message']); ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- product details inner end -->

                    <!-- product details reviews start -->
                    <div class="product-details-reviews section-padding pb-0">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="product-review-info">
                                    <ul class="nav review-tab">

                                        <li>
                                            <a class="active" data-bs-toggle="tab" href="#tab_three">Bình luận
                                                (<?= $countComment ?>)</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content reviews-tab">

                                        <div class="tab-pane fade show active" id="tab_three">
                                            <?php foreach ($listBinhLuan as $binhLuan): ?>
                                            <div class="total-reviews">
                                                <div class="rev-avatar">
                                                    <img src="<?= BASE_URL . $binhLuan['anh_dai_dien'] ?>" alt="">
                                                </div>
                                                <div class="review-box">

                                                    <div class="post-author">
                                                        <p><span><?= $binhLuan['ho_ten'] ?> -
                                                            </span><?= $binhLuan['ngay_dang'] ?></p>
                                                    </div>
                                                    <p><?= $binhLuan['noi_dung'] ?></p>
                                                </div>
                                            </div>
                                            <?php endforeach ?>
                                            <form action="<?= BASE_URL . '?act=gui-binh-luan' ?>" method="post"
                                                class="review-form">
                                                <input type="hidden" name="san_pham_id" value="<?= $sanPham['id'] ?>">
                                                <div class="form-group row">
                                                    <div class="col">
                                                        <label class="col-form-label"><span class="text-danger">*</span>
                                                            Nội dung bình luận</label>
                                                        <textarea name="noi_dung" class="form-control"
                                                            required></textarea>
                                                    </div>
                                                </div>

                                                <div class="buttons">
                                                    <button class="btn btn-sqr" type="submit">Bình luận</button>
                                                </div>
                                            </form> <!-- end of review-form -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- product details reviews end -->
                </div>
                <!-- product details wrapper end -->
            </div>
        </div>
    </div>
    <!-- page main wrapper end -->

    <!-- related products area start -->
    <section class="related-products section-padding">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <!-- section title start -->
                    <div class="section-title text-center">
                        <h2 class="title">Sản phẩm liên quan</h2>
                        <p class="sub-title"></p>
                    </div>
                    <!-- section title start -->
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="product-carousel-4 slick-row-10 slick-arrow-style">
                        <!-- product item start -->
                        <?php foreach ($listSanPhamCungDanhMuc as $key => $sanPham): ?>
                        <!-- product item start -->
                        <div class="product-item">
                            <figure class="product-thumb">
                                <a href="<?= BASE_URL . '?act=chi-tiet-san-pham&id_san_pham=' . $sanPham['id']; ?>">
                                    <img class="pri-img" src="<?= BASE_URL . $sanPham['hinh_anh'] ?>" alt="product">
                                    <img class="sec-img" src="<?= BASE_URL . $sanPham['hinh_anh'] ?>" alt="product">
                                </a>
                                <div class="product-badge">
                                    <?php
                                        $ngayNhap = new DateTime($sanPham['ngay_nhap']);
                                        $ngayHienTai = new DateTime();
                                        $tinhNgay = $ngayHienTai->diff($ngayNhap);

                                        if ($tinhNgay->days <= 7) {
                                        ?>
                                    <div class="product-label new">
                                        <span>Mới</span>
                                    </div>
                                    <?php
                                        }
                                        ?>
                                    <?php if ($sanPham['gia_khuyen_mai']) { ?>
                                    <div class="product-label discount">
                                        <span>Giảm giá</span>
                                    </div>

                                    <?php } ?>
                                </div>
                                <div class="cart-hover">
                                    <a href="<?= BASE_URL . '?act=chi-tiet-san-pham&id_san_pham=' . $sanPham['id']; ?>"><button
                                            class="btn btn-cart">Xem chi tiết</button></a>
                                </div>
                            </figure>
                            <div class="product-caption text-center">

                                <h6 class="product-name">
                                    <a
                                        href="<?= BASE_URL . '?act=chi-tiet-san-pham&id_san_pham=' . $sanPham['id']; ?>"><?= $sanPham['ten_san_pham'] ?></a>
                                </h6>
                                <div class="price-box">
                                    <?php if ($sanPham['gia_khuyen_mai']) { ?>
                                    <span
                                        class="price-regular"><?= formatPrice($sanPham['gia_khuyen_mai']) . 'đ'; ?></span>
                                    <span
                                        class="price-old"><del><?= formatPrice($sanPham['gia_san_pham']) . 'đ'; ?></del></span>
                                    <?php } else { ?>
                                    <span
                                        class="price-regular"><?= formatPrice($sanPham['gia_san_pham']) . 'đ'; ?></span>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        <!-- product item end -->

                        <?php endforeach ?>
                        <!-- product item end -->

                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- related products area end -->
</main>



<?php require_once 'layout/miniCart.php'; ?>

<?php require_once 'layout/footer.php'; ?>