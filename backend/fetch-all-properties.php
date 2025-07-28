<?php
require_once 'db.php';

// Get filters from POST or set defaults
$minPrice = $_POST['minPrice'] ?? 0;
$maxPrice = $_POST['maxPrice'] ?? 999999999;
$city = $_POST['city'] ?? '';
$minSize = $_POST['minSize'] ?? 0;
$maxSize = $_POST['maxSize'] ?? 99999;
$type = $_POST['propertyType'] ?? '';
$bedrooms = $_POST['bedrooms'] ?? '';

// Base query
$query = "SELECT 
    p.id, p.title, p.price, p.type, p.area, p.location, 
    p.images_json, u.name AS user_name 
FROM properties p 
JOIN users u ON p.user_id = u.id 
WHERE p.price BETWEEN ? AND ? 
  AND p.area BETWEEN ? AND ? 
  AND p.status = 'approved'
";

$params = [$minPrice, $maxPrice, $minSize, $maxSize];
$types = "dddd";

// Apply filters
if (!empty($city)) {
    $query .= " AND p.location LIKE ?";
    $params[] = "%$city%";
    $types .= "s";
}
if (!empty($type)) {
    $query .= " AND p.type = ?";
    $params[] = $type;
    $types .= "s";
}
if (!empty($bedrooms)) {
    $query .= " AND p.bedrooms = ?";
    $params[] = $bedrooms;
    $types .= "s";
}

// Prepare & execute
$stmt = $conn->prepare($query);
if ($stmt === false) {
    echo json_encode(['status' => 'error', 'message' => 'Query preparation failed.']);
    exit;
}
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

$properties = [];
while ($row = $result->fetch_assoc()) {
    $properties[] = [
        'id' => $row['id'],
        'title'      => $row['title'],
        'price'      => (float)$row['price'],
        'type'       => $row['type'],
        'area'       => (float)$row['area'],
        'location'   => $row['location'],
        'user_name'  => $row['user_name'],
        'images'     => json_decode($row['images_json'] ?? '[]')
    ];
}

// Output JSON
echo json_encode([
    'status' => 'success',
    'properties' => $properties
]);
?>
