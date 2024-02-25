<?php
// Подключаем файл с функциями

  function csvLoad( $file_name, $delimiter = ',' ) {
      $file = fopen( $file_name, 'r' );

      $table = [ ];
      if ( $file ) {
          while ( false !== ( $row = fgetcsv( $file, 0, $delimiter ) ) ) {
              $table[] = $row;
          }
          fclose( $file );
      }

      return $table;
  };
  function csvSave($file_name, $data, $delimiter = ',') {
        $file = fopen( $file_name, 'w' );
        foreach ( $data as $row ) {
            fputcsv($file, $row, $delimiter);
        }
        fclose($file);
  };

if (isset($_POST['delete_last'])) {
    // Загружаем данные из CSV файла
    $data = csvLoad('data.csv');

    // Удаляем последнюю строку
    array_pop($data);

    // Сохраняем обновленные данные обратно в CSV файл
    csvSave('data.csv', $data);

    // Перенаправляем обратно на index.php
    header('Location: index.php');
    exit;
}
?>