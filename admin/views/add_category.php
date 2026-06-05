<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-dark mb-0">Add New Category</h3>
        <a href="index.php?page=categories" class="btn btn-secondary shadow-sm rounded-1">
            <i class="fas fa-arrow-left me-2"></i>Back to List
        </a>
    </div>

    <div class="card border-0 shadow-sm" style="max-width: 600px;">
        <div class="card-body p-4">
            <form method="POST" action="">
                
                <div class="mb-4">
                    <label class="form-label fw-bold text-secondary">Tên Danh Mục</label>
                    <input type="text" name="category_name" class="form-control border-primary py-2" placeholder="e.g., Croissants, Cookies..." required>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold text-secondary">Trạng Thái</label>
                    <select name="status" class="form-select border-primary py-2" style="cursor: pointer;">
                        <option value="1">Hiển thị</option>
                        <option value="0">Ẩn</option>
                    </select>
                </div>

                <button type="submit" name="btn_add_category" class="btn btn-primary px-4 py-2 fw-bold shadow-sm rounded-1 w-100">
                    <i class="fas fa-plus-circle me-2"></i>Confirm Add Category
                </button>

            </form>
        </div>
    </div>
</div>