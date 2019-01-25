<?
  $user = "root";
  $pass = "";
  $db_name = "jobs_db";
  $host = "localhost";
  $opt = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
  $dbh = new PDO("mysql:host=$host;dbname=$db_name", $user, $pass, $opt);
?>
