<?
  session_start();
  include '../include/db_connect.php';

  $id_city = $_SESSION["city_id"];

  $stmt = $dbh->query("SELECT date_start, date_end FROM jobs WHERE vip = 1 AND id_city = $id_city AND date_end >= cast(NOW() as date) AND id_status != 4");
  while($row = $stmt->fetch()){
    $arr[] = $row;
  }
  echo json_encode($arr);
?>
