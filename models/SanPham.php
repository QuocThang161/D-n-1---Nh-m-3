<?php
class SanPham {
    public  $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }

    public function getAllSanPham(){
        try {
            $sql = 'SELECT san_phams.*, danh_mucs.ten_danh_muc
            FROM san_phams
            INNER JOIN danh_mucs ON san_phams.danh_muc_id = danh_mucs.id
            ';
            $stmt = $this->conn->prepare($sql);

            $stmt->execute();
            
            return $stmt->fetchAll();
        } catch (Exception $e) {
            echo "Lỗi" . $e->getMessage();
        }
    }


    public function getDetailSanPham($id){
        try {
            $sql = 'SELECT san_phams.*, danh_mucs.ten_danh_muc
            FROM san_phams
            INNER JOIN danh_mucs ON san_phams.danh_muc_id = danh_mucs.id
            WHERE san_phams.id = :id';

            $stmt = $this->conn->prepare($sql);

            $stmt->execute([':id'=>$id]);

            return $stmt->fetch();
        } catch (Exception $e) {
            echo "lỗi" . $e->getMessage();
        }
    }

    public function getListAnhSanPham($id){
        try {
            $sql = 'SELECT * FROM hinh_anh_san_phams WHERE san_pham_id = :id';

            $stmt = $this->conn->prepare($sql);

            $stmt->execute([':id'=>$id]);

            return $stmt->fetchAll();
        } catch (Exception $e) {
            echo "lỗi" . $e->getMessage();
        }
    }

    public function getVariantsBySanPhamId($san_pham_id){
        try{
            $sql = 'SELECT * FROM san_pham_bien_the WHERE san_pham_id = :san_pham_id';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':san_pham_id' => $san_pham_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo "lỗi" . $e->getMessage();
        }
    }

    // 1. Thêm biến thể (màu + size + số lượng), không duplicate
    public function addVariant($san_pham_id, $mau_sac, $size, $so_luong)
    {
        try {
            $sql = 'SELECT COUNT(*) FROM san_pham_bien_the
                    WHERE san_pham_id = :san_pham_id
                      AND mau_sac = :mau_sac
                      AND size = :size';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':san_pham_id' => $san_pham_id,
                ':mau_sac' => $mau_sac,
                ':size' => $size
            ]);

            if ((int)$stmt->fetchColumn() > 0) {
                return [
                    'success' => false,
                    'message' => 'Biến thể đã tồn tại'
                ];
            }

            $sql = 'INSERT INTO san_pham_bien_the (san_pham_id, mau_sac, size, so_luong_bien_the)
                    VALUES (:san_pham_id, :mau_sac, :size, :so_luong_bien_the)';
            $stmt = $this->conn->prepare($sql);
            $ok = $stmt->execute([
                ':san_pham_id' => $san_pham_id,
                ':mau_sac' => $mau_sac,
                ':size' => $size,
                ':so_luong_bien_the' => $so_luong
            ]);

            return [
                'success' => $ok,
                'message' => $ok ? 'Thêm biến thể thành công' : 'Lỗi thêm biến thể'
            ];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    // 2. Lấy 1 biến thể theo id
    public function getVariantById($id){
        try{
            $sql = 'SELECT * FROM san_pham_bien_the WHERE id = :id';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo "lỗi" . $e->getMessage();
        }
    }

    // 2.1 Lấy biến thể theo san_pham_id + mau_sac + size
    public function getVariantByProductColorSize($san_pham_id, $mau_sac, $size)
    {
        try {
            $sql = 'SELECT * FROM san_pham_bien_the WHERE san_pham_id = :san_pham_id AND mau_sac = :mau_sac AND size = :size LIMIT 1';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':san_pham_id' => $san_pham_id,
                ':mau_sac' => $mau_sac,
                ':size' => $size,
            ]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo "lỗi" . $e->getMessage();
        }
    }

    // 3. Kiểm tra tồn kho (nếu so_luong <= 0 thì hết hàng)
    public function checkVariantStock($id)
    {
        $variant = $this->getVariantById($id);
        if (!$variant) {
            return ['ok' => false, 'message' => 'Biến thể không tồn tại'];
        }

        if ((int)$variant['so_luong_bien_the'] <= 0) {
            return ['ok' => false, 'message' => 'Hết hàng'];
        }

        return ['ok' => true, 'message' => 'Còn hàng', 'variant' => $variant];
    }

    // 4. Trừ số lượng khi mua (không cho trừ khi không đủ)
    public function decreaseVariantStock($id, $qty)
    {
        if ($qty <= 0) {
            return ['success' => false, 'message' => 'Số lượng phải lớn hơn 0'];
        }

        $variant = $this->getVariantById($id);
        if (!$variant) {
            return ['success' => false, 'message' => 'Biến thể không tồn tại'];
        }

        if ((int)$variant['so_luong_bien_the'] < $qty) {
            return ['success' => false, 'message' => 'Không đủ hàng'];
        }

        $sql = 'UPDATE san_pham_bien_the SET so_luong_bien_the = so_luong_bien_the - :qty WHERE id = :id';
        $stmt = $this->conn->prepare($sql);
        $ok = $stmt->execute([':qty' => $qty, ':id' => $id]);

        return ['success' => $ok, 'message' => $ok ? 'Đã trừ tồn kho' : 'Cập nhật tồn kho thất bại'];
    }

    public function getBinhLuanFromSanPham($id){
        try {
            $sql = 'SELECT binh_luans.*, tai_khoans.ho_ten, tai_khoans.anh_dai_dien
            FROM binh_luans
            INNER JOIN tai_khoans ON binh_luans.tai_khoan_id = tai_khoans.id
            WHERE binh_luans.san_pham_id = :id AND binh_luans.trang_thai = 1
            ';

            $stmt = $this->conn->prepare($sql);

            $stmt->execute([':id'=>$id]);

            return $stmt->fetchAll();
        } catch (Exception $e) {
            echo "lỗi" . $e->getMessage();
        }
    }

    public function insertBinhLuan($tai_khoan_id, $san_pham_id, $noi_dung, $ngay_dang) {
    try {
        $sql = "INSERT INTO binh_luans (tai_khoan_id, san_pham_id, noi_dung, ngay_dang) 
                VALUES (:tai_khoan_id, :san_pham_id, :noi_dung, :ngay_dang)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':tai_khoan_id' => $tai_khoan_id,
            ':san_pham_id' => $san_pham_id,
            ':noi_dung' => $noi_dung,
            ':ngay_dang' => $ngay_dang
        ]);
    } catch (Exception $e) {
        echo "Lỗi: " . $e->getMessage();
    }
}


    public function getListSanPhamDanhMuc($danh_muc_id){
        try {
            $sql = 'SELECT san_phams.*, danh_mucs.ten_danh_muc
            FROM san_phams
            INNER JOIN danh_mucs ON san_phams.danh_muc_id = danh_mucs.id
            WHERE san_phams.danh_muc_id = :danh_muc_id';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':danh_muc_id' => $danh_muc_id]);
            
            return $stmt->fetchAll();
        } catch (Exception $e) {
            echo "Lỗi" . $e->getMessage();
        }
    }

}