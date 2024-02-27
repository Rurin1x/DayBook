<?php
function csvLoad($file_name, $delimiter = ',') {
    $file = fopen($file_name, 'r');
    $table = [];

    if ($file) {
        while (false !== ($row = fgetcsv($file, 0, $delimiter))) {
            $table[] = $row;
        }
        fclose($file);
    }

    return $table;
}

function csvSave($file_name, $data, $delimiter = ',') {
    $file = fopen($file_name, 'w');
    foreach ($data as $row) {
        fputcsv($file, $row, $delimiter);
    }
    fclose($file);
}

if (isset($_POST['delete_last'])) {
    $data = csvLoad('data.csv');
    array_pop($data);
    csvSave('data.csv', $data);
    header('Location: index.php');
    exit;
}
?>
