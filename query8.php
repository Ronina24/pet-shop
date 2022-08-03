<?php
include "db.php";

if (!isset($_GET['month']))
    $_GET['month'] = -1;

$query  = "SELECT sum(total_price) from orders where month(order_date) >= month(now())- $_GET[month]";
$result = mysqli_query($connection, $query);
if(!$result){
	die("Query Failed (Total income)");
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
    <title>query 8</title>
</head>

<body>
    <main>
        <div class="container">
            <div class="titel">
                <h2>Total income in X month</h2>
                <a href="index.php" role="button" class="btn btn-outline-warning"><i class="fa fa-mail-reply" style="font-size:24px"></i></a>
            </div>
            <form action="#" method="GET">
                <div class="mb-3">
                    <label for="month" class="form-label">number of month:</label>
                    <div class="form_submit">
                        <input type="number" class="form-control" name="month" >
                        <button type="submit" class="btn btn-outline-warning bth-margin">Submit</button>
                    </div>
                </div>
            </form>
            <table class="table table-striped table-hover">
                <thead>
                    <tr class="table-warning">
                        <th>Total income in <?php echo $_GET['month']?> month:</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 0;
                    while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                        <tr>
                            <td><?php echo $row['sum(total_price)']; ?></td>
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