<?
  session_start();
  include '../include/db_connect.php';

  $id_worker = (int)$_POST["id_worker"];
  $id_job = $_SESSION["open_job"];

  $stmt = $dbh->query("DELETE FROM workers WHERE id_user = $id_worker AND id_job = $id_job");
?>
