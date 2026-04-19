<?php

class AdminDonHangController
{
    public $modelDonHang;

    public function __construct()
    {
        $this->modelDonHang = new AdminDonHang();
        $this->modelSanPham = new AdminSanPham();
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

        // Nếu không tìm thấy đơn hàng thì chuyển hướng về danh sách
        if (!$donHang) {
            // Optionally set a flash message
            $_SESSION['error'] = 'Không tìm thấy đơn hàng.';
            header("Location: " . BASE_URL_ADMIN . '?act=don-hang');
            exit();
        }

        // Lấy danh sách sản phẩm đã đặt của đơn hàng ở bảng chi_tiet_don_hangs

        $sanPhamDonHang = $this->modelDonHang->getListSpDonHang($don_hang_id);

        $listTrangThaiDonHang = $this->modelDonHang->getAllTrangThaiDonHang();

        require_once './views/donhang/detailDonHang.php';
    }



    public function formEditDonHang()
    {

        $id = $_GET['id_don_hang'];
        $donHang = $this->modelDonHang->getDetailDonHang($id);
        $listTrangThaiDonHang = $this->modelDonHang->getAllTrangThaiDonHang();
        if ($donHang) {
            require_once './views/donhang/editDonHang.php';
            deleteSessionError();
        } else {
            header("Location: " . BASE_URL_ADMIN . '?act=don-hang');
            exit();
        }
    }


   public function postEditDonHang()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $don_hang_id = $_POST['don_hang_id'] ?? '';

            // Lấy đơn cũ
            $donHangOld = $this->modelDonHang->getDetailDonHang($don_hang_id);

            if (!$donHangOld) {
                $_SESSION['error'] = 'Đơn hàng không tồn tại';
                header("Location: " . BASE_URL_ADMIN . '?act=don-hang');
                exit();
            }

            // Lấy dữ liệu + trim
            $ten_nguoi_nhan = trim($_POST['ten_nguoi_nhan'] ?? '');
            $sdt_nguoi_nhan = trim($_POST['sdt_nguoi_nhan'] ?? '');
            $email_nguoi_nhan = trim($_POST['email_nguoi_nhan'] ?? '');
            $dia_chi_nguoi_nhan = trim($_POST['dia_chi_nguoi_nhan'] ?? '');
            $ghi_chu = trim($_POST['ghi_chu'] ?? '');
            $trang_thai_id = (int)($_POST['trang_thai_id'] ?? 0);

            $errors = [];

            // VALIDATE
            if ($ten_nguoi_nhan === '') {
                $errors['ten_nguoi_nhan'] = 'Tên người nhận không được để trống';
            }

            if ($sdt_nguoi_nhan === '') {
                $errors['sdt_nguoi_nhan'] = 'SĐT không được để trống';
            } elseif (!preg_match('/^[0-9]{9,11}$/', $sdt_nguoi_nhan)) {
                $errors['sdt_nguoi_nhan'] = 'SĐT không hợp lệ';
            }

            if ($email_nguoi_nhan === '') {
                $errors['email_nguoi_nhan'] = 'Email không được để trống';
            } elseif (!filter_var($email_nguoi_nhan, FILTER_VALIDATE_EMAIL)) {
                $errors['email_nguoi_nhan'] = 'Email không hợp lệ';
            }

            if ($dia_chi_nguoi_nhan === '') {
                $errors['dia_chi_nguoi_nhan'] = 'Địa chỉ không được để trống';
            }

            if ($trang_thai_id === 0) {
                $errors['trang_thai_id'] = 'Chọn trạng thái đơn hàng';
            }

            $_SESSION['error'] = $errors;

