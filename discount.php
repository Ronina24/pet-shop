<?php
include "db.php";

if (!isset($_GET['order'])) {
    $_GET['order'] = 0;
    $_GET['discount'] = 0;
    $query  = "SELECT order_id, total_price, order_date from orders";
}else{
    $query  = "SELECT order_id, total_price, order_date from orders where order_id =  $_GET[order]";
}
$query_procedure = "call discount($_GET[order], $_GET[discount])";
mysqli_query($connection, $query_procedure);
$result = mysqli_query($connection, $query);
if(!$result){
        die("Query Failed (PROCEDURE best_seller)");
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
    <title>Discount</title>
</head>

<body>
    <main>
        <div class="container">
            <div class="titel">
                <h2>Discount</h2>
                <a href="index.php" role="button" class="btn btn-outline-warning"><i class="fa fa-mail-reply" style="font-size:24px"></i></a>
            </div>
            <form action="#" method="GET">
                <div class="mb-3">
                    <label for="order" class="form-label"> Order Number:</label>
                    <input type="number" class="form-control" name="order" required>
                </div>
                <div class="mb-3">
                <label for="discount" class="form-label">Percentage discount</label>
                    <input type="number" class="form-control" name="discount" required>
                </div>
                <button type="submit" class="btn btn-outline-warning">Submit</button>
            </form>
            <table class="table table-striped table-hover">
                <thead>
                    <tr class="table-warning">
                        <th>order id</th>
                        <th>total price</th>
                        <th>order date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 0;
                    while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                
                        <tr>
                            <td><?php echo $row['order_id']; ?></td>
                            <td><?php echo $row['total_price']; ?></td>
                            <td><?php echo $row['order_date']; ?></td>
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