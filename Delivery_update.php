<?php
include "db.php";

$query  = "SELECT order_id, concat(c.first_name,' ',c.last_name) as 'coustmer name',
        e.employee_id ,concat(e.first_name,' ',e.last_name) as 'employee_name' 
    from customers c
	inner join orders o using(customer_id) 
    inner join employees e using(employee_id)
    WHERE shipped = 0";

if (isset($_GET['Order'])) {
    $query_update = "call update_delivery($_GET[Order], $_GET[employee])";
    $query_res = "SELECT order_id, concat(e.first_name,' ',e.last_name) as 'employee_name' 
                from customers c inner join orders o using(customer_id) 
    inner join employees e using(employee_id)
    WHERE order_id = $_GET[Order]";
    $result = mysqli_query($connection, $query_res);
    mysqli_query($connection, $query_update);
    mysqli_query($connection, $query_res);
    $row_res = mysqli_fetch_assoc($result);
}

$result = mysqli_query($connection, $query);
if (!$result) {
    die("Query Failed (Delivery update)");
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
    <title>Set delivery</title>
</head>

<body>
    <main>
        <div class="container">
            <div class="titel">
                <h2>Delivery update</h2>
                <a href="index.php" role="button" class="btn btn-outline-warning"><i class="fa fa-mail-reply" style="font-size:24px"></i></a>
            </div>
            <form action="#" method="GET">
                <div class="mb-3">
                    <label for="Order" class="form-label"> Order ID:</label>
                    <input type="number" class="form-control" name="Order" required>
                </div>
                <div class="mb-3">
                    <label for="employee" class="form-label"> Employee ID:</label>
                    <input type="number" class="form-control" name="employee" required>
                </div>
                <button type="submit" class="btn btn-outline-warning">Submit</button>
            </form>
            <?php  if (isset($_GET['Order'])) { 
                echo "<h4>The shipment of order $row_res[order_id] created by $row_res[employee_name] has been successfully updated</h4>";
            }?>
            <table class="table table-striped table-hover">
                <thead>
                    <tr class="table-warning">
                        <th>Order id</th>
                        <th>Coustmer name</th>
                        <th>Employee id</th>
                        <th>Employee name</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 0;
                   
                        while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                            <tr>
                                <td><?php echo $row['order_id']; ?></td>
                                <td><?php echo $row['coustmer name']; ?></td>
                                <td><?php echo $row['employee_id']; ?></td>
                                <td><?php echo $row['employee_name']; ?></td>
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