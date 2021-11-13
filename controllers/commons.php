<?php
// Database connection
include('config/db.php');


function getOptionsBySql($sql)
{
	global $connection;
	$options = null;
	if (empty($sql)) {
		return "sql is empty";
	}

	$query = mysqli_query($connection, $sql);
	if (mysqli_num_rows($query) > 0) {
		while ($row = mysqli_fetch_array($query)) {
			$options .= '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
		}
	}
	return $options;
}

// Выборка данных
function db_get_data($sql, $field = '')
{
	global $connection;
	$result = mysqli_query($connection, $sql);
	if (mysqli_num_rows($result) > 0) {
		$row = mysqli_fetch_array($result);
		mysqli_free_result($result);
		if ($field == '') return $row;
		else	return $row[$field];
	}
	return false;
}

//Выборка данных в виде массива
function db_get_array($sql, $field1, $field2 = '')
{
	global $connection;
	$records = array();
	$result = mysqli_query($connection, $sql);
	if (mysqli_num_rows($result) > 0) {
		while ($row = mysqli_fetch_array($result)) {
			if ($field1 && $field2 && isset($row[$field1]) && isset($row[$field2])) $records[$row[$field1]] = $row[$field2];
			else $records[] = $row[$field1];
		}
		mysqli_free_result($result);
	}
	return $records;
}


function getSelectOptionWithOthers($valuesArray, $selectedValue = "", $fieldName = "")
{
	$option = null;
	if (!is_array($selectedValue)) {
		$option .= '<option value="" selected>Выберите</option>';
		foreach ($valuesArray as $value => $label) {
			if ($value == $selectedValue)
				$selected = ' selected';
			else
				$selected = '';

			$option .= '<option value="' . $value . '"' . $selected . '>' . $label . '</option>';
		}
	} else {
		foreach ($valuesArray as $value => $label) {
			if (isset($selectedValue[$value]))
				$checked = 'checked';
			else
				$checked = '';

			$option .= '<input type="checkbox" name="' . $fieldName . '[' . $value . ']" value="1" ' . $checked . '>' . $label . '</option><br>';
		}
	}

	return $option;
}


function cleanStr($str)
{
	global $connection;
	$str = strip_tags($str);
	$str = htmlspecialchars($str);
	$str = mysqli_real_escape_string($connection, $str);

	return $str;
}

function daysBetweenDates($start, $end)
{
	$difference = null;
	$datetime1 = new DateTime($start);
	$datetime2 = new DateTime($end);
	$difference = $datetime1->diff($datetime2);

	// return $difference->d;
	return $difference->days+1;
}
