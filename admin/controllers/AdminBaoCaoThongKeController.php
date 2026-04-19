<?php
class AdminBaoCaoThongKeController
{
    public $modelBaoCao;

    public function __construct()
    {
        $this->modelBaoCao = new AdminBaoCaoThongKe();
    }

    public function home()
    {
        // Lấy dữ liệu thống kê từ database
        $countDonHang = $this->modelBaoCao->getCountDonHang();
        $tongDoanhThu = $this->modelBaoCao->getTongDoanhThu();
        $countSanPham = $this->modelBaoCao->getCountSanPham();
        $countKhachHang = $this->modelBaoCao->getCountKhachHang();

        require_once './views/home.php';
    }
}