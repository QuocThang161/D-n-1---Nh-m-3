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
        }catch(Exception $e){
            echo "Lỗi" . $e->getMessage();
        }
    }

    public function getListAnhSanPham($id){
        try{
            $sql = 'SELECT * FROM hinh_anh_san_phams WHERE san_pham_id = :id';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id'=>$id]);
            return $stmt->fetchAll();
        }catch(Exception $e){
            echo "Lỗi" . $e->getMessage();
        }
    }

    public function getDetailAnhSanPham($id){
        try {
            $sql = 'SELECT * FROM hinh_anh_san_phams WHERE id = :id';

            $stmt = $this->conn->prepare($sql);

            $stmt->execute([':id'=>$id]);

            return $stmt->fetch();
        } catch (Exception $e) {
            echo "lỗi" . $e->getMessage();
        }
    }

     public function getBinhLuanFromSanPham($id){
        try {
            $sql = 'SELECT binh_luans.*, tai_khoans.ho_ten, tai_khoans.anh_dai_dien
            FROM binh_luans
            INNER JOIN tai_khoans ON binh_luans.tai_khoan_id = tai_khoans.id
            WHERE binh_luans.san_pham_id = :id
            ';

            $stmt = $this->conn->prepare($sql);

            $stmt->execute([':id'=>$id]);

            return $stmt->fetchAll();
        } catch (Exception $e) {
            echo "lỗi" . $e->getMessage();
        }
    }

       public function getListSanPhamDanhMuc($danh_muc_id, $current_product_id = null) {
    try {
        // Thêm điều kiện loại trừ sản phẩm đang xem
        $sql = 'SELECT san_phams.*, danh_mucs.ten_danh_muc
                FROM san_phams  
                INNER JOIN danh_mucs ON san_phams.danh_muc_id = danh_mucs.id
                WHERE san_phams.danh_muc_id = :danh_muc_id';
        
        if ($current_product_id) {
            $sql .= ' AND san_phams.id <> :id'; // <> là dấu khác trong SQL
        }

        $stmt = $this->conn->prepare($sql);
        
        $params = [':danh_muc_id' => $danh_muc_id];
        if ($current_product_id) {
            $params[':id'] = $current_product_id;
        }

        $stmt->execute($params);
        return $stmt->fetchAll();
    } catch (Exception $e) {
        echo " lỗi: " . $e->getMessage();
    }
}

public function addBinhLuan($san_pham_id, $tai_khoan_id, $noi_dung, $ngay_dang) {
    try {
        
        $sql = 'INSERT INTO binh_luans (san_pham_id, tai_khoan_id, noi_dung, ngay_dang, trang_thai) 
                VALUES (:san_pham_id, :tai_khoan_id, :noi_dung, :ngay_dang, 1)';

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            ':san_pham_id' => $san_pham_id,
            ':tai_khoan_id' => $tai_khoan_id,
            ':noi_dung' => $noi_dung,
            ':ngay_dang' => $ngay_dang
        ]);

        return true;
    } catch (Exception $e) {
        echo "Lỗi thêm bình luận: " . $e->getMessage();
        return false;
    }
}
}