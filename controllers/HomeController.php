<?php

class HomeController
{
    public $modelSanPham;
    public $modelTaikhoan;
    public $modelGioHang;
    public function __construct()
    {
        $this->modelSanPham = new SanPham();
        $this->modelTaikhoan = new TaiKhoan();
        $this->modelGioHang = new GioHang();
    }

    public function home()
    {
        $listSanPham = $this->modelSanPham->getAllSanPham();
        require_once './views/home.php';
    }

    public function chiTietSanPham() {
               $id = $_GET['id_san_pham'];

            $sanPham = $this->modelSanPham->getDetailSanPham($id);

            $listAnhSanPham = $this->modelSanPham->getListAnhSanPham($id);

            $listBinhLuan = $this->modelSanPham->getBinhLuanFromSanPham($id);

            $listSanPhamCungDanhMuc = $this->modelSanPham->getAllSanPhamDanhMuc($sanPham['danh_muc_id']);
            


            // var_dump($listAnhSanPham);die;
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

    public function postLogin(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            // lấy email và pass gửi lên từ form 
            $email = $_POST['email'];
            $password = $_POST['password'];

            // var_dump($email);die;

            // Xử lý kiểm tra thông tin đăng nhập

            $user = $this->modelTaikhoan->checkLogin($email, $password);

            if ($user == $email) { // Trường hợp đăng nhập thành công
                // Lưu thông tin vào session 
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

    public function addGioHang(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            if(isset($_SESSION['user_client'])){
                $mail = $this->modelTaikhoan->getTaiKhoanformEmail($_SESSION['user_client']);
                // var_dump($mail['id']);die;
                $gioHang = $this->modelGioHang->getGioHangFromUser($mail['id']);
                if(!$gioHang){
                    $gioHangId = $this->modelGioHang->addGioHang($mail['id']);
                    $gioHang = ['id' => $gioHangId];
                     $chiTietGioHang = $this->modelGioHang->getDetailGioHang($gioHang['id']);

                }else{
                     $chiTietGioHang = $this->modelGioHang->getDetailGioHang($gioHang['id']);
                }
               

                $san_pham_id = $_POST['san_pham_id'];
                $so_luong = $_POST['so_luong'];
                // lấy dữ liệu giỏ hàng của người dùng
                $checkSanPham = false;

                foreach($chiTietGioHang as $detail){
                    if($detail['san_pham_id'] == $san_pham_id){
                        $newSoLuong = $detail['so_luong'] + $so_luong;
                        $this->modelGioHang->updateSoLuong($gioHang['id'], $san_pham_id, $newSoLuong);
                        $checkSanPham = true;
                        break;
                    }

                }
                if(!$checkSanPham){
                    $this->modelGioHang->addDetailGioHang($gioHang['id'], $san_pham_id, $so_luong);
                }
                header("Location: " . BASE_URL . '?act=gio-hang');
                exit();
            }else{
                var_dump('Chưa đăng nhập');die;
            }
            

        }
        
    }

    public function gioHang(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            if(isset($_SESSION['user_client'])){
                $mail = $this->modelTaikhoan->getTaiKhoanformEmail($_SESSION['user_client']);
                // var_dump($mail['id']);die;
                $gioHang = $this->modelGioHang->getGioHangFromUser($mail['id']);
                if(!$gioHang){
                    $gioHangId = $this->modelGioHang->addGioHang($mail['id']);
                    $gioHang = ['id' => $gioHangId];
                     $chiTietGioHang = $this->modelGioHang->getDetailGioHang($gioHang['id']);

                }else{
                     $chiTietGioHang = $this->modelGioHang->getDetailGioHang($gioHang['id']);
                }
                require_once './views/gioHang.php';
            }else{
                var_dump('Chưa đăng nhập');die;
            }
            

        }
    }

 
  
}