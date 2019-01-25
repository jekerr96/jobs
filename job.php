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


  $id_job = (int)$_GET["id"];
  $my_id = $_SESSION["id"];
  $_SESSION["open_job"] = $id_job;

  if($my_id != ""){
    $stmt = $dbh->query("SELECT COUNT(*) as count FROM workers WHERE id_user = $my_id AND id_job = $id_job");
    $row = $stmt->fetch();
    if($row["count"] >= 1){
      $in_job = true;
      $_SESSION["in_job"] = true;
    }
    else{
      $_SESSION["in_job"] = false;
    }
  }



  $stmt = $dbh->query("SELECT title, description, price, jobs.img as img, id_user, lat, lon, addr, category.id as id_category, category.name as category, category.img as category_img, color, id_worker, vip, id_status FROM jobs INNER JOIN category ON jobs.id_category = category.id WHERE jobs.id = $id_job");
  $row = $stmt->fetch();

  if($my_id == $row["id_user"])
    $its_my = true;

  $category = $row["category"];
    if($row["img"] != "N;")
      $img = unserialize($row["img"]);
    else
      $img[] = "no_product.png";
  $title = $row["title"];
  $price = $row["price"];
  $description = $row["description"];
  $addr = $row["addr"];
  $lat = $row["lat"];
  $lon = $row["lon"];
  $category_img = $row["category_img"];
  $id_category = $row["id_category"];
  $color = $row["color"];
  $id_accept_worker = $row["id_worker"];
  $id_author = $row["id_user"];
  $vip = $row["vip"];
  $id_status = $row["id_status"];

  if($id_accept_worker == $my_id)
    $accept_worker = true;
