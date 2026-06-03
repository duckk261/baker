<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">Contact Management <span class="badge bg-danger fs-6 ms-2"></span></h2>
    </div>

    <div class="card border-0 shadow-sm p-4">
        <div class="mb-4" style="max-width: 400px;">
            <form method="GET" action="index.php" class="input-group">
                <input type="hidden" name="page" value="contacts">
                <input type="text" name="search" class="form-control border-primary" placeholder="Tìm Kiếm Theo Tên..." value="<?php echo htmlspecialchars($search ?? ''); ?>">
                <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">ID</th>
                        <th>Tên Khách Hàng</th>
                        <th>Email</th>
                        <th>Số Điện Thoại</th>
                        <th style="width: 30%;">Lời Nhắn</th>
                        <th>Ngày Tạo</th>
                    </tr>
                </thead>
             <tbody>
    <?php
    if ($contacts_data && mysqli_num_rows($contacts_data) > 0) {
        while ($row = mysqli_fetch_assoc($contacts_data)) {
            echo "<tr>
                    <td class='ps-3'>#{$row['contact_id']}</td>
                    <td class='fw-bold'>{$row['name']}</td>
                    <td>{$row['email']}</td>
                    <td>{$row['phone']}</td>
                    <td class='text-muted small'>{$row['message']}</td>
                    <td>{$row['created_at']}</td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='6' class='text-center py-4'>Chưa có liên hệ nào.</td></tr>";
    }
    ?>
</tbody>
            </table>
        </div>
    </div>
</div>