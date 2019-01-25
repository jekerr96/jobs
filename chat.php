<?
  session_start();
  if($_SESSION["remember"] == 1)
    session_set_cookie_params(60 * 60 * 24 * 30);
  session_regenerate_id();
  include 'include/db_connect.php';

  $id_job = (int)$_GET["job"];
  $my_id = $_SESSION["id"];

  $stmt = $dbh->query("SELECT avatar, name, positive, negative, id_worker, id_user FROM users INNER JOIN jobs ON jobs.id_user = users.id WHERE jobs.id = $id_job");
  $row = $stmt->fetch();

  if($my_id == "" || $row["id_worker"] == 0)
    $block = true;
  else if($row["id_user"] === $my_id)
    $its_my = true;
  else if($row["id_worker"] === $my_id)
    $its_my = false;
  else $block = true;

  $avatar = $row["avatar"];
  $name = $row["name"];
  $positive = $row["positive"];
  $negative = $row["negative"];
  $id_worker = $row["id_worker"];
  $id_user = $row["id_user"];

  $name = split(" ", $row["name"]);
  $name = $name[1]." ".mb_substr($name[0], 0, 1).".";

  if(!$block){
    $stmt = $dbh->query("SELECT avatar, name, positive, negative FROM users WHERE id = $id_worker");
    $row = $stmt->fetch();

    $worker_avatar = $row["avatar"];
    $worker_name = $row["name"];
    $worker_positive = $row["positive"];
    $worker_negative = $row["negative"];

    $worker_name = split(" ", $row["name"]);
    $worker_name = $worker_name[1]." ".mb_substr($worker_name[0], 0, 1).".";
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
    <title>Чат</title>
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
    <script type="text/javascript" src="//jobs/js/script.js"></script>
    <script type="text/javascript" src="//jobs/js/chat.js"></script>
  </head>
  <body>
    <?
    include 'include/dialogs.php';

    ?>
    <div class="body_block">
      <?
        include 'include/header.php';
        echo '<script>var open_job='.$id_job.';</script>';
      ?>

      <div class="chat_container">

        <div class="head_chat">

        </div>
        <div class="chat_content <? if(!$its_my) echo "reverse_chat"; ?>">
          <?
            if($block){
              echo "У вас нет доступа к этому чату.";
              exit;
            }
            if($its_my)
              $name_msg = $name;
            else
              $name_msg = $worker_name;
            echo "<div hidden name='$name_msg' class='get_param' my_id='".$my_id."' get='".$id_job."'></div>"

          ?>
          <div class="block_author_info_chat">
            <div class="author_info_chat">
              <img src="//jobs/uploads/<? echo $avatar; ?>" alt="" class="author_img_chat">
              <div class="author_name">
                <? echo $name; ?>
              </div>
              <div class="reiting_my_comments">
                <span class="positive_my_comments"><? echo $positive; ?></span>
                <span class="negative_my_comments"><? echo $negative; ?></span>
              </div>
            </div>
          </div>
          <div class="block_chat">


          </div>
          <div class="block_worker_info_chat">
            <div class="worker_info_chat">
              <img src="//jobs/uploads/<? echo $worker_avatar; ?>" alt="" class="worker_img_chat">
              <div class="worker_name">
                <? echo $worker_name; ?>
              </div>
              <div class="reiting_my_comments">
                <span class="positive_my_comments"><? echo $worker_positive; ?></span>
                <span class="negative_my_comments"><? echo $worker_negative; ?></span>
              </div>
            </div>
          </div>
        </div>
        <div class="block_bottom_chat">
          <div class="empty_left_chat">

          </div>
          <div class="block_write" contenteditable="true">

          </div>
          <div class="block_btns_chat">
            <div class="btn_send">
              Отправить
            </div><br><div class="btn_load_chat">
              Загрузить
            </div>
            <input type="file" class="input_load_files_chat" hidden multiple name="" value="">
            <div class="block_name_files">

            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
