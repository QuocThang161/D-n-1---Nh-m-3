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

    public function home(){
        $listSanPham = $this->modelSanPham->getAllSanPham();
        require_once './views/home.php';
    }

    public function chiTietSanPham(){
        $id = $_GET['id_san_pham'];

        $sanPham = $this->modelSanPham->getDetailSanPham($id);

        $listAnhSanPham = $this->modelSanPham->getListAnhSanPham($id);

        // Luôn đảm bảo ảnh chính cũng xuất hiện trong gallery (dù album rỗng)
        if (!empty($sanPham['hinh_anh'])) {
            array_unshift($listAnhSanPham, ['link_hinh_anh' => $sanPham['hinh_anh']]);
        }

        $listSanPhamBienThe = $this->modelSanPham->getVariantsBySanPhamId($id);

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

    public function formLogin(){
       
        require_once './views/auth/formLogin.php';
        deleteSessionError();
        exit();
        
        }

    public function logout(){
        unset($_SESSION['user_client']);
        header("Location: " . BASE_URL . '?act=login');
        exit();
    }

    public function postLogin(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            // lấy email và pass gửi lên từ form 
            $email = $_POST['email'];
            $password = $_POST['password'];

            // var_dump($email);die;

            // Xử lý kiểm tra thông tin đăng nhập

            $user = $this->modelTaiKhoan->checkLogin($email, $password);

            if ($user == $email) { // Trường hợp đăng nhập thành công
                // Lưu thông tin vào session (lấy dữ liệu user đầy đủ)
                $userInfo = $this->modelTaiKhoan->getTaiKhoanFromEmail($email);
                if ($userInfo) {
                    $_SESSION['user_client'] = $userInfo;
                } else {
                    $_SESSION['user_client'] = $email; // fallback
                }
                header("Location: " . BASE_URL);
                exit();
            }else{
                // Lỗi thì lưu lỗi vào session
                $_SESSION['error'] = $user;
                // var_dump($_SESSION['error']);die;

                $_SESSION['flash'] = true;

                header("Location: " . BASE_URL . '?act=login');
                exit();
                
            }
        }
    }

    public function thongTinTaiKhoan()
    {
        if (isset($_SESSION['user_client'])) {
            $tai_khoan_id = is_array($_SESSION['user_client']) ? $_SESSION['user_client']['id'] : null;
            if ($tai_khoan_id === null) {
                $userClient = $this->modelTaiKhoan->getTaiKhoanFromEmail($_SESSION['user_client']);
                $tai_khoan_id = $userClient['id'] ?? null;
            }

            if (!$tai_khoan_id) {
                header("Location: " . BASE_URL . '?act=login');
                exit();
            }

            $user = $this->modelTaiKhoan->getTaiKhoanById($tai_khoan_id);
            require_once './views/thongTinTaiKhoan.php';
        } else {
            var_dump('Chưa đăng nhập');
            die;
        }
    }
    
    public function postEditCaNhan() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_client'])) {
            $userClient = $_SESSION['user_client'];
            if (is_array($userClient) && isset($userClient['id'])) {
                $id = $userClient['id'];
            } else {
                $userFromEmail = $this->modelTaiKhoan->getTaiKhoanFromEmail($userClient);
                $id = $userFromEmail['id'] ?? null;
            }

            if (!$id) {
                header("Location: " . BASE_URL . '?act=login');
                exit();
            }

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

        }

        $status = $status ?? false;

        if ($status) {
            // Cập nhật lại session user
            $_SESSION['user_client'] = $this->modelTaiKhoan->getTaiKhoanById($id);
            
            // Lưu thông báo vào session (chỉ tồn tại 1 lần)
            $_SESSION['success'] = "Cập nhật thông tin thành công!";
            
            header("Location: " . BASE_URL . "?act=thong-tin-tai-khoan");
            exit();
        }
    }


    public function addGioHang(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Debug: var_dump($_POST); die;
            if (isset($_SESSION['user_client'])) {
                $mail = $this->modelTaiKhoan->getTaiKhoanFromEmail($_SESSION['user_client']);
                if (!$mail) {
                    $_SESSION['error'] = 'Không tìm thấy tài khoản.';
                    header("Location: " . BASE_URL . '?act=login');
                    exit();
                }

                // Lấy dữ liệu giỏ hàng của người dùng
                
                $gioHang = $this->modelGioHang->getGioHangFromUser($mail['id']);
                if (!$gioHang) {
                    $gioHangId = $this->modelGioHang->addGioHang($mail['id']);
                    $gioHang = ['id'=>$gioHangId];
                    $chiTietGioHang = $this->modelGioHang->getDetailGioHang($gioHang['id']);
                }else{
                    $chiTietGioHang = $this->modelGioHang->getDetailGioHang($gioHang['id']);
                }

                $san_pham_id = $_POST['san_pham_id'];
                $so_luong = $_POST['so_luong'];
                $san_pham_bien_the_id = !empty($_POST['san_pham_bien_the_id']) ? $_POST['san_pham_bien_the_id'] : null;
                $selected_color = trim($_POST['variant_color'] ?? '');
                $selected_size = trim($_POST['variant_size'] ?? '');

                // Kiểm tra nếu sản phẩm có biến thể thì phải chọn biến thể
                $sanPham = $this->modelSanPham->getDetailSanPham($san_pham_id);
                $listBienThe = $this->modelSanPham->getVariantsBySanPhamId($san_pham_id);

                // FIX: không lấy san_pham.so_luong để kiểm kho, chỉ kiểm kho theo san_pham_bien_the
                // fallback: nếu không có san_pham_bien_the_id (JS failed) thì tìm variant server
                if (empty($san_pham_bien_the_id) && !empty($listBienThe) && $selected_color !== '' && $selected_size !== '') {
                    $variant = $this->modelSanPham->getVariantByProductColorSize($san_pham_id, $selected_color, $selected_size);
                    if ($variant) {
                        $san_pham_bien_the_id = $variant['id'];
                    }
                }

                // Nếu sản phẩm có danh sách biến thể nhưng ID gửi lên vẫn trống
                if (!empty($listBienThe) && (empty($san_pham_bien_the_id) || $san_pham_bien_the_id == '')) {
                    $_SESSION['error'] = 'Vui lòng chọn màu sắc và size cho sản phẩm này.';
                    header("Location: " . BASE_URL . '?act=chi-tiet-san-pham&id_san_pham=' . $san_pham_id);
                    exit();
                }

                if (!empty($san_pham_bien_the_id)) {
                    $checkVariant = $this->modelSanPham->checkVariantStock($san_pham_bien_the_id);
                    if (!$checkVariant['ok']) {
                        $_SESSION['error'] = $checkVariant['message'];
                        header("Location: " . BASE_URL . '?act=chi-tiet-san-pham&id_san_pham=' . $san_pham_id);
                        exit();
                    }

                    $requiredQty = (int)$so_luong;
                    $availableQty = (int)$checkVariant['variant']['so_luong_bien_the'];
                    if ($requiredQty > $availableQty) {
                        $_SESSION['error'] = 'Số lượng yêu cầu vượt quá tồn kho biến thể.';
                        header("Location: " . BASE_URL . '?act=chi-tiet-san-pham&id_san_pham=' . $san_pham_id);
                        exit();
                    }
                }

                $checkSanPham = false;
                foreach($chiTietGioHang as $detail){
                    if ($detail['san_pham_id'] == $san_pham_id
                        && ($detail['san_pham_bien_the_id'] == $san_pham_bien_the_id
                        || (is_null($detail['san_pham_bien_the_id']) && is_null($san_pham_bien_the_id)))) {
                        $newSoLuong = $detail['so_luong'] + $so_luong;
                        $this->modelGioHang->updateSoLuong($gioHang['id'], $san_pham_id, $newSoLuong, $san_pham_bien_the_id);
                        $checkSanPham = true;
                        break;
                    }
                }
                if(!$checkSanPham){
                    $this->modelGioHang->addDetailGioHang($gioHang['id'], $san_pham_id, $so_luong, $san_pham_bien_the_id);
                }
                header("Location:" . BASE_URL . '?act=gio-hang');
                exit();
            }else{
                $_SESSION['error'] = 'Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng.';
                header("Location: " . BASE_URL . '?act=form-login');
                exit();
            }
            

            
            
        }
    }

    public function xoaSanPhamGioHang(){
        if (isset($_SESSION['user_client'])) {
            $user = $this->modelTaiKhoan->getTaiKhoanFromEmail($_SESSION['user_client']);
            $gioHang = $this->modelGioHang->getGioHangFromUser($user['id']);
            
            if ($gioHang) {
                $san_pham_id = $_GET['id_san_pham'];
                $san_pham_bien_the_id = $_GET['id_bien_the'] ?? null;
                $this->modelGioHang->deleteDetailGioHang($gioHang['id'], $san_pham_id, $san_pham_bien_the_id);
            }
            header("Location: " . BASE_URL . '?act=gio-hang');
            exit();
        } else {
            header("Location: " . BASE_URL . '?act=login');
            exit();
        }
    }

    public function gioHang(){
        if (isset($_SESSION['user_client'])) {
            $mail = $this->modelTaiKhoan->getTaiKhoanFromEmail($_SESSION['user_client']);
            // Lấy dữ liệu giỏ hàng của người dùng
            
            $gioHang = $this->modelGioHang->getGioHangFromUser($mail['id']);
            if (!$gioHang) {
                $gioHangId = $this->modelGioHang->addGioHang($mail['id']);
                $gioHang = ['id'=>$gioHangId];
                $chiTietGioHang = $this->modelGioHang->getDetailGioHang($gioHang['id']);
            }else{
                $chiTietGioHang = $this->modelGioHang->getDetailGioHang($gioHang['id']);
            }
            // var_dump($chiTietGioHang);die;

            require_once './views/gioHang.php';

        }else{
            header("Location: ". BASE_URL . '?act=login');
        }
    }

    public function thanhToan(){
        if (isset($_SESSION['user_client'])) {
            $user = $this->modelTaiKhoan->getTaiKhoanFromEmail($_SESSION['user_client']);
            // Lấy dữ liệu giỏ hàng của người dùng
            
            $gioHang = $this->modelGioHang->getGioHangFromUser($user['id']);
            if (!$gioHang) {
                $gioHangId = $this->modelGioHang->addGioHang($user['id']);
                $gioHang = ['id'=>$gioHangId];
                $chiTietGioHang = $this->modelGioHang->getDetailGioHang($gioHang['id']);
            }else{
                $chiTietGioHang = $this->modelGioHang->getDetailGioHang($gioHang['id']);
            }
            // var_dump($chiTietGioHang);die;

            require_once './views/thanhToan.php';

        }else{
            var_dump('Chưa đăng nhập');die;
        }                               
                                            
        
    }

    public function postThanhToan(){
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

            $user = $this->modelTaiKhoan->getTaiKhoanFromEmail($_SESSION['user_client']);
            $tai_khoan_id = $user['id'];

            $ma_don_hang = 'DH-' . rand(1000,9999);

            // Thêm thông tin vào db 

            $don_hang_id = $this->modelDonHang->addDonHang($tai_khoan_id,
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

                // Kiểm tra tồn kho toàn bộ trước khi thêm đơn
                foreach($chiTietGioHang as $item){
                    if (!empty($item['san_pham_bien_the_id'])) {
                        $checkVariant = $this->modelSanPham->checkVariantStock($item['san_pham_bien_the_id']);
                        if (!$checkVariant['ok']) {
                            $_SESSION['error'] = 'Biến thể "' . $item['ten_san_pham'] . '" ' . $checkVariant['message'];
                            header("Location: " . BASE_URL . '?act=gio-hang');
                            exit();
                        }

                        if ((int)$item['so_luong'] > (int)$checkVariant['variant']['so_luong_bien_the']) {
                            $_SESSION['error'] = 'Số lượng "' . $item['ten_san_pham'] . '" không đủ.';
                            header("Location: " . BASE_URL . '?act=gio-hang');
                            exit();
                        }
                    }
                }

                // Thực hiện thêm chi tiết đơn và trừ tồn kho biến thể
                foreach($chiTietGioHang as $item){
                    $donGia = $item['gia_khuyen_mai'] ?? $item['gia_san_pham'];

                    $this->modelDonHang->addChiTietDonHang(
                        $don_hang_id,  // ID đơn hàng vừa tạo (số nguyên)
                        $item['san_pham_id'], // ID sản phẩm
                        $donGia,  // đơn giá
                        $item['so_luong'], //số lượng 
                        $donGia * $item['so_luong'], // Thành tiền
                        $item['san_pham_bien_the_id'] ?? null
                    );

                    if (!empty($item['san_pham_bien_the_id'])) {
                        // FIX: Trừ theo biến thể, không trừ san_phams.so_luong
                        $result = $this->modelSanPham->decreaseVariantStock($item['san_pham_bien_the_id'], $item['so_luong']);
                        if (!$result['success']) {
                            $_SESSION['error'] = 'Giảm tồn kho thất bại: ' . $result['message'];
                            header("Location: " . BASE_URL . '?act=gio-hang');
                            exit();
                        }
                    }
                }

                $this->modelGioHang->clearDetailGioHang($gioHang['id']);
                $this->modelGioHang->clearGioHang($tai_khoan_id);

                header("Location: " . BASE_URL . '?act=lich-su-mua-hang');
                exit();
            } else{
                var_dump("Lỗi đặt hàng vui lòng thử lại sau");
                die;
            }
        }
    }

    public function lichSuMuaHang(){
        if (isset($_SESSION['user_client'])) {
            //Lấy ra thông tin tài khoản đăng nhập

            $user = $this->modelTaiKhoan->getTaiKhoanFromEmail($_SESSION['user_client']);
            $tai_khoan_id = $user['id'];

            // Lấy ra danh sách trạng thái đơn hàng
            $arrTrangThaiDonHang = $this->modelDonHang->getTrangThaiDonHang(); // Đảm bảo gọi không tham số
            $trangThaiDonHang = array_column($arrTrangThaiDonHang, 'ten_trang_thai', 'id');

            // Lấy ra danh sách phương thức thanh toán
            $arrPhuongThucThanhToan = $this->modelDonHang->getPhuongThucThanhToan();
            $phuongThucThanhToan = array_column($arrPhuongThucThanhToan, 'ten_phuong_thuc', 'id');


            // Lấy ra danh sách đơn hàng của tài khoản
            $donHangs = $this->modelDonHang->getDonHangFromUser($tai_khoan_id);
           require_once './views/lichSuMuaHang.php';


        } else {
            var_dump('Chưa đăng nhập');die;
        }
    }

    public function chiTietMuaHang(){
         if (isset($_SESSION['user_client'])) {
            //Lấy ra thông tin tài khoản đăng nhập

            $user = $this->modelTaiKhoan->getTaiKhoanFromEmail($_SESSION['user_client']);
            $tai_khoan_id = $user['id'];

            // Lấy id đơn hàng truyền từ URL
            $donHangId = $_GET['id'];

             // Lấy ra danh sách trạng thái đơn hàng
            $arrTrangThaiDonHang = $this->modelDonHang->getTrangThaiDonHang(); // Đảm bảo gọi không tham số
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
            var_dump('Chưa đăng nhập');die;
        }
    
    }

    public function huyDonHang(){
         if (isset($_SESSION['user_client'])) {
            //Lấy ra thông tin tài khoản đăng nhập

            $user = $this->modelTaiKhoan->getTaiKhoanFromEmail($_SESSION['user_client']);
            $tai_khoan_id = $user['id'];

            // Lấy id đơn hàng truyền từ URL
            $donHangId = $_GET['id'];

            //Kiểm tra đơn hàng
            $donHang = $this->modelDonHang->getDonHangById($donHangId);

            if($donHang['tai_khoan_id'] != $tai_khoan_id){
                echo "Bạn không có quyền huỷ đơn hàng này";
                exit;
            }

            if($donHang['trang_thai_id'] != 1){
                echo "Chỉ đơn hàng chưa xác nhận mới có thể huỷ";
                exit;
            }

            // Huỷ đơn hàng

            $this->modelDonHang->updateTrangThaiDonHang($donHangId, 11);
            header("Location: " . BASE_URL . '?act=lich-su-mua-hang');
            exit();
        


        } else {
            var_dump('Chưa đăng nhập');die;
        }
    
    }

    public function postBinhLuan()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_SESSION['user_client'])) {
                $userId = $this->modelTaiKhoan->getTaiKhoanFromEmail($_SESSION['user_client'])['id'];
                $san_pham_id = $_POST['san_pham_id'];
                $noi_dung = $_POST['noi_dung'];
                $ngay_dang = date('Y-m-d');

                // Gọi model để thêm bình luận
                $status = $this->modelSanPham->insertBinhLuan($userId, $san_pham_id, $noi_dung, $ngay_dang);
                
                if ($status) {
                    header("Location: " . BASE_URL . "?act=chi-tiet-san-pham&id_san_pham=" . $san_pham_id);
                    exit();
                }
            } else {
                $_SESSION['error'] = "Vui lòng đăng nhập để bình luận";
                header("Location: " . BASE_URL . "?act=login");
                exit();
            }
        }
    }
}