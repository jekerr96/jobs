<?
  session_start();
  include '../include/db_connect.php';

  $my_id = $_SESSION["id"];
  $id_job = $_SESSION["open_job"];

  $stmt = $dbh->query("DELETE FROM workers WHERE id_job = $id_job AND id_user = $my_id");
?>
