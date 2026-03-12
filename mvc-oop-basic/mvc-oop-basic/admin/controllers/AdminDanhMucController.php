<?php
class AdminDanhMucController
{
    public $modelDanhmuc;
    public function __construct()
    {
        $this->modelDanhmuc = new AdminDanhMuc();
    }
    public function danhSachDanhMuc()
    {
        $listDanhMuc = $this->modelDanhmuc->getAllDanhmuc();
        require_once './views/danhmuc/DanhMuc.php';
    }
}