            // Nếu không lỗi
            if (empty($errors)) {

                try {
                
                    $this->modelDonHang->conn->beginTransaction();

                    // UPDATE đơn hàng
                    $status = $this->modelDonHang->updateDonHang(
                        $don_hang_id,
                        $ten_nguoi_nhan,
                        $sdt_nguoi_nhan,
                        $email_nguoi_nhan,
                        $dia_chi_nguoi_nhan,
                        $ghi_chu,
                        $trang_thai_id
                    );

                    // Nếu chuyển sang huỷ → hoàn kho
                    if ($status && $trang_thai_id == 11 && ($donHangOld['trang_thai_id'] ?? 0) != 11) {

                        $sanPhamDonHang = $this->modelDonHang->getListSpDonHang($don_hang_id);

                        foreach ($sanPhamDonHang as $item) {

                            if (!empty($item['san_pham_bien_the_id']) && $item['so_luong'] > 0) {

                                $this->modelSanPham->tangSoLuongBienThe(
                                    $item['san_pham_bien_the_id'],
                                    (int)$item['so_luong']
                                );
                            }
                        }
                    }

                   
                    $this->modelDonHang->conn->commit();

                    header("Location: " . BASE_URL_ADMIN . '?act=don-hang');
                    exit();

                } catch (Exception $e) {

                    
                    $this->modelDonHang->conn->rollBack();

                    die("Lỗi hệ thống: " . $e->getMessage());
                }

            } else {

                $_SESSION['flash'] = true;

                header("Location: " . BASE_URL_ADMIN . '?act=form-sua-don-hang&id_don_hang=' . $don_hang_id);
                exit();
            }
        }
    }

    // // // Sửa album ảnh
    // // - Sửa ảnh cũ
    // //  + Thêm ảnh mới 
    // //  + Không thêm ảnh mới
    // // - Không sửa ảnh cũ
    // //  + Thêm ảnh mới 
    // //  + Không thêm ảnh mới 
    // // - Xóa ảnh cũ
    // //  + Thêm ảnh mới 
    // //  + Không thêm ảnh mới 

    // public function postEditAnhSanPham(){
    //     if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //         $san_pham_id = $_POST['san_pham_id'] ?? '';

    //         // Lấy danh sách ảnh hiện tại của sản phẩm
    //         $listAnhSanPhamCurrent = $this->modelSanPham->getListAnhSanPham($san_pham_id);

    //         // xử lý các ảnh được gửi từ form
    //         $img_array = $_FILES['img_array'];
    //         $img_delete = isset($_POST['img_delete']) ? explode(',', $_POST['img_delete']) : [];
    //         $current_img_ids = $_POST['current_img_ids'] ?? [];

    //         // Khai báo mảng để lưu ảnh thêm mới hoặc thay thế ảnh cũ 
    //         $upload_file = [];

    //         // Upload ảnh mới hoặc thay thế ảnh cũ 
    //         foreach($img_array['name'] as $key=>$value){
    //             if ($img_array['error'][$key] == UPLOAD_ERR_OK) {
    //                 $new_file = uploadFileAlbum($img_array, './uploads/', $key);
    //                 if ($new_file) {
    //                     $upload_file[] = [
    //                         'id' => $current_img_ids[$key] ?? null,
    //                         'file' => $new_file
    //                     ];
    //                 }
    //             }
    //         }

    //         // Lưu ảnh mới vào db và xóa ảnh cũ nếu có 
    //         foreach($upload_file as $file_info){
    //             if ($file_info['id']) {
    //                 $old_file = $this->modelSanPham->getDetailAnhSanPham($file_info['id'])['link_hinh_anh'];

    //                 // cập nhật ảnh cũ
    //                 $this->modelSanPham->updateAnhSanPham($file_info['id'], $file_info['file']);

    //                 // xóa ảnh cũ
    //                 deleteFile($old_file);
    //             } else{
    //                 // Thêm ảnh mới 
    //                 $this->modelSanPham->insertAlbumAnhSanPham($san_pham_id, $file_info['file']);
    //             }
    //         }

    //         // Xử lý xóa ảnh 
    //         foreach($listAnhSanPhamCurrent as $anhSP){
    //             $anh_id = $anhSP['id'];
    //             if (in_array($anh_id, $img_delete)) {
    //                 // Xóa ảnh trong db
    //                 $this->modelSanPham->destroyAnhSanPham($anh_id);

    //                 // Xóa file 
    //                 deleteFile($anhSP['link_hinh_anh']);
    //             }
    //         }
    //         header("Location: " . BASE_URL_ADMIN . '?act=form-sua-san-pham&id_san_pham=' . $san_pham_id);
    //         exit();
    //     }
    // }


    // public function deleteSanPham()
    // {
    //     $id = $_GET['id_san_pham'];
    //     $sanPham = $this->modelSanPham->getDetailSanPham($id);

    //     $listAnhSanPham = $this->modelSanPham->getListAnhSanPham($id);


    //     if ($sanPham) {
    //         deleteFile($sanPham['hinh_anh']);
    //         $this->modelSanPham->destroySanPham($id);
    //     }
    //     if ($listAnhSanPham) {
    //         foreach($listAnhSanPham as $key=>$anhSP){
    //             deleteFile($anhSP['link_hinh_anh']);
    //             $this->modelSanPham->destroyAnhSanPham($anhSP['id']);
    //         }
    //     }

    //     header("Location: " . BASE_URL_ADMIN . '?act=san-pham');
    //     exit();
    // }


    // public function detailSanPham()
    // {
    //     $id = $_GET['id_san_pham'];

    //     $sanPham = $this->modelSanPham->getDetailSanPham($id);

    //     $listAnhSanPham = $this->modelSanPham->getListAnhSanPham($id);

    //     // var_dump($listAnhSanPham);die;
    //     if ($sanPham) {
    //         require_once './views/sanpham/detailSanPham.php';

    //     } else {
    //         header("Location: " . BASE_URL_ADMIN . '?act=san-pham');
    //         exit();
    //     }
    // }
}