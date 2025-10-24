<?php
// Test database insert
require __DIR__ . '/vendor/autoload.php';

// Bootstrap CodeIgniter
$pathsConfig = new \Config\Paths();
$bootstrap = new \CodeIgniter\Boot($pathsConfig);
$app = $bootstrap->bootWeb();

echo "<h1>Database Insert Test</h1>";

// Test database connection
$db = \Config\Database::connect();
echo "Database connected: " . ($db ? 'YES' : 'NO') . "<br><br>";

// Test insert
$data = [
    'course_id' => 4,
    'file_name' => 'test_manual_insert.pdf',
    'file_path' => 'writable/uploads/materials/test123.pdf',
    'created_at' => date('Y-m-d H:i:s')
];

echo "<h2>Attempting to insert:</h2>";
echo "<pre>" . print_r($data, true) . "</pre>";

try {
    $builder = $db->table('materials');
    $result = $builder->insert($data);
    
    if ($result) {
        $insertId = $db->insertID();
        echo "<h3 style='color:green'>✓ SUCCESS! Insert ID: " . $insertId . "</h3>";
        
        // Show the inserted record
        $query = $db->query("SELECT * FROM materials WHERE id = ?", [$insertId]);
        $record = $query->getRowArray();
        echo "<pre>" . print_r($record, true) . "</pre>";
        
        // Clean up test record
        $db->query("DELETE FROM materials WHERE id = ?", [$insertId]);
        echo "<p><em>Test record deleted.</em></p>";
    } else {
        echo "<h3 style='color:red'>✗ FAILED to insert</h3>";
        echo "Error: " . $db->error()['message'];
    }
} catch (Exception $e) {
    echo "<h3 style='color:red'>✗ EXCEPTION</h3>";
    echo "Error: " . $e->getMessage();
}

echo "<br><a href='/dashboard'>Back to Dashboard</a>";
?>
