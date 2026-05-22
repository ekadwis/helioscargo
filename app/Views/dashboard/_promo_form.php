<div class="row g-3">
    <div class="col-12">
        <label class="form-label">Judul Promo</label>
        <input type="text" name="title" class="form-control" required>
    </div>
    <div class="col-12">
        <label class="form-label">Deskripsi</label>
        <textarea name="description" class="form-control" rows="3"></textarea>
    </div>
    <div class="col-md-4">
        <label class="form-label">Badge Text</label>
        <input type="text" name="badge_text" class="form-control" placeholder="Contoh: Limited Offer">
    </div>
    <div class="col-md-4">
        <label class="form-label">Badge Color</label>
        <select name="badge_color" class="form-control">
            <option value="warning">Warning (Kuning)</option>
            <option value="success">Success (Hijau)</option>
            <option value="primary">Primary (Biru)</option>
            <option value="danger">Danger (Merah)</option>
            <option value="info">Info (Cyan)</option>
            <option value="secondary">Secondary (Abu)</option>
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">Berlaku s/d</label>
        <input type="date" name="valid_until" class="form-control">
    </div>
    <div class="col-12">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="is_active" value="1" checked>
            <label class="form-check-label">Tampilkan di landing page</label>
        </div>
    </div>
</div>