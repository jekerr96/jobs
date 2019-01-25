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
?>
<!DOCTYPE html>
<html lang="ru" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Подтверждение E-mail</title>
    <link rel="stylesheet" href="//jobs/css/jquery-ui.min.css">
    <link rel="stylesheet" href="//jobs/css/jquery-ui.structure.min.css">
    <link rel="stylesheet" href="//jobs/css/jquery-ui.theme.min.css">
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
      <div class="block_verify">
        <?
          $code = $_GET["code"];
          $stmt = $dbh->prepare("SELECT * FROM verify WHERE code = ?");
          $stmt->execute(array($code));
          foreach ($stmt as $row) {
            $id = $row["id"];
            $id_user = $row["id_user"];
            $email = $row["email"];
            $used = $row["used"];
          }
          if($used == 1){
            $msg = "Данная ссылка не действительна";
          }
          else{
            if($email != ""){
              $sql = "UPDATE users SET email = '$email' WHERE id = $id_user";
              $stmt = $dbh->query($sql);
              $msg = "E-mail успешно изменен";
              $_SESSION["email"] = $email;
            }
            else{
              $sql = "UPDATE users SET verify_email = 1 WHERE id = $id_user";
              $stmt = $dbh->query($sql);
              $msg = "E-mail успешно подтвержден";
            }
          }

          echo $msg;
        ?>
      </div>
    </div>
  </body>
</html>
