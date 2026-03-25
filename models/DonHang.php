<?php

class DonHang
{
    public $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }

    public function addDonHang($ghi_chu,$email_nguoi_nhan , $tai_khoan_id, $ten_nguoi_nhan, $sdt_nguoi_nhan, $dia_chi_nguoi_nhan, $phuong_thuc_thanh_toan_id, $ngay_dat, $trang_thai_id, $ma_don_hang, $tong_tien){
        try {
            $sql = 'INSERT INTO don_hangs (ghi_chu, email_nguoi_nhan, tai_khoan_id, ten_nguoi_nhan, sdt_nguoi_nhan, dia_chi_nguoi_nhan, phuong_thuc_thanh_toan_id, ngay_dat, trang_thai_id, ma_don_hang, tong_tien) 
                    VALUES (:ghi_chu, :email_nguoi_nhan, :tai_khoan_id, :ten_nguoi_nhan, :sdt_nguoi_nhan, :dia_chi_nguoi_nhan, :phuong_thuc_thanh_toan_id, :ngay_dat, :trang_thai_id, :ma_don_hang, :tong_tien)';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':ghi_chu' => $ghi_chu,
                ':email_nguoi_nhan' => $email_nguoi_nhan,
                ':tai_khoan_id' => $tai_khoan_id,
                ':ten_nguoi_nhan' => $ten_nguoi_nhan,
                ':sdt_nguoi_nhan' => $sdt_nguoi_nhan,
                ':dia_chi_nguoi_nhan' => $dia_chi_nguoi_nhan,
                ':phuong_thuc_thanh_toan_id' => $phuong_thuc_thanh_toan_id,
                ':ngay_dat' => $ngay_dat,
                ':trang_thai_id' => $trang_thai_id,
                ':ma_don_hang' => $ma_don_hang,
                ':tong_tien' => $tong_tien,
                
            ]);
            return $this->conn->lastInsertId();
        } catch (Exception $e) {
            echo " lỗi" . $e->getMessage();
            return false;
        }
    }

}