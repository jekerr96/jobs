<?
  session_start();
  if($_SESSION["remember"] == 1)
    session_set_cookie_params(60 * 60 * 24 * 30);
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

  $code = $_GET["code"];
  $stmt = $dbh->prepare("SELECT COUNT(*) as count, id_user, used, date_add FROM restore_password WHERE code = ?");
  $stmt->execute(array($code));
  foreach ($stmt as $row) {
    if($row["count"] == 0)
      $error = "Данная ссылка не действительна";
    $date = strtotime($row["date_add"]);
    //TODO проверить даты на хостинге
    $now = time() + 3600 * 4;
    if($now - $date > 3600)
      $error = "Данная ссылка не действительна";
    if($row["used"] == 1)
      $error = "Данная ссылка не действительна";

    $my_id = $row["id_user"];
  }

  if(isset($_POST["sub"])){
    $pass = $_POST["pass"];
    $rPass = $_POST["rPass"];

    if(strlen($pass) >= 6){
      if($pass !== $rPass){
        $error .= "Пароли не совпадают<br>";
      }
      else{
        $pass = sha1("qwDfBhYtZc".sha1("aZzVgr21!$^121.".$pass."hjyt5*&3%e")."po66(53)33@_-");
      }
    }
    else{
      $error .= "Пароль должен содержать минимум 6 символов<br>";
    }

    if($error == ""){
      $stmt = $dbh->query("UPDATE users SET pass = '$pass' WHERE id = $my_id");
      $stmt = $dbh->query("UPDATE restore_password SET used = 1 WHERE code = '$code'");
      header("Location: /");
      exit;
    }
  }

?>
<!DOCTYPE html>
<html lang="ru" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Восстановление пароля</title>
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
    <script src="https://www.gstatic.com/firebasejs/5.5.5/firebase.js"></script>
    <script>
      // Initialize Firebase
      var config = {
        apiKey: "AIzaSyBGjFi2O7QbXn-8TdHKXllWANP54LgX7pA",
        authDomain: "jobs-1537965587542.firebaseapp.com",
        databaseURL: "https://jobs-1537965587542.firebaseio.com",
        projectId: "jobs-1537965587542",
        storageBucket: "jobs-1537965587542.appspot.com",
        messagingSenderId: "211154635824"
      };
      firebase.initializeApp(config);
    </script>
  </head>
  <body>
    <?
  include 'include/dialogs.php';
  ?>
    <div class="body_block">
      <?
        include 'include/header.php';
      ?>
      <div class="block_restore">
        <form class="" action="" method="post">
          <div class="error_restore">
            <? echo $error; $error = "";?>
          </div>
          <div class="block_input_restore">
            <label for="">Пароль</label>
            <input type="password" name="pass" placeholder="Пароль" value="">
          </div>
          <div class="block_input_restore">
            <label for="">Повторите пароль</label>
            <input type="password" name="rPass" placeholder="Повторите пароль" value="">
          </div>
          <div class="block_input_restore">
            <input type="submit" name="sub" class="sub_restore" value="Сохранить">
          </div>
        </form>
      </div>
    </div>
  </body>
</html>
