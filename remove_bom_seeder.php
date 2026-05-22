<?php
$file = 'database/seeders/TransactionSeeder.php';

if (file_exists($file)) {
    $content = file_get_contents($file);
    // Remove BOM
    $content = preg_replace('/^\xEF\xBB\xBF/', '', $content);
    // Remove any characters before <?php
    $content = preg_replace('/^[^<]*<\?php/', '<?php', $content);
    file_put_contents($file, $content);
    echo "Fixed: $file\n";
    echo "File has been fixed successfully!\n";
} else {
    echo "File not found: $file\n";
    echo "Please make sure TransactionSeeder.php exists in database/seeders/\n";
}