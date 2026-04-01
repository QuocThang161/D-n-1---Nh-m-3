<?php

class HomeController
{
    public $modelSanPham;
    public $modelTaiKhoan;
    public $modelGioHang;
    public $modelDonHang;

    public function __construct()
    {
        $this->modelSanPham = new SanPham();
        $this->modelTaiKhoan = new TaiKhoan();
        $this->modelGioHang = new GioHang();
        $this->modelDonHang = new DonHang();
    }

    public function home()
    {
        $listSanPham = $this->modelSanPham->getAllSanPham();
        require_once './views/home.php';
    }

    public function chiTietSanPham()
    {
        $id = $_GET['id_san_pham'];

        $sanPham = $this->modelSanPham->getDetailSanPham($id);

        $listAnhSanPham = $this->modelSanPham->getListAnhSanPham($id);

        $listBinhLuan = $this->modelSanPham->getBinhLuanFromSanPham($id);

        $listSanPhamCungDanhMuc = $this->modelSanPham->getListSanPhamDanhMuc($sanPham['danh_muc_id']);

        // var_dump($listSanPhamCungDanhMuc);die;
        if ($sanPham) {
            require_once './views/detailSanPham.php';
        } else {
            header("Location: " . BASE_URL);
            exit();
        }
    }

    public function postBinhLuan()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_client'])) {
            $san_pham_id = $_POST['san_pham_id'];
            $noi_dung = $_POST['noi_dung'];
            $tai_khoan_id = $_SESSION['user_client']['id']; // Lấy ID người dùng đang đăng nhập
            $ngay_dang = date('Y-m-d H:i:s');

            // Gọi hàm vừa tạo ở Model
            $check = $this->modelSanPham->addBinhLuan($san_pham_id, $tai_khoan_id, $noi_dung, $ngay_dang);

