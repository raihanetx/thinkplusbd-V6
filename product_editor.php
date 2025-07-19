<?php
session_start();

if (!isset($_SESSION['admin_logged_in_thinkplusbd']) || $_SESSION['admin_logged_in_thinkplusbd'] !== true) {
    header("Location: admin_login.php");
    exit();
}

function get_products() {
    $products_file_path = __DIR__ . '/products.json';
    if (!file_exists($products_file_path)) {
        return [];
    }
    $json_data = file_get_contents($products_file_path);
    return json_decode($json_data, true);
}

$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$products = get_products();
$product_to_edit = null;

if ($product_id > 0) {
    foreach ($products as $product) {
        if ($product['id'] === $product_id) {
            $product_to_edit = $product;
            break;
        }
    }
}

if (!$product_to_edit) {
    die("Product not found.");
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product - Admin Panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="admin_dashboard.css">
</head>
<body>
    <div class="admin-wrapper">
        <aside class="admin-sidebar" id="adminSidebar">
            <div class="logo-admin">
                <img src="https://i.postimg.cc/4NtztqPt/IMG-20250603-130207-removebg-preview-1.png" alt="THINK PLUS BD Logo">
            </div>
            <nav class="admin-nav">
                <ul>
                    <li><a href="admin_dashboard.php"><i class="fas fa-chart-pie"></i> <span>Dashboard</span></a></li>
                    <li><a href="admin_dashboard.php?page=categories"><i class="fas fa-tags"></i> <span>Manage Categories</span></a></li>
                    <li><a href="edit_products.php" class="active"><i class="fas fa-edit"></i> <span>Edit Products</span></a></li>
                    <li><a href="product_code_generator.html" target="_blank"><i class="fas fa-plus-circle"></i> <span>Add Product Helper</span></a></li>
                    <li><a href="admin_dashboard.php?logout=1"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a></li>
                </ul>
            </nav>
        </aside>
        <main class="admin-main-content" id="adminMainContent">
            <header class="admin-topbar">
                <div style="display:flex; align-items:center;">
                    <i class="fas fa-bars sidebar-toggle" id="sidebarToggle"></i>
                    <h1>Edit Product</h1>
                </div>
                <a href="admin_dashboard.php?logout=1" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </header>
            <div class="admin-page-content">
                <div class="content-card">
                    <h2 class="card-title">Editing "<?php echo htmlspecialchars($product_to_edit['name']); ?>"</h2>
                    <form action="update_product.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="product_id" value="<?php echo $product_to_edit['id']; ?>">

                        <div class="form-group" style="margin-bottom: 1rem;">
                            <label for="name">Product Title</label>
                            <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($product_to_edit['name']); ?>" class="form-control" style="width: 100%; padding: 0.5rem; border-radius: var(--border-radius); border: 1px solid var(--border-color);">
                        </div>

                        <div class="form-group" style="margin-bottom: 1rem;">
                            <label for="description">Short Description</label>
                            <textarea name="description" id="description" rows="3" class="form-control" style="width: 100%; padding: 0.5rem; border-radius: var(--border-radius); border: 1px solid var(--border-color);"><?php echo htmlspecialchars($product_to_edit['description']); ?></textarea>
                        </div>

                        <div class="form-group" style="margin-bottom: 1rem;">
                            <label for="longDescription">Long Description</label>
                            <textarea name="longDescription" id="longDescription" rows="10" class="form-control" style="width: 100%; padding: 0.5rem; border-radius: var(--border-radius); border: 1px solid var(--border-color);"><?php echo htmlspecialchars($product_to_edit['longDescription']); ?></textarea>
                        </div>

                        <div class="form-group" style="margin-bottom: 1rem;">
                            <label for="price">Price</label>
                            <input type="number" step="0.01" name="price" id="price" value="<?php echo htmlspecialchars($product_to_edit['price']); ?>" class="form-control" style="width: 100%; padding: 0.5rem; border-radius: var(--border-radius); border: 1px solid var(--border-color);">
                        </div>

                        <div class="form-group" style="margin-bottom: 1rem;">
                            <label>Current Image</label>
                            <div>
                                <img src="<?php echo htmlspecialchars($product_to_edit['image']); ?>" alt="Current Image" style="max-width: 200px; max-height: 200px;">
                            </div>
                        </div>

                        <div class="form-group" style="margin-bottom: 1rem;">
                            <label for="image">Upload New Image (optional)</label>
                            <input type="file" name="image" id="image" class="form-control-file">
                        </div>

                        <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1rem; border: none; background-color: var(--primary-color); color: white; border-radius: var(--border-radius); cursor: pointer;">Update Product</button>
                    </form>
                </div>
            </div>
        </main>
    </div>
    <script src="admin_dashboard.js"></script>
</body>
</html>
