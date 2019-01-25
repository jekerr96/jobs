<?
  session_start();
  include 'include/db_connect.php';
  $id = $_GET["id"];
  $stmt = $dbh->query("SELECT * FROM group_category WHERE id = $id");
  $row = $stmt->fetch();
  $name = $row["name"];

  if(isset($_POST["sub"])){
    $name = $_POST["name"];

    if($name != ""){
      $stmt = $dbh->query("UPDATE group_category SET name = '$name' WHERE id=$id");
      header("Location: //jobs/new_category");
      exit;
    }

  }
?>
<!DOCTYPE html>
<html lang="ru" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Изменение категории</title>
    <script type="text/javascript" src="//jobs/js/jscolor.js"></script>
  </head>
  <body>
    <form class="" action="" method="post">
      <input type="text" name="name" value="<? echo $name; ?>">
      <input type="submit" name="sub" value="Изменить">
    </form>
  </body>
</html>
