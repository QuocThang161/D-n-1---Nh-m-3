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
                                <li class="breadcrumb-item active" aria-current="page">Đăng ký</li>
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
                    <div class="col-lg-7 col-md-10">
                        <div class="login-reg-form-wrap">
                            <h5 class="text-center mb-4">ĐĂNG KÝ TÀI KHOẢN</h5>

                            <?php if (isset($_SESSION['error'])): ?>
                            <div class="alert alert-danger text-center">
                                <i class="fa fa-exclamation-circle"></i> <?= $_SESSION['error'] ?>
                            </div>
                            <?php unset($_SESSION['error']); ?>
                            <?php else: ?>
                            <p class="login-box-msg text-center text-muted mb-4">Vui lòng điền đầy đủ thông tin bên dưới
                            </p>
                            <?php endif; ?>

                            <form action="<?= BASE_URL . '?act=check-dang-ky' ?>" method="post">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="single-input-item mb-3">
                                            <label class="form-label">Họ và tên <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" placeholder="Nhập họ tên..." name="ho_ten"
                                                class="form-control" required />
                                        </div>

                                        <div class="single-input-item mb-3">
                                            <label class="form-label">Email <span class="text-danger">*</span></label>
                                            <input type="email" placeholder="Địa chỉ Email..." name="email"
                                                class="form-control" required autocomplete="username" />
                                        </div>

                                        <div class="single-input-item mb-3">
                                            <label class="form-label">Mật khẩu <span
                                                    class="text-danger">*</span></label>
                                            <input type="password" placeholder="Mật khẩu..." name="password"
                                                class="form-control" required autocomplete="new-password" />
                                        </div>

                                        <div class="single-input-item mb-3">
                                            <label class="form-label">Nhập lại mật khẩu <span
                                                    class="text-danger">*</span></label>
                                            <input type="password" placeholder="Xác nhận mật khẩu..."
                                                name="confirm_password" class="form-control" required
                                                autocomplete="new-password" />
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="single-input-item mb-3">
                                            <label class="form-label">Số điện thoại <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="so_dien_thoai" class="form-control"
                                                placeholder="Nhập số điện thoại..." required>
                                        </div>

                                        <div class="single-input-item mb-3">
                                            <label class="form-label">Ngày sinh <span
                                                    class="text-danger">*</span></label>
                                            <input type="date" name="ngay_sinh" class="form-control" required>
                                        </div>

                                        <div class="single-input-item mb-3">
                                            <label class="form-label">Địa chỉ <span class="text-danger">*</span></label>
                                            <textarea name="dia_chi" class="form-control"
                                                placeholder="Nhập địa chỉ cụ thể..." rows="4" required
                                                style="height: 110px;"></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="single-input-item mt-4">
                                    <button class="btn btn-sqr w-100 shadow-sm">ĐĂNG KÝ NGAY</button>
                                </div>

                                <div class="single-input-item text-center mt-3">
                                    <p class="mb-0">Đã có tài khoản? <a href="<?= BASE_URL . '?act=login' ?>"
                                            class="text-primary font-weight-bold">Đăng nhập tại đây</a></p>
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