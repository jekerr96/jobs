<?
session_start();
if($_SESSION["remember"] == 1)
  session_set_cookie_params(60 * 60 * 24 * 30);
session_regenerate_id();
include 'include/db_connect.php';

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

if(isset($_POST["sub"])){
  $name = $_POST["name"];

  if($name != ""){
    $address = urlencode($name);
    $responce = file_get_contents("http://search.maps.sputnik.ru/search?q=".$address);
    $responce = json_decode($responce);
    $lat = $responce->result[0]->position->lat;
    $lon = $responce->result[0]->position->lon;

    $stmt = $dbh->query("INSERT INTO city(name, lat, lon) VALUES('$name', '$lat', '$lon')");
    header("Location: add_city.php");
    exit;
  }
  else $error = "Не указано название города";

}
?>
<!DOCTYPE html>
<html lang="ru" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Города</title>
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
  </head>
  <body>
    <? include 'include/dialogs.php'; ?>
    <div class="body_block">
      <?
        include 'include/header.php';
        echo $error;
      ?>
      <form class="" action="" method="post">
        <input type="text" name="name" value="" placeholder="Название города" required>
        <input type="submit" name="sub" value="Добавить">
      </form>
    </div>
  </body>
</html>
