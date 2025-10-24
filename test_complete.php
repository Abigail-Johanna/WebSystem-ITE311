<?php
// Complete system test
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Complete Upload System Test</h1>";
echo "<style>
    .pass { color: green; font-weight: bold; }
    .fail { color: red; font-weight: bold; }
    table { border-collapse: collapse; width: 100%; }
    td, th { border: 1px solid #ddd; padding: 8px; text-align: left; }
    th { background-color: #4CAF50; color: white; }
</style>";

// Test 1: PHP Configuration
echo "<h2>1. PHP Upload Configuration</h2>";
echo "<table>";
echo "<tr><th>Setting</th><th>Value</th><th>Status</th></tr>";

$upload_max = ini_get('upload_max_filesize');
$post_max = ini_get('post_max_size');
$file_uploads = ini_get('file_uploads');

echo "<tr><td>file_uploads</td><td>" . ($file_uploads ? 'ON' : 'OFF') . "</td><td class='" . ($file_uploads ? 'pass' : 'fail') . "'>" . ($file_uploads ? '✓' : '✗') . "</td></tr>";
echo "<tr><td>upload_max_filesize</td><td>$upload_max</td><td class='pass'>✓</td></tr>";
echo "<tr><td>post_max_size</td><td>$post_max</td><td class='pass'>✓</td></tr>";
echo "</table>";

// Test 2: Directory Structure
echo "<h2>2. Directory Structure</h2>";
echo "<table>";
echo "<tr><th>Path</th><th>Exists</th><th>Writable</th></tr>";

$paths = [
    'writable' => __DIR__ . '/writable',
    'uploads' => __DIR__ . '/writable/uploads',
    'materials' => __DIR__ . '/writable/uploads/materials'
];

foreach ($paths as $name => $path) {
    $exists = is_dir($path);
    $writable = is_writable($path);
    echo "<tr><td>$name</td>";
    echo "<td class='" . ($exists ? 'pass' : 'fail') . "'>" . ($exists ? '✓ YES' : '✗ NO') . "</td>";
    echo "<td class='" . ($writable ? 'pass' : 'fail') . "'>" . ($writable ? '✓ YES' : '✗ NO') . "</td>";
    echo "</tr>";
    
    if (!$exists) {
        mkdir($path, 0755, true);
        echo "<tr><td colspan='3'>Created: $path</td></tr>";
    }
}
echo "</table>";

// Test 3: Database Connection
echo "<h2>3. Database Connection</h2>";
try {
    require __DIR__ . '/app/Config/Database.php';
    $config = config('Database');
    $db = \Config\Database::connect();
    
    echo "<p class='pass'>✓ Database connected successfully</p>";
    echo "<p>Database: " . $config->default['database'] . "</p>";
    
    // Test tables
    echo "<h3>Tables Check:</h3>";
    echo "<table>";
    echo "<tr><th>Table</th><th>Exists</th><th>Rows</th></tr>";
    
    $tables = ['courses', 'materials', 'enrollments'];
    foreach ($tables as $table) {
        $query = $db->query("SHOW TABLES LIKE '$table'");
        $exists = $query->getNumRows() > 0;
        
        echo "<tr><td>$table</td>";
        echo "<td class='" . ($exists ? 'pass' : 'fail') . "'>" . ($exists ? '✓ YES' : '✗ NO') . "</td>";
        
        if ($exists) {
            $count = $db->query("SELECT COUNT(*) as cnt FROM $table")->getRow()->cnt;
            echo "<td>$count</td>";
        } else {
            echo "<td>-</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
    
    // Show courses
    echo "<h3>Available Courses:</h3>";
    $courses = $db->query("SELECT id, title FROM courses")->getResultArray();
    if ($courses) {
        echo "<ul>";
        foreach ($courses as $course) {
            echo "<li>ID: {$course['id']} - {$course['title']}</li>";
        }
        echo "</ul>";
    } else {
        echo "<p class='fail'>No courses found!</p>";
    }
    
} catch (Exception $e) {
    echo "<p class='fail'>✗ Database Error: " . $e->getMessage() . "</p>";
}

// Test 4: Try Manual Insert
echo "<h2>4. Test Database Insert</h2>";
try {
    $testData = [
        'course_id' => 4,
        'file_name' => 'test_file.pdf',
        'file_path' => 'writable/uploads/materials/test123.pdf',
        'created_at' => date('Y-m-d H:i:s')
    ];
    
    $builder = $db->table('materials');
    $result = $builder->insert($testData);
    
    if ($result) {
        $insertId = $db->insertID();
        echo "<p class='pass'>✓ Insert successful! ID: $insertId</p>";
        
        // Clean up
        $db->query("DELETE FROM materials WHERE id = ?", [$insertId]);
        echo "<p><em>(Test record deleted)</em></p>";
    } else {
        echo "<p class='fail'>✗ Insert failed: " . json_encode($db->error()) . "</p>";
    }
} catch (Exception $e) {
    echo "<p class='fail'>✗ Insert exception: " . $e->getMessage() . "</p>";
}

// Test 5: Check Routes
echo "<h2>5. Routes Check</h2>";
echo "<p>Upload URL should be: <code>/admin/course/4/upload</code></p>";
echo "<p><a href='/admin/course/4/upload'>Test Upload Link for Course 4</a></p>";

echo "<h2>✅ Test Complete</h2>";
echo "<p><a href='/dashboard'>Go to Dashboard</a> | <a href='/admin/course/4/upload'>Test Upload</a></p>";
?>
