<?php
return [
    'subject' => 'Baker Store - Xác nhận đơn hàng #{order_id}',
    'body' => '<div style="font-family: Arial, sans-serif; color: #333;">
<h2 style="color:#B5651D;">Baker Store xin chào bạn {fullname}!</h2>
<p>Cảm ơn bạn đã chọn Baker Store cho bữa tiệc bánh ngọt của mình.</p>
<p><strong>Mã đơn hàng:</strong> #{order_id}</p>
<p><strong>Hình thức thanh toán:</strong> {payment}</p>
<p><strong>Địa chỉ giao hàng:</strong> {address}</p>
<p><strong>Ghi chú đơn hàng:</strong> {notes}</p>
<h3 style="color:#B5651D;">Chi tiết đơn hàng</h3>
<table style="width:100%;border-collapse:collapse;font-size:14px;">
    <thead>
        <tr style="background:#f7e7d5;">
            <th style="padding:10px;border:1px solid #ddd;text-align:left;">Sản phẩm</th>
            <th style="padding:10px;border:1px solid #ddd;text-align:center;">Số lượng</th>
            <th style="padding:10px;border:1px solid #ddd;text-align:right;">Đơn giá</th>
            <th style="padding:10px;border:1px solid #ddd;text-align:right;">Thành tiền</th>
        </tr>
    </thead>
    {order_items}
</table>
<p style="margin-top:20px;"><strong>Subtotal:</strong> {subtotal}</p>
<p><strong>Phí vận chuyển:</strong> {shipping}</p>
<p><strong>Tổng thanh toán:</strong> {total_amount}</p>
<hr />
<p>Chúng tôi sẽ chuẩn bị bánh và giao hàng đến bạn trong thời gian sớm nhất.</p>
<p>Nếu bạn cần hỗ trợ thay đổi đơn hàng hoặc tư vấn thêm bánh ngọt, vui lòng liên hệ:</p>
<p><strong>Hotline:</strong> 0987 654 321&nbsp;|&nbsp;<strong>Email:</strong> support@baker.store</p>
<p>Chúc bạn một ngày ngọt ngào!</p>
<p><strong>Baker Store</strong></p>
</div>',
];
?>