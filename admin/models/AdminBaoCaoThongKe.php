<?php
class AdminBaoCaoThongKe
{
    public $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }

    public function getCountDonHang()
    {
        $sql = "SELECT COUNT(*) as total FROM don_hangs";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetch()['total'];
    }

    public function getTongDoanhThu()
    {
        // Chỉ tính doanh thu từ các đơn hàng không bị hủy (giả sử trạng thái 11 là hủy)
        $sql = "SELECT SUM(tong_tien) as total FROM don_hangs WHERE trang_thai_id != 11";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetch()['total'] ?? 0;
    }

    public function getCountSanPham()
    {
        $sql = "SELECT COALESCE(SUM(so_luong_bien_the), 0) as total FROM san_pham_bien_the";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetch()['total'];
    }

    public function getCountKhachHang()
    {
        // Chức vụ 2 là khách hàng theo logic hệ thống
        $sql = "SELECT COUNT(*) as total FROM tai_khoans WHERE chuc_vu_id = 2";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetch()['total'];
    }
}