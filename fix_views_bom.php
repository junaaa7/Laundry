<?php
function removeBOMFromFile($file) {
    $content = file_get_contents($file);
    // Remove BOM
    $content = preg_replace('/^\xEF\xBB\xBF/', '', $content);
    // Remove any whitespace before <?php or <!DOCTYPE
    $content = ltrim($content);
    file_put_contents($file, $content);
    return true;
}

function scanViews($dir) {
    $files = scandir($dir);
    foreach ($files as $file) {
        if ($file == '.' || $file == '..') continue;
        $path = $dir . DIRECTORY_SEPARATOR . $file;
        if (is_dir($path)) {
            scanViews($path);
        } elseif (pathinfo($path, PATHINFO_EXTENSION) == 'php' || pathinfo($path, PATHINFO_EXTENSION) == 'blade.php') {
            removeBOMFromFile($path);
            echo "Fixed: $path\n";
        }
    }
}

echo "Removing BOM from views...\n";
scanViews(__DIR__ . '/resources/views');
echo "Done!\n";