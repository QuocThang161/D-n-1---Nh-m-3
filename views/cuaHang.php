    <?php require_once 'layout/header.php'?>

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
                                    <li class="breadcrumb-item active" aria-current="page">Cửa hàng</li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- breadcrumb area end -->

        <!-- page main wrapper start -->
        <div class="shop-main-wrapper section-padding">
            <div class="container">
                <div class="row">
                    <!-- sidebar area start -->
                    <div class="col-lg-3 order-2 order-lg-1">
                        <aside class="sidebar-wrapper">
                            <!-- single sidebar start -->
                            <div class="sidebar-single">
                                <h5 class="sidebar-title">Danh mục</h5>
                                <div class="sidebar-body">
                                    <ul class="shop-categories">
                                        <li><a href="<?= BASE_URL . '?act=cua-hang' ?>">Tất cả sản phẩm</a></li>
                                        <?php foreach($listDanhMuc as $danhmuc): ?>
                                        <li><a href="<?= BASE_URL . '?act=cua-hang&id_dan_muc=' . $danhmuc['id'] ?>">
                                                <?= $danhmuc['ten_danh_muc'] ?>
                                            </a></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                            <!-- single sidebar end -->


                            <!-- single sidebar start -->
                            <div class="sidebar-banner">
                                <div class="img-container">
                                    <a href="#">
                                        <img src="assets/img/banner/sidebar-banner.jpg" alt="">
                                    </a>
                                </div>
                            </div>
                            <!-- single sidebar end -->
                        </aside>
                    </div>
                    <!-- sidebar area end -->

                    <!-- shop main wrapper start -->
                    <div class="col-lg-9 order-1 order-lg-2">
                        <div class="shop-product-wrapper">
                            <!-- shop product top wrap start -->
                            <div class="shop-top-bar">
                                <div class="row align-items-center">
                                    <div class="col-lg-7 col-md-6 order-2 order-md-1">
                                        <div class="top-bar-left">
                                            <div class="product-view-mode">
                                                <a class="active" href="#" data-target="grid-view"
                                                    data-bs-toggle="tooltip" title="Grid View"><i
                                                        class="fa fa-th"></i></a>
                                            </div>
                                            <div class="product-amount">
                                                <p>Hiển thị <?= count($listSanPham) ?> sản phẩm</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-5 col-md-6 order-1 order-md-2">
                                        <div class="top-bar-right">
                                            <div class="product-short">
                                                <p>Sắp xếp theo:</p>
                                                <select class="nice-select" id="sortSelect"
                                                    onchange="location = this.value;">
                                                    <option
                                                        value="<?= BASE_URL . '?act=cua-hang' . ($id_dan_muc ? '&id_dan_muc='.$id_dan_muc : '') ?>"
                                                        <?= $sort == '' ? 'selected' : '' ?>>Mặc định</option>
                                                    <option
                                                        value="<?= BASE_URL . '?act=cua-hang' . ($id_dan_muc ? '&id_dan_muc='.$id_dan_muc : '') . '&sort=gia_asc' ?>"
                                                        <?= $sort == 'gia_asc' ? 'selected' : '' ?>>Giá: Thấp đến cao
                                                    </option>
                                                    <option
                                                        value="<?= BASE_URL . '?act=cua-hang' . ($id_dan_muc ? '&id_dan_muc='.$id_dan_muc : '') . '&sort=gia_desc' ?>"
                                                        <?= $sort == 'gia_desc' ? 'selected' : '' ?>>Giá: Cao đến thấp
                                                    </option>
                                                    <option
                                                        value="<?= BASE_URL . '?act=cua-hang' . ($id_dan_muc ? '&id_dan_muc='.$id_dan_muc : '') . '&sort=ten_asc' ?>"
                                                        <?= $sort == 'ten_asc' ? 'selected' : '' ?>>Tên: A-Z</option>
                                                    <option
                                                        value="<?= BASE_URL . '?act=cua-hang' . ($id_dan_muc ? '&id_dan_muc='.$id_dan_muc : '') . '&sort=ten_desc' ?>"
                                                        <?= $sort == 'ten_desc' ? 'selected' : '' ?>>Tên: Z-A</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- shop product top wrap start -->

                            <!-- product item list wrapper start -->
                            <div class="shop-product-wrap grid-view row mbn-30">
                                <?php foreach ($listSanPham as $sanPham): ?>
                                <div class="col-md-4 col-sm-6">
                                    <!-- product grid start -->
                                    <div class="product-item">
                                        <figure class="product-thumb">
                                            <a
                                                href="<?= BASE_URL . '?act=chi-tiet-san-pham&id_san_pham=' . $sanPham['id'] ?>">
                                                <img class="pri-img" src="<?= BASE_URL . $sanPham['hinh_anh'] ?>"
                                                    alt="product">
                                                <img class="sec-img" src="<?= BASE_URL . $sanPham['hinh_anh'] ?>"
                                                    alt="product">
                                            </a>
                                            <div class="product-badge">
                                                <?php if ($sanPham['gia_khuyen_mai']): ?>
                                                <div class="product-label discount">
                                                    <span>Giảm giá</span>
                                                </div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="cart-hover">
                                                <a
                                                    href="<?= BASE_URL . '?act=chi-tiet-san-pham&id_san_pham=' . $sanPham['id'] ?>">
                                                    <button class="btn btn-cart">Xem chi tiết</button>
                                                </a>
                                            </div>
                                        </figure>
                                        <div class="product-caption text-center">
                                            <div class="product-identity">
                                                <p class="manufacturer-name"><a
                                                        href="#"><?= htmlspecialchars($sanPham['ten_danh_muc']) ?></a>
                                                </p>
                                            </div>
                                            <h6 class="product-name">
                                                <a
                                                    href="<?= BASE_URL . '?act=chi-tiet-san-pham&id_san_pham=' . $sanPham['id'] ?>"><?= htmlspecialchars($sanPham['ten_san_pham']) ?></a>
                                            </h6>
                                            <div class="price-box">
                                                <?php if ($sanPham['gia_khuyen_mai']): ?>
                                                <span
                                                    class="price-regular"><?= formatPrice($sanPham['gia_khuyen_mai']) ?>đ</span>
                                                <span
                                                    class="price-old"><del><?= formatPrice($sanPham['gia_san_pham']) ?>đ</del></span>
                                                <?php else: ?>
                                                <span
                                                    class="price-regular"><?= formatPrice($sanPham['gia_san_pham']) ?>đ</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- product grid end -->
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <!-- product item list wrapper end -->

                            <!-- start pagination area -->
                            <div class="paginatoin-area text-center">
                                <ul class="pagination-box">
                                    <?php if ($page > 1): ?>
                                    <li><a class="previous"
                                            href="<?= BASE_URL . '?act=cua-hang' . ($id_dan_muc ? '&id_dan_muc='.$id_dan_muc : '') . ($sort ? '&sort='.$sort : '') . '&page=' . ($page - 1) ?>"><i
                                                class="pe-7s-angle-left"></i></a></li>
                                    <?php endif; ?>

                                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                    <li class="<?= $i == $page ? 'active' : '' ?>">
                                        <a
                                            href="<?= BASE_URL . '?act=cua-hang' . ($id_dan_muc ? '&id_dan_muc='.$id_dan_muc : '') . ($sort ? '&sort='.$sort : '') . '&page=' . $i ?>">
                                            <?= $i ?>
                                        </a>
                                    </li>
                                    <?php endfor; ?>

                                    <?php if ($page < $totalPages): ?>
                                    <li><a class="next"
                                            href="<?= BASE_URL . '?act=cua-hang' . ($id_dan_muc ? '&id_dan_muc='.$id_dan_muc : '') . ($sort ? '&sort='.$sort : '') . '&page=' . ($page + 1) ?>"><i
                                                class="pe-7s-angle-right"></i></a></li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                            <!-- end pagination area -->
                        </div>
                    </div>
                    <!-- shop main wrapper end -->
                </div>
            </div>
        </div>
        <!-- page main wrapper end -->
    </main>

    <?php require_once 'layout/miniCart.php'; ?>

    <?php require_once 'layout/footer.php'?>