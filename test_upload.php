<?php
// Simple diagnostic script for upload system
echo "<h1>Upload System Diagnostic</h1>";

// Check writable directory
$uploadPath = __DIR__ . '/writable/uploads/materials/';
echo "<h2>1. Upload Directory Check:</h2>";
echo "Path: " . $uploadPath . "<br>";
echo "Exists: " . (is_dir($uploadPath) ? 'YES' : 'NO') . "<br>";
echo "Writable: " . (is_writable($uploadPath) ? 'YES' : 'NO') . "<br>";

if (!is_dir($uploadPath)) {
    mkdir($uploadPath, 0755, true);
    echo "Directory created!<br>";
}

// Check database connection
echo "<h2>2. Database Connection:</h2>";
try {
    require __DIR__ . '/app/Config/Database.php';
    $config = config('Database');
    $db = \Config\Database::connect();
    
    echo "Connected: YES<br>";
    
    // Check materials table
    $query = $db->query("SHOW TABLES LIKE 'materials'");
    if ($query->getNumRows() > 0) {
        echo "Materials table: EXISTS<br>";
        
        // Check table structure
        $structure = $db->query("DESCRIBE materials")->getResultArray();
        echo "<table border='1'><tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th></tr>";
        foreach ($structure as $col) {
            echo "<tr>";
            echo "<td>" . $col['Field'] . "</td>";
            echo "<td>" . $col['Type'] . "</td>";
            echo "<td>" . $col['Null'] . "</td>";
            echo "<td>" . $col['Key'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "Materials table: DOES NOT EXIST<br>";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "<br>";
}

// Check PHP upload settings
echo "<h2>3. PHP Upload Settings:</h2>";
echo "upload_max_filesize: " . ini_get('upload_max_filesize') . "<br>";
echo "post_max_size: " . ini_get('post_max_size') . "<br>";
echo "max_file_uploads: " . ini_get('max_file_uploads') . "<br>";
echo "file_uploads: " . (ini_get('file_uploads') ? 'ON' : 'OFF') . "<br>";

echo "<h2>4. Test Complete</h2>";
echo "<a href='/dashboard'>Back to Dashboard</a>";
?>
