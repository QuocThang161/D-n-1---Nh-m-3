<?php

class HomeController
{
    public $modelSanPham;
    public function __construct()
    {
        $this->modelSanPham = new SanPham();
    }

    public function home()
    {
        echo "Đây là trang home của tôi";
    }
    public function trangchu()
    {
        echo "Đây là trang chủ của tôi";
    }
    public function danhSachSanPham()
    {
        $listProduct = $this->modelSanPham->getAllProduct();
        require_once './views/listProduct.php';
    }
}
