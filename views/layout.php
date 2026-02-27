<?php
// Flash message helper
function getFlash() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}
$flash = getFlash();
$currentYear = date('Y');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Library Manager' ?> | LibraBase</title>
    <meta name="description" content="LibraBase - Modern book library management system">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="app-wrapper">
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-brand">
            <div class="brand-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
                </svg>
            </div>
            <div class="brand-text">
                <span class="brand-name">LibraBase</span>
                <span class="brand-sub">Book Manager</span>
            </div>
        </div>

        <nav class="sidebar-nav">
            <span class="nav-label">NAVIGATION</span>
            <a href="index.php" class="nav-item <?= (!isset($_GET['action']) || $_GET['action'] === 'index') ? 'active' : '' ?>">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
                    <rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>
                </svg>
                Dashboard
            </a>
            <a href="index.php?action=create" class="nav-item <?= (isset($_GET['action']) && $_GET['action'] === 'create') ? 'active' : '' ?>">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/>
                    <line x1="8" y1="12" x2="16" y2="12"/>
                </svg>
                Add New Book
            </a>
        </nav>

        <div class="sidebar-footer">
            <div class="footer-stats">
                <span class="stats-label">Total Books</span>
                <span class="stats-value" id="totalBooks">—</span>
            </div>
        </div>
    </aside>

    <!-- Main -->
    <main class="main-content">
        <div class="topbar">
            <div class="topbar-left">
                <h1 class="page-title"><?= $pageTitle ?? 'Dashboard' ?></h1>
                <p class="page-breadcrumb">LibraBase <?= isset($pageTitle) ? '/ ' . htmlspecialchars($pageTitle) : '' ?></p>
            </div>
            <div class="topbar-right">
                <div class="topbar-time">
                    <span id="live-time"></span>
                    <span class="time-zone">WIB</span>
                </div>
            </div>
        </div>

        <?php if ($flash): ?>
        <div class="flash-data"
             data-type="<?= $flash['type'] ?>"
             data-msg="<?= htmlspecialchars($flash['msg']) ?>">
        </div>
        <?php endif; ?>

        <div class="content-area">
            <?= $content ?? '' ?>
        </div>
    </main>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="assets/js/app.js"></script>
</body>
</html>
