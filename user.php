<?php
include 'core/db.php';
include 'core/function.php';

$uploadDir = 'images/files/'; 
$allowTypes = array('jpg', 'png', 'jpeg', 'gif', 'bmp'); 
$mode_allowed = array('edit', 'add', 'delete');

if (isset($_GET['mode']) === true && in_array($_GET['mode'], $mode_allowed) === true) {
	if ($_GET['mode'] === 'edit') {
		if (isset($_GET['id']) === true && empty($_GET['id']) === false ) {
			$id = $_GET['id'];
            $query = mysqli_query($conn, "SELECT COUNT(`id`) FROM `users` WHERE `id` = '$id'");
			if (mysqli_result($query, 0) == 1) {
				$row = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `users` WHERE `id` = '$id'"));
			} else {
				$errors[] = 'Harap maaf, pengguna tidak wujud.';
			}
		} else {
			$errors[] = 'Harap maaf, pengguna tidak wujud.';
		}
	} else if ($_GET['mode'] === 'delete') {
		if (isset($_GET['id']) === true && empty($_GET['id']) === false ) {
			$id = $_GET['id'];
            $query = mysqli_query($conn, "SELECT COUNT(`id`) FROM `users` WHERE `id` = '$id'");
			if (mysqli_result($query, 0) == 1) {
				$delete = mysqli_query($conn, "DELETE FROM `users` WHERE `id` = '$id'");
			} else {
				$errors[] = 'Harap maaf, pengguna tidak wujud.';
			}
			
			if($delete) {
				echo '<META HTTP-EQUIV="refresh" content="0;URL=index.php">'; 
				exit();
			}
		} else {
			$errors[] = 'Harap maaf, pengguna tidak wujud.';
		}
	}
} else {
    echo '<META HTTP-EQUIV="refresh" content="0;URL=index.php">'; 
    exit();
}

