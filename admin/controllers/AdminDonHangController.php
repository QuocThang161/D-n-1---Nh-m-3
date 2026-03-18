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
        $don_hang_id = $_GET['id_don_hang'];

        // Lấy thông tin đơn hàng ở bảng don_hangs
        $donHang = $this->modelDonHang->getDetailDonHang($don_hang_id);

        //lấy danh sách sản phẩm đã đặt của đơn hàng ở bảng chi tiết đơn hàng
        $sanPhamDonHang = $this->modelDonHang->getListDonHang($don_hang_id);

        $listTrangThaiDonHang = $this->modelDonHang->getAlltrangThaiDonHang();

        require_once './views/donhang/detailDonHang.php';
    }
    

}