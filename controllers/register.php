<?php

    // Database connection
    include('config/db.php');

    // Swiftmailer lib
    require_once './lib/vendor/autoload.php';

    // Error & success messages
    global $success_msg, $email_exist, $f_NameErr, $l_NameErr, $_emailErr, $_mobileErr, $_passwordErr;
    global $fNameEmptyErr, $lNameEmptyErr, $emailEmptyErr, $mobileEmptyErr, $passwordEmptyErr, $email_verify_err, $email_verify_success;

    // Set empty form vars for validation mapping
    $_first_name = $_last_name = $_email = $_mobile_number = $_password = "";

    if(isset($_POST["submit"])) {
        $firstname     = $_POST["firstname"];
        $lastname      = $_POST["lastname"];
        $email         = $_POST["email"];
        $mobilenumber  = $_POST["mobilenumber"];
        $password      = $_POST["password"];

        // check if email already exist
        $email_check_query = mysqli_query($connection, "SELECT * FROM users WHERE email = '{$email}' ");
        $rowCount = mysqli_num_rows($email_check_query);


        // PHP validation
        // Verify if form values are not empty
        if(!empty($firstname) && !empty($lastname) && !empty($email) && !empty($mobilenumber) && !empty($password)){

            // check if user email already exist
            if($rowCount > 0) {
                $email_exist = '
                    <div class="alert alert-danger" role="alert">
                        User with email already exist!
                    </div>
                ';
            } else {
                // clean the form data before sending to database
                $_first_name = mysqli_real_escape_string($connection, $firstname);
                $_last_name = mysqli_real_escape_string($connection, $lastname);
                $_email = mysqli_real_escape_string($connection, $email);
                $_mobile_number = mysqli_real_escape_string($connection, $mobilenumber);
                $_password = mysqli_real_escape_string($connection, $password);

                // perform validation
                if(!preg_match("/^[a-zA-Z ]*$/", $_first_name)) {
                    $f_NameErr = '<div class="alert alert-danger">
                            Допускаются только буквы и пробелы.
                        </div>';
                }
                if(!preg_match("/^[a-zA-Z ]*$/", $_last_name)) {
                    $l_NameErr = '<div class="alert alert-danger">
                            Допускаются только буквы и пробелы.
                        </div>';
                }
                if(!filter_var($_email, FILTER_VALIDATE_EMAIL)) {
                    $_emailErr = '<div class="alert alert-danger">
                            Формат электронной почты недействителен.
                        </div>';
                }
                if(!preg_match("/^[0-9]{12}+$/", $_mobile_number)) {
                    $_mobileErr = '<div class="alert alert-danger">
                            Разрешены только 12-значные мобильные номера.
                        </div>';
                }
                if(!preg_match("/^(?=.*\d)(?=.*[@#\-_$%^&+=§!\?])(?=.*[a-z])(?=.*[A-Z])[0-9A-Za-z@#\-_$%^&+=§!\?]{6,20}$/", $_password)) {
                    $_passwordErr = '<div class="alert alert-danger">
                             Пароль должен содержать от 6 до 20 символов, содержать как минимум один специальный символ, нижний регистр, верхний регистр и цифру.
                        </div>';
                }

                // Store the data in db, if all the preg_match condition met
                if((preg_match("/^[a-zA-Z ]*$/", $_first_name)) && (preg_match("/^[a-zA-Z ]*$/", $_last_name)) &&
                 (filter_var($_email, FILTER_VALIDATE_EMAIL)) && (preg_match("/^[0-9]{10}+$/", $_mobile_number)) &&
                 (preg_match("/^(?=.*\d)(?=.*[@#\-_$%^&+=§!\?])(?=.*[a-z])(?=.*[A-Z])[0-9A-Za-z@#\-_$%^&+=§!\?]{8,20}$/", $_password))){

                    // Generate random activation token
                    $token = md5(rand().time());

                    // Password hash
                    $password_hash = password_hash($password, PASSWORD_BCRYPT);

                    // Query
                    $sql = "INSERT INTO users (firstname, lastname, email, mobilenumber, password, token, is_active,
                    date_time) VALUES ('{$firstname}', '{$lastname}', '{$email}', '{$mobilenumber}', '{$password_hash}',
                    '{$token}', '1', now())";

                    // Create mysql query
                    $sqlQuery = mysqli_query($connection, $sql);

                    if(!$sqlQuery){
                        die("MySQL query failed!" . mysqli_error($connection));
                    }

                }
            }
        } else {
            if(empty($firstname)){
                $fNameEmptyErr = '<div class="alert alert-danger">
                    Имя не может быть пустым
                </div>';
            }
            if(empty($lastname)){
                $lNameEmptyErr = '<div class="alert alert-danger">
                    Фамилия не может быть пустым
                </div>';
            }
            if(empty($email)){
                $emailEmptyErr = '<div class="alert alert-danger">
                    Email не может быть пустым
                </div>';
            }
            if(empty($mobilenumber)){
                $mobileEmptyErr = '<div class="alert alert-danger">
                    Номер телефона не может быть пустым
                </div>';
            }
            if(empty($password)){
                $passwordEmptyErr = '<div class="alert alert-danger">
                    Пароль не может быть пустым
                </div>';
            }
        }
    }
?>
