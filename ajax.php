<?php
include('./controllers/commons.php');

if (isset($_POST['type'])) {
    if ($_POST['type'] == "html-request") {
        $out = 404;
         if ($_POST['action'] == "getInsurancePriceAjax") {
            $country_id = $_POST['country_id'];
            $country_categories_id = db_get_data("SELECT * FROM countries WHERE id = '{$country_id}' LIMIT 1", "country_categories_id");

            $sql = "SELECT * FROM insurances WHERE country_categories_id = '{$country_categories_id}' ORDER BY insurance_price";
            $query = mysqli_query($connection, $sql);
            // $out = null;

            if(mysqli_num_rows($query)>0){
                $out ='<option value="" selected>Выберите сумму страхования</option>';
                while($row = mysqli_fetch_array($query)) {
                    //если 1 тогда доллар
                    //если 2 тогда евро
                    //Не элегантное решение нужно подумать как решить
                    $sumbol = $row['symbols'] == 1?"&#x24;":"&#8364;"; // символы долларов
                    $out .='<option value="'.$row['id'].'">'.$sumbol.' '.$row['insurance_price'].'</option>';
                }
            }else{
                $out = 404;
            }
         }

         if ($_POST['action'] == "getVisitTargetsAjax") {
            $visit_type = $_POST['visit_type'];
            $dates = db_get_array("SELECT * From visit_targets WHERE is_active = 1 AND multiply_type = ".$visit_type ,"id", "name");
            $out = getSelectOptionWithOthers($dates);
         }

          if ($_POST['action'] == "CalculateInsuranceAjax") {
                $country_id = $_POST['country_id'];
                $visit_type = $_POST['visit_type'];
                $visit_target = $_POST['visit_target'];
                $insurance_price = $_POST['insurance_price'];
                if (isset($_POST['insurance_price']) && isset($_POST['visit_type']) && isset($_POST['visit_target']) && isset($_POST['country_id'])){
                    $insurance_symbol = db_get_data("SELECT * FROM insurances WHERE id = '{$insurance_price}' ORDER BY insurance_price");
                    $sumbol = $insurance_symbol['symbols'] == 1?"&#x24;":"&#8364;"; // символы долларов
                    if($visit_type == 0){
                        $date_between = $_POST['date_between'];
                        $haystack = $date_between;
                        $needle   = '—';
                        $pos      = strripos($haystack, $needle);
                        if ($pos === false) {
                            if($date_between == ""){
                                $out = 404;
                                print_r($out);
                                exit;
                            }
                            $between_days = 1;
                        } else {
                            $start = substr($date_between, 0, 10);
                            $end = substr($date_between, 14, 23);

                            $between_days = daysBetweenDates($start, $end);
                            $out =  '<br>'.$date_between."<br> " .$start."<br> ".daysBetweenDates($start, $end);
                            // exit;x
                        }
                        $tarif = db_get_data("SELECT * FROM tarifs WHERE visit_target_id = $visit_target AND tarif_start_day <= $between_days  AND tarif_end_day >= $between_days AND insurance_id = $insurance_price AND is_active = 1");
                        $out = '<p class="m-0">Количество дней: <span id="days">'.$between_days.'</span></p><p class="m-0"  >Тариф за период: '.$sumbol .' <span id="tarif">'.$tarif['factor'].'</span></p><p class="m-0" >Сумма для оплаты: '.$sumbol .' <span id="pay_summ">'.$tarif['factor']*$between_days.'</span></p>';
                        $_SESSION['data'] = $out;

                    }else if($visit_type == 1){
                        $between_days = intval($_POST['date_between']);
                        // $out = $_POST;

                        $tarif = db_get_data("SELECT * FROM tarifs WHERE visit_target_id = $visit_target AND tarif_end_day = $between_days AND insurance_id = $insurance_price AND is_active = 1");
                        //Вывод суммы если
                        $out = '<p class="m-0">Количество дней: <span id="days">'.$between_days.'</span></p><p class="m-0">Тариф за период: '.$sumbol .'<span id="tarif"> '.$tarif['factor'].'</span></p><p class="m-0" >Сумма для оплаты: '.$sumbol .' <span id="pay_summ">'.$tarif['factor'].'</span></p>';
                        $_SESSION['data'] = $out;
                    }
                }else{
                    $out = 404;
                }
         }
         if ($_POST['action'] == "SendAjaxFormAjax") {
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $timestamp = isset($_SESSION['form_submitted_ts'])
                    ? $_SESSION['form_submitted_ts']
                    : null;
                if(is_null($timestamp) || (time() - $timestamp) > 1 * 60) {

                    global $connection;

                    $country_id = $_POST['country_id'];
                    $visit_type = $_POST['visit_type'];
                    $visit_target = $_POST['visit_target'];
                    $insurance_price = $_POST['insurance_price'];

                    $date_between = CleanStr($_POST['date_between']);
                    $days = $_POST['days'];
                    $tarif = $_POST['tarif'];
                    $pay_summ = $_POST['pay_summ'];
                    $fio = CleanStr($_POST['fio']);
                    $phone = CleanStr($_POST['phone']);

                    $sql = "INSERT INTO `requests`(`fio`, `phone`, `country_id`, `visit_type`, `visit_target`, `insurance_id`, `date_between`, `days`, `tarif`, `pay_summ`, `status`, `time_created`) VALUES ('$fio','$phone','$country_id','$visit_type','$visit_target','$insurance_price','$date_between','$days','$tarif','$pay_summ',1, NOW())";
                    $query = mysqli_query($connection, $sql);

                    // $out = $sql;
                    if($query){
                        $out = '<div class="alert alert-success" role="alert">Вы успешно отправили заявку</div>';
                        $_SESSION['form_submitted_ts'] = time();
                    }else{
                        $out = '<div class="alert alert-danger" role="alert">Ошибка при добавлении данных</div>';
                    }
                } else {
                    // Form submitted in last 10 minutes.  Perhaps send HTTP 403 header.
                    $out = '<div class="alert alert-danger" role="alert">Вы уже отправляли заявку</div>';
                }
            }


         }


        print_r($out);
    }
}else{
    header("Location: index.php");
}
