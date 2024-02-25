<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>DayBook</title>
  <script>
    function toggleFields() {
        const inputContainer = document.getElementById('input-container');
        inputContainer.classList.toggle('show');
    };
    function refreshPage() {
      window.location.reload();
    };
  </script>
  <style>
  table {
    width: 100%;
    border-collapse: collapse;
    margin: 25px 0;
    font-size: 0.9em;
    font-family: sans-serif;
    min-width: 400px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
  }

  thead tr {
    background-color: #009879;
    color: #ffffff;
    text-align: left;
  }

  th, td {
    padding: 12px 15px;
  }

  tbody tr {
    border-bottom: 1px solid #dddddd;
  }

  tbody tr:nth-of-type(even) {
    background-color: #f3f3f3;
  }

  tbody tr:last-of-type {
    border-bottom: 2px solid #009879;
  }

  tbody tr.active-row {
    font-weight: bold;
    color: #009879;
  }
  .input {
    width: 100%;
    padding: 10px;
    border: 2px solid #ccc;
    border-radius: 4px;
    background-color: #f8f8f8;
    font-size: 16px;
    margin-bottom: 10px;
  }

  /* Стили для textarea */
  textarea.input {
    height: 150px;
    resize: none;
  }

  /* Стили для фокуса */
  .input:focus {
    border-color: #007bff;
    box-shadow: 0 0 5px #007bff;
  }
#input-container {
  width: 100%;
  padding: 10px;
  border: 2px solid #ccc;
  border-radius: 4px;
  background-color: #f8f8f8;
  font-size: 16px;
  margin-bottom: 10px;
  display: none;
  opacity: 0;
  transform: translateY(-20px);
  transition: opacity 0.3s ease, transform 0.3s ease;
}

#input-container.show {
  display: block;
  opacity: 1;
  transform: translateY(0);
}
.button {
  background-color: #009879; /* Зеленый цвет фона */
  border: none;
  color: white;
  padding: 15px 32px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  border-radius: 4px; /* Закругленные углы */
  cursor: pointer; /* Изменение курсора при наведении */
  transition: background-color 0.3s ease; /* Плавное изменение цвета при наведении */
}

.button:hover {
  background-color: #008CBA; /* Синий цвет фона при наведении */
}
</style>
</head>
<body>
  <h1>DayBook backend test</h1>
  <button class="button" onclick="toggleFields()">Показать/скрыть поля</button>
  <form action="delete_last_row.php" method="post">
      <input class="button" type="submit" name="delete_last" value="Удалить последнюю строку">
    </form>
   <div id="input-container">
  <form method="post">
      <input class="input" id="text-input" type="text" name="training_status" placeholder="Статус тренеровки" required>
      <input class="input" id="text-input" type="text" name="hrv_7_days" placeholder="HRV за 7 дней" required>
      <input class="input" id="text-input" type="text" name="hrv_today" placeholder="HRV за днь" required>
      <input class="input" id="text-input" type="text" name="vo2max" placeholder="VO2MAX" required>
      <input class="input" id="text-input" type="text" name="sleep_duration" placeholder="Длительность сна" required>
      <input class="input" id="text-input" type="text" name="sleep_quality" placeholder="Качество сна" required>
      <input class="input" id="text-input" type="text" name="weight" placeholder="Вес" required>
      <input class="input" id="text-input" type="text" name="resting_calories" placeholder="Калории в состоянии покоя" required>
      <input class="input" id="text-input" type="text" name="active_calories" placeholder="Активные калории" required>
      <input class="input" id="text-input" type="text" name="resting_pulse" placeholder="Пульс в состоянии покоя" required>
      <input class="input" id="text-input" type="text" name="max_pulse" placeholder="Максимальный пульс" required>
      <input class="input" id="text-input" type="text" name="hydration" placeholder="Гидратация" required>
      <input class="input" id="text-input" type="text" name="steps" placeholder="Шаги" required>
      <input class="input" id="text-input" type="text" name="stress" placeholder="Стресс" required>
      <input class="input" id="text-input" type="text" name="exercise" placeholder="Занятие спортом" required>
      <input class="input" id="text-input" type="text" name="wellbeing" placeholder="Самочувствие" required>
      <input class="input" id="text-input" type="text" name="bad_habits" placeholder="Вредные привычки" required>
      <input class="button" type="submit" value="Сохранить">
  </form>
  </div>
<?php
  echo formatTable(csvLoad('data.csv'));

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
  }

  function csvSave($file_name, $data, $delimiter = ',') {
      $file = fopen( $file_name, 'w' );
      foreach ( $data as $row ) {
          fputcsv($file, $row, $delimiter);
      }
      fclose($file);
  }

  function formatCell( $cell, $tag ) {
      $cell = $cell ? $cell : '&nbsp;';

      return "<$tag>$cell</$tag>";
  }

  function formatRow( $row, $rowTag, $cellTag ) {
      $row = array_map( function ( $cell ) use ( $cellTag ) {
          return formatCell( $cell, $cellTag );
      }, $row );

      $row = implode( '', $row );

      return formatCell( $row, $rowTag ).PHP_EOL;
  }

  function formatTable( $table ) {
      $header = formatRow( $table[0], 'thead', 'th' );
      $body   = [ ];
      foreach ( array_slice( $table, 1 ) as $row ) {
          $body[] = formatRow( $row, 'tr', 'td' );
      }
      $body = formatCell(implode( '',  $body), 'tbody' );

      return formatCell( $header . $body, 'table' );
  };

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
      // данные из формы собираем /\ ух какой говнокод
      $training_status = $_POST['training_status'];
      $hrv_7_days = $_POST['hrv_7_days'];
      $hrv_today = $_POST['hrv_today'];
      $vo2max = $_POST['vo2max'];
      $sleep_duration = $_POST['sleep_duration'];
      $sleep_quality = $_POST['sleep_quality'];
      $weight = $_POST['weight'];
      $resting_calories = $_POST['resting_calories'];
      $active_calories = $_POST['active_calories'];
      $resting_pulse = $_POST['resting_pulse'];
      $max_pulse = $_POST['max_pulse'];
      $hydration = $_POST['hydration'];
      $steps = $_POST['steps'];
      $stress = $_POST['stress'];
      $exercise = $_POST['exercise'];
      $wellbeing = $_POST['wellbeing'];
      $bad_habits = $_POST['bad_habits'];
      // Загрузка данных из CSV
      $data = csvLoad('data.csv');

      // Создаем новую строку с данными из формы
      $new_row = [$training_status, $hrv_7_days, $hrv_today, $vo2max, $sleep_duration, $sleep_quality, $weight, $resting_calories,
      $active_calories, $resting_pulse, $max_pulse, $hydration, $steps, $stress, $exercise, $wellbeing, $bad_habits];

      // Добавляем новую строку в массив
      $data[] = $new_row;

      // Сохранение обновленных данных в CSV
      csvSave('data.csv', $data);

      // echo "Новые данные добавлены в файл!";
      // header("Refresh: 0");
      // echo formatTable($data);
      };
?>
<button class="button" onclick="refreshPage()">Обновить таблицу</button>
</body>
</html>