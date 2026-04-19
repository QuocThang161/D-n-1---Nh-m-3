<!-- Start Header Area -->
<header class="header-area header-wide">
    <!-- main header start -->
    <div class="main-header d-none d-lg-block">


        <!-- header middle area start -->
        <div class="header-main-area sticky">
            <div class="container">
                <div class="row align-items-center position-relative">

                    <!-- start logo area -->
                    <div class="col-lg-2">
                        <div class="logo">
                            <a href="<?= BASE_URL ?>">
                                <img src="assets/img/logo/logo.jpg.png" class="img-fluid"
                                    style="max-width: 100px; margin-left: 80px;" alt="Brand Logo">
                            </a>
                        </div>
                    </div>
                    <!-- start logo area -->

                    <!-- main menu area start -->
                    <div class="col-lg-6 position-static">
                        <div class="main-menu-area">
                            <div class="main-menu">
                                <!-- main menu navbar start -->
                                <nav class="desktop-menu">
                                    <ul>
                                        <li><a href="<?= BASE_URL ?>">Trang chủ</a></li>
                                        <li>
                                            <a href="<?= BASE_URL . '?act=cua-hang' ?>">Cửa hàng
                                                <i class="fa fa-angle-down"></i></a>
                                        </li>
                                        <li><a href="contact-us.html"></a></li>
                                        <li><a href="contact-us.html"></a></li>
                                    </ul>
                                </nav>
                                <!-- main menu navbar end -->
                            </div>
                        </div>
                    </div>
                    <!-- main menu area end -->

                    <!-- mini cart area start -->
                    <div class="col-lg-4">
                        <div
                            class="header-right d-flex align-items-center justify-content-xl-between justify-content-lg-end">
                            <div class="header-search-container">
                                <button class="search-trigger d-xl-none d-lg-block"><i
                                        class="pe-7s-search"></i></button>
                                <form action="<?= BASE_URL ?>" method="get"
                                    class="header-search-box d-lg-none d-xl-block">
                                    <input type="hidden" name="act" value="cua-hang">
                                    <input type="text" name="search" placeholder="Nhập tên sản phẩm..."
                                        class="header-search-field">
                                    <button class="header-search-btn"><i class="pe-7s-search"></i></button>
                                </form>
                            </div>
                            <div class="header-configure-area">
                                <ul class="nav justify-content-end">
                                    <li class="user-hover">
                                        <a href="#">
                                            <i class="pe-7s-user"></i>
                                        </a>
                                        <ul class="dropdown-list">
                                            <?php if(!isset($_SESSION['user_client'])){?>
                                            <li><a href="<?= BASE_URL . '?act=login' ?>">Đăng nhập</a></li>
                                            <?php } else {
                                                $userModel = new TaiKhoan();
                                                $userInfo = $userModel->getTaiKhoanFromEmail($_SESSION['user_client']);
                                            ?>
                                            <li><a
                                                    href="<?= BASE_URL . '?act=thong-tin-tai-khoan' ?>"><?= htmlspecialchars($userInfo['ho_ten'] ?? 'Người dùng') ?></a>
                                            </li>
                                            <li><a href="<?= BASE_URL . '?act=lich-su-mua-hang' ?>">Lịch sử mua hàng</a>
                                            </li>
                                            <?php }?>
                                        </ul>
                                    </li>

                                    <?php $cartSummary = getCartSummary(); ?>
                                    <li>
                                        <a href="#" class="minicart-btn">
                                            <i class="pe-7s-shopbag"></i>
                                            <div class="notification"><?= $cartSummary['count'] ?: 0 ?></div>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- mini cart area end -->

                </div>
            </div>
        </div>
        <!-- header middle area end -->
    </div>
    <!-- main header start -->


</header>
<!-- end Header Area -->