?>
<!DOCTYPE html>
<html lang="ru" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Заявка</title>

    <link rel="stylesheet" href="http://jobs/css/jquery-ui.min.css">
    <link rel="stylesheet" href="http://jobs/css/jquery-ui.structure.min.css">
    <link rel="stylesheet" href="http://jobs/css/jquery-ui.theme.min.css">
    <link rel="stylesheet" href="https://maps-js.apissputnik.ru/v0.3/sputnik_maps_bundle.min.css" />
    <script type="text/javascript" src="//code.jquery.com/jquery-3.3.1.min.js"></script>
    <link rel="stylesheet" href="http://jobs/css/style.css">
    <script type="text/javascript" src="http://jobs/js/jquery-ui.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script type="text/javascript" src="//jobs/js/jquery.maskedinput.min.js"></script>
    <script type="text/javascript" src="http://jobs/js/script.js"></script>
    <script src="https://maps-js.apissputnik.ru/v0.3/sputnik_maps_bundle.min.js"></script>
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
      <div class="block_content_job">
        <div class="wrap_job"  style="border-bottom: 2px solid #<? echo $color; ?>">

        <div class="block_head_category_job" style="background: #<? echo $color; ?>">
          <?
            echo $category;
          ?>
        </div>
        <div class="job_container">
          <div class="job_images">
            <div class="active_img">
              <img src="//jobs/uploads/<? echo $img[0]; ?>" alt="">
            </div>
            <div class="block_nav_images">
              <?
                $selected = "";
                foreach ($img as $src) {
                  if($selected == "")
                    $selected = "class='selected_img'";
                  else
                    $selected = " ";
                  echo '<img '.$selected.' src="//jobs/uploads/'.$src.'"/>';
                }
              ?>
            </div>
          </div>
          <div class="info_container">
            <div class="name_and_price_job">
              <div class="block_name_job_info">
                <div class="name_job_info">
                  <?
                    echo $title;
                  ?>
                </div>
              </div>
              <div class="block_price_job_info">
                <div class="price_job_info">
                  <? echo $price; ?> руб.
                </div>
              </div>

            </div>
            <div class="text_and_map_job">
              <div class="text_job_info">
                <span class="border_text_job">
                <?
                  echo $description;
                ?>
              </span>
              </div>
              <div class="block_map_job_info">
              <div class="block_border_map_job_info">
                <div id="map_job_info"></div>
              </div>
              <div class="address_map">
                <? echo $addr == "" ? "Адрес не указан" : $addr; ?>
              </div>
              <div class="block_accept_job">
                <?
                  if($in_job && !$its_my && $id_accept_worker == "0"){
                    echo '<div class="btn_leave_job">Покинуть заявку</div>';
                  }
                  else if(!$its_my && $id_accept_worker == "0"){
                    echo '<div class="btn_accept_job">
                      Принять заявку +
                    </div>';
                  }
                  if(($its_my && $id_accept_worker != 0 || $accept_worker) && $my_id != ""){
                    echo '
                    <a href="//jobs/chat/'.$id_job.'" class="link_to_chat">
                    <div class="btn_to_chat">Перейти в чат</div>
                    </a>
                    ';
                  }
                  if($its_my && $id_status < 3){  //TODO добавить проверку статуса
                    echo '<div class="btn_change_job">
                      Изменить
                    </div>';

                    if($vip != 1 )
                    echo '
                    <div class="btn_del_job">
                      Удалить
                    </div>';
                  }
                  if($its_my && $id_status == 3){
                    echo '
                      <div class="btn_close_job">Закрыть</div>
                    ';
                  }

                ?>
                <div class="btn_back">
                  Вернуться
                </div>
              </div>
                  </div>
            </div>
          </div>
        </div>

        <div class="block_list_employees_job">
          <div class="list_employees_job">
            <div class="border_employees_job">
            <?
              if($id_accept_worker == "0" && $its_my){
                $sql = "SELECT users.id as id, avatar, name FROM workers INNER JOIN users ON workers.id_user = users.id WHERE id_job = $id_job";
              }
              else if($id_accept_worker != "0" && $its_my){
                $sql = "SELECT id, avatar, name FROM users WHERE id = $id_accept_worker";
              }
              else{
                $sql = "SELECT id, avatar, name FROM users WHERE id = $id_author";
                $bckr = "style='background : #ffae0036; border: 1px solid rgb(245, 214, 189);'";
              }
              $stmt = $dbh->query($sql);
              while($row = $stmt->fetch()){
                $name = split(" ", $row["name"]);
                $name = $name[1]." ".mb_substr($name[0], 0, 1).".";
                if($its_my && $id_accept_worker == "0"){
                  $id_worker = $row["id"];
                  $del = "<img class='del_worker' id_worker='$id_worker' src='//jobs/images/del.png' title='Отклонить заявку'>";
                  $accept = "<div class='accept_worker' id_worker='$id_worker'>Принять</div>";
                }
                echo '
                <div class="employee_job" '.$bckr.'>
                '.$del.'
                <a href="//jobs/profile/'.$row["id"].'" class="link_profile">
                  <div class="avatar_employee">
                    <img src="//jobs/uploads/'.$row["avatar"].'" alt="">
                  </div>
                  <div class="login_employee">
                    '.$name.'
                  </div>
                  </a>
                  '.$accept.'
                </div>
                ';
              }
            ?>
          </div>
          </div>

        </div>

      </div>
      </div>
    </div>
    <script type="text/javascript">
    <?
      $marker = true;
      if($lat == "")
      {
        $lat = "55.7522200";
        $lon = "37.6155600";
        $marker = false;
      }
    ?>
      var map1 = L.sm.map('map_job_info', <? echo "{center: [$lat,$lon ],";?> zoom: 15});
      <? if($marker) echo "var myMarker1 = L.sm.marker([$lat,$lon ],{
        iconUrl: '//jobs/uploads/$category_img',
        iconSize: [40, 40] // размер иконки
});
      myMarker1.addTo(map1); // первый способ: карта добавляет маркер
      "; ?>
    </script>
  </body>
</html>
