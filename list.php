<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/config.php';

$conn = getDB();

$search = trim($_GET['search'] ?? '');

if ($search !== '') {
    $sql = "SELECT id, image_path, name, category, material, price, stock_qty
            FROM items
            WHERE name LIKE ? OR category LIKE ? OR material LIKE ?
            ORDER BY id ASC";
    $stmt = $conn->prepare($sql);
    $searchTerm = "%{$search}%";
    $stmt->bind_param("sss", $searchTerm, $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $sql = "SELECT id, image_path, name, category, material, price, stock_qty
            FROM items
            ORDER BY id ASC";
    $result = $conn->query($sql);
}
?>
<!DOCTYPE html>
<html lang="he">
<head>
    <meta charset="UTF-8">
    <title>Jewelry Inventory Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        h1 {
            margin-bottom: 20px;
        }

        .top-links {
            margin-bottom: 15px;
        }

        .top-links a {
            margin-right: 10px;
        }

        .search-box {
            margin: 15px 0;
        }

        .search-box input[type="text"] {
            padding: 8px;
            width: 280px;
        }

        .search-box button {
            padding: 8px 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
            vertical-align: middle;
        }

        th {
            background: #f2f2f2;
        }

        .thumb {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 6px;
            border: 1px solid #ccc;
            display: block;
        }

        .no-image {
            color: #888;
            font-style: italic;
        }

        .low-stock {
            color: red;
            font-weight: bold;
        }

        .success {
            color: green;
            margin-bottom: 10px;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <h1>Jewelry Inventory Management</h1>

    <div class="top-links">
        <strong>Welcome, admin!</strong>
        <a href="items_add.php">+ Add New Item</a>
        <a href="logout.php">Logout</a>
    </div>

    <?php if (isset($_GET['success']) && $_GET['success'] === 'added'): ?>
        <div class="success">Item added successfully.</div>
    <?php endif; ?>

    <?php if (isset($_GET['success']) && $_GET['success'] === 'updated'): ?>
        <div class="success">Item updated successfully.</div>
    <?php endif; ?>

    <?php if (isset($_GET['success']) && $_GET['success'] === 'deleted'): ?>
        <div class="success">Item deleted successfully.</div>
    <?php endif; ?>

    <form method="GET" class="search-box">
        <input type="text" name="search" placeholder="Search name, category, material..." value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit">Search</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Image</th>
                <th>Name</th>
                <th>Category</th>
                <th>Material</th>
                <th>Price ($)</th>
                <th>Stock</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo (int)$row['id']; ?></td>

                        <td>
                            <?php
                            $hasImage = !empty($row['image_path']) && file_exists(__DIR__ . '/' . $row['image_path']);
                            ?>
                            <?php if ($hasImage): ?>
                                <img
                                    src="<?php echo htmlspecialchars($row['image_path']); ?>"
                                    alt="Item Image"
                                    class="thumb"
                                >
                            <?php else: ?>
                                <span class="no-image">No image</span>
                            <?php endif; ?>
                        </td>

                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['category']); ?></td>
                        <td><?php echo htmlspecialchars($row['material']); ?></td>
                        <td><?php echo number_format((float)$row['price'], 2); ?></td>

                        <td class="<?php echo ((int)$row['stock_qty'] <= 3) ? 'low-stock' : ''; ?>">
                            <?php echo (int)$row['stock_qty']; ?>
                        </td>

                        <td>
                            <a href="items_edit.php?id=<?php echo (int)$row['id']; ?>">Edit</a> |
                            <a href="items_delete.php?id=<?php echo (int)$row['id']; ?>" onclick="return confirm('Delete this item?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8">No items found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</body>
</html>
