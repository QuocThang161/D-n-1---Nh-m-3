<?php require_once 'layout/header.php'; ?>

<?php require_once 'layout/menu.php'; ?>

<div class="container my-5">
    <h2>Liên hệ với chúng tôi</h2>
    <div class="row mt-4">
        <div class="col-md-5">
            <div class="contact-info p-4 bg-light">
                <h4>Thông tin cửa hàng</h4>
                <p><i class="fa fa-map-marker"></i> Địa chỉ: 13 Trịnh Văn Bô, Nam Từ Liêm, Hà Nội</p>
                <p><i class="fa fa-phone"></i> Điện thoại: 0334901927</p>
                <p><i class="fa fa-envelope"></i> Email: TBTshop@gmail.com</p>
                <hr>
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3723.8639311820684!2d105.74468687504412!3d21.038129780613456!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x313455e940879933%3A0xcf10b34e9f1a03df!2zVHLGsOG7nW5nIENhbyDEkeG6s25nIEZQVCBQb2x5dGVjaG5pYw!5e0!3m2!1svi!2s!4v1775218149322!5m2!1svi!2s" width="400" height="400" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>

        <div class="col-md-7">
            <form action="?act=gui-lien-he" method="POST">
                <div class="mb-3">
                    <label>Họ và tên</label>
                    <input type="text" class="form-control" placeholder="Nhập tên của bạn">
                </div>
                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" class="form-control" placeholder="name@example.com">
                </div>
                <div class="mb-3">
                    <label>Nội dung lời nhắn</label>
                    <textarea class="form-control" rows="5"></textarea>
                </div>
                <button type="submit" class="btn btn-dark">Gửi tin nhắn</button>
            </form>
        </div>
    </div>
</div>

<?php require_once 'layout/miniCart.php' ?>

<?php require_once 'layout/footer.php'; ?>