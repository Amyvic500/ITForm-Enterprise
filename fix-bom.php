<?php
$UTF8Sig = "\xef\xbb\xbf";
$filesToFix = [
    'index.php',
    'src/Controllers/AuthController.php',
    'src/Controllers/BaseController.php',
    'src/Controllers/RequestController.php',
    'src/Controllers/ApprovalController.php',
    'src/Controllers/AdminController.php',
    'src/Controllers/DashboardController.php',
    'src/Models/UserModel.php',
    'src/Models/RequestModel.php',
    'src/Models/ApprovalModel.php',
    'src/Services/AuthService.php',
    'src/Services/RequestService.php',
    'src/Services/ApprovalService.php',
    'src/utils/Database.php',
    'src/utils/Router.php'
];

echo "🔧 Removing UTF-8 BOM...\n\n";
$fixedCount = 0;

foreach ($filesToFix as $file) {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        if (strpos($content, $UTF8Sig) === 0) {
            $content = substr($content, 3);
            file_put_contents($file, $content);
            $fixedCount++;
            echo "✅ Fixed: $file\n";
        } else {
            echo "✔️  Clean: $file\n";
        }
    } else {
        echo "⚠️  Missing: $file\n";
    }
}

echo "\n✅ Complete! Fixed $fixedCount files\n";
?>
