<?php

class TaiKhoan
{
    public $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }

    public function checkLogin($email, $mat_khau){
        try {
            $sql = "SELECT * FROM tai_khoans WHERE email = :email";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(['email'=>$email]);
            $user = $stmt->fetch();

            // Hỗ trợ cả mật khẩu đã hash và mật khẩu plain text (cho dữ liệu demo chưa hash)
            $passMatch = false;
            if (password_verify($mat_khau, $user['mat_khau'])) {
                $passMatch = true;
            } elseif ($mat_khau === $user['mat_khau']) {
                $passMatch = true; // tạm thời cho môi trường cũ, nên update hash sau
            }

            if ($user && $passMatch) {
                if ($user['chuc_vu_id'] == 2) {
                    if ($user['trang_thai'] == 1) {
                        return $user['email']; // Trường hợp đăng nhập thành công
                    } else {
                        return "Tài khoản bị cấm";
                    }
                } else {
                    return "Tài khoản không có quyền đăng nhập";
                }
            } else {
                return "Bạn nhập sai thông tin mật khẩu hoặc tài khoản";
            }
        } catch (\Exception $e) {
            echo "lỗi" . $e->getMessage();
            return false;
        }
    }

    public function getTaiKhoanformEmail($email){
        try {
            $sql = 'SELECT * FROM tai_khoans WHERE email = :email';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':email' => $email]);
            return $stmt->fetch();
        } catch (\Exception $e) {
            echo "lỗi" . $e->getMessage();
            return false;
        }
    }
}