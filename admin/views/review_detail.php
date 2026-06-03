<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-dark">Detailed Reviews</h3>
        <a href="index.php?page=reviews" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i>Back to Reviews</a>
    </div>
    
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4 py-3" style="width: 20%;">Customer</th>
                            <th class="text-center py-3" style="width: 10%;">Rating</th>
                            <th class="py-3" style="width: 50%;">Comment</th>
                            <th class="text-end pe-4 py-3" style="width: 20%;">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($r = mysqli_fetch_assoc($reviews_detail)): ?>
                        <tr>
                            <td class="ps-4 fw-bold text-primary"><?php echo htmlspecialchars($r['full_name']); ?></td>
                            <td class="text-center">
                                <span class="badge bg-warning text-dark px-3"><?php echo $r['rating']; ?>/5</span>
                            </td>
                            <td class="text-muted"><?php echo htmlspecialchars($r['comment']); ?></td>
                            <td class="text-end pe-4 text-secondary"><?php echo $r['created_at']; ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>