<?
  session_start();
  include '../include/db_connect.php';
  $id_city = (int)$_POST["id_city"];
  $my_id = $_SESSION["id"];

  $stmt = $dbh->query("SELECT * FROM city WHERE id = $id_city");
  $row = $stmt->fetch();
  $_SESSION["city_id"] = $row["id"];
  $_SESSION["city_name"] = $row["name"];
  $_SESSION["city_lat"] = $row["lat"];
  $_SESSION["city_lon"] = $row["lon"];
  $_SESSION["auto_city"] = true;

  if($my_id != "")
    $stmt = $dbh->query("UPDATE users SET id_city = $id_city WHERE id = $my_id");
  echo 1;
?>
