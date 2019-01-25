<?
  session_start();
  include '../include/db_connect.php';

  $id_worker = (int)$_POST["id_worker"];
  $id_job = $_SESSION["open_job"];

  $stmt = $dbh->query("SELECT COUNT(*) as count FROM workers WHERE id_user = $id_worker AND id_job = $id_job");
  $row = $stmt->fetch();
  if($row["count"] >= 1){
    $stmt = $dbh->query("UPDATE jobs SET id_worker = $id_worker, id_status = 3 WHERE id = $id_job");
    $stmt = $dbh->query("DELETE FROM workers WHERE id_job = $id_job");
  }

?>
