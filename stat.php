<?php
include 'core/db.php';
include 'core/function.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="icon" type="image/x-icon" href="/images/favicon.ico">
    <link rel="stylesheet" href="css/styles.css">
    <script src="js/script.js"></script>
</head>
<body>
    <h1>Statistik Pengguna</h1>
    <p>
        <a class="btn warning" href="index.php">Kembali</a>
    </p>
    <p><small>Statistik Pengguna Mengikut Jantina.</small></p>
    <table class="table">
        <tr class="disable">
            <th>Jantina</th>
            <th>Bilangan</th>
        </tr>
        <?php
            $query = mysqli_query($conn, "SELECT `gander`, COUNT(DISTINCT(`id`)) AS `Total` FROM `users` GROUP BY `gander`");
            if (mysqli_num_rows($query) != 0) {
                while (($row = mysqli_fetch_assoc($query)) != false) {
                    echo '<tr><td>' . $row['gander'] . '</td><td>' . $row['Total'] . '</td></tr>';
                }
            } else {
                echo '<tr><td colspan="2">Tiada maklumat ditemui</td></tr>';
            }
        ?>
    </table>
    <p></p>
    <p><small>Statistik Pengguna Mengikut Kategori Umur.</small></p>
    <table class="table">
        <tr class="disable">
            <th>0-9</th>
            <th>10-19</th>
            <th>20-29</th>
            <th>30-39</th>
            <th>40-49</th>
            <th>50-59</th>
            <th>60-69</th>
            <th>70-79</th>
            <th>80-89</th>
            <th>90-99</th>
        </tr>
        <?php
            $query = mysqli_query($conn, "SELECT CASE
            WHEN `age` BETWEEN 0 AND 9 THEN '1'
            WHEN `age` BETWEEN 10 AND 19 THEN '2'
            WHEN `age` BETWEEN 20 AND 29 THEN '3'
            WHEN `age` BETWEEN 30 AND 39 THEN '4'
            WHEN `age` BETWEEN 40 AND 49 THEN '5'
            WHEN `age` BETWEEN 50 AND 59 THEN '6'
            WHEN `age` BETWEEN 60 AND 69 THEN '7'
            WHEN `age` BETWEEN 70 AND 79 THEN '8'
            WHEN `age` BETWEEN 80 AND 89 THEN '9'
            WHEN `age` BETWEEN 90 AND 99 THEN '10'
            END AS `range`,
            COUNT(`id`) as `ageT`
            FROM `users` GROUP BY `range` ORDER BY `range`");
            if (mysqli_num_rows($query) != 0) {
                $x = 1;
                while (($row = mysqli_fetch_assoc($query)) != false) {
                    while ($x <= 10) {
                        if ($x == $row['range']) {
                            echo '<td>' . $row['ageT'] . '</td>';
                            $x++;
                            break;
                        } else {
                            echo '<td></td>';
                            $x++;
                        }
                    }
                }
                if ($x <= 10) {
                    while ($x <= 10) {
                        echo '<td></td>';
                        $x++;
                    }
                }
            } else {
                echo '<tr><td colspan="10">Tiada maklumat ditemui</td></tr>';
            }
        ?>
    </table>
</body>
</html>