if (empty($_POST) === false) {
	//$required_fields = array('name', 'noic', 'age', 'gander', 'avatar');
	$required_fields = array('name', 'noic', 'avatar');
	foreach ($_POST as $key=>$value) {
		if (empty($value) && in_array($key,$required_fields) === true) {
			$errors[] = 'Ruang \'' . $key . '\' yang bertanda bintang (*) adalah wajib diisi.';
			//break 1;
		}
	}
	
	if (empty($errors) === true) {
        $query = mysqli_query($conn, "SELECT COUNT(`id`) FROM `users` WHERE `noic` = '".$_POST['noic']."'");
		if ($_GET['mode'] === 'add') {
			if (mysqli_result($query, 0) == 1) {
				$errors[] = 'No KP \'' . $_POST['noic'] . '\' sudah didaftarkan.';
			}
			if (is_numeric($_POST['noic']) !== true) {
				$errors[] = 'No KP hanya mengandungi nombor sahaja.';
			}
            if (preg_match("/\\s/", $_POST['noic']) == true) {
                $errors[] = 'No KP tidak boleh mempunyai ruang.';
            }
            if (strlen($_POST['noic']) <> 12) {
                $errors[] = 'No KP anda tidak sah.';
            }
			/* if (is_numeric($_POST['age']) !== true) {
				$errors[] = 'Umur hanya mengandungi nombor sahaja.';
			} */
		} else if ($_GET['mode'] === 'edit') {
			if (mysqli_result($query, 0) == 1 && $row['noic'] !== $_POST['noic']) {
				$errors[] = 'No KP \'' . $_POST['noic'] . '\' sudah didaftarkan.';
			}
			if (is_numeric($_POST['noic']) !== true) {
				$errors[] = 'No KP hanya mengandungi nombor sahaja.';
			}
            if (preg_match("/\\s/", $_POST['noic']) == true) {
                $errors[] = 'No KP tidak boleh mempunyai ruang.';
            }
            if (strlen($_POST['noic']) <> 12) {
                $errors[] = 'No KP anda tidak sah.';
            }
			/* if (is_numeric($_POST['age']) !== true) {
				$errors[] = 'Umur hanya mengandungi nombor sahaja.';
			} */
		}

        $img = str_replace(" ", "_", $_FILES['file']['name']);
        $tmp = $_FILES['file']['tmp_name'];

        $uploadStatus = 1;
        $uploadedFile = ''; 
        if(!empty($_FILES['file']['name'])){ 
            $fileName = basename($img); 
            $targetFilePath = $uploadDir . $img; 
            $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION)); 

            if(in_array($fileType, $allowTypes)){
                if($_FILES['file']['size'] > 3000000){
                    $uploadStatus = 0;
                    $errors[] = 'Maaf, saiz fail dibenarkan tidak lebih 3Mb.'; 
                } else {
                    if(move_uploaded_file($tmp, $targetFilePath)){ 
                        $uploadedFile = $fileName;
                    } else { 
                        $uploadStatus = 0;
                        $errors[] = 'Maaf, terdapat ralat semala muat-naik.'; 
                    } 
                }
            } else { 
                $uploadStatus = 0;
                $errors[] = 'Maaf, hanya fail '.implode('/', $allowTypes).' dibenarkan muat-naik.'; 
            }
        } elseif (empty($_FILES['file']['name']) && $_GET['mode'] === 'edit') {
            $uploadedFile = $row['avatar'];
        }
	}
}
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
    <h1>Daftar Pengguna</h1>
    <?php	
		if (empty($_POST) === false && empty($errors) === true) {
            $currYear = date("Y");
            if (substr($_POST['noic'],0, 2) > substr(date("Y"),3,2)) {
                $age = date("Y") - (1900 + substr($_POST['noic'],0 , 2));
            } else {
                $age = date("Y") - (2000 + substr($_POST['noic'],0, 2));
            }
            if (($_POST['noic'] % 2) == 0) {
                $gander = "Perempuan";
            } else {
                $gander = "Lelaki";
            }
            $update_data = array(
                'name' => $_POST['name'],
                'noic' => $_POST['noic'],
                'age' => $age,
                'gander' => $gander,
                'avatar' => $uploadedFile
            );
			if ($_GET['mode'] === 'edit') {
				foreach ($update_data as $field=>$data) {
                    $update[] = '`' . $field . '` = \'' . $data . '\'';
                }
                mysqli_query( $conn, "UPDATE `users` SET " . implode(', ',$update) . " WHERE `id` = '" .$id . "'");
			} else if ($_GET['mode'] === 'add') {
                $fields = '`' . implode('`, `', array_keys($update_data)) . '`';
                $data = '\'' . implode('\', \'', $update_data) . '\'';
                
                mysqli_query( $conn, "INSERT INTO `users` ($fields) VALUES ($data)");
			}
			
			echo '<META HTTP-EQUIV="refresh" content="0;URL=index.php">';
			exit();
		} else if (empty($errors) === false){
			echo '<ul><li>' . implode('</li><li>', $errors) . '</li></ul>';
		}
	?>
    <form action="" method="post" enctype="multipart/form-data">
        <table class="table noborder">
            <tr>
                <td>Nama</td>
                <td><input type="text" id="name" name="name" value="<?php if (empty($errors) === true && $_GET['mode'] === 'edit'){ echo $row['name']; } else if (empty($errors) === false && empty($_POST) === false) { echo $_POST['name']; } ?>"></td>
                <td rowspan="2">
                    <p>Avatar</p>
                    <p><img src="<?php if (empty($errors) === true && $_GET['mode'] === 'edit'){ if (!empty($row["avatar"])) { echo "images/files/" . $row["avatar"]; } elseif ($row["gander"] == "Lelaki") { echo "images/male.png"; } elseif ($row["gander"] == "Perempuan") { echo "images/female.png"; } else { echo "images/no-photo.png"; } } else if ($_GET['mode'] === 'add') { echo "images/no-photo.png"; } ?>" alt="avatar" height="100" width="100"></p>
                    <input type="file" id="file" name="file">
                </td>
            </tr>
            <tr>
                <td>No. KP</td>
                <td><input type="text" id="noic" name="noic" value="<?php if (empty($errors) === true && $_GET['mode'] === 'edit'){ echo $row['noic']; } else if (empty($errors) === false && empty($_POST) === false) { echo $_POST['noic']; } ?>"></td>
            </tr>
            <!-- <tr>
                <td>Umur</td>
                <td><input type="number" id="age" name="age" value="<?php if (empty($errors) === true && $_GET['mode'] === 'edit'){ echo $row['age']; } else if (empty($errors) === false && empty($_POST) === false) { echo $_POST['age']; } ?>"></td>
            </tr>
            <tr>
                <td>Jantina</td>
                <td>
                    <select id="gander" name="gander">
                        <option value=''>Sila pilih...</option>
                        <option value='Lelaki' <?php if (empty($errors) === true && $_GET['mode'] === 'edit'){ if ($row['gander'] == 'Lelaki') { echo 'selected="selected"'; } } else if (empty($errors) === false && empty($_POST) === false) { if ($_POST['gander'] == 'Lelaki') { echo 'selected="selected"'; } } ?>>Lelaki</option>
                        <option value='Perempuan' <?php if (empty($errors) === true && $_GET['mode'] === 'edit'){ if ($row['gander'] == 'Perempuan') { echo 'selected="selected"'; } } else if (empty($errors) === false && empty($_POST) === false) { if ($_POST['gander'] == 'Perempuan') { echo 'selected="selected"'; } } ?>>Perempuan</option>
                    </select>
                </td>
            </tr> -->
            <tr>
                <td colspan="3">
                    <a class="btn warning" href="index.php">Kembali</a>
                    <input class="btn grey" type="reset">
                    <input class="btn success" type="submit" value="Submit">
                </td>
            </tr>
        </table>
    </form>
</body>
</html>