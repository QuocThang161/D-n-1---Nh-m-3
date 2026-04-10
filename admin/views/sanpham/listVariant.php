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
                    <h1>Biến thể sản phẩm: <?= $sanPham['ten_san_pham'] ?></h1>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <a href="<?= BASE_URL_ADMIN . '?act=form-them-bien-the&id_san_pham=' . $sanPham['id'] ?>" class="btn btn-success">Thêm biến thể</a>
                            <a href="<?= BASE_URL_ADMIN . '?act=chi-tiet-san-pham&id_san_pham=' . $sanPham['id'] ?>" class="btn btn-secondary">Trở về sản phẩm</a>
                        </div>
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>STT</th>
                                        <th>Màu sắc</th>
                                        <th>Size</th>
                                        <th>Số lượng</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($listBienThe as $key => $bienThe) : ?>
                                    <tr>
                                        <td><?= $key + 1 ?></td>
                                        <td><?= htmlspecialchars($bienThe['mau_sac']) ?></td>
                                        <td><?= htmlspecialchars($bienThe['size']) ?></td>
                                        <td><?= $bienThe['so_luong_bien_the'] ?? $bienThe['so_luong'] ?? 0 ?></td>
                                        <td>
                                            <a class="btn btn-sm btn-warning" href="<?= BASE_URL_ADMIN . '?act=form-sua-bien-the&id=' . $bienThe['id'] ?>">Sửa</a>
                                            <a class="btn btn-sm btn-danger" href="<?= BASE_URL_ADMIN . '?act=xoa-bien-the&id=' . $bienThe['id'] ?>" onclick="return confirm('Xóa biến thể?')">Xóa</a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Footer  -->
<?php include './views/layout/footer.php'; ?>

<script>
$(function() {
    $("#example1").DataTable({
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false
    });
});
</script>
