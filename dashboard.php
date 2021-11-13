<?php include('config/db.php');

if(!$_SESSION['lastname']){
    header("Location: index.php");
}

?>
<!doctype html>
<html lang="ru">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
    <title>Система</title>
    <!-- jQuery + Bootstrap JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
</head>

<body>

    <div class="container mt-5">
        <div class="d-flex justify-content-center">
            <div class="card" style="width: 25rem">
                <div class="card-body">
                    <h5 class="card-title text-center mb-4">Пользователь</h5>
                    <h6 class="card-subtitle mb-2 text-muted"><?php echo $_SESSION['firstname']; ?>
                        <?php echo $_SESSION['lastname']; ?></h6>
                    <p class="card-text">Email адрес: <?php echo $_SESSION['email']; ?></p>
                    <p class="card-text">Номер телефона: <?php echo $_SESSION['mobilenumber']; ?></p>

                    <a class="btn btn-danger btn-block" href="logout.php">Выход</a>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
