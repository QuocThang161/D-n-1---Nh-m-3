<?php
// Edit form for order
?>
<h1>Sửa đơn hàng</h1>
<form action="?act=sua-don-hang" method="post">
    <input type="hidden" name="id" value="<?php echo $donHang['id']; ?>">
    <label>Trạng thái:</label>
    <select name="trang_thai">
        <option value="pending" <?php echo $donHang['trang_thai'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
        <option value="shipped" <?php echo $donHang['trang_thai'] == 'shipped' ? 'selected' : ''; ?>>Shipped</option>
        <option value="delivered" <?php echo $donHang['trang_thai'] == 'delivered' ? 'selected' : ''; ?>>Delivered</option>
    </select>
    <button type="submit">Cập nhật</button>
</form>