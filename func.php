<?php
include "db.php";

if (!isset($_GET['month'])) {
    $_GET['month'] = 0;
    $_GET['year'] = 0;
}

$query  = "SELECT first_name, last_name, total_count(first_name, $_GET[month] , $_GET[year]) as total 
from employees 
INNER JOIN
    orders 
    using(employee_id) 
    where total_count(first_name, $_GET[month], $_GET[year])
    group by employee_id";
$result = mysqli_query($connection, $query);
if(!$result){
        die("Query Failed (function)");
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
                <h2>All orders by date</h2>
                <a href="index.php" role="button" class="btn btn-outline-warning"><i class="fa fa-mail-reply" style="font-size:24px"></i></a>
            </div>
            <form action="#" method="GET">
                <div class="mb-3">
                    <label for="month" class="form-label"> month: (MM)</label>
                    <input type="number" class="form-control" name="month" required>
                </div>
                <div class="mb-3">
                <label for="year" class="form-label"> year: (YYYY)</label>
                    <input type="number" class="form-control" name="year" required>
                </div>
                <button type="submit" class="btn btn-outline-warning">Submit</button>
            </form>
            <table class="table table-striped table-hover">
                <thead>
                    <tr class="table-warning">
                        <th>first name</th>
                        <th>last name</th>
                        <th>Total income on <?php echo $_GET['month']."/".$_GET['year']?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 0;
                    while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                
                        <tr>
                            <td><?php echo $row['first_name']; ?></td>
                            <td><?php echo $row['last_name']; ?></td>
                            <td><?php echo $row['total']; ?></td>
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