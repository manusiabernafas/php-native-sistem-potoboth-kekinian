<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Mobooth Admin' ?></title>
    <!-- Favicon - inline SVG to avoid 404 -->
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><rect width='100' height='100' rx='18' fill='%231f2937' /><path d='M18 42h64c6.63 0 12 5.37 12 12v8c0 6.63-5.37 12-12 12h-8c-2.21 0-4-1.79-4-4v-2c0-2.21-1.79-4-4-4H34c-2.21 0-4 1.79-4 4v2c0 2.21-1.79 4-4 4H18c-6.63 0-12-5.37-12-12v-8c0-6.63 5.37-12 12-12z' fill='%230284c7' /><rect x='22' y='51' width='14' height='4' rx='2' fill='%23ffffff' /><rect x='27' y='46' width='4' height='14' rx='2' fill='%23ffffff' /><circle cx='66' cy='52' r='3' fill='%23ffffff' /><circle cx='74' cy='58' r='3' fill='%23ffffff' /><circle cx='58' cy='58' r='3' fill='%23ffffff' /><circle cx='66' cy='64' r='3' fill='%23ffffff' /></svg>">
    
    <!-- Google Fonts for Modern Typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Press+Start+2P&family=Pixelify+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Lucide Icons (CDN) for clean modern icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- SweetAlert2 (Retro Style Support) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
    <!-- Core External CSS Files (No inline styles, no mixed code) -->
    <link rel="stylesheet" href="<?= BASEURL; ?>/assets/css/variables.css">
    <link rel="stylesheet" href="<?= BASEURL; ?>/assets/css/variables.css?v=<?= time(); ?>">
    <link rel="stylesheet" href="<?= BASEURL; ?>/assets/css/main.css?v=<?= time(); ?>">
    <link rel="stylesheet" href="<?= BASEURL; ?>/assets/css/components.css?v=<?= time(); ?>">
    
    <!-- Dynamic Page-Specific Stylesheets -->
    <?php if (isset($css) && is_array($css)): ?>
        <?php foreach ($css as $styleFile): ?>
            <link rel="stylesheet" href="<?= BASEURL; ?>/assets/css/<?= $styleFile; ?>.css?v=<?= time(); ?>">
        <?php endforeach; ?>
    <?php elseif (isset($css)): ?>
        <link rel="stylesheet" href="<?= BASEURL; ?>/assets/css/<?= $css; ?>.css?v=<?= time(); ?>">
    <?php endif; ?>
    <script>
        window.BASEURL = '<?= BASEURL; ?>';
    </script>
</head>
<body>
