<?
  session_start();
  include '../include/db_connect.php';
  $my_id = $_SESSION["id"];
  $id_job = $_SESSION["open_job"];

  $stmt = $dbh->query("SELECT COUNT(*) as count FROM jobs WHERE id_user = $my_id AND id = $id_job AND id_status = 3");
  $row = $stmt->fetch();
  if($row["count"] == 1){
    $stmt = $dbh->query("UPDATE jobs SET id_status = 4 WHERE id = $id_job");
    echo 1;
  }


?>
