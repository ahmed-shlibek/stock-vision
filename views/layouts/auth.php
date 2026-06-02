<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'Login') ?> — <?= APP_NAME ?></title>
    <meta name="base-url" content="<?= BASE_URL ?>">
    
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- App CSS -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/variables.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/base.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/components.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/forms.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/utilities.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/auth.css">
</head>
<body>
    <div class="auth-page">
        <?php require $content; ?>
    </div>
    
    <?php require __DIR__ . '/../partials/toast.php'; ?>
    
    <!-- Core JS -->
    <script src="<?= BASE_URL ?>/js/utils.js"></script>
    <script src="<?= BASE_URL ?>/js/app.js"></script>
    <script src="<?= BASE_URL ?>/js/auth.js"></script>
</body>
</html>
