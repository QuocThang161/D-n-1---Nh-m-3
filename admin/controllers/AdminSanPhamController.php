<?php

public function formEditDanhMuc(){
    // hàm này dùng để hiển thị form nhập
    //lấy ra thông tin của danh mục cần sửa
    $id = $_GET['id_danh_muc'];
    $danhMuc = $this->modelDanhMuc->getDetailDanhMuc($id);
    if($danhMuc){
        require_once './views/danhMuc/editDanhMuc.php';
    }else{
        header("Location: " . BASE_URL_ADMIN . '?act=danh-muc');
        exit();
    }
}

if (empty($error)){
    // nếu không có lỗi thì tiến hành thêm sản phẩm

    $san_pham_id = $this->modelSanPham=>insertSanPham($ten_san_pham, $gia_san_pham, $gia_khuyen_mai, $so_luong, $ngay_nhap, $danh_muc_id, $trang_thai, $mo_ta, $file_thumb);
    // xử lý thêm album ảnh sản phẩm img_array
    if(!empty($img_array['name'])){
        foreach ($img_array['name'] as $key =>$value){
            $file = [
                'name' => $img_array['name'][$key],
                'type' => $img_array['type'][$key],
                'tmp_name' => $img_array['tmp_name'][$key],
                'error' => $img_array['error'][$key],
                'size' => $img_array['size'][$key],
            ];

            $link_hinh_anh = uploadFile($file, './uploads');
            $this->modelSanPham->insertAlbumAnhSanPham($san_pham_id,$link_hinh_anh);
        }
    }
    header("location: " . BASE_URL_ADMIN . '?act=san-pham');
    exit();
}else{
    // trả về form và lỗi
    // đặt chỉ thị xóa session sau khi hiển thị form
    $_SESSION['flash']=true;
    header("Location: ". BASE_URL_ADMIN . '?act=form-them-san-pham');
    exit();
}


public function formEditSanPham(){
    // hàm này dùng để hiển thị form nhập
    //lấy ra thông tin của sản phẩm cần sửa
    $id = $_GET['id_san_pham'];
    $sanPham = $this->modelSanPham->getDetailSanPham($id);
    $listSanPham = $this->modelSanPham->getListAnhSanPham($id);
    $thisDanhMuc = $this->modelDanhMuc->getAllDanhMuc();
    if($sanPham){
        require_once './views/sanpham/editSanPham.php';
    }else{
        header("Location: " . BASE_URL_ADMIN . '?act=san-pham');
        exit();
    }
}

public function postEditDanhMuc(){
    // hàm này để xử lí thêm dữ liệu
    // kiểm tra xem dữ liệu có phải đc submit lên không
}