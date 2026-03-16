<?php
class AdminDanhMuc
{
    public $conn;
    public function __construct()
    {
        $this->conn =  connectDB();
    }
    public function getAllDanhmuc()
    {
        try {
            $sql = 'SELECT * FROM danh_mucs';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            echo "Lỗi" . $e->getMessage();
        }
    }
    public function insertDanhMuc($ten_danh_muc, $mo_ta){
        try {
            $sql = 'INSERT INTO danh_mucs (ten_danh_muc, mo_ta)
                    VALUES (:ten_danh_muc, :mo_ta)';

            $stmt = $this->conn->prepare($sql);

            $stmt->execute([
                ':ten_danh_muc' => $ten_danh_muc,
                ':mo_ta' => $mo_ta
            ]);

            return true;
        } catch (Exception $e) {
            echo "lỗi" . $e->getMessage();
        }
    }
}
