<?
  session_start();
  include '../include/db_connect.php';
  $id_profile = $_SESSION["open_profile"];
  $my_id = $_SESSION["id"];

  if(isset($_POST["sub"])){
    $positive = (int)$_POST["radio_new_comment"];
    $text = $_POST["text_comment"];

    $stmt = $dbh->prepare("INSERT INTO comments(id_author, id_user, comment, positive) VALUES(?, ?, ?, ?)");
    $stmt->execute(array($my_id, $id_profile, $text, $positive));
    header("Location: ".$_SERVER["HTTP_REFERER"]);
  }
?>