            if ($check) {
                header("Location: " . BASE_URL . "?act=chi-tiet-san-pham&id_san_pham=" . $san_pham_id);
                exit();
            }
        } else {
            echo "Bạn cần đăng nhập để bình luận";
        }
    }

    public function formLogin()
    {

        require_once './views/auth/formLogin.php';
        deleteSessionError();
        exit();
    }

    public function logout()
    {
        unset($_SESSION['user_client']);
        header("Location: " . BASE_URL . '?act=login');
        exit();
    }

    public function postLogin()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            // Gọi Model để kiểm tra
            $user = $this->modelTaiKhoan->checkLogin($email, $password);

            // Sửa ở đây: Kiểm tra xem $user có phải là mảng dữ liệu hợp lệ không
            if (is_array($user)) {
                // Đăng nhập thành công -> Lưu mảng user vào Session
                $_SESSION['user_client'] = $user;

                header("Location: " . BASE_URL);
                exit();
            } else {
                // Đăng nhập thất bại -> $user lúc này thường là chuỗi thông báo lỗi
                $_SESSION['error'] = $user;
                $_SESSION['flash'] = true;

                header("Location: " . BASE_URL . '?act=login');
                exit();
            }
        }
    }

    public function addGioHang()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_SESSION['user_client'])) {
                $tai_khoan_id = $_SESSION['user_client']['id'];

                // Lấy dữ liệu từ form (nút Thêm vào giỏ hàng)
                $san_pham_id = $_POST['san_pham_id'];
                $so_luong = $_POST['so_luong'];

                // Bước 1: Tìm giỏ hàng của người dùng
                $gioHang = $this->modelGioHang->getGioHangFromUser($tai_khoan_id);

                // Nếu người dùng chưa có giỏ hàng thì tạo mới
                if (!$gioHang) {
                    $gioHangId = $this->modelGioHang->addGioHang($tai_khoan_id);
                    $gioHang = ['id' => $gioHangId];
                }

                // Bước 2: Lấy toàn bộ sản phẩm đang có trong giỏ để kiểm tra trùng
                $chiTietGioHang = $this->modelGioHang->getDetailGioHang($gioHang['id']);

                $checkSanPham = false;
                foreach ($chiTietGioHang as $detail) {
                    if ($detail['san_pham_id'] == $san_pham_id) {
                        // Nếu sản phẩm đã tồn tại -> Cập nhật cộng dồn số lượng
                        $newSoLuong = $detail['so_luong'] + $so_luong;
                        $this->modelGioHang->updateSoLuong($gioHang['id'], $san_pham_id, $newSoLuong);
                        $checkSanPham = true;
                        break;
                    }
                }

                // Bước 3: Nếu sản phẩm chưa có trong giỏ -> Thêm mới vào chi tiết
                if (!$checkSanPham) {
                    $this->modelGioHang->addDetailGioHang($gioHang['id'], $san_pham_id, $so_luong);
                }

                // Bước 4: Chuyển hướng sang trang giỏ hàng để xem kết quả
                header("Location: " . BASE_URL . '?act=gio-hang');
                exit();
            } else {
                // Nếu chưa đăng nhập, bắt quay lại trang login
                header("Location: " . BASE_URL . '?act=login');
                exit();
            }
        }
    }

    public function gioHang()
    {
        if (isset($_SESSION['user_client'])) {
            $tai_khoan_id = $_SESSION['user_client']['id'];
            $gioHang = $this->modelGioHang->getGioHangFromUser($tai_khoan_id);
            if (!$gioHang) {
                $gioHangId = $this->modelGioHang->addGioHang($tai_khoan_id);
                $gioHang = ['id' => $gioHangId];
                $chiTietGioHang = $this->modelGioHang->getDetailGioHang($gioHang['id']);
            } else {
                $chiTietGioHang = $this->modelGioHang->getDetailGioHang($gioHang['id']);
            }
            // var_dump($chiTietGioHang);die;

            require_once './views/gioHang.php';
        } else {
            header("Location: " . BASE_URL . '?act=login');
        }
    }

    public function thanhToan()
{
    if (isset($_SESSION['user_client'])) {
        $tai_khoan_id = $_SESSION['user_client']['id'];
        
        // --- THÊM DÒNG NÀY ---
        // Lấy thông tin chi tiết của người dùng để đổ vào form thanh toán
        $user = $this->modelTaiKhoan->getTaiKhoanById($tai_khoan_id);
        // ---------------------

        $gioHang = $this->modelGioHang->getGioHangFromUser($tai_khoan_id);
        if (!$gioHang) {
            $gioHangId = $this->modelGioHang->addGioHang($tai_khoan_id);
            $chiTietGioHang = $this->modelGioHang->getDetailGioHang($gioHangId);
        } else {
            $chiTietGioHang = $this->modelGioHang->getDetailGioHang($gioHang['id']);
        }

        // Truyền cả $user và $chiTietGioHang sang view
        require_once './views/thanhToan.php';
    } else {
        header("Location: " . BASE_URL . '?act=login');
        exit();
    }
}

    public function postThanhToan()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // var_dump($_POST);die;
            $ten_nguoi_nhan = $_POST['ten_nguoi_nhan'];
            $email_nguoi_nhan = $_POST['email_nguoi_nhan'];
            $sdt_nguoi_nhan = $_POST['sdt_nguoi_nhan'];
            $dia_chi_nguoi_nhan = $_POST['dia_chi_nguoi_nhan'];
            $ghi_chu = $_POST['ghi_chu'];
            $tong_tien = $_POST['tong_tien'];
            $phuong_thuc_thanh_toan_id = $_POST['phuong_thuc_thanh_toan_id'];

            $ngay_dat = date('Y-m-d');
            $trang_thai_id = 1;


            $tai_khoan_id = $_SESSION['user_client']['id'];

            $ma_don_hang = 'DH-' . rand(1000, 9999);

            // Thêm thông tin vào db 

            $don_hang_id = $this->modelDonHang->addDonHang(
                $tai_khoan_id,
                $ten_nguoi_nhan,
                $email_nguoi_nhan,
                $sdt_nguoi_nhan,
                $dia_chi_nguoi_nhan,
                $ghi_chu,
                $tong_tien,
                $phuong_thuc_thanh_toan_id,
                $ngay_dat,
                $ma_don_hang,
                $trang_thai_id
            );

            // Lấy thông tin đơn hàng
            $gioHang = $this->modelGioHang->getGioHangFromUser($tai_khoan_id);

            // Lưu sản phẩm vào chi tiết đơn hàng
            if ($gioHang) {
                // Lấy ra toàn bộ sản phẩm trong giỏ hàng
                $chiTietGioHang = $this->modelGioHang->getDetailGioHang($gioHang['id']);
                foreach ($chiTietGioHang as $item) {
                    $donGia = $item['gia_khuyen_mai'] ?? $item['gia_san_pham'];

                    $this->modelDonHang->addChiTietDonHang(
                        $don_hang_id,  // ID đơn hàng vừa tạo (số nguyên)
                        $item['san_pham_id'], // ID sản phẩm
                        $donGia,  // đơn giá
                        $item['so_luong'], //số lượng 
                        $donGia * $item['so_luong'] // Thành tiền
                    );
                }

                $this->modelGioHang->clearDetailGioHang($gioHang['id']);
                $this->modelGioHang->clearGioHang($tai_khoan_id);

                header("Location: " . BASE_URL . '?act=lich-su-mua-hang');
                exit();
            } else {
                var_dump("Lỗi đặt hàng vui lòng thử lại sau");
                die;
            }
        }
    }

    public function lichSuMuaHang()
    {
        if (isset($_SESSION['user_client'])) {
            //Lấy ra thông tin tài khoản đăng nhập

            $tai_khoan_id = $_SESSION['user_client']['id'];

            // Lấy ra danh sách trạng thái đơn hàng
            $arrTrangThaiDonHang = $this->modelDonHang->getTrangThaiDonHang();
            $trangThaiDonHang = array_column($arrTrangThaiDonHang, 'ten_trang_thai', 'id');

            // Lấy ra danh sách phương thức thanh toán
            $arrPhuongThucThanhToan = $this->modelDonHang->getPhuongThucThanhToan();
            $phuongThucThanhToan = array_column($arrPhuongThucThanhToan, 'ten_phuong_thuc', 'id');
            // var_dump($trangThaiDonHang); 
            // var_dump($phuongThucThanhToan);
            // die;

            // Lấy ra danh sách đơn hàng của tài khoản
            $donHangs = $this->modelDonHang->getDonHangFromUser($tai_khoan_id);
            require_once './views/lichSuMuaHang.php';
        } else {
            var_dump('Chưa đăng nhập');
            die;
        }
    }

    public function chiTietMuaHang()
    {
        if (isset($_SESSION['user_client'])) {
            //Lấy ra thông tin tài khoản đăng nhập

            $tai_khoan_id = $_SESSION['user_client']['id'];

            // Lấy id đơn hàng truyền từ URL
            $donHangId = $_GET['id'];

            // Lấy ra danh sách trạng thái đơn hàng
            $arrTrangThaiDonHang = $this->modelDonHang->getTrangThaiDonHang();
            $trangThaiDonHang = array_column($arrTrangThaiDonHang, 'ten_trang_thai', 'id');

            // Lấy ra danh sách phương thức thanh toán
            $arrPhuongThucThanhToan = $this->modelDonHang->getPhuongThucThanhToan();
            $phuongThucThanhToan = array_column($arrPhuongThucThanhToan, 'ten_phuong_thuc', 'id');

            // Lấy ra thông tin đơn hàng theo id
            $donHang = $this->modelDonHang->getDonHangById($donHangId);

            // Lấy thông tin sản phẩm của đơn hàng trong bảng chi tiết đơn hàng
            $chiTietDonHang = $this->modelDonHang->getChiTietDonHangById($donHangId);

            if ($donHang['tai_khoan_id'] != $tai_khoan_id) {
                echo "Bạn không có quyền truy cập đơn hàng này";
                exit;
            }

            require_once './views/chiTietMuaHang.php';
        } else {
            var_dump('Chưa đăng nhập');
            die;
        }
    }

    public function huyDonHang()
    {
        if (isset($_SESSION['user_client'])) {
            //Lấy ra thông tin tài khoản đăng nhập

            $tai_khoan_id = $_SESSION['user_client']['id'];

            // Lấy id đơn hàng truyền từ URL
            $donHangId = $_GET['id'];

            //Kiểm tra đơn hàng
            $donHang = $this->modelDonHang->getDonHangById($donHangId);

            if ($donHang['tai_khoan_id'] != $tai_khoan_id) {
                echo "Bạn không có quyền huỷ đơn hàng này";
                exit;
            }

            if ($donHang['trang_thai_id'] != 1) {
                echo "Chỉ đơn hàng chưa xác nhận mới có thể huỷ";
                exit;
            }

            // Huỷ đơn hàng

            $this->modelDonHang->updateTrangThaiDonHang($donHangId, 11);
            header("Location: " . BASE_URL . '?act=lich-su-mua-hang');
            exit();
        } else {
            var_dump('Chưa đăng nhập');
            die;
        }
    }
    public function thongTinTaiKhoan()
    {
        if (isset($_SESSION['user_client'])) {
            $tai_khoan_id = $_SESSION['user_client']['id'];
            $user = $this->modelTaiKhoan->getTaiKhoanById($tai_khoan_id);
            require_once './views/thongTinTaiKhoan.php';
        } else {
            var_dump('Chưa đăng nhập');
            die;
        }
    }
    // Trong HomeController.php
    public function postEditCaNhan()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_client'])) {
            $id = $_SESSION['user_client']['id'];
            $userOld = $this->modelTaiKhoan->getTaiKhoanById($id);

            // Chuẩn bị dữ liệu
            $ho_ten = $_POST['ho_ten'];
            $so_dien_thoai = $_POST['so_dien_thoai'];
            $dia_chi = $_POST['dia_chi'];
            $ngay_sinh = $_POST['ngay_sinh'];
            $gioi_tinh = $_POST['gioi_tinh'];

            $anh_dai_dien = $_FILES['anh_dai_dien'];
            $path_anh_dai_dien = $userOld['anh_dai_dien']; // Mặc định là ảnh cũ

            if ($anh_dai_dien['error'] == 0) {
                // Upload ảnh mới vào thư mục ./uploads/
                $new_path = uploadFile($anh_dai_dien, './uploads/');
                if ($new_path) {
                    $path_anh_dai_dien = $new_path;
                    // Xóa ảnh cũ nếu nó tồn tại
                    deleteFile($userOld['anh_dai_dien']);
                }
            }

            $status = $this->modelTaiKhoan->updateTaiKhoan($id, $ho_ten, $so_dien_thoai, $dia_chi, $ngay_sinh, $gioi_tinh, $path_anh_dai_dien);

            if ($status) {
                // Cập nhật lại session user
                $_SESSION['user_client'] = $this->modelTaiKhoan->getTaiKhoanById($id);

                // Lưu thông báo vào session (chỉ tồn tại 1 lần)
                $_SESSION['success'] = "Cập nhật thông tin thành công!";

                header("Location: " . BASE_URL . "?act=thong-tin-tai-khoan");
                exit();
            }
        }
    }

    public function updateQuantity() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_client'])) {
        $tai_khoan_id = $_SESSION['user_client']['id'];
        $san_pham_id = $_POST['product_id'];
        $so_luong = $_POST['quantity'];

        // Bước 1: Tìm giỏ hàng của người dùng
        $gioHang = $this->modelGioHang->getGioHangFromUser($tai_khoan_id);

        if ($gioHang) {
            // Bước 2: Cập nhật trực tiếp số lượng vào Database
            // Hàm updateSoLuong này bạn đã có và đang dùng ở hàm addGioHang rồi
            $this->modelGioHang->updateSoLuong($gioHang['id'], $san_pham_id, $so_luong);
            
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Không tìm thấy giỏ hàng']);
        }
        exit;
        }
    }

    public function xoaGioHang()
{
    if (isset($_SESSION['user_client'])) {
        // Lấy ID chi tiết giỏ hàng từ URL
        $chiTietGioHangId = $_GET['id_chi_tiet_gio_hang'];

        // Gọi Model để xóa
        $this->modelGioHang->deleteDetailGioHang($chiTietGioHangId);

        // Chuyển hướng quay lại trang cũ (hoặc trang giỏ hàng)
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();
    } else {
        header("Location: " . BASE_URL . '?act=login');
        exit();
    }
}
}
