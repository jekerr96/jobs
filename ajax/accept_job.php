<?
  session_start();

  if($_SESSION["in_job"])
    exit;

  include '../include/db_connect.php';

  $id_job = $_SESSION["open_job"];
  $my_id = $_SESSION["id"];

  $stmt = $dbh->query("SELECT id_status FROM jobs WHERE id = $id_job");
  $row = $stmt->fetch();
  //TODO проверить статус

  $stmt = $dbh->query("INSERT INTO workers(id_user, id_job) VALUES($my_id, $id_job)");
  echo 1;
?>
