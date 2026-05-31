<h2 class="fw-bold mb-4">Customer Management <span class="badge bg-danger fs-6 ms-2"></span></h2>
<div class="card shadow-sm border-0 p-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-dark"><tr><th>ID</th><th>Họ Tên</th><th>Email</th><th>Số điện thoại</th><th>Địa chỉ</th></tr></thead>
            <tbody>
                <?php
                if ($customers && mysqli_num_rows($customers) > 0) {
                    while ($row = mysqli_fetch_assoc($customers)) {
                        $c_id = $row['customer_id'] ?? $row['id'] ?? 'N/A';
                        $c_name = $row['full_name'] ?? 'N/A';
                        $c_email = $row['email'] ?? 'N/A';
                        $c_phone = $row['phone_number'] ?? 'N/A';
                        $c_address = $row['address'] ?? 'N/A';
                        echo "<tr><td class='text-secondary fw-bold'>#{$c_id}</td><td class='fw-bold'>{$c_name}</td><td>{$c_email}</td><td>{$c_phone}</td><td>{$c_address}</td></tr>";
                    }
                } else { echo "<tr><td colspan='5' class='text-center py-4 text-muted'>Chưa có khách hàng nào trong hệ thống.</td></tr>"; }
                ?>
            </tbody>
        </table>
    </div>
</div>