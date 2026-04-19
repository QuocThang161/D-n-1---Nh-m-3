<?php require_once './views/layout/header.php'; ?>
<?php require_once './views/layout/menu.php'; ?>

<main>
    <div class="breadcrumb-area">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="breadcrumb-wrap">
                        <nav aria-label="breadcrumb">
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="<?= BASE_URL ?>"><i class="fa fa-home"></i> Trang
                                        chủ</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Đăng nhập</li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="login-register-wrapper section-padding">
        <div class="container">
            <div class="member-area-from-wrap">
                <div class="row justify-content-center">
                    <div class="col-lg-6 col-md-8">
                        <div class="login-reg-form-wrap">
                            <h5 class="text-center mb-4">ĐĂNG NHẬP</h5>

                            <?php if (isset($_SESSION['error'])): ?>
                            <div class="alert alert-danger text-center">
                                <i class="fa fa-exclamation-circle"></i> <?= $_SESSION['error'] ?>
                            </div>
                            <?php unset($_SESSION['error']); ?>
                            <?php endif; ?>

                            <?php if (isset($_SESSION['success'])): ?>
                            <div class="alert alert-success text-center">
                                <i class="fa fa-check-circle"></i> <?= $_SESSION['success'] ?>
                            </div>
                            <?php unset($_SESSION['success']); ?>
                            <?php endif; ?>

                            <form action="<?= BASE_URL . '?act=check-login' ?>" method="post">
                                <div class="single-input-item mb-3">
                                    <label class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" placeholder="Nhập Email của bạn..." name="email"
                                        class="form-control" required autocomplete="username" />
                                </div>

                                <div class="single-input-item mb-3">
                                    <label class="form-label">Mật khẩu <span class="text-danger">*</span></label>
                                    <input type="password" placeholder="Nhập mật khẩu..." name="password"
                                        class="form-control" required autocomplete="current-password" />
                                </div>

                                <div class="single-input-item mb-3">
                                    <div class="login-reg-form-meta d-flex align-items-center justify-content-between">
                                        <div class="remember-meta">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="rememberMe">
                                                <label class="custom-control-label" for="rememberMe">Ghi nhớ đăng
                                                    nhập</label>
                                            </div>
                                        </div>
                                        <a href="<?= BASE_URL . '?act=dang-ky' ?>" class="forget-pwd text-primary">Bạn
                                            chưa có tài khoản?</a>
                                    </div>
                                </div>

                                <div class="single-input-item mt-4">
                                    <button class="btn btn-sqr w-100 shadow-sm">ĐĂNG NHẬP</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require_once './views/layout/footer.php'; ?>