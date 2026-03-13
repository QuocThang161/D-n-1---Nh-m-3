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




public function formEditSanPham(){
    // hàm này dùng để hiển thị form nhập
    //lấy ra thông tin của sản phẩm cần sửa
    $id = $_GET['id_san_pham'];
    $sanPham = $this->modelSanPham->getDetailSanPham($id);
    $listSanPham = $this->modelSanPham->getListAnhSanPham($id);
    $thisDanhMuc = $this->modelDanhMuc->getAllDanhMuc();
    if($sanPham){
        require_once './views/sanpham/editSanPham.php';
        deteleSessionError();
    }else{
        header("Location: " . BASE_URL_ADMIN . '?act=san-pham');
        exit();
    }
}

public function postEditSanPham(){
    // hàm này để xử lí thêm dữ liệu
    // kiểm tra xem dữ liệu có phải đc submit lên không
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        // lấy ra dữ liệu
        // lấy ra dữ liệu của sản phẩm
        $san_pham_id = $_POST['san_pham_id'] ?? '';
        // truy vấn
        $sanPhamOld = $this->modelSanPham->getDetailSanPham($san_pham_id);
        $old_file = $sanPhamOld['hinh_anh']; // lấy ảnh cũ để phục vụ cho sửa ảnh

        $ten_san_pham = $_POST['ten_san_pham'] ?? '';
        $gia_san_pham = $_POST['gia_san_pham'] ?? '';
        $so_luong = $_POST['so_luong'] ?? '';
        $ngay_nhap = $_POST['ngay_nhap'] ?? '';
        $danh_muc_id = $_POST['danh_muc_id'] ?? '';
        $trang_thai = $_POST['trang_thai'] ?? '';
        $mo_ta = $_POST['mo_ta'] ?? '';
        $hinh_anh = $_POST['hinh_anh'] ?? null;
       

        $error = [];
        if(empty($ten_san_pham)){
            $errors['ten_san_pham'] = 'Tên sản phẩm không được để trống';
        }
        if(empty($gia_san_pham)){
            $errors['gia_san_pham'] = 'giá sản phẩm không được để trống';
        }
        if(empty($so_luong)){
            $errors['so_luong'] = 'số lượng sản phẩm không được để trống';
        }
        if(empty($ngay_nhap)){
            $errors['ngay_nhap'] = 'ngày nhập sản phẩm không được để trống';
        }
        if(empty($danh_muc_id)){
            $errors['danh_muc_id'] = 'danh mục sản phẩm không được để trống';
        }
        if(empty($trang_thai)){
            $errors['trang_thai'] = 'trạng thái sản phẩm không được để trống';
        }
        if($hinh_anh['error']!== 0){
            $errors['hinh_anh'] = 'Phải chọn ảnh sản phẩm';
        
        }
        $_SESSION['error'] = $errors;

        // logic sửa ảnh
        if(isset())

        // nếu không có lỗi thì tiến hành thêm sản phẩm
        if(empty($errors)){
            
        
    // nếu không có lỗi thì tiến hành thêm sản phẩm

    $san_pham_id = $this->modelSanPham->updateSanPham($ten_san_pham, $gia_san_pham, $gia_khuyen_mai, $so_luong, $ngay_nhap, $danh_muc_id, $trang_thai, $mo_ta, $file_thumb);
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
    }
}