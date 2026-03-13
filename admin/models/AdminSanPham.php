<?php
class AdminSanPham {
    public $conn;
    public function __construct()
    {
        $this->conn = connectDB();
    }
    public function getAllSanpham(){
        try{
            $sql = 'SELECT san_phams.*, danh_mucs.ten_danh_muc
            FROM san_phams
            INNER JOIN danh_mucs ON san_phams.danh_muc_id = danh_mucs.id';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        }catch(Exception $e){
            echo "Lỗi" . $e->getMessage();
        }
    }

    public function insertSanPham($ten_san_pham, $gia_san_pham, $gia_khuyen_mai, $so_luong, $ngay_nhap, $danh_muc_id, $trang_thai, $mota, $hinh_anh){
        try{
            $sql = 'INSERT INTO san_pham (ten_san_pham, gia_san_pham, gia_khuyen_mai, so_luong, ngay_nhap, danh_muc_id, trang_thai, mo_ta, hinh_anh)
            VALUES (:ten_san_pham, :gia_san_pham, :gia_khuyen_mai, :so_luong, :ngay_nhap, :danh_muc_id, :trang_thai, :mo_ta, :hinh_anh)';

            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':ten_san_pham' => $ten_san_pham,
                ':gia_san_pham' => $gia_san_pham,
                ':gia_khuyen_mai' => $gia_khuyen_mai,
                ':so_luong' => $so_luong,
                ':ngay_nhap' => $ngay_nhap,
                ':danh_muc_id' => $danh_muc_id,
                ':trang_thai' => $trang_thai,
                ':mo_ta' => $mo_ta,
                ':hinh_anh' => $hinh_anh,
            ]);
            // lấy sản phảm vừa thêm 
            return $this->conn->lastInsertId();
        }catch (Exception $e){
            echo "Lỗi" . $e->getMessage();
        }
    }
    public function formAddSanpham(){
        $listDanhMuc = $this->modelDanhMuc->getAllDanhMuc();
        require_once './views/sanpham/addSanPham.php';

        deleteSesionError();
    }

    public function insertAlbumSanPham($san_pham_id, $link_hinh_anh){
        try{
            $sql = 'INSERT INTO hinh_anh_san_pham (san_pham_id, link_hinh_anh)
            VALUES (:san_pham_id :link_hinh_anh)';

            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':san_pham_id' => $san_pham_id,
                ':link_hinh_anh' => $link_hinh_anh,
            ]);
            // lấy sản phảm vừa thêm 
            return true;
        }catch (Exception $e){
            echo "Lỗi" . $e->getMessage();
        }
    }

    public function getDatailSanPham($id){
        try{
            $sql = 'SELECT * FROM san_phams WHERE id = :id';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id'=>$id]);
            return $stmt->fetch();
        }catch(Exception $e){
            echo "Lỗi" . $e->getMessage();
        }
    }

    public function getListAnhSanPham($id){
        try{
            $sql = 'SELECT *FROM hinh_anh_san_phams WHERE san_pham_id = :id';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id'=>$id]);
            return $stmt->fetch();
        }catch(Exception $e){
            echo "Lỗi" . $e->getMessage();
        }
    }

    
}


