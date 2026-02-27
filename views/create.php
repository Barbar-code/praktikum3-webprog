<?php
$genres = [
    'Technology', 'Science Fiction', 'Fantasy', 'Mystery', 'Romance',
    'History', 'Biography', 'Self-Help', 'Psychology', 'Philosophy',
    'Economics', 'Art', 'Travel', 'Children', 'Other'
];
$currentYear = date('Y');
$data = $data ?? [];
$errors = $errors ?? [];
ob_start();
?>
<div class="card form-card">
    <div class="card-header">
        <h2 class="card-title">Add New Book</h2>
        <a href="index.php" class="btn btn-ghost">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                <line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/>
            </svg>
            Back
        </a>
    </div>
    <div class="card-body">
        <form action="index.php?action=create" method="POST" id="bookForm" novalidate>
            <div class="form-grid">
                <div class="form-group <?= isset($errors['title']) ? 'has-error' : '' ?>">
                    <label for="title">Book Title <span class="required">*</span></label>
                    <input type="text" id="title" name="title" class="form-control"
                           placeholder="e.g. The Great Gatsby"
                           value="<?= htmlspecialchars($data['title'] ?? '') ?>">
                    <?php if (isset($errors['title'])): ?>
                        <span class="error-msg"><?= $errors['title'] ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group <?= isset($errors['author']) ? 'has-error' : '' ?>">
                    <label for="author">Author <span class="required">*</span></label>
                    <input type="text" id="author" name="author" class="form-control"
                           placeholder="e.g. F. Scott Fitzgerald"
                           value="<?= htmlspecialchars($data['author'] ?? '') ?>">
                    <?php if (isset($errors['author'])): ?>
                        <span class="error-msg"><?= $errors['author'] ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group <?= isset($errors['genre']) ? 'has-error' : '' ?>">
                    <label for="genre">Genre <span class="required">*</span></label>
                    <select id="genre" name="genre" class="form-control">
                        <option value="">— Select Genre —</option>
                        <?php foreach ($genres as $g): ?>
                        <option value="<?= $g ?>" <?= ($data['genre'] ?? '') === $g ? 'selected' : '' ?>>
                            <?= $g ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (isset($errors['genre'])): ?>
                        <span class="error-msg"><?= $errors['genre'] ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group <?= isset($errors['isbn']) ? 'has-error' : '' ?>">
                    <label for="isbn">ISBN <span class="required">*</span></label>
                    <input type="text" id="isbn" name="isbn" class="form-control"
                           placeholder="e.g. 9780061120084"
                           value="<?= htmlspecialchars($data['isbn'] ?? '') ?>">
                    <?php if (isset($errors['isbn'])): ?>
                        <span class="error-msg"><?= $errors['isbn'] ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group <?= isset($errors['year']) ? 'has-error' : '' ?>">
                    <label for="year">Publication Year <span class="required">*</span></label>
                    <input type="number" id="year" name="year" class="form-control"
                           placeholder="e.g. 2024" min="1000" max="<?= $currentYear ?>"
                           value="<?= htmlspecialchars($data['year'] ?? $currentYear) ?>">
                    <?php if (isset($errors['year'])): ?>
                        <span class="error-msg"><?= $errors['year'] ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group <?= isset($errors['stock']) ? 'has-error' : '' ?>">
                    <label for="stock">Stock</label>
                    <input type="number" id="stock" name="stock" class="form-control"
                           placeholder="e.g. 5" min="0"
                           value="<?= htmlspecialchars($data['stock'] ?? '1') ?>">
                    <?php if (isset($errors['stock'])): ?>
                        <span class="error-msg"><?= $errors['stock'] ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group full-width">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" class="form-control" rows="4"
                              placeholder="Short synopsis or notes about this book..."><?= htmlspecialchars($data['description'] ?? '') ?></textarea>
                </div>
            </div>

            <div class="form-actions">
                <a href="index.php" class="btn btn-ghost">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                        <polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/>
                    </svg>
                    Save Book
                </button>
            </div>
        </form>
    </div>
</div>
<?php
$content = ob_get_clean();
$pageTitle = 'Add New Book';
require_once __DIR__ . '/layout.php';
