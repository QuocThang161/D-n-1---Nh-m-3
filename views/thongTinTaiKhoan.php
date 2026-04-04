<?php require_once 'layout/header.php'; ?>

<?php require_once 'layout/menu.php'; ?>

<main>
    <!-- cart main wrapper start -->
    <div class="container mt-5">
        <h2>Thông tin tài khoản</h2>
        <?php
            $userData = [];
            if (isset($user) && is_array($user)) {
                $userData = $user;
            } elseif (isset($_SESSION['user_client']) && is_array($_SESSION['user_client'])) {
                $userData = $_SESSION['user_client'];
            }
        ?>
        <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $_SESSION['success'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['success']); // Xóa thông báo sau khi đã hiển thị ?>
        <?php endif; ?>
        <form action="<?= BASE_URL . '?act=sua-thong-tin-ca-nhan' ?>" method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-4 text-center">
                    <div class="mb-3">
                        <img src="<?= !empty($userData['anh_dai_dien']) ? BASE_URL . $userData['anh_dai_dien'] : './assets/img/default-avatar.png' ?>"
                            alt="Avatar" class="rounded-circle img-thumbnail"
                            style="width: 200px; height: 200px; object-fit: cover;">
                    </div>
                    <div class="mb-3">
                        <label for="anh_dai_dien" class="form-label">Thay đổi ảnh đại diện</label>
                        <input class="form-control" type="file" id="anh_dai_dien" name="anh_dai_dien">
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="mb-3">
                        <label class="form-label">Họ tên</label>
                        <input type="text" class="form-control" name="ho_ten"
                            value="<?= htmlspecialchars($userData['ho_ten'] ?? '') ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email"
                            value="<?= htmlspecialchars($userData['email'] ?? '') ?>" readonly>
                        <small class="text-muted">Email không thể thay đổi để đảm bảo bảo mật.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Số điện thoại</label>
                        <input type="text" class="form-control" name="so_dien_thoai"
                            value="<?= htmlspecialchars($userData['so_dien_thoai'] ?? '') ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Địa chỉ</label>
                        <input type="text" class="form-control" name="dia_chi"
                            value="<?= htmlspecialchars($userData['dia_chi'] ?? '') ?>">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Ngày sinh</label>
                            <input type="date" class="form-control" name="ngay_sinh"
                                value="<?= htmlspecialchars($userData['ngay_sinh'] ?? '') ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Giới tính</label>
                            <br>
                            <select class="form-select" name="gioi_tinh">
                                <option value="1" <?= ($userData['gioi_tinh'] ?? null) == 1 ? 'selected' : '' ?>>
                                    Nam</option>
                                <option value="0" <?= ($userData['gioi_tinh'] ?? null) == 0 ? 'selected' : '' ?>>Nữ
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-4">
                        <form action="">
                            <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                            <button>
                                <a href="<?= BASE_URL . '?act=doi-mat-khau' ?>"
                                    class="btn btn-outline-danger shadow-sm ms-2">Đổi mật khẩu</a>
                            </button>
                    </div>
        </form>
    </div>
    </div>
    </form>
    </div>
    <!-- cart main wrapper end -->
</main>


<?php require_once 'layout/miniCart.php'; ?>

<?php require_once 'layout/footer.php'; ?>