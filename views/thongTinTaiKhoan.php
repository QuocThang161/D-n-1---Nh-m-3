<?php require_once 'layout/header.php'; ?>
<?php require_once 'layout/menu.php'; ?>

<main>
    <div class="container mt-5 mb-5">
        <h2 class="mb-4">Thông tin tài khoản</h2>

        <?php
            $userData = [];
            if (isset($user) && is_array($user)) {
                $userData = $user;
            } elseif (isset($_SESSION['user_client']) && is_array($_SESSION['user_client'])) {
                // Lưu ý: Nếu SESSION chỉ lưu email, bạn nên dùng biến $user từ Controller truyền sang
                $userData = $_SESSION['user_client'];
            }
        ?>

        <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $_SESSION['success'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <form action="<?= BASE_URL . '?act=sua-thong-tin-ca-nhan' ?>" method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-4 text-center">
                    <div class="mb-3">
                        <img src="<?= !empty($userData['anh_dai_dien']) ? BASE_URL . $userData['anh_dai_dien'] : './assets/img/default-avatar.png' ?>"
                            alt="Avatar" class="rounded-circle img-thumbnail shadow-sm"
                            style="width: 200px; height: 200px; object-fit: cover;">
                    </div>
                    <div class="mb-3">
                        <label for="anh_dai_dien" class="form-label">Thay đổi ảnh đại diện</label>
                        <input class="form-control" type="file" id="anh_dai_dien" name="anh_dai_dien">
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Họ tên</label>
                        <input type="text" class="form-control" name="ho_ten"
                            value="<?= htmlspecialchars($userData['ho_ten'] ?? '') ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Email</label>
                        <input type="email" class="form-control bg-light" name="email"
                            value="<?= htmlspecialchars($userData['email'] ?? '') ?>" readonly>
                        <small class="text-muted italic">Email không thể thay đổi để đảm bảo bảo mật.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Số điện thoại</label>
                        <input type="text" class="form-control" name="so_dien_thoai"
                            value="<?= htmlspecialchars($userData['so_dien_thoai'] ?? '') ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Địa chỉ</label>
                        <input type="text" class="form-control" name="dia_chi"
                            value="<?= htmlspecialchars($userData['dia_chi'] ?? '') ?>">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold">Ngày sinh</label>
                            <input type="date" class="form-control" name="ngay_sinh"
                                value="<?= htmlspecialchars($userData['ngay_sinh'] ?? '') ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold">Giới tính</label>
                            <select class="form-select" name="gioi_tinh">
                                <option value="1" <?= ($userData['gioi_tinh'] ?? null) == 1 ? 'selected' : '' ?>>Nam
                                </option>
                                <option value="0" <?= ($userData['gioi_tinh'] ?? null) == 0 ? 'selected' : '' ?>>Nữ
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-4 border-top pt-3">
                        <button type="submit" class="btn btn-primary px-4">Lưu thay đổi</button>

                        <a href="<?= BASE_URL . '?act=doi-mat-khau' ?>"
                            class="btn btn-outline-danger shadow-sm ms-2">Đổi mật khẩu</a>

                        <a href="<?= BASE_URL . '?act=logout' ?>" class="btn btn-danger float-end"
                            onclick="return confirm('Bạn có chắc chắn muốn đăng xuất không?')">
                            <i class="fa fa-sign-out"></i> Đăng xuất
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</main>

<?php require_once 'layout/miniCart.php'; ?>
<?php require_once 'layout/footer.php'; ?>