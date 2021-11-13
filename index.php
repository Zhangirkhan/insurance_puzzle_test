<?php
session_start();


?>
<!doctype html>
<html lang="ru">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <title>Система</title>
    <!-- jQuery + Bootstrap JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <!-- Flatpicker -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://npmcdn.com/flatpickr/dist/flatpickr.min.js"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/ru.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme/dist/select2-bootstrap4.min.css">

    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>

    <script src="https://www.kimep.kz/wp-content/themes/kimep/js/jquery.maskedinput.min.js"></script>
    <script src="js/custom.js"></script>


</head>

<body>

    <!-- Header -->
    <?php // include('./header.php'); ?>

    <!-- Login script -->
    <?php include('./controllers/login.php');
          include('./controllers/commons.php');
    ?>

    <div class="App">
        <div class="vertical-center">
            <div class="container">
                <div class="title text-center mb-5">
                    <h1 class="">Страхование для туристов</h1>
                    <small id="emailHelp" class="form-text text-muted">*Заполните поля и мы сделаем предварительный расчет стоимости страхования</small>
                </div>
                <form id="insuranse_form" method="POST">
                    <div class="row">
                    <div class="form-group col-6">
                        <label for="country_id">Выберите страну поездки</label> </br>
                        <select class="form-control select2-with-search country_id" id="country_id" name="country_id" onchange="getInsurancePrice()" >
                           <?php
                                $data = db_get_array("SELECT * From countries WHERE is_active = 1 ORDER BY name","id", "name");
                                echo getSelectOptionWithOthers($data);
                           ?>
                        </select>
                    </div>
                    <div class="form-group col-6">
                        <label for="visit_type">Выберите тип поездки</label></br>
                        <select class="form-control select2-without-search visit_type" id="visit_type" name="visit_type" onchange="getVisitTargets()">
                            <option selected value="">Выберите тип поездки</option>
                            <option value="0" >По определенным датам</option>
                            <option value="1" >Многократные поездки</option>
                        </select>
                    </div>
                    <div class="form-group col-6">
                        <label for="visit_target">Выберите цель поездки</label></br>
                        <select class="form-control select2-with-search visit_target" id="visit_target" name="visit_target" onchange="getInsurancePrice()" disabled >

                        </select>
                    </div>
                    <div class="form-group col-6">
                        <label for="insurance_price">Выберите сумму страхования</label></br>
                        <select class="form-control select2-without-search insurance_price" id="insurance_price" name="insurance_price" disabled >
                            <option selected >Выберите сумму страхования</option>
                        </select>
                    </div>
                    <div class="form-group col-6">
                        <label for="date_between">Дата</label></br>
                        <div id="date_choose">
                            <input type="text" class="form-control" id="date_between" placeholder="Выберите промежуток дат.." aria-describedby="date_between" name="date_between">
                        </div>
                    </div>
                     <div class="form-group col-6">
                        <label for="exampleInputEmail1">Данные страховки </label>
                        <button type="button" class="form-control btn btn-primary" id="calculate" >Расчитать</button>
                    </div>
                    <div class="form-group col-12 text-center"><span id = "summ" style="color: red;">Заполните все поля</span></div>
                    <div class="form-group col-4 form_callback" style="display:none;">
                            <label for="fio">ФИО</label></br>
                            <input type="text" class="form-control" id="fio" placeholder="Введите фио"  name="fio">
                        </div>
                        <div class="form-group col-4 form_callback" style="display:none;">
                            <label for="phone">Телефон</label></br>
                            <input type="phone" class="form-control" id="phone" placeholder="Введите номер телефона" name="phone">
                        </div>
                        <div class="form-group col-4 form_callback" style="display:none;">
                            <label for="exampleInputEmail1">&nbsp</label>
                            <button type="button" class="form-control btn btn-primary" id="submit">Оставить заявку</button>
                    </div>
                    <div class="form-group col-12" id="results"></div>

                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
<script>


</script>
</html>
