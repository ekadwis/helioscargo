<div class="row g-3">
    <div class="col-12">
        <label class="form-label">Judul Berita</label>
        <input type="text" name="title" class="form-control" required>
    </div>
    <div class="col-12">
        <label class="form-label">Excerpt <small class="text-muted">(ringkasan singkat)</small></label>
        <textarea name="excerpt" class="form-control" rows="2"></textarea>
    </div>
    <div class="col-12">
        <label class="form-label">Konten Lengkap</label>
        <textarea name="content" class="form-control" rows="6"></textarea>
    </div>
    <div class="col-12">
        <label class="form-label">Gambar <small class="text-muted">(JPG/PNG, maks 2MB)</small></label>
        <input type="file" name="image" class="form-control" accept="image/*">
    </div>
    <div class="col-12">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="is_published" value="1" checked>
            <label class="form-check-label">Publish ke landing page</label>
        </div>
    </div>
</div>