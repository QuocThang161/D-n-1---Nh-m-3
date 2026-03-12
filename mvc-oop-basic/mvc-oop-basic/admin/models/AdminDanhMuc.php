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
}
