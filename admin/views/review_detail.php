<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-dark">Detailed Reviews</h3>
        <a href="index.php?page=reviews" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i>Back to Reviews</a>
    </div>
    
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
    <thead>
        <tr>
            <th>Customer</th>
            <th>Rating</th>
            <th>Comment</th>
            <th>Date</th>
            <th class="text-center">Action</th> 
        </tr>
    </thead>
    <tbody>
        <?php 
        // 1. ĐỔI THÀNH $reviews_detail Ở ĐÂY NÀY!
        while ($row = mysqli_fetch_assoc($reviews_detail)): 
            
            // Lấy ID và Trạng thái
            $r_id = $row['review_id'] ?? $row['id']; 
            $status_val = isset($row['status']) ? $row['status'] : 1;
        ?>
        <tr>
            <td class="fw-bold text-warning"><?php echo $row['full_name']; ?></td>
            <td><span class="badge bg-warning text-dark"><?php echo $row['rating']; ?>/5</span></td>
            <td><?php echo $row['comment']; ?></td>
            <td><?php echo $row['created_at']; ?></td>
            
            <td class="text-center">
                <a href="index.php?page=reviews&action=toggle_review&id=<?php echo $r_id; ?>" 
                   class="btn btn-sm btn-<?php echo ($status_val == 1) ? 'outline-warning' : 'success'; ?> me-1" 
                   title="<?php echo ($status_val == 1) ? 'Ẩn đánh giá' : 'Hiện đánh giá'; ?>">
                    <i class="fas <?php echo ($status_val == 1) ? 'fa-eye-slash' : 'fa-eye'; ?>"></i>
                </a>

                <a href="index.php?page=reviews&action=delete_review&id=<?php echo $r_id; ?>" 
                   class="btn btn-sm btn-outline-danger" 
                   onclick="return confirm('Xóa bình luận này?');" title="Xóa bình luận">
                    <i class="fas fa-trash"></i>
                </a>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>
            </div>
        </div>
    </div>
</div>