
function onlineFlatpickr() {
    //Инициализация календаря
    const today = new Date();
    const mindate = (today.getDate() + 1) + '.' + (today.getMonth() + 1) + '.' + (today.getFullYear());
    const maxdate = (today.getDate()) + '.' + (today.getMonth() + 1) + '.' + (today.getFullYear() + 1);
    $("#date_between").flatpickr({
        mode: "range",
        altInput: true,
        altFormat: "d.m.Y",
        dateFormat: "d.m.Y",
        allowInput: true,
        minDate: mindate,
        maxDate: maxdate,
        locale: "ru",
        onClose: function (selectedDates, dateStr, instance) {
            // calcDate();
        }
    });


}

function onlineSelect2() {
    //Инициализация select2
    $('.select2-with-search').select2({
        minimumResultsForSearch: '',
        theme: 'bootstrap4',
        width: '100%',
    });
    $('.select2-without-search').select2({
        minimumResultsForSearch: Infinity,
        theme: 'bootstrap4',
        width: '100%',
    });
}

// После готовности сайта запускаем наши модули
$(document).ready(function () {

    $("#phone").mask("+79999999999");

    onlineSelect2();
    onlineFlatpickr();

    $('#calculate').click(function () {
        var validationResponse = $('#insuranse_form').valid();

        if (validationResponse) {
            // when true, your logic
            // alert("send message");
            // console.log("Ok send");
            CalculateInsurance();
            showOrderForm();

        } else {
            // when false, your logic
            return false;
        }
    });


    $('#submit').click(function () {
        var validationResponse = $('#insuranse_form').valid();

        if (validationResponse) {

            sendAjaxForm();
        } else {
            // when false, your logic
            return false;
        }
    });

    $("#insuranse_form").validate({
        rules: {
            country_id: "required",
            visit_type: "required",
            visit_target: "required",
            insurance_price: "required",
            date_between: "required",
            fio: "required",
            phone: "required",

        },
        messages: {
            country_id: "Пожалуйста выберите страну поездки",
            visit_type: "Пожалуйста выберите тип поездки ",
            visit_target: "Пожалуйста выберите цель поездки",
            insurance_price: "Пожалуйста выберите сумму страхования",
            date_between: "Пожалуйста выберите дату",
            fio: "Пожалуйста введите ФИО",
            phone: "Пожалуйста введите номер телефона",
        },


    });
});


function getInsurancePrice() {

    jQuery.post(
        "ajax.php", {
        type: "html-request",
        country_id: $("#country_id").val(),
        // visit_target: $("#visit_target").val(),
        action: "getInsurancePriceAjax",
    },
        onGetInsurancePrice
    );
}
function onGetInsurancePrice(data) {

    if (data == 404) {

        $("#insurance_price").prop("disabled", true);
    } else {
        $("#insurance_price").empty();
        $('#insurance_price').append(data);
        $("#insurance_price").prop("disabled", false);
    }
}


function getVisitTargets() {

    jQuery.post(
        "ajax.php", {
        type: "html-request",
        visit_type: $("#visit_type").val(),
        action: "getVisitTargetsAjax",
    },
        onGetVisitTargets
    );

    var visit_type = $("#visit_type").val();
    $("#date_choose").empty();
    var data;
    if (visit_type == 0) {
        data = `<input type="email" class="form-control" id="date_between" placeholder="Выберите промежуток дат.." aria-describedby="date_between" name="date_between"><small id="date_between" class="form-text text-muted"></small>`;
    } else if (visit_type == 1) {
        data = `<select class="form-control select2-without-search" id="date_between" name="date_between" onchange="calcDate()">
                            <option value="" selected >Выберите дату страхования</option>
                            <option value="180">1 год (180 дней)</option>
                            <option value="90">1 год (90 дней)</option>
                            <option value="60">1 год (60 дней)</option>
                            <option value="45">6 месяцев (45 дней)</option>
                            <option value="30">3 месяца (30 дней)</option>
                            <option value="15">1 месяц (15 дней)</option>
                        </select>`;
    }
    $('#date_choose').append(data);

    if (visit_type == 0) {
        onlineFlatpickr();
    } else if (visit_type == 1) {
        onlineSelect2();
    }

}

function onGetVisitTargets(data) {
    if (data == 404) {

        $("#visit_target").prop("disabled", true);
    } else {
        $("#visit_target").empty();
        $('#visit_target').append(data);
        onlineSelect2();
        $("#visit_target").prop("disabled", false);

    }

}

function CalculateInsurance() {
    jQuery.post(
        "ajax.php", {
        type: "html-request",
        country_id: $("#country_id").val(),
        visit_type: $("#visit_type").val(),
        visit_target: $("#visit_target").val(),
        insurance_price: $("#insurance_price").val(),
        date_between: $("#date_between").val(),

        action: "CalculateInsuranceAjax",
    },
        onCalculateInsurance
    );
}

function onCalculateInsurance(data) {

    if (data == 404) {
        $("#summ").empty();
        $('#summ').append("Заполните все поля");
    } else {
        $("#summ").empty();
        $('#summ').append(data);
    }
}

function showOrderForm() {
    $(".form_callback").show();
}


function sendAjaxForm() {
    jQuery.post(
        "ajax.php", {
        type: "html-request",
        country_id: $("#country_id").val(),
        visit_type: $("#visit_type").val(),
        visit_target: $("#visit_target").val(),
        insurance_price: $("#insurance_price").val(),
        date_between: $("#date_between").val(),
        days: $("#days").html(),
        tarif: $("#tarif").html(),
        pay_summ: $("#pay_summ").html(),
        fio: $("#fio").val(),
        phone: $("#phone").val(),
        action: "SendAjaxFormAjax",
    },
        onSendAjaxForm
    );
    console.log("test");
}

function onSendAjaxForm(data) {
    $("#results").empty();
    $('#results').append(data);
}
