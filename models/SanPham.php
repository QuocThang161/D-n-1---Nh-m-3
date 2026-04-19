<?php
class SanPham {
    public  $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }

    public function getAllSanPham(){
        try {
            $sql = 'SELECT san_phams.*, danh_mucs.ten_danh_muc,
                           COALESCE(SUM(san_pham_bien_the.so_luong_bien_the), 0) AS so_luong_thuc_te
            FROM san_phams
            INNER JOIN danh_mucs ON san_phams.danh_muc_id = danh_mucs.id
            LEFT JOIN san_pham_bien_the ON san_phams.id = san_pham_bien_the.san_pham_id
            GROUP BY san_phams.id
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
            $sql = 'SELECT san_phams.*, danh_mucs.ten_danh_muc,
                           COALESCE(SUM(san_pham_bien_the.so_luong_bien_the), 0) AS so_luong_thuc_te
            FROM san_phams
            INNER JOIN danh_mucs ON san_phams.danh_muc_id = danh_mucs.id
            LEFT JOIN san_pham_bien_the ON san_phams.id = san_pham_bien_the.san_pham_id
            WHERE san_phams.id = :id
            GROUP BY san_phams.id';

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

    // Giữ lại để tương thích ngược nếu cần, nhưng chuyển sang dùng so_luong_bien_the
    public function checkVariantStock($id)
    {
        $variant = $this->getVariantById($id);
        $ok = ($variant && (int)$variant['so_luong_bien_the'] > 0);
        return ['ok' => $ok, 'message' => $ok ? 'Còn hàng' : 'Hết hàng', 'variant' => $variant];
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
            $sql = 'SELECT san_phams.*, danh_mucs.ten_danh_muc,
                           COALESCE(SUM(san_pham_bien_the.so_luong_bien_the), 0) AS so_luong_thuc_te
            FROM san_phams
            INNER JOIN danh_mucs ON san_phams.danh_muc_id = danh_mucs.id
            LEFT JOIN san_pham_bien_the ON san_phams.id = san_pham_bien_the.san_pham_id
            WHERE san_phams.danh_muc_id = :danh_muc_id
            GROUP BY san_phams.id';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':danh_muc_id' => $danh_muc_id]);
            
            return $stmt->fetchAll();
        } catch (Exception $e) {
            echo "Lỗi" . $e->getMessage();
        }
    }

    public function getAllDanhMuc() {
        try {
            $sql = 'SELECT * FROM danh_mucs';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            echo "Lỗi: " . $e->getMessage();
        }
    }

    public function countAllSanPham($search = null) {
        try {
            $sql = 'SELECT COUNT(*) FROM san_phams WHERE 1';
            if ($search) $sql .= " AND ten_san_pham LIKE :search";
            $stmt = $this->conn->prepare($sql);
            if ($search) $stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (Exception $e) {
            echo "Lỗi: " . $e->getMessage();
        }
    }

    public function countSanPhamByDanhMuc($danh_muc_id, $search = null) {
        try {
            $sql = 'SELECT COUNT(*) FROM san_phams WHERE danh_muc_id = :danh_muc_id';
            if ($search) $sql .= " AND ten_san_pham LIKE :search";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':danh_muc_id', $danh_muc_id);
            if ($search) $stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (Exception $e) {
            echo "Lỗi: " . $e->getMessage();
        }
    }

    public function getSanPhamPage($limit, $offset, $sort = '', $search = null) {
        $orderBy = $this->getOrderByString($sort);
        try {
            $sql = "SELECT san_phams.*, danh_mucs.ten_danh_muc,
                           COALESCE(SUM(san_pham_bien_the.so_luong_bien_the), 0) AS so_luong_thuc_te
                    FROM san_phams
                    INNER JOIN danh_mucs ON san_phams.danh_muc_id = danh_mucs.id
                    LEFT JOIN san_pham_bien_the ON san_phams.id = san_pham_bien_the.san_pham_id
                    WHERE 1";
            if ($search) $sql .= " AND ten_san_pham LIKE :search";
            $sql .= " GROUP BY san_phams.id $orderBy LIMIT :limit OFFSET :offset";
            
            $stmt = $this->conn->prepare($sql);
            if ($search) $stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
            // Cần bindValue để ép kiểu số nguyên cho LIMIT/OFFSET
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            echo "Lỗi: " . $e->getMessage();
        }
    }

    public function getSanPhamByDanhMucPage($danh_muc_id, $limit, $offset, $sort = '', $search = null) {
        $orderBy = $this->getOrderByString($sort);
        try {
            $sql = "SELECT san_phams.*, danh_mucs.ten_danh_muc,
                           COALESCE(SUM(san_pham_bien_the.so_luong_bien_the), 0) AS so_luong_thuc_te
                    FROM san_phams
                    INNER JOIN danh_mucs ON san_phams.danh_muc_id = danh_mucs.id
                    LEFT JOIN san_pham_bien_the ON san_phams.id = san_pham_bien_the.san_pham_id
                    WHERE san_phams.danh_muc_id = :danh_muc_id";
            if ($search) $sql .= " AND ten_san_pham LIKE :search";
            $sql .= " GROUP BY san_phams.id $orderBy LIMIT :limit OFFSET :offset";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':danh_muc_id', $danh_muc_id);
            if ($search) $stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            echo "Lỗi: " . $e->getMessage();
        }
    }

    private function getOrderByString($sort) {
        switch ($sort) {
            case 'gia_asc':
                return 'ORDER BY (CASE WHEN gia_khuyen_mai > 0 THEN gia_khuyen_mai ELSE gia_san_pham END) ASC';
            case 'gia_desc':
                return 'ORDER BY (CASE WHEN gia_khuyen_mai > 0 THEN gia_khuyen_mai ELSE gia_san_pham END) DESC';
            case 'ten_asc':
                return 'ORDER BY ten_san_pham ASC';
            case 'ten_desc':
                return 'ORDER BY ten_san_pham DESC';
            default:
                // Mặc định sắp xếp theo sản phẩm mới nhất
                return 'ORDER BY san_phams.id DESC';
        }
    }

    public function tangSoLuongBienThe($variant_id, $so_luong) {
        try {
            $sql = "UPDATE san_pham_bien_the SET so_luong_bien_the = so_luong_bien_the + :so_luong WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([':id' => $variant_id, ':so_luong' => $so_luong]);
        } catch (Exception $e) {
            return false;
        }
    }

    public function checkSoLuongBienThe($variant_id, $so_luong) {
        try {
            $sql = "SELECT so_luong_bien_the FROM san_pham_bien_the WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $variant_id]);
            $current_qty = $stmt->fetchColumn();
            return $current_qty >= $so_luong;
        } catch (Exception $e) {
            return false;
        }
    }

    public function giamSoLuongBienThe($variant_id, $so_luong) {
        try {
            $sql = "UPDATE san_pham_bien_the SET so_luong_bien_the = so_luong_bien_the - :so_luong WHERE id = :id AND so_luong_bien_the >= :so_luong";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([':id' => $variant_id, ':so_luong' => $so_luong]);
        } catch (Exception $e) {
            return false;
        }
    }
}