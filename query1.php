<?php
include "db.php";

$query  = "SELECT product_name,amount FROM products";
$result = mysqli_query($connection, $query);
if (!$result) {
	die("Query Failed (Display Inventory)");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="./css/style.css">
	<title>query 1</title>
</head>
<body>
	<main>
		<div class="container">
			<div class="titel">
				<h2>Inventory</h2>
				<a href="index.php" role="button" class="btn btn-outline-warning"><i class="fa fa-mail-reply" style="font-size:24px"></i></a>
			</div>
			<table class="table table-striped table-hover">
				<thead>
					<tr class="table-warning">
						<th>Product name</th>
						<th>Amount</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 0;
					while ($row = mysqli_fetch_assoc($result)) {
					?>
						<tr>
							<td><?php echo $row['product_name']; ?></td>
							<td><?php echo $amount = $row['amount'] ?></td>
						</tr>
					<?php
						$i++;
					}
					mysqli_close($connection)
					?>
				</tbody>
			</table>
		</div>
	</main>

</body>

</html>