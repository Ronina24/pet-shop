<?php
include "db.php";

if (!isset($_GET['week']))
    $_GET['week'] = -1;

$query  = "SELECT order_id, total_price, order_date, delivery_date, concat(c.first_name,' ',c.last_name) as 'customers full name',concat(e.first_name,' ',e.last_name) as 'employee full name'
from orders 
inner join delivery d using(delivery_id) 
inner join customers c using(customer_id) 
inner join employees e using(employee_id) WHERE order_date >= curdate() - INTERVAL DAYOFWEEK(curdate())+7* $_GET[week] DAY";
$result = mysqli_query($connection, $query);
if(!$result){
	die("Query Failed (orders by weeks)");
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
    <title>query 2</title>
</head>

<body>
    <main>
        <div class="container">
            <div class="titel">
                <h2>All orders by weeks</h2>
                <a href="index.php" role="button" class="btn btn-outline-warning"><i class="fa fa-mail-reply" style="font-size:24px"></i></a>
            </div>
            <form action="#" method="GET">
                <div class="mb-3">
                    <label for="week" class="form-label">number of week:</label>
                    <div class="form_submit">
                        <input type="number" class="form-control" name="week" require>
                        <button type="submit" class="btn btn-outline-warning bth-margin">Submit</button>
                    </div>
                </div>
            </form>
            <table class="table table-striped table-hover">
                <thead>
                    <tr class="table-warning">
                        <th>Order id</th>
                        <th>Total price</th>
                        <th>Order data</th>
                        <th>Delivery date</th>
                        <th>customers full name</th>
                        <th>employee full name</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 0;
                    while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                        <tr>
                            <td><?php echo $row['order_id'] ?></td>
                            <td><?php echo $row['total_price'] ?></td>
                            <td><?php echo $row['order_date'] ?></td>
                            <td><?php echo $row['delivery_date'] ?></td>
                            <td><?php echo $row['customers full name'] ?></td>
                            <td><?php echo $row['employee full name'] ?></td>
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