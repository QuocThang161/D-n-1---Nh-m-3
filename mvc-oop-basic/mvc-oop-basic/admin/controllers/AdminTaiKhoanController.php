<?php 

class AdminTaiKhoanController
{
    public $modelTaiKhoan;
    public $modelDonHang;
    public $modelSanPham;

    public function __construct()
    {
        $this->modelTaiKhoan = new AdminTaiKhoan();
        $this->modelDonHang = new AdminDonHang();
        $this->modelSanPham = new AdminSanPham();
    }

    public function danhSachQuanTri()
    {
        $listQuanTri = $this->modelTaiKhoan->getAllTaiKhoan(1);

        require_once './views/taikhoan/quantri/listQuanTri.php';
    }

    public function formAddQuanTri(){
        require_once './views/taikhoan/quantri/addQuanTri.php';

        deleteSessionError();
    }


    public function postAddQuanTri()
    {
        // Hàm này dùng để xử lý thêm dữ liệu

        // Kiểm tra xem dữ liệu có phải đc submit lên không
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Lấy ra dữ liệu
            $ho_ten = $_POST['ho_ten'];
            $email = $_POST['email'];

            // Tạo 1 mảng trống để chứa dữ liệu
            $errors = [];
            if (empty($ho_ten)) {
                $errors['ho_ten'] = 'Tên không được để trống';
            }

            if (empty($email)) {
                $errors['email'] = 'Email không được để trống';
            }

            $_SESSION['error'] = $errors;

            // Nếu ko có lỗi thì tiến hành thêm tài khoản
            if (empty($errors)) {
                // Nếu ko có lỗi thì tiến hành thêm tài khoản
                // var_dump('Oke');
                // đặt password mặc định - 123@123ab
                $password = password_hash('123@123ab', PASSWORD_BCRYPT);

                // Khai báo chức vụ
                $chuc_vu_id = 1;
                // var_dump($password);die;
                $this->modelTaiKhoan->insertTaiKhoan($ho_ten, $email, $password, $chuc_vu_id);
                
                header("Location: " . BASE_URL_ADMIN . '?act=list-tai-khoan-quan-tri');
                exit();
            } else {
                // Trả về form và lỗi
                $_SESSION['flash'] = true;

                header("Location: " . BASE_URL_ADMIN . '?act=form-them-quan-tri');
                exit();
            }
        }
    }

    public function formEditQuanTri(){
        $id_quan_tri = $_GET['id_quan_tri'];
        $quanTri = $this->modelTaiKhoan->getDetailTaiKhoan($id_quan_tri);
        // var_dump($quanTri);die;
        require_once './views/taikhoan/quantri/editQuanTri.php';

        deleteSessionError();
    }

    public function postEditQuanTri()
    {

        // Kiểm tra xem dữ liệu có phải đc submit lên không
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Lấy ra dữ liệu
            
            $quan_tri_id = $_POST['quan_tri_id'] ?? '';


            $ho_ten = $_POST['ho_ten'] ?? '';
            $email = $_POST['email'] ?? '';
            $so_dien_thoai = $_POST['so_dien_thoai'] ?? '';
            $trang_thai = $_POST['trang_thai'] ?? '';
            

            // Tạo 1 mảng trống để chứa dữ liệu
            $errors = [];
            
            if (empty($ho_ten)) {
                $errors['ho_ten'] = 'Tên người dùng không được để trống';
            }
            if (empty($email)) {
                $errors['email'] = 'Email người dùng không được để trống';
            }

            if (empty($trang_thai)) {
                $errors['trang_thai'] = 'Vui lòng chọn trạng thái';
            }
            
            $_SESSION['error'] = $errors;
            
            if (empty($errors)) {
                
                $this->modelTaiKhoan->updateTaiKhoan($quan_tri_id,
                                                    $ho_ten, 
                                                    $email, 
                                                    $so_dien_thoai, 
                                                    $trang_thai
                                                );

                // var_dump($abc);die;
                header("Location: " . BASE_URL_ADMIN . '?act=list-tai-khoan-quan-tri');
                exit();
            } else {
                // Trả về form và lỗi
                // Đặt chỉ thị xóa session sau khi hiển thị form 
                $_SESSION['flash'] = true;
                header("Location: " . BASE_URL_ADMIN . '?act=form-sua-quan-tri&id_quan_tri=' . $quan_tri_id);
                exit();
            }
        }
    }

    public function resetPassword(){
        $tai_khoan_id = $_GET['id_quan_tri'];
        $tai_khoan = $this->modelTaiKhoan->getDetailTaiKhoan($tai_khoan_id);
        // đặt password mặc định - 123@123ab
        $password = password_hash('123@123ab', PASSWORD_BCRYPT);

        $status = $this->modelTaiKhoan->resetPassword($tai_khoan_id, $password);
        // var_dump($status);die;
        if ($status && $tai_khoan['chuc_vu_id'] == 1) {
            header("Location: " . BASE_URL_ADMIN . '?act=list-tai-khoan-quan-tri');
            exit();
        }elseif($status && $tai_khoan['chuc_vu_id'] == 2){
            header("Location: " . BASE_URL_ADMIN . '?act=list-tai-khoan-khach-hang');
            exit();
        }else{
            var_dump('Lỗi khi reset tài khoản');die;
        }
    }

    public function danhSachKhachHang()
    {
        $listKhachHang = $this->modelTaiKhoan->getAllTaiKhoan(2);

        require_once './views/taikhoan/khachhang/listKhachHang.php';
    }

    public function formEditKhachHang(){
        $id_khach_hang = $_GET['id_khach_hang'];
        $khachHang = $this->modelTaiKhoan->getDetailTaiKhoan($id_khach_hang);

        require_once './views/taikhoan/khachhang/editKhachHang.php';

        deleteSessionError();
    }

    public function postEditKhachHang()
    {

        // Kiểm tra xem dữ liệu có phải đc submit lên không
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Lấy ra dữ liệu
            
            $khach_hang_id = $_POST['khach_hang_id'] ?? '';


            $ho_ten = $_POST['ho_ten'] ?? '';
            $email = $_POST['email'] ?? '';
            $so_dien_thoai = $_POST['so_dien_thoai'] ?? '';
            $ngay_sinh = $_POST['ngay_sinh'] ?? '';
            $gioi_tinh = $_POST['gioi_tinh'] ?? '';
            $dia_chi = $_POST['dia_chi'] ?? '';
            $trang_thai = $_POST['trang_thai'] ?? '';
            

            // Tạo 1 mảng trống để chứa dữ liệu
            $errors = [];
            
            if (empty($ho_ten)) {
                $errors['ho_ten'] = 'Tên người dùng không được để trống';
            }
            if (empty($email)) {
                $errors['email'] = 'Email người dùng không được để trống';
            }

            if (empty($ngay_sinh)) {
                $errors['ngay_sinh'] = 'ngày sinh người dùng không được để trống';
            }

            if (empty($gioi_tinh)) {
                $errors['gioi_tinh'] = 'giới tính người dùng không được để trống';
            }

            if (empty($trang_thai)) {
                $errors['trang_thai'] = 'Vui lòng chọn trạng thái';
            }
            
            $_SESSION['error'] = $errors;
            
            if (empty($errors)) {
                
                $this->modelTaiKhoan->updateKhachHang($khach_hang_id,
                                                    $ho_ten, 
                                                    $email, 
                                                    $so_dien_thoai, 
                                                    $ngay_sinh, 
                                                    $gioi_tinh, 
                                                    $dia_chi, 
                                                    $trang_thai
                                                );

                // var_dump($abc);die;
                header("Location: " . BASE_URL_ADMIN . '?act=list-tai-khoan-khach-hang');
                exit();
            } else {
                // Trả về form và lỗi
                // Đặt chỉ thị xóa session sau khi hiển thị form 
                $_SESSION['flash'] = true;
                header("Location: " . BASE_URL_ADMIN . '?act=form-sua-khach-hang&id_khach_hang=' . $khach_hang_id);
                exit();
            }
        }
    }

    public function deltailKhachHang(){
        $id_khach_hang = $_GET['id_khach_hang'];
        $khachHang = $this->modelTaiKhoan->getDetailTaiKhoan($id_khach_hang);

        $listDonHang  = $this->modelDonHang->getDonHangFromKhachHang($id_khach_hang);

        $listBinhLuan = $this->modelSanPham->getBinhLuanFromKhachHang($id_khach_hang);

        require_once './views/taikhoan/khachhang/detailKhachHang.php';
    }






    public function formLogin(){
       
        require_once './views/auth/formLogin.php';
        deleteSessionError();
        exit();
        
        }

    public function login(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            // lấy email và pass gửi lên từ form 
            $email = $_POST['email'];
            $password = $_POST['password'];

            // var_dump($email);die;

            // Xử lý kiểm tra thông tin đăng nhập

            $user = $this->modelTaiKhoan->checkLogin($email, $password);

            if ($user == $email) { // Trường hợp đăng nhập thành công
                // Lưu thông tin vào session 
                $_SESSION['user_admin'] = $user;
                header("Location: " . BASE_URL_ADMIN);
                exit();
            }else{
                // Lỗi thì lưu lỗi vào session
                $_SESSION['error'] = $user;
                // var_dump($_SESSION['error']);die;

                $_SESSION['flash'] = true;

                header("Location: " . BASE_URL_ADMIN . '?act=login-admin');
                exit();
                
            }
        }
    }

    public function logout(){
        if (isset($_SESSION['user_admin'])) {
            unset($_SESSION['user_admin']);
            header("Location: " . BASE_URL_ADMIN . '?act=login-admin');
        }
    }


    public function formEditCaNhanQuanTri(){
        $email = $_SESSION['user_admin'];
        $thongTin = $this->modelTaiKhoan->getTaiKhoanformEmail($email);
        // var_dump($thongTin);die;
        require_once './views/taikhoan/canhan/editCaNhan.php';
        deleteSessionError();
    }

    public function postEditMatKhauCaNhan(){
        // var_dump($_POST);die;
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $old_pass = $_POST['old_pass'];
            $new_pass = $_POST['new_pass'];
            $confirm_pass = $_POST['confirm_pass'];

            

            
            //Lấy thông tin user từ session
            $user = $this->modelTaiKhoan->getTaiKhoanformEmail($_SESSION['user_admin']);

            // var_dump($user);die;
            $checkPass = password_verify($old_pass, $user['mat_khau']);

            $errors = [];

            if (!$checkPass) {
                $errors['old_pass'] = 'Mật khẩu người dùng không đúng';
            }

            if ($new_pass !== $confirm_pass) {
                $errors['confirm_pass'] = 'Mật khẩu nhập lại không đúng';
            }

            if (empty($old_pass)) {
                $errors['old_pass'] = 'Vui lòng điền trường dữ liệu này';
            }
            if (empty($new_pass)) {
                $errors['new_pass'] = 'Vui lòng điền trường dữ liệu này';
            }
            if (empty($confirm_pass)) {
                $errors['confirm_pass'] = 'Vui lòng điền trường dữ liệu này';
            }

            $_SESSION['error'] = $errors;

            if (!$errors) {
                // Thực hiện đổi mật khẩu
                $hashPass = password_hash($new_pass, PASSWORD_BCRYPT);
                $status = $this->modelTaiKhoan->resetPassword($user['id'], $hashPass);
                if ($status) {
                    $_SESSION['success'] = "Đã đổi mật khẩu thành công";
                    $_SESSION['flash'] = true;
                    header("Location: " . BASE_URL_ADMIN . '?act=form-sua-thong-tin-ca-nhan-quan-tri');
                }
            }else{
                // Lỗi thì lưu lỗi vào session

                $_SESSION['flash'] = true;

                header("Location: " . BASE_URL_ADMIN . '?act=form-sua-thong-tin-ca-nhan-quan-tri');
                exit();
                
            }

            
                
            

        }
    }

}