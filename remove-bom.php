<?php
$file = 'src/utils/Router.php';
$content = file_get_contents($file);
$bom = "\xef\xbb\xbf";
if (strpos($content, $bom) === 0) {
    $content = substr($content, 3);
    file_put_contents($file, $content);
    echo "✅ BOM removed from Router.php\n";
} else {
    echo "✔️  Router.php already clean\n";
}
?>
