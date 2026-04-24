<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = getDB();

    $category_id = 1; // temporary fixed value to satisfy DB
    $name = trim($_POST['name'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $material = trim($_POST['material'] ?? '');
    $price = (float)($_POST['price'] ?? 0);
    $stock_qty = (int)($_POST['stock_qty'] ?? 0);
    $image_path = '';

    if ($name === '') {
        $error = 'Name is required.';
    } elseif ($price <= 0) {
        $error = 'Price must be greater than 0.';
    } elseif ($stock_qty < 0) {
        $error = 'Stock cannot be negative.';
    }

    if ($error === '' && isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $upload_dir = __DIR__ . '/uploads/';
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

        if (in_array($ext, ['jpg', 'jpeg', 'png'])) {
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            $filename = 'item_' . time() . '_' . rand(100, 999) . '.' . $ext;
            $newpath = $upload_dir . $filename;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $newpath)) {
                $image_path = 'uploads/' . $filename;
            } else {
                $error = 'Image upload failed.';
            }
        } else {
            $error = 'Only JPG, JPEG, PNG files are allowed.';
        }
    }

    if ($error === '') {
        $stmt = $conn->prepare("
            INSERT INTO items (category_id, name, category, material, price, stock_qty, image_path)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "isssdis",
            $category_id,
            $name,
            $category,
            $material,
            $price,
            $stock_qty,
            $image_path
        );

        if ($stmt->execute()) {
            header('Location: items_list.php?success=added');
            exit;
        } else {
            $error = 'Insert failed: ' . $stmt->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Item</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 650px; margin: 40px auto; }
        h1 { margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; font-weight: bold; margin-bottom: 6px; }
        input { width: 100%; padding: 8px; box-sizing: border-box; }
        .error { background: #ffe6e6; color: #b30000; padding: 10px; border-radius: 6px; margin-bottom: 15px; }
        button { background: #28a745; color: white; border: none; padding: 10px 18px; border-radius: 6px; cursor: pointer; }
        button:hover { background: #218838; }
        .cancel-link { margin-left: 10px; color: #666; text-decoration: none; }
    </style>
</head>
<body>

<h1>Add New Item</h1>

<?php if ($error !== ''): ?>
    <div class="error"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
    <div class="form-group">
        <label>Name:</label>
        <input type="text" name="name" required>
    </div>

    <div class="form-group">
        <label>Category:</label>
        <input type="text" name="category">
    </div>

    <div class="form-group">
        <label>Material:</label>
        <input type="text" name="material">
    </div>

    <div class="form-group">
        <label>Price:</label>
        <input type="number" step="0.01" name="price" required>
    </div>

    <div class="form-group">
        <label>Stock:</label>
        <input type="number" name="stock_qty" value="0" required>
    </div>

    <div class="form-group">
        <label>Image:</label>
        <input type="file" name="image" accept=".jpg,.jpeg,.png">
    </div>

    <button type="submit">Add Item</button>
    <a href="items_list.php" class="cancel-link">Cancel</a>
</form>

</body>
</html>
