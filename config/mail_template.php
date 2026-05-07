<?php
return [
    'subject' => 'Baker Store - Order Confirmation #{order_id}',
    'body' => '<div style="font-family: Arial, sans-serif; color: #333;">
<h2 style="color:#B5651D;">Dear {fullname},</h2>
<p>Thank you for choosing Baker Store. We have received your order and are now processing it.</p>
<p><strong>Order ID:</strong> #{order_id}</p>
<p><strong>Payment Method:</strong> {payment}</p>
<p><strong>Shipping Address:</strong> {address}</p>
<p><strong>Order Notes:</strong> {notes}</p>
<h3 style="color:#B5651D;">Order Details</h3>
<table style="width:100%;border-collapse:collapse;font-size:14px;">
    <thead>
        <tr style="background:#f7e7d5;">
            <th style="padding:10px;border:1px solid #ddd;text-align:left;">Product</th>
            <th style="padding:10px;border:1px solid #ddd;text-align:center;">Quantity</th>
            <th style="padding:10px;border:1px solid #ddd;text-align:right;">Unit Price</th>
            <th style="padding:10px;border:1px solid #ddd;text-align:right;">Total</th>
        </tr>
    </thead>
    {order_items}
</table>
<p style="margin-top:20px;"><strong>Subtotal:</strong> {subtotal}</p>
<p><strong>VAT (8%):</strong> {vat}</p>
<p><strong>Shipping Fee:</strong> {shipping}</p>
<p><strong>Grand Total:</strong> {total_amount}</p>
<hr />
<p>We are preparing your order and will deliver it to you as soon as possible.</p>
<p>If you need any assistance or would like to modify your order, please feel free to contact us:</p>
<p><strong>Hotline:</strong> 0987 654 321&</p>
<p><strong>Email:</strong> support@baker.store</p>
<p>Have a sweet day!</p>
<p><strong>Baker Store</strong></p>
</div>',
];
