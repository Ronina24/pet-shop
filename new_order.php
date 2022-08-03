<?php
include "db.php";

$query  = "SELECT * FROM products";
$query_cos = "SELECT customer_id, concat(first_name,' ',last_name) as name_customer FROM customers";
$query_emp = "SELECT employee_id, concat(first_name,' ',last_name) as name_employee FROM employees
inner join  roles using(role_id) where role = 'seller'";
$query_delivery = "SELECT employee_id, concat(first_name,' ',last_name) as name_employee FROM employees
inner join  roles using(role_id) where role = 'enovy'";

$result_emp = mysqli_query($connection, $query_emp);
$result_cos = mysqli_query($connection, $query_cos);
$result_delivery = mysqli_query($connection, $query_delivery);
$result = mysqli_query($connection, $query);
if (!$result) {
    die("Query Failed (The employee who sold the most products)");
}


if (isset($_GET['submit']) && !empty($_GET['prod'])) {

    $coustemr = $_GET['coustemr'];
    $employee = $_GET['employee'];
    $delivery = $_GET['delivery'];
    $delivery_date=$_GET['dalivery_date'];

    $query_delivery = "INSERT INTO delivery (enovy_id,delivery_date,delivery_price) VALUES ('$delivery','$delivery_date','0')";
    $result_delivery= mysqli_query($connection, $query_delivery);
    if (!$result_delivery) {
        die("Query Failed (query_delivery)");
    }

    $query_order = "INSERT INTO orders(total_price, order_date, delivery_id, customer_id, employee_id,shipped) VALUES ('0',now(),(SELECT delivery_id FROM delivery order by delivery_id  DESC limit 1),$coustemr,$employee,'0')";
    $result_order_id = mysqli_query($connection, $query_order);
    if (!$result_order_id) {
        die("Query Failed (result_order_id)");
    }
    $prod = is_array($_GET['prod']) ? $_GET['prod'] : array();
    foreach ($prod as $value) {
        if($_GET[$value] == null) {$q ='0';} else{$q = $_GET[$value];}

        $query_pro = "INSERT INTO products_orders(order_id, product_id, quantity) VALUES((SELECT order_id FROM  orders order by order_id  DESC limit 1), $value,   $q)";
        $result_prod = mysqli_query($connection, $query_pro);
        if (!$result_prod) {
            die("Query Failed (result_order_id)");
        }
    }
    
    $query_update = "call set_t_order((SELECT order_id FROM orders order by order_id DESC limit 1))";
    $result_update = mysqli_query($connection, $query_update);

     header('Location: index.php');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <script src="js/scripts.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="./css/style.css">
    <title>New order</title>
</head>

<body>
    <main>
        <div class="container">
            <div class="titel">
                <h2>New order</h2>
                <a href="index.php" role="button" class="btn btn-outline-warning"><i class="fa fa-mail-reply" style="font-size:24px"></i></a>
            </div>

            <table class="table table-striped table-hover">
                <thead>
                    <tr class="table-warning">
                        <th>#</th>
                        <th>Product id</th>
                        <th>Amount</th>
                        <th>Product name</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 0;
                    while ($row = mysqli_fetch_assoc($result)) {
                    ?>

                        <form method="get" action="#">
                            <tr>
                                <td><input type="checkbox" id="prod" name="prod[]" value="<?php echo $row['product_id'] ?>"></td>
                                <td><?php echo $row['product_id']; ?></td>
                                <td><?php echo $row['amount']; ?></td>
                                <td><?php echo $row['product_name']; ?></td>
                                <td><?php echo $row['description']; ?></td>
                                <td><?php echo $row['price']; ?></td>
                                <td><input type="number" class="input_num" name="<?php echo $row['product_id'] ?>"></td>
                            </tr>
                        <?php
                        $i++;
                    }
                    mysqli_close($connection)
                        ?>
                </tbody>
            </table>
        </div>
        <div class="form_submit">
            
            <select class="custom-select" id="coustemr" name="coustemr">
            <option value=" ">coustemr Name</option>
                <?php

                while ($row = mysqli_fetch_assoc($result_cos)) {
                ?>
                    <option value="<?php echo $row['customer_id']; ?>"><?php echo $row['name_customer']; ?></option>
                <?php

                } ?>
            </select>
            <select class="custom-select" id="employee" name="employee">
            <option value=" ">employee Name</option>
                <?php

                while ($row = mysqli_fetch_assoc($result_emp)) {
                ?>
                    <option value="<?php echo $row['employee_id']; ?>"><?php echo $row['name_employee']; ?></option>
                <?php

                } ?>
            </select>
        </div>
        <div class="form_submit">
        
            <select class="custom-select" id="delivery" name="delivery">
            <option value=" ">Delivery Name</option>
                <?php

                while ($row = mysqli_fetch_assoc($result_delivery)) {
                ?>
                    <option value="<?php echo $row['employee_id']; ?>"><?php echo $row['name_employee']; ?></option>
                <?php

                } ?>
            </select>
            <input type="date" class="custom-select" id="start" name="dalivery_date">
        </div>
        <div>
            <button type="submit" name="submit" class="btn btn-outline-warning btn-lg btn-block ">Submit</button>
        </div>
        </form>
    </main>
    
</body>

</html>