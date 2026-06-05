<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">Quản Lý Danh Mục<span class="badge bg-danger fs-6 ms-2"></span></h2>
        <a href="index.php?page=add_category" class="btn text-white fw-bold shadow-sm" style="background-color: #c4a16b;">
            <i class="fas fa-plus me-2"></i>Thêm Danh Mục</a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            
            <form action="index.php" method="GET" class="mb-4">
                <input type="hidden" name="page" value="categories"> 
                <div class="input-group" style="max-width: 400px;">
                    <input type="text" name="search" class="form-control bg-light border-0" placeholder="Search by name..." value="<?php echo htmlspecialchars($search ?? ''); ?>">
                    <button type="submit" class="btn text-white px-4" style="background-color: #c4a16b;">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover align-middle border-bottom">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" class="py-3 px-3">ID</th>
                            <th scope="col" class="py-3">Tên Danh Mục</th>
                            <th scope="col" class="py-3 text-center">Total Products</th>
                            <th scope="col" class="py-3 text-center">Trạng Thái</th>
                            <th scope="col" class="py-3">Created Date</th>
                            <th scope="col" class="py-3 text-center">Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        <?php 
                        if (isset($cats_query) && $cats_query && mysqli_num_rows($cats_query) > 0) {
                            while ($cat = mysqli_fetch_assoc($cats_query)) {
                                $c_id = $cat['category_id'] ?? $cat['id'] ?? 0;
                                $c_name = $cat['category_name'] ?? $cat['name'] ?? 'Unnamed';
                                $c_status = $cat['status'] ?? 1; 
                                $c_date = isset($cat['created_at']) ? date('Y-m-d', strtotime($cat['created_at'])) : '2025-01-01';

                                $count_query = @mysqli_query($db, "SELECT COUNT(*) as total FROM products WHERE category_id = '$c_id'");
                                $p_count = $count_query ? mysqli_fetch_assoc($count_query)['total'] : 0;
                        ?>
                            <tr>
                                <td class="px-3 fw-bold text-muted">#<?php echo $c_id; ?></td>
                                <td class="fw-bold text-dark"><?php echo $c_name; ?></td>
                                <td class="text-center fw-bold" style="color: #c4a16b;"><?php echo $p_count; ?></td>
                                <td class="text-center">
                                    <?php if ($c_status == 1): ?>
                                        <span class="badge bg-success px-3 py-2 rounded-1">Hiển thị</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger px-3 py-2 rounded-1">Ẩn</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-muted"><?php echo $c_date; ?></td>
                                <td class="text-center">
                                    <a href="index.php?page=edit_category&id=<?php echo $c_id; ?>" class="btn btn-success btn-sm rounded-1 me-1" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="javascript:void(0);" onclick="confirmAction(event, 'index.php?action=delete_category&id=<?php echo $c_id; ?>', 'Are you sure you want to delete this category?');" class="btn btn-danger btn-sm rounded-1" title="Delete">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php 
                            }
                        } else {
                            echo '<tr><td colspan="6" class="text-center py-4 text-muted">Không tìm thấy danh mục.</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>