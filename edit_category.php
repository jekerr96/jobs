<?
  session_start();
  include 'include/db_connect.php';
  $id = $_GET["id"];
  $stmt = $dbh->query("SELECT category.id as id, category.name as name, img, id_group, color FROM category WHERE category.id = $id");
  $row = $stmt->fetch();
  $name = $row["name"];
  $color = $row["color"];
  $id_group = $row["id_group"];

  if(isset($_POST["sub"])){

    $name = $_POST["name"];
    $img = $_FILES["img"];
    $color = $_POST["color"];
    $group = $_POST["group"];

    if(file_exists($img["tmp_name"])){
      $ext = pathinfo($img["name"], PATHINFO_EXTENSION);
      $new_name = getName();
      $new_path = "uploads/".$new_name.".".$ext;
      $icon = $new_name.".".$ext;
      if (!@copy($img['tmp_name'], $new_path)){
        $error = "Ошибка копирования картинки";
      }

  }
  if($error == ""){
    $update_icon = "img = img";
    if($icon != "")
      $update_icon = "img = '$icon'";
      $stmt = $dbh->query("UPDATE category SET name = '$name', $update_icon, color = '$color', id_group = $group WHERE id = $id");
      header("Location: //jobs/new_category");
      exit;
  }


  }
  function getName(){
    $arr = array('a','b','c','d','e','f',
                   'g','h','i','j','k','l',
                   'm','n','o','p','r','s',
                   't','u','v','x','y','z',
                   'A','B','C','D','E','F',
                   'G','H','I','J','K','L',
                   'M','N','O','P','R','S',
                   'T','U','V','X','Y','Z',
                   '1','2','3','4','5','6',
                   '7','8','9','0');
      $name = "";
      for($i = 0; $i < 32; $i++)
      {
        $index = rand(0, count($arr) - 1);
        $name .= $arr[$index];
    }
    return $name;
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
    <form class="" action="" method="post" enctype="multipart/form-data">
      <input type="text" name="name" placeholder="Название категории" value="<? echo $name; ?>">
      <label for="">Иконка</label>
      <input type="file" name="img" value="">
      <input type="text" name="color" placeholder="Цвет" value="<? echo $color; ?>" class="jscolor">
      <select class="" name="group">
        <?
          $stmt = $dbh->query("SELECT * FROM group_category");
          while($row = $stmt->fetch()){
            $selected = "";
            if($row["id"] == $id_group)
              $selected = "selected";
            echo '
            <option '.$selected.' value="'.$row["id"].'">'.$row["name"].'</option>
            ';
          }
        ?>
      </select>
      <input type="submit" name="sub" value="Изменить">
    </form>
  </body>
</html>
