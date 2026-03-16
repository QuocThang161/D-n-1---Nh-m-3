<!-- header  -->
<?php require './views/layout/header.php'; ?>
<!-- Navbar -->
<?php include './views/layout/navbar.php'; ?>
<!-- /.navbar -->

<!-- Main Sidebar Container -->
<?php include './views/layout/sidebar.php'; ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Chi tiết đơn hàng: <?= $donHang['ma_don_hang'] ?></h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Thông tin người nhận</h3>
                        </div>
                        <div class="card-body">
                            <p><strong>Tên người nhận:</strong> <?= htmlspecialchars($donHang['ten_nguoi_nhan']) ?></p>
                            <p><strong>Số điện thoại:</strong> <?= htmlspecialchars($donHang['sdt_nguoi_nhan']) ?></p>
                            <p><strong>Địa chỉ:</strong> <?= htmlspecialchars($donHang['dia_chi_nguoi_nhan']) ?></p>
                            <p><strong>Email:</strong> <?= htmlspecialchars($donHang['email_nguoi_nhan']) ?></p>
                            <p><strong>Ngày đặt:</strong> <?= date('d/m/Y H:i:s', strtotime($donHang['ngay_dat'])) ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">Thông tin đơn hàng</h3>
                        </div>
                        <div class="card-body">
                            <p><strong>Mã đơn hàng:</strong> <?= htmlspecialchars($donHang['ma_don_hang']) ?></p>
                            <p><strong>Tổng tiền:</strong> <?= number_format($donHang['tong_tien'], 0, ',', '.') ?> VNĐ
                            </p>
                            <p><strong>Trạng thái:</strong> <span
                                    class="badge bg-success"><?= htmlspecialchars($donHang['ten_trang_thai']) ?></span>
                            </p>
                            <p><strong>Ghi chú:</strong> <?= htmlspecialchars($donHang['ghi_chu']) ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Danh sách sản phẩm</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Hình ảnh</th>
                                        <th>Tên sản phẩm</th>
                                        <th>Số lượng</th>
                                        <th>Giá</th>
                                        <th>Thành tiền</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $stt = 1;
                                    foreach ($listSanPham as $sanPham) : ?>
                                    <tr>
                                        <td><?= $stt++ ?></td>
                                        <td><img src="<?= BASE_URL . $sanPham['hinh_anh'] ?>" alt="" width="80px"></td>
                                        <td><?= htmlspecialchars($sanPham['ten_san_pham']) ?></td>
                                        <td><?= $sanPham['so_luong'] ?></td>
                                        <td><?= number_format($sanPham['gia'], 0, ',', '.') ?> VNĐ</td>
                                        <td><?= number_format($sanPham['gia'] * $sanPham['so_luong'], 0, ',', '.') ?>
                                            VNĐ</td>
                                    </tr>
                                    <?php endforeach ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
            <a href="<?= BASE_URL_ADMIN . '?act=don-hang' ?>" class="btn btn-danger mb-3">Quay lại</a>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<!-- Footer  -->
<?php include './views/layout/footer.php'; ?>
<!-- End footer  -->

</body>

</html>