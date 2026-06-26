$UTF8Sig = [byte[]]@(0xEF, 0xBB, 0xBF)
$filesToFix = @(
    'index.php',
    'src\Controllers\AuthController.php',
    'src\Controllers\BaseController.php',
    'src\Controllers\RequestController.php',
    'src\Controllers\ApprovalController.php',
    'src\Controllers\AdminController.php',
    'src\Controllers\DashboardController.php',
    'src\Models\UserModel.php',
    'src\Models\RequestModel.php',
    'src\Models\ApprovalModel.php',
    'src\Services\AuthService.php',
    'src\Services\RequestService.php',
    'src\Services\ApprovalService.php',
    'src\utils\Database.php',
    'src\utils\Router.php'
)

$fixedCount = 0
Write-Host "🔧 Removing UTF-8 BOM from 15 files..." -ForegroundColor Cyan
Write-Host ""

foreach ($file in $filesToFix) {
    if (Test-Path $file) {
        $fileBytes = [System.IO.File]::ReadAllBytes($file)
        if ($fileBytes.Length -ge 3 -and $fileBytes[0] -eq $UTF8Sig[0] -and $fileBytes[1] -eq $UTF8Sig[1] -and $fileBytes[2] -eq $UTF8Sig[2]) {
            $fileWithoutBom = $fileBytes[3..($fileBytes.Length - 1)]
            [System.IO.File]::WriteAllBytes($file, $fileWithoutBom)
            $fixedCount++
            Write-Host "✅ Fixed: $file" -ForegroundColor Green
        }
        else {
            Write-Host "✔️  Clean: $file" -ForegroundColor Gray
        }
    }
    else {
        Write-Host "⚠️  Missing: $file" -ForegroundColor Yellow
    }
}

Write-Host ""
Write-Host "✅ Complete! Fixed $fixedCount files" -ForegroundColor Green
