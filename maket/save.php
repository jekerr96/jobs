<?
  $txt = rawurldecode($_POST["txt"]);
  echo $txt;
  $fp = fopen("index.php", "w+"); // Открываем файл в режиме записи
$test = fwrite($fp, $txt); // Запись в файл

fclose($fp); //Закрытие файла
?>
