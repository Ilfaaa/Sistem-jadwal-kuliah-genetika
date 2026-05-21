<?php
$lines = file('app/Http/Controllers/PenjadwalankuliahController.php');
$line = $lines[415]; // 0-indexed, so line 416
echo "Line 416: " . $line;
echo "Hex: " . bin2hex($line) . "\n";
// Also check nearby for any stray characters
for ($i = 414; $i < 430; $i++) {
    $hex = bin2hex(trim($lines[$i]));
    if (strpos($hex, 'e28099') !== false) {
        echo "RAW Unicode found on line " . ($i+1) . ": " . trim($lines[$i]) . "\n";
    }
}
