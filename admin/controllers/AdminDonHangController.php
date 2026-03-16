<?php

class AdminDonHangController
{
    public $modelDonHang;

    public function __construct()
    {
        $this->modelDonHang = new AdminDonHang();

    }

    public function danhSachDonHang()
    {

        $listDonHang = $this->modelDonHang->getAllDonHang();

        require_once './views/donhang/listDonHang.php';
    }

    public function detailDonHang()
    {
        $id_don_hang = $_GET['id_don_hang'];
        $donHang = $this->modelDonHang->getDetailDonHang($id_don_hang);
        $listSanPham = $this->modelDonHang->getSanPhamByDonHang($id_don_hang);

        if ($donHang) {
            require_once './views/donhang/detailDonHang.php';
        } else {
            header("Location: " . BASE_URL_ADMIN . '?act=don-hang');
            exit();
        }
    }

}