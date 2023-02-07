<?php
include 'core/db.php';
include 'core/function.php';

$listrow = 1;
$sql = "SELECT * FROM `users`";
$result = mysqli_query($conn, $sql);
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
    <h1>Senarai Pengguna</h1>
    <p>
        <a class="btn primary" href="user.php?mode=add">Daftar Pengguna</a>
        <a class="btn success" href="stat.php">Statistik</a>
    </p>
    <table class="table">
        <thead>
            <tr class="disable">
                <th>Bil</th>
                <th>Avatar</th>
                <th>Nama</th>
                <th>No. KP</th>
                <th>Umur</th>
                <th>Jantina</th>
                <th>Tindakan</th>
            </tr>
        </thead>
        <tbody>
            <?php
                if (mysqli_num_rows($result) > 0) {
                    while($row = mysqli_fetch_assoc($result)) {
                        echo "<tr><td>" . $listrow . "</td><td>";
                        if (!empty($row["avatar"])) {
                            echo "<img src='" . $row["avatar"]. "' alt='avatar_" . $row["avatar"]. "' height='50' width='50'>";
                        } elseif ($row["gander"] == "Lelaki") {
                            echo "<img src='images/male.png' alt='avatar_male' height='50' width='50'>";
                        } elseif ($row["gander"] == "Perempuan") {
                            echo "<img src='images/female.png' alt='avatar_female' height='50' width='50'>";
                        }
                        echo "</td><td>" . $row["name"]. "</td><td>" . $row["noic"]. "</td><td>" . $row["age"]. "</td><td>" . $row["gander"]. "</td><td><a class='btn warning' href='user.php?mode=edit&id=" . $row["id"]. "'>Sunting</a><a class='btn danger' href='user.php?mode=delete&id=" . $row["id"]. "'>Padam</a></td></tr>";
                        $listrow += 1;
                    }
                } else {
                    echo "<tr><td colspan='7'>Tiada Maklumat</td></tr>";
                }
            ?>
        </tbody>
    </table>
</body>
</html>