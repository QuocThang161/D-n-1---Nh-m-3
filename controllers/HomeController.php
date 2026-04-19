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

    public function cuaHang(){
        $id_dan_muc = $_GET['id_dan_muc'] ?? null;
        $sort = $_GET['sort'] ?? '';
        $search = $_GET['search'] ?? null; // Lấy tham số tìm kiếm
        
        // Logic phân trang
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = 9;
        $offset = ($page - 1) * $perPage;

        if ($id_dan_muc) {
            $totalItems = $this->modelSanPham->countSanPhamByDanhMuc($id_dan_muc, $search);
            $listSanPham = $this->modelSanPham->getSanPhamByDanhMucPage($id_dan_muc, $perPage, $offset, $sort, $search);
        } else {
            $totalItems = $this->modelSanPham->countAllSanPham($search);
            $listSanPham = $this->modelSanPham->getSanPhamPage($perPage, $offset, $sort, $search);
        }

        $totalPages = ceil($totalItems / $perPage);
        $listDanhMuc = $this->modelSanPham->getAllDanhMuc();
        
        require_once './views/cuaHang.php';
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

        // Trừ số lượng sản phẩm đã có trong giỏ hàng để hiển thị tồn kho thực tế cho người dùng
        if (isset($_SESSION['user_client'])) {
            $user = $this->modelTaiKhoan->getTaiKhoanFromEmail($_SESSION['user_client']);
            if ($user) {
                $gioHang = $this->modelGioHang->getGioHangFromUser($user['id']);
                if ($gioHang) {
                    $chiTietGioHang = $this->modelGioHang->getDetailGioHang($gioHang['id']);
                    foreach ($listSanPhamBienThe as &$bienThe) {
                        foreach ($chiTietGioHang as $itemCart) {
                            if ($itemCart['san_pham_bien_the_id'] == $bienThe['id']) {
                                $bienThe['so_luong_bien_the'] -= $itemCart['so_luong_gio'];
                            }
                        }
                    }
                }
            }
        }

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

            if (is_array($user)) { // Trường hợp đăng nhập thành công (giống AdminController)
                $_SESSION['user_client'] = $user;
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

    public function formRegister(){
        require_once './views/auth/formRegister.php';
        deleteSessionError();
        exit();
    }

    public function postRegister(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $ho_ten = $_POST['ho_ten'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];
            $so_dien_thoai = $_POST['so_dien_thoai'];
            $ngay_sinh = $_POST['ngay_sinh'];
            $dia_chi = $_POST['dia_chi'];

            if ($password !== $confirm_password) {
                $_SESSION['error'] = "Mật khẩu nhập lại không khớp.";
                header("Location: " . BASE_URL . '?act=dang-ky');
                exit();
            }

            // Kiểm tra email tồn tại
            $checkUser = $this->modelTaiKhoan->getTaiKhoanFromEmail($email);
            if ($checkUser) {
                $_SESSION['error'] = "Email này đã được đăng ký.";
                header("Location: " . BASE_URL . '?act=dang-ky');
                exit();
            }

            $passwordHash = password_hash($password, PASSWORD_BCRYPT);
            $status = $this->modelTaiKhoan->insertTaiKhoan($ho_ten, $email, $passwordHash, 2, $so_dien_thoai, $ngay_sinh, $dia_chi); // 2: Khách hàng

            if ($status) {
                $_SESSION['success'] = "Đăng ký thành công! Vui lòng đăng nhập.";
                header("Location: " . BASE_URL . '?act=login');
                exit();
            } else {
                $_SESSION['error'] = "Đã có lỗi xảy ra, vui lòng thử lại.";
                header("Location: " . BASE_URL . '?act=dang-ky');
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

                // Kiểm tra trạng thái và tổng tồn kho của sản phẩm
                if ($sanPham['trang_thai'] != 1 || $sanPham['so_luong_thuc_te'] <= 0) {
                    $_SESSION['error'] = 'Sản phẩm này hiện không còn bán hoặc đã hết hàng.';
                    header("Location: " . BASE_URL . '?act=chi-tiet-san-pham&id_san_pham=' . $san_pham_id);
                    exit();
                }

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
                    // Tìm số lượng biến thể này đã có sẵn trong giỏ hàng
                    $currentQtyInCart = 0;
                    foreach ($chiTietGioHang as $detail) {
                        if ($detail['san_pham_bien_the_id'] == $san_pham_bien_the_id) {
                            $currentQtyInCart = $detail['so_luong_gio'];
                            break;
                        }
                    }

                    // Kiểm tra: (Số lượng đã có + Số lượng sắp thêm) có vượt quá tồn kho không
                    $tongSoLuongYeuCau = (int)$so_luong + (int)$currentQtyInCart;
                    
                    if (!$this->modelSanPham->checkSoLuongBienThe($san_pham_bien_the_id, $tongSoLuongYeuCau)) {
                        $_SESSION['error'] = 'Tổng số lượng trong giỏ hàng vượt quá số lượng sản phẩm còn lại trong kho.';
                        header("Location: " . BASE_URL . '?act=chi-tiet-san-pham&id_san_pham=' . $san_pham_id);
                        exit();
                    }
                }

                $checkSanPham = false;
                foreach($chiTietGioHang as $detail){
                    if ($detail['san_pham_id'] == $san_pham_id
                        && ($detail['san_pham_bien_the_id'] == $san_pham_bien_the_id
                        || (is_null($detail['san_pham_bien_the_id']) && is_null($san_pham_bien_the_id)))) {
                        $newSoLuong = $detail['so_luong_gio'] + $so_luong;
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
                // Nếu id_bien_the rỗng (từ URL) thì chuyển về null để query SQL đúng
                $san_pham_bien_the_id = !empty($_GET['id_bien_the']) ? $_GET['id_bien_the'] : null;
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
                        // Gọi hàm check số lượng mới
                        if (!$this->modelSanPham->checkSoLuongBienThe($item['san_pham_bien_the_id'], $item['so_luong_gio'])) {
                            $_SESSION['error'] = 'Sản phẩm "' . $item['ten_san_pham'] . '" đã hết hàng hoặc không đủ số lượng.';
                            header("Location: " . BASE_URL . '?act=gio-hang');
                            exit();
                        }
                    }
                }

                // Thực hiện thêm chi tiết đơn và trừ tồn kho biến thể
                foreach($chiTietGioHang as $item){
                    $donGia = $item['gia_khuyen_mai'] ?? $item['gia_san_pham'];
                    $soLuong = (int)$item['so_luong_gio'];
                    $thanhTien = $donGia * $soLuong;

                    $this->modelDonHang->addChiTietDonHang(
                        $don_hang_id,  // ID đơn hàng vừa tạo (số nguyên)
                        $item['san_pham_id'], // ID sản phẩm
                        $donGia,  // đơn giá
                        $soLuong, //số lượng (có thể là 0)
                        $thanhTien, // Thành tiền (sẽ là 0 nếu số lượng là 0)
                        $item['san_pham_bien_the_id'] ?? null
                    );

                    // Chỉ thực hiện trừ tồn kho nếu số lượng đặt lớn hơn 0
                    if (!empty($item['san_pham_bien_the_id']) && $soLuong > 0) {
                        $status = $this->modelSanPham->giamSoLuongBienThe($item['san_pham_bien_the_id'], $soLuong);
                        // Trong trường hợp này, nếu checkSoLuongBienThe đã pass ở trên thì giamSoLuong sẽ thành công
                    }
                    
                    if (isset($status) && $status === false) {
                        $_SESSION['error'] = 'Lỗi cập nhật tồn kho cho sản phẩm: ' . $item['ten_san_pham'];
                        header("Location: " . BASE_URL . '?act=gio-hang');
                        exit();
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
            $status = $this->modelDonHang->updateTrangThaiDonHang($donHangId, 11);
            
            if ($status) {
                // Hoàn lại số lượng vào kho biến thể
                $chiTietDonHang = $this->modelDonHang->getChiTietDonHangById($donHangId);
                foreach ($chiTietDonHang as $item) {
                    if (!empty($item['san_pham_bien_the_id'])) {
                        $this->modelSanPham->tangSoLuongBienThe($item['san_pham_bien_the_id'], $item['so_luong']);
                    }
                }
            }
            
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

    public function updateSoLuongGioHang()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_client'])) {

            $user = $this->modelTaiKhoan->getTaiKhoanFromEmail($_SESSION['user_client']);
            $gioHang = $this->modelGioHang->getGioHangFromUser($user['id']);

            if (!$gioHang) {
                header("Location: " . BASE_URL . '?act=gio-hang');
                exit();
            }

            $san_pham_id = $_POST['id_san_pham'];
            $bien_the = !empty($_POST['id_bien_the']) ? $_POST['id_bien_the'] : null;
            $so_luong = (int)$_POST['so_luong'];

            // XÓA nếu = 0
            if ($so_luong <= 0) {
                $this->modelGioHang->deleteDetailGioHang(
                    $gioHang['id'],
                    $san_pham_id,
                    $bien_the
                );

                $_SESSION['success'] = "Đã xóa sản phẩm";
                header("Location: " . BASE_URL . '?act=gio-hang');
                exit();
            }

            // CHECK KHO
            if ($bien_the) {
                $variant = $this->modelSanPham->getVariantById($bien_the);

                if ($variant && $so_luong > $variant['so_luong_bien_the']) {
                    $_SESSION['error'] = "Quá số sản phẩm trong kho";
                    header("Location: " . BASE_URL . '?act=gio-hang');
                    exit();
                }
            } else {
                $sp = $this->modelSanPham->getDetailSanPham($san_pham_id);

                if ($sp && $so_luong > $sp['so_luong']) {
                    $_SESSION['error'] = "Quá số sản phẩm trong kho";
                    header("Location: " . BASE_URL . '?act=gio-hang');
                    exit();
                }
            }

            // UPDATE
            $this->modelGioHang->updateSoLuong(
                $gioHang['id'],
                $san_pham_id,
                $so_luong,
                $bien_the
            );

            $_SESSION['success'] = "Cập nhật thành công";

            header("Location: " . BASE_URL . '?act=gio-hang');
            exit();
        }
    }
}