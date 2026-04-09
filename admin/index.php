<?php
session_start();

// 1. Require file Common
require_once '../commons/env.php'; 
require_once '../commons/function.php'; 

// 2. Require Controllers
require_once './controllers/AdminDanhMucController.php';
require_once './controllers/AdminSanPhamController.php';
require_once './controllers/AdminDonHangController.php';
require_once './controllers/AdminBaoCaoThongKeController.php';
require_once './controllers/AdminTaiKhoanController.php';

// 3. Require Models
require_once './models/AdminDanhMuc.php';
require_once './models/AdminSanPham.php';
require_once './models/AdminDonHang.php';
require_once './models/AdminTaiKhoan.php';

// 4. Lấy Route
$act = $_GET['act'] ?? '/';

// 5. Kiểm tra đăng nhập (Ngoại trừ các trang login/logout)
if (!in_array($act, ['login-admin', 'check-login-admin', 'logout-admin'])) {
    checkLoginAdmin();
}

// 6. Điều hướng Route bằng match
try {
    match ($act) {
        // Dashboard - Thống kê
        '/' => (new AdminBaoCaoThongKeController())->home(),

        // Quản lý Danh mục
        'danh-muc'           => (new AdminDanhMucController())->danhSachDanhMuc(),
        'form-them-danh-muc' => (new AdminDanhMucController())->formAddDanhMuc(),
        'them-danh-muc'      => (new AdminDanhMucController())->postAddDanhMuc(),
        'form-sua-danh-muc'  => (new AdminDanhMucController())->formEditDanhMuc(),
        'sua-danh-muc'       => (new AdminDanhMucController())->postEditDanhMuc(),
        'xoa-danh-muc'       => (new AdminDanhMucController())->deleteDanhMuc(),

        // Quản lý Sản phẩm & Bình luận
        'san-pham'               => (new AdminSanPhamController())->danhSachSanPham(),
        'form-them-san-pham'     => (new AdminSanPhamController())->formAddSanPham(),
        'them-san-pham'          => (new AdminSanPhamController())->postAddSanPham(),
        'form-sua-san-pham'      => (new AdminSanPhamController())->formEditSanPham(),
        'sua-san-pham'           => (new AdminSanPhamController())->postEditSanPham(),
        'sua-album-anh-san-pham' => (new AdminSanPhamController())->postEditAnhSanPham(),
        'xoa-san-pham'           => (new AdminSanPhamController())->deleteSanPham(),
        'chi-tiet-san-pham'      => (new AdminSanPhamController())->detailSanPham(),
        'update-trang-thai-binh-luan' => (new AdminSanPhamController())->updateTrangThaiBinhLuan(),

        // Quản lý Đơn hàng
        'don-hang'           => (new AdminDonHangController())->danhSachDonHang(),
        'form-sua-don-hang'  => (new AdminDonHangController())->formEditDonHang(),
        'sua-don-hang'       => (new AdminDonHangController())->postEditDonHang(),
        'chi-tiet-don-hang'  => (new AdminDonHangController())->detailDonHang(),

        // Quản lý Tài khoản (Quản trị, Khách hàng, Cá nhân)
        'list-tai-khoan-quan-tri' => (new AdminTaiKhoanController())->danhSachQuanTri(),
        'form-them-quan-tri'      => (new AdminTaiKhoanController())->formAddQuanTri(),
        'them-quan-tri'           => (new AdminTaiKhoanController())->postAddQuanTri(),
        'form-sua-quan-tri'       => (new AdminTaiKhoanController())->formEditQuanTri(),
        'sua-quan-tri'            => (new AdminTaiKhoanController())->postEditCaNhanQuanTri(),
        
        'list-tai-khoan-khach-hang' => (new AdminTaiKhoanController())->danhSachKhachHang(),
        'form-sua-khach-hang'       => (new AdminTaiKhoanController())->formEditKhachHang(),
        'sua-khach-hang'            => (new AdminTaiKhoanController())->postEditKhachHang(),
        'chi-tiet-khach-hang'       => (new AdminTaiKhoanController())->deltailKhachHang(),

        'form-sua-thong-tin-ca-nhan-quan-tri' => (new AdminTaiKhoanController())->formEditCaNhanQuanTri(),
        'sua-thong-tin-ca-nhan-quan-tri'      => (new AdminTaiKhoanController())->postEditCaNhanQuanTri(),
        'sua-mat-khau-ca-nhan-quan-tri'       => (new AdminTaiKhoanController())->postEditMatKhauCaNhan(),
        'reset-password'                      => (new AdminTaiKhoanController())->resetPassword(),

        // Auth
        'login-admin'       => (new AdminTaiKhoanController())->formLogin(),
        'check-login-admin' => (new AdminTaiKhoanController())->login(),
        'logout-admin'      => (new AdminTaiKhoanController())->logout(),

        // Trường hợp không khớp route nào (404)
        default => (new AdminBaoCaoThongKeController())->home(), 
    };
} catch (UnhandledMatchError $e) {
    // Xử lý lỗi nếu không có default (tốt nhất nên dùng default ở trên)
    header("HTTP/1.0 404 Not Found");
    echo "Trang bạn tìm không tồn tại!";
}