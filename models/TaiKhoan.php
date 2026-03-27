<?php

class TaiKhoan
{
    public $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }
    public function checkLogin($email, $mat_khau)
    {
        try {
            $sql = "SELECT * FROM tai_khoans WHERE email = :email";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch();

            // Support both hashed and legacy-plain passwords.
            // If the password in DB is hashed, use password_verify.
            // If it's stored in plain text (legacy), allow one-time login
            // and immediately replace it with a secure hash.
            $passwordMatches = false;
            if ($user) {
                if (password_verify($mat_khau, $user['mat_khau'])) {
                    $passwordMatches = true;
                } elseif ($user['mat_khau'] === $mat_khau) {
                    // legacy plaintext match: migrate to hashed password
                    $passwordMatches = true;
                    $newHash = password_hash($mat_khau, PASSWORD_DEFAULT);
                    try {
                        $updateSql = "UPDATE tai_khoans SET mat_khau = :hash WHERE id = :id";
                        $updateStmt = $this->conn->prepare($updateSql);
                        $updateStmt->execute([':hash' => $newHash, ':id' => $user['id']]);
                        // update local copy so following checks treat it as hashed
                        $user['mat_khau'] = $newHash;
                    } catch (\Exception $e) {
                        // Non-fatal: continue even if migration update fails
                    }
                }
            }

            if ($user && $passwordMatches) {
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
    public function getTaiKhoanFromEmail($email)
    {
        try {
            $sql = 'SELECT * FROM tai_khoans WHERE email = :email';

            $stmt = $this->conn->prepare($sql);

            $stmt->execute([
                ':email' => $email
            ]);

            return $stmt->fetch();
        } catch (Exception $e) {
            echo "lỗi" . $e->getMessage();
        }
    }
}
