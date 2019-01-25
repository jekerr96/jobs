<?
  session_start();
  if($_SESSION["remember"] == 1)
    session_set_cookie_params(60 * 60 * 24 * 30);
  session_regenerate_id();
  include 'include/db_connect.php';

  if(isset($_GET["del_group"])){
    $del = $_GET["del_group"];
    $stmt = $dbh->query("DELETE FROM group_category WHERE id=$del");
    header("Location: //jobs/new_category");
    exit;
  }

  if(isset($_GET["del_cat"])){
    $del = $_GET["del_cat"];
    $stmt = $dbh->query("DELETE FROM category WHERE id=$del");
    header("Location: //jobs/new_category");
    exit;
  }

  if(isset($_POST["sub_group"])){
    $name = $_POST["name"];
    if($name == "") $error = "Не указано имя";

    if($error == ""){
      $stmt = $dbh->query("INSERT INTO group_category(name) VALUES('$name')");
      header("Location: //jobs/new_category");
      exit;
    }
  }

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
  else $error = "Ошибка загрузки картинки";
  if($name == "") $error = "Не указано имя";
  if($color == "") $error = "Не выбран цвет";

  if($error == ""){
    $stmt = $dbh->query("INSERT INTO category(name, img, color, id_group) VALUES('$name', '$icon', '$color', $group)");
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

  if(!$_SESSION["auto_city"] && !isset($_SESSION["city_id"])){
    $request = file_get_contents("http://api.sypexgeo.net/xJNHl/json/37.112.190.177");
    //$request = file_get_contents("http://api.sypexgeo.net/xJNHl/json/".$_SERVER['REMOTE_ADDR']);
    $array = json_decode($request);
    $city_name = $array->city->name_ru;
    $stmt = $dbh->query("SELECT id, name, lat, lon FROM city WHERE name = '$city_name'");
    $row = $stmt->fetch();
    if($row["id"] != ""){
      $_SESSION["city_id"] = $row["id"];
      $_SESSION["city_name"] = $row["name"];
      $_SESSION["city_lat"] = $row["lat"];
      $_SESSION["city_lon"] = $row["lon"];
      $city_lat = $row["lat"];
      $city_lon = $row["lon"];
    }
    else{
      $_SESSION["city_id"] = 1;
      $_SESSION["city_name"] = "Барнаул";
      $_SESSION["city_lat"] = "53.347378";
      $_SESSION["city_lon"] = "83.77841";
      $city_lat = "53.347378";
      $city_lon = "83.77841";
    }

  }
?>
<!DOCTYPE html>
<html lang="ru" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Категории</title>
  </head>
  <body>
    <link rel="stylesheet" href="//jobs/css/jquery-ui.min.css">
    <link rel="stylesheet" href="//jobs/css/jquery-ui.structure.min.css">
    <link rel="stylesheet" href="//jobs/css/jquery-ui.theme.min.css">
    <link rel="stylesheet" href="https://maps-js.apissputnik.ru/v0.3/sputnik_maps_bundle.min.css" />
    <link rel="stylesheet" href="//jobs/css/style.css">
    <script type="text/javascript" src="//code.jquery.com/jquery-3.3.1.min.js"></script>
    <script type="text/javascript" src="//jobs/js/jquery-ui.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
  <script type="text/javascript" src="//jobs/js/jquery.maskedinput.min.js"></script>
    <script type="text/javascript" src="//jobs/js/script.js"></script>
    <script type="text/javascript" src="//jobs/js/jscolor.js"></script>
  </body>

  <?
    include 'include/dialogs.php';
  ?>
  <div class="body_block">
    <?
      include 'include/header.php';
      echo $error;
    ?>
    <div class="">
      <span>Группы</span>
      <form class="" action="" method="post">
        <input type="text" name="name" value="">
        <input type="submit" name="sub_group" value="Добавить">
      </form>
    </div>

    <table>
      <th>Название</th>
      <th>Действие</th>
      <?
        $stmt = $dbh->query("SELECT * FROM group_category");
        while($row = $stmt->fetch()){
          echo '
          <tr>
          <td>'.$row["name"].'</td>
          <td><a href="//jobs/edit_group.php?id='.$row["id"].'" class="edit_group">Изменить</a> <a href="?del_group='.$row["id"].'" class="del_group">Удалить</a></td>
          </tr>
          ';
        }
      ?>
    </table>

    <div class="">
      <span>Категории</span>
      <form class="" action="" method="post" enctype="multipart/form-data">
        <input type="text" name="name" placeholder="Название категории" value="">
        <label for="">Иконка</label>
        <input type="file" name="img" value="">
        <input type="text" name="color" placeholder="Цвет" value="" class="jscolor">
        <select class="" name="group">
          <?
            $stmt = $dbh->query("SELECT * FROM group_category");
            while($row = $stmt->fetch()){
              echo '
              <option value="'.$row["id"].'">'.$row["name"].'</option>
              ';
            }
          ?>
        </select>
        <input type="submit" name="sub" value="Добавить">
      </form>

      <table>
        <th>Название</th>
        <th>Иконка</th>
        <th>Цвет</th>
        <th>Группа</th>
        <th>Действие</th>
        <?
          $stmt = $dbh->query("SELECT category.id as id, category.name as name, img, id_group, color, group_category.name as name_group FROM category LEFT JOIN group_category ON category.id_group = group_category.id");
          while($row = $stmt->fetch()){
            echo '
            <tr>
            <td>'.$row["name"].'</td>
            <td><img width="40px" src="//jobs/uploads/'.$row["img"].'"/></td>
            <td style="background: #'.$row["color"].';">#'.$row["color"].'</td>
            <td>'.$row["name_group"].'</td>
            <td><a href="//jobs/edit_category.php?id='.$row["id"].'" class="edit_category">Изменить</a> <a href="?del_cat='.$row["id"].'"class="del_category">Удалить</a></td>
            </tr>
            ';
          }
        ?>
      </table>
    </div>


  </div>

</html>
