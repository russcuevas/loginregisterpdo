<?php
include 'connect.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}


// Set the number of items to display per page
$itemsPerPage = 10;

// Get the total number of items in the database
$totalItemsQuery = "SELECT COUNT(*) AS total_items FROM menu";
$totalItemsResult = $conn->query($totalItemsQuery);
$totalItems = $totalItemsResult->fetch(PDO::FETCH_ASSOC)['total_items'];

// Calculate the total number of pages
$totalPages = ceil($totalItems / $itemsPerPage);

// Get the current page number from the URL parameter, or set it to 1 if not present
$pageNumber = isset($_GET['page']) ? intval($_GET['page']) : 1;

// Make sure the page number is within the valid range
if ($pageNumber < 1) {
    $pageNumber = 1;
} else if ($pageNumber > $totalPages) {
    $pageNumber = $totalPages;
}

// Calculate the offset for the SQL query
$offset = max(($pageNumber - 1) * $itemsPerPage, 0);

// Construct the SQL query with the limit and offset clauses
$query = "SELECT * FROM menu LIMIT $itemsPerPage OFFSET $offset";
$result = $conn->query($query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User | Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <form action="" method="POST">
	<nav class="navbar navbar-expand-lg navbar-light bg-light">
		<a class="navbar-brand" href="#">User Dashboard</a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="navbarNav">
			<ul class="navbar-nav ml-auto">
				<li class="nav-item">
					<a class="nav-link" href="#" style="">Cart</a>
				</li>
                <li class="nav-item">
					<a class="nav-link" href="#" style="">Orders</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="logout.php" style="">Logout</a>
				</li>
			</ul>
		</div>
	</nav>

    <div class="container my-5">
	<h1 class="mb-5" style="text-align: center; text-decoration: underline;">AVAILABLE MENU</h1>

	<form action="#" method="GET">
		<div class="form-group">
			<input type="text" name="search" class="form-control" placeholder="Search for food...">
		</div>
		<button type="submit" class="btn btn-primary">Search</button>
	</form>

	<table class="table">
		<thead>
			<tr>
				<th>Food ID</th>
				<th>Food Name</th>
				<th>Price</th>
                <th>Image</th>
				<th>Actions</th>
			</tr>
		</thead>
		<tbody>
            <?php
                // Retrieve menu items from the database
                $query = "SELECT * FROM menu LIMIT $itemsPerPage OFFSET $offset";
                $result = $conn->query($query);

                // Loop through the menu items and display them in the table
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    $fid = $row['id'];
                    $fname = $row['name'];
                    $price = $row['price'];
                    $fimage = $row['image'];
            ?>
                <tr>
                    <td><?php echo $fid; ?></td>
                    <td><?php echo $fname; ?></td>
                    <td><?php echo $price; ?></td>
                    <td><?php echo $fimage; ?></td>
                    <td>
                        <a href="quick_view.php">View</a> / 
                        <a href="order.php">Order</a>
                    </td>
                </tr>
            <?php } ?>
		</tbody>
	</table>
</div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-fg6f5U6KP5U6H5WTkMfRz/Fa8G1"></script>
</body>
</html>
