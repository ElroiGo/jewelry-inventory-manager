<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/config.php';

$conn = getDB();

$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);

if ($id <= 0) {
    header('Location: items_list.php');
    exit;
}

// Load item first
$stmt = $conn->prepare("
    SELECT id, name, image_path
    FROM items
    WHERE id = ?
");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$item = $result->fetch_assoc();

if (!$item) {
    header('Location: items_list.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $image_full_path = '';

    if (!empty($item['image_path'])) {
        $image_full_path = __DIR__ . '/' . $item['image_path'];
    }

    // Delete row from DB
    $stmt = $conn->prepare("DELETE FROM items WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Delete image file if it exists
        if ($image_full_path !== '' && file_exists($image_full_path)) {
            unlink($image_full_path);
        }

        header('Location: items_list.php?success=deleted');
        exit;
    } else {
        $error = 'Delete failed: ' . $stmt->error;
    }
}

$currentImageExists = !empty($item['image_path']) && file_exists(__DIR__ . '/' . $item['image_path']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Item</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 40px auto;
        }

        h1 {
            margin-bottom: 20px;
        }

        .box {
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 8px;
            background: #fafafa;
        }

        .error {
            background: #ffe6e6;
            color: #b30000;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 15px;
        }

        .item-image {
            margin: 15px 0;
        }

        .item-image img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border: 1px solid #ccc;
            border-radius: 6px;
            display: block;
        }

        .warning {
            color: #b30000;
            font-weight: bold;
            margin: 15px 0;
        }

        button {
            background: #dc3545;
            color: white;
            border: none;
            padding: 10px 18px;
            border-radius: 6px;
            cursor: pointer;
        }

        button:hover {
            background: #b52a37;
        }

        .cancel-link {
            margin-left: 10px;
            color: #666;
            text-decoration: none;
        }

        .no-image {
            color: #888;
            font-style: italic;
        }
    </style>
</head>
<body>

<h1>Delete Item #<?php echo (int)$item['id']; ?></h1>

<?php if ($error !== ''): ?>
    <div class="error"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<div class="box">
    <p><strong>Name:</strong> <?php echo htmlspecialchars($item['name']); ?></p>

    <div class="item-image">
        <strong>Image:</strong><br>
        <?php if ($currentImageExists): ?>
            <img src="<?php echo htmlspecialchars($item['image_path']); ?>" alt="Item image">
        <?php else: ?>
            <span class="no-image">No image</span>
        <?php endif; ?>
    </div>

    <p class="warning">Are you sure you want to delete this item?</p>
    <p>This will also remove the image file from the uploads folder if it exists.</p>

    <form method="POST">
        <input type="hidden" name="id" value="<?php echo (int)$item['id']; ?>">
        <button type="submit">Yes, Delete Item</button>
        <a href="items_list.php" class="cancel-link">Cancel</a>
    </form>
</div>

</body>
</html>
