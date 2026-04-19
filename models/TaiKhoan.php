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
                if ($user['trang_thai'] == 1) {
                    // Trả về toàn bộ mảng user để đồng bộ dữ liệu với Controller và Admin
                    return $user; 
                } else {
                    return "Tài khoản đang bị khóa hoặc chưa kích hoạt";
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
            if (is_array($email)) {
                if (isset($email['email'])) {
                    $email = $email['email'];
                } elseif (isset($email['id'])) {
                    return $this->getTaiKhoanById($email['id']);
                } else {
                    return false;
                }
            }

            if (!is_string($email) || empty($email)) {
                return false;
            }

            $sql = 'SELECT * FROM tai_khoans WHERE email = :email';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':email' => trim($email)]);

            return $stmt->fetch();
        } catch (Exception $e) {
            echo "lỗi" . $e->getMessage();
            return false;
        }
    }

        public function getTaiKhoanById($id)
{
    try {
        $sql = 'SELECT * FROM tai_khoans WHERE id = :id';
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);

        return $stmt->fetch();
    } catch (Exception $e) {
        echo "Lỗi: " . $e->getMessage();
    }
}

    public function updateThongTinCaNhan($id, $ho_ten, $so_dien_thoai, $dia_chi, $ngay_sinh, $gioi_tinh, $anh_dai_dien = null) {
        try {
            $sql = 'UPDATE tai_khoans 
                    SET ho_ten = :ho_ten, 
                        so_dien_thoai = :so_dien_thoai, 
                        dia_chi = :dia_chi, 
                        ngay_sinh = :ngay_sinh, 
                        gioi_tinh = :gioi_tinh';

            if ($anh_dai_dien) {
                $sql .= ', anh_dai_dien = :anh_dai_dien';
            }

            $sql .= ' WHERE id = :id';

            $stmt = $this->conn->prepare($sql);

            $params = [
                ':ho_ten' => $ho_ten,
                ':so_dien_thoai' => $so_dien_thoai,
                ':dia_chi' => $dia_chi,
                ':ngay_sinh' => $ngay_sinh,
                ':gioi_tinh' => $gioi_tinh,
                ':id' => $id
            ];

            if ($anh_dai_dien) {
                $params[':anh_dai_dien'] = $anh_dai_dien;
            }

            return $stmt->execute($params);
        } catch (Exception $e) {
            echo "Lỗi cập nhật thông tin cá nhân: " . $e->getMessage();
            return false;
        }
    }
    public function updateTaiKhoan($id, $ho_ten, $so_dien_thoai, $dia_chi, $ngay_sinh, $gioi_tinh, $anh_dai_dien) {
    try {
        $sql = "UPDATE tai_khoans 
                SET ho_ten = :ho_ten, 
                    so_dien_thoai = :so_dien_thoai, 
                    dia_chi = :dia_chi, 
                    ngay_sinh = :ngay_sinh, 
                    gioi_tinh = :gioi_tinh, 
                    anh_dai_dien = :anh_dai_dien 
                WHERE id = :id";
        
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':ho_ten' => $ho_ten,
            ':so_dien_thoai' => $so_dien_thoai,
            ':dia_chi' => $dia_chi,
            ':ngay_sinh' => $ngay_sinh,
            ':gioi_tinh' => $gioi_tinh,
            ':anh_dai_dien' => $anh_dai_dien,
            ':id' => $id
        ]);
    } catch (Exception $e) {
        echo "Lỗi: " . $e->getMessage();
        return false;
    }
}

    public function insertTaiKhoan($ho_ten, $email, $mat_khau, $chuc_vu_id, $so_dien_thoai = null, $ngay_sinh = null, $dia_chi = null) {
        try {
            $sql = 'INSERT INTO tai_khoans (ho_ten, email, mat_khau, chuc_vu_id, trang_thai, so_dien_thoai, ngay_sinh, dia_chi) 
                    VALUES (:ho_ten, :email, :mat_khau, :chuc_vu_id, :trang_thai, :so_dien_thoai, :ngay_sinh, :dia_chi)';
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([
                ':ho_ten' => $ho_ten,
                ':email' => $email,
                ':mat_khau' => $mat_khau,
                ':chuc_vu_id' => $chuc_vu_id,
                ':trang_thai' => 1, // Mặc định trạng thái là hoạt động (1)
                ':so_dien_thoai' => $so_dien_thoai,
                ':ngay_sinh' => $ngay_sinh,
                ':dia_chi' => $dia_chi
            ]);
        } catch (Exception $e) {
            echo "Lỗi: " . $e->getMessage();
        }
    }
}