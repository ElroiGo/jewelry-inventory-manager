<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/config.php';

$id = (int)($_GET['id'] ?? 0);

if ($id <= 0) {
    header('Location: items_list.php');
    exit;
}

$conn = getDB();

$stmt = $conn->prepare("
    SELECT id, name, category, material, price, stock_qty, image_path
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
    $name = trim($_POST['name'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $material = trim($_POST['material'] ?? '');
    $price = (float)($_POST['price'] ?? 0);
    $stock_qty = (int)($_POST['stock_qty'] ?? 0);
    $remove_image = isset($_POST['remove_image']) ? 1 : 0;

    $image_path = $item['image_path'];

    if ($name === '') {
        $error = 'Name is required.';
    } elseif ($price <= 0) {
        $error = 'Price must be greater than 0.';
    } elseif ($stock_qty < 0) {
        $error = 'Stock cannot be negative.';
    }

    if ($error === '') {
        $old_image_full_path = '';
        if (!empty($item['image_path'])) {
            $old_image_full_path = __DIR__ . '/' . $item['image_path'];
        }

        // Remove current image if checkbox was selected
        if ($remove_image) {
            if (!empty($item['image_path']) && file_exists($old_image_full_path)) {
                unlink($old_image_full_path);
            }
            $image_path = '';
        }

        // Upload new image if selected
        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
            $upload_dir = __DIR__ . '/uploads/';
            $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

            if (in_array($ext, ['jpg', 'jpeg', 'png'])) {
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }

                $filename = 'item_' . $id . '_' . time() . '.' . $ext;
                $newpath = $upload_dir . $filename;

                if (move_uploaded_file($_FILES['image']['tmp_name'], $newpath)) {
                    // If old image still exists and is different, delete it
                    if (!empty($item['image_path']) && file_exists($old_image_full_path)) {
                        unlink($old_image_full_path);
                    }

                    $image_path = 'uploads/' . $filename;
                } else {
                    $error = 'Image upload failed.';
                }
            } else {
                $error = 'Only JPG, JPEG, PNG files are allowed.';
            }
        }
    }

    if ($error === '') {
        $stmt = $conn->prepare("
            UPDATE items
            SET name = ?, category = ?, material = ?, price = ?, stock_qty = ?, image_path = ?
            WHERE id = ?
        ");

        $stmt->bind_param(
            "sssdisi",
            $name,
            $category,
            $material,
            $price,
            $stock_qty,
            $image_path,
            $id
        );

        if ($stmt->execute()) {
            header('Location: items_list.php?success=updated');
            exit;
        } else {
            $error = 'Update failed: ' . $stmt->error;
        }
    }
}

$currentImageExists = !empty($item['image_path']) && file_exists(__DIR__ . '/' . $item['image_path']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Item</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 650px;
            margin: 40px auto;
        }

        h1 {
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 6px;
        }

        input[type="text"],
        input[type="number"],
        input[type="file"] {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }

        .error {
            background: #ffe6e6;
            color: #b30000;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 15px;
        }

        .current-image-box {
            margin-bottom: 15px;
        }

        .current-image-box img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border: 1px solid #ccc;
            border-radius: 6px;
            display: block;
            margin-top: 8px;
        }

        .remove-wrap {
            margin-top: 10px;
        }

        .remove-wrap label {
            display: inline;
            font-weight: normal;
        }

        button {
            background: #007cba;
            color: white;
            border: none;
            padding: 10px 18px;
            border-radius: 6px;
            cursor: pointer;
        }

        button:hover {
            background: #005f91;
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

<h1>Edit Item #<?php echo (int)$item['id']; ?></h1>

<?php if ($error !== ''): ?>
    <div class="error"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<div class="current-image-box">
    <label>Current Image:</label>

    <?php if ($currentImageExists): ?>
        <img src="<?php echo htmlspecialchars($item['image_path']); ?>" alt="Current item image">
        <div class="remove-wrap">
            <input type="checkbox" id="remove_image" name="remove_image" form="editForm">
            <label for="remove_image">Remove current image</label>
        </div>
    <?php else: ?>
        <div class="no-image">No image</div>
    <?php endif; ?>
</div>

<form method="POST" enctype="multipart/form-data" id="editForm">
    <div class="form-group">
        <label>Name:</label>
        <input type="text" name="name" value="<?php echo htmlspecialchars($item['name']); ?>" required>
    </div>

    <div class="form-group">
        <label>Category:</label>
        <input type="text" name="category" value="<?php echo htmlspecialchars($item['category']); ?>">
    </div>

    <div class="form-group">
        <label>Material:</label>
        <input type="text" name="material" value="<?php echo htmlspecialchars($item['material']); ?>">
    </div>

    <div class="form-group">
        <label>Price:</label>
        <input type="number" step="0.01" name="price" value="<?php echo htmlspecialchars($item['price']); ?>" required>
    </div>

    <div class="form-group">
        <label>Stock:</label>
        <input type="number" name="stock_qty" value="<?php echo (int)$item['stock_qty']; ?>" required>
    </div>

    <div class="form-group">
        <label>Replace / Add Image:</label>
        <input type="file" name="image" accept=".jpg,.jpeg,.png">
    </div>

    <button type="submit">Update Item</button>
    <a href="items_list.php" class="cancel-link">Cancel</a>
</form>

</body>
</html>
