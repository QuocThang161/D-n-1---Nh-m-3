<?php 
session_start();
// Require file Common
require_once './commons/env.php'; // Khai báo biến môi trường
require_once './commons/function.php'; // Hàm hỗ trợ

// Require toàn bộ file Controllers
require_once './controllers/HomeController.php';

// Require toàn bộ file Models
require_once './models/SanPham.php';
require_once './models/TaiKhoan.php';
require_once './models/GioHang.php';
require_once './models/DonHang.php';

// Route
$act = $_GET['act'] ?? '/';
// var_dump($_GET['act']);die();

// if ($_GET['act']) {
//     $act = $_GET['act'];
// }else{
//     $act = '/';
// }

// Để bảo bảo tính chất chỉ gọi 1 hàm Controller để xử lý request thì mình sử dụng match
match ($act) {

// route 
    '/' => (new HomeController())->home(), // route trang chủ

    'cua-hang' => (new HomeController())->cuaHang(),

    'chi-tiet-san-pham' => (new HomeController())->chiTietSanPham(),
    'gui-binh-luan'     => (new HomeController())->postBinhLuan(), 
    'them-gio-hang' =>(new HomeController())->addGioHang(),
    'gio-hang' =>(new HomeController())->gioHang(),
    'xoa-san-pham-gio-hang' => (new HomeController())->xoaSanPhamGioHang(),
    'update-so-luong-gio-hang' => (new HomeController())->updateSoLuongGioHang(),
    'thanh-toan' =>(new HomeController())->thanhToan(),
    'xu-ly-thanh-toan' =>(new HomeController())->postThanhToan(),
    'lich-su-mua-hang' =>(new HomeController())->lichSuMuaHang(),
    'chi-tiet-mua-hang' =>(new HomeController())->chiTietMuaHang(),
    'huy-don-hang' =>(new HomeController())->huyDonHang(),



    // Auth
    'login' => (new HomeController())->formLogin(),
    'dang-ky' => (new HomeController())->formRegister(),
    'check-dang-ky' => (new HomeController())->postRegister(),
    'check-login' => (new HomeController())->postLogin(),
    'logout' => (new HomeController())->logout(),
    'thong-tin-tai-khoan' => (new HomeController())->thongTinTaiKhoan(),
    'sua-thong-tin-ca-nhan' => (new HomeController())->postEditCaNhan(),
    
    default => (new HomeController())->home(),
};