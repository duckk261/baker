<?php
// Gán biến linh hoạt để tránh lỗi tên cột
$current_name = $cat_info['category_name'] ?? $cat_info['name'] ?? '';
$current_status = $cat_info['status'] ?? 1;
?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-dark mb-0">Edit Category</h3>
        <a href="index.php?page=categories" class="btn btn-secondary shadow-sm rounded-1">
            <i class="fas fa-arrow-left me-2"></i>Back to List
        </a>
    </div>

    <div class="card border-0 shadow-sm" style="max-width: 600px;">
        <div class="card-body p-4">
            <form method="POST" action="">
                
                <div class="mb-4">
                    <label class="form-label fw-bold text-secondary">Category Name</label>
                    <input type="text" name="category_name" class="form-control border-primary py-2" value="<?php echo htmlspecialchars($current_name); ?>" required>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold text-secondary">Status</label>
                    <select name="status" class="form-select border-primary py-2" style="cursor: pointer;">
                        <option value="1" <?php echo ($current_status == 1) ? 'selected' : ''; ?>>Visible</option>
                        <option value="0" <?php echo ($current_status == 0) ? 'selected' : ''; ?>>Hidden</option>
                    </select>
                </div>

                <button type="submit" name="btn_update_category" class="btn btn-primary px-4 py-2 fw-bold shadow-sm rounded-1 w-100">
                    <i class="fas fa-save me-2"></i>Save Changes
                </button>

            </form>
        </div>
    </div>
</div>