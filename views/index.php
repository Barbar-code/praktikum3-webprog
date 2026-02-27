<?php
$genres = [
    'Technology', 'Science Fiction', 'Fantasy', 'Mystery', 'Romance',
    'History', 'Biography', 'Self-Help', 'Psychology', 'Philosophy',
    'Economics', 'Art', 'Travel', 'Children', 'Other'
];
$currentYear = date('Y');
ob_start();
?>
<div class="stats-grid">
    <div class="stat-card accent-purple">
        <div class="stat-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
            </svg>
        </div>
        <div class="stat-info">
            <p class="stat-value"><?= count($books) ?></p>
            <p class="stat-label">Total Books</p>
        </div>
    </div>
    <div class="stat-card accent-teal">
        <div class="stat-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
            </svg>
        </div>
        <div class="stat-info">
            <p class="stat-value"><?= count(array_unique(array_column($books, 'author'))) ?></p>
            <p class="stat-label">Authors</p>
        </div>
    </div>
    <div class="stat-card accent-amber">
        <div class="stat-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
            </svg>
        </div>
        <div class="stat-info">
            <p class="stat-value"><?= count(array_unique(array_column($books, 'genre'))) ?></p>
            <p class="stat-label">Genres</p>
        </div>
    </div>
    <div class="stat-card accent-rose">
        <div class="stat-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/>
                <line x1="7" y1="7" x2="7.01" y2="7"/>
            </svg>
        </div>
        <div class="stat-info">
            <p class="stat-value"><?= array_sum(array_column($books, 'stock')) ?></p>
            <p class="stat-label">Total Stock</p>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h2 class="card-title">Book Collection</h2>
        <a href="index.php?action=create" class="btn btn-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/>
                <line x1="8" y1="12" x2="16" y2="12"/>
            </svg>
            Add Book
        </a>
    </div>
    <div class="card-body">
        <table id="booksTable" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Genre</th>
                    <th>ISBN</th>
                    <th>Year</th>
                    <th>Stock</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($books)): ?>
                <tr>
                    <td colspan="8" style="text-align:center;padding:2rem;color:var(--text-muted)">
                        No books found. <a href="index.php?action=create">Add your first book</a>.
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($books as $i => $book): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td>
                        <div class="book-title"><?= htmlspecialchars($book['title']) ?></div>
                    </td>
                    <td><?= htmlspecialchars($book['author']) ?></td>
                    <td><span class="genre-badge"><?= htmlspecialchars($book['genre']) ?></span></td>
                    <td><code class="isbn-code"><?= htmlspecialchars($book['isbn']) ?></code></td>
                    <td><?= $book['year'] ?></td>
                    <td>
                        <span class="stock-badge <?= $book['stock'] <= 0 ? 'out' : ($book['stock'] <= 2 ? 'low' : 'ok') ?>">
                            <?= $book['stock'] ?>
                        </span>
                    </td>
                    <td>
                        <div class="action-btns">
                            <a href="index.php?action=edit&id=<?= $book['id'] ?>" class="btn-icon btn-edit" title="Edit">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                </svg>
                            </a>
                            <button class="btn-icon btn-delete" title="Delete"
                                    data-id="<?= $book['id'] ?>"
                                    data-title="<?= htmlspecialchars($book['title']) ?>">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/>
                                    <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php
$content = ob_get_clean();
$pageTitle = 'Dashboard';
require_once __DIR__ . '/layout.php';
