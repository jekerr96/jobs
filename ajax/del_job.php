<?
  session_start();
  include '../include/db_connect.php';
  $my_id = $_SESSION["id"];
  $id_job = $_SESSION["open_job"];

  $stmt = $dbh->query("SELECT COUNT(*) as count FROM jobs WHERE id_user = $my_id AND id = $id_job AND vip = 0 AND id_status != 4");
  $row = $stmt->fetch();
  if($row["count"] == 1){
    $stmt = $dbh->query("DELETE FROM jobs WHERE id = $id_job");
    echo 1;
  }


?>
