<!-- header  -->
<?php require './views/layout/header.php'; ?>
<!-- Navbar -->
<?php include './views/layout/navbar.php'; ?>
<!-- /.navbar -->

<!-- Main Sidebar Container -->
<?php include './views/layout/sidebar.php'; ?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Thêm biến thể cho: <?= $sanPham['ten_san_pham'] ?></h1>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8">
                    <div class="card card-primary">
                        <div class="card-body">
                            <form action="<?= BASE_URL_ADMIN . '?act=them-bien-the' ?>" method="POST">
                                <input type="hidden" name="san_pham_id" value="<?= $sanPham['id'] ?>">

                                <div class="form-group">
                                    <label>Màu sắc</label>
                                    <input type="text" class="form-control" name="mau_sac" required>
                                </div>
                                <div class="form-group">
                                    <label>Size</label>
                                    <input type="text" class="form-control" name="size" required>
                                </div>
                                <div class="form-group">
                                    <label>Số lượng</label>
                                    <input type="number" class="form-control" name="so_luong" min="0" required>
                                </div>

                                <button type="submit" class="btn btn-success">Lưu</button>
                                <a href="<?= BASE_URL_ADMIN . '?act=variant-san-pham&id_san_pham=' . $sanPham['id'] ?>" class="btn btn-secondary">Hủy</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Footer  -->
<?php include './views/layout/footer.php'; ?>
