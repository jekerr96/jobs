<?


  session_start();
  if($_SESSION["remember"] == 1)
    session_set_cookie_params(60 * 60 * 24 * 30);
  session_regenerate_id();
  include 'include/db_connect.php';

  $filter_category = $_GET["category"];
  $filter_price_start = (int)$_GET["price_start"];
  $filter_price_end = (int)$_GET["price_end"];

  if($filter_category != ""){
    foreach ($filter_category as $cat) {
      $cat_filter .= "id_category = ".(int)$cat." OR ";
    }
    if($cat_filter != ""){
      $filter = " AND (".mb_substr($cat_filter, 0, -4).")";
    }
  }

  if($filter_price_start != "")
    $filter .= " AND price >= $filter_price_start";
  if($filter_price_end != "")
    $filter .= " AND price <= $filter_price_end";

  $_SESSION["filter_jobs"] = $filter;

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

  $id_city = $_SESSION["city_id"];
  $city_lat = $_SESSION["city_lat"];
  $city_lon = $_SESSION["city_lon"];
  $my_id = $_SESSION["id"];
?>
<!DOCTYPE html>
<html lang="ru" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Главная</title>
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
    <script type="text/javascript" src="//jobs/js/script.js?v3"></script>
    <script type="text/javascript" src="//jobs/js/load_jobs_index.js"></script>
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

    <div class="content">
      <div class="money_block1">
        реклама
      </div>
      <div class="block_content">
        <div class="block_map">
          <div id="map"></div>
        </div>
        <div class="block_donate_jobs">
          <?
            //TODO добавить проверку по статусу
            $stmt = $dbh->query("SELECT jobs.id as id, category.img as img, title, description, price FROM jobs INNER JOIN category ON jobs.id_category = category.id WHERE id_city = $id_city AND vip = 1 AND date_start <= cast(NOW() as date) AND date_end >= cast(NOW() as date) AND id_status = 2 ORDER BY id DESC");
            while($row = $stmt->fetch()){
              if(mb_strlen($row["title"]) > 40)
                $title = mb_substr($row["title"], 0, 40)."...";
              else
                $title = $row["title"];
              if(mb_strlen($row["description"]) > 160)
                $description = mb_substr($row["description"], 0, 160)."...";
              else
                $description = $row["description"];
              echo '
              <a class="link_job" href="//jobs/job/'.$row["id"].'">
              <div class="donate_jobs">
                <div class="block_img_donate_jobs">
                  <img src="//jobs/uploads/'.$row["img"].'" class="img_jobs" alt="">
                </div>
                <div class="block_text_donate_jobs">
                  <div class="header_donate_jobs">
                    '.$title.'
                  </div>
                  <div class="text_donate_jobs">
                    '.$description.'
                  </div>
                </div>
                <br>
                <div class="block_price_donate_jobs">
                  <div class="price_donate_jobs">
                    '.$row["price"].' р.
                  </div>
                </div>
              </div>
              </a>
              ';


            }
          ?>
        </div>
      </div>
      <div class="money_block2">
        Реклама
      </div>
    </div>

    <div class="block_jobs">
      <div class="block_form_filter">
        <form class="form_filter" action="" method="get">
          <div class="block_filter_elements">
            <div class="block_btn_category_filter">
              <div class="btn_category_filter">
                <span>Категория</span>
                <img class="btn_category_img btn_category_img_index" src="//jobs/images/strelka.png" alt="">
              </div>
              <div class="block_select_category hide_select_category">
                <?
                  $stmt = $dbh->query("SELECT category.id as id, category.name as name, group_category.name as name_group FROM category INNER JOIN group_category ON category.id_group = group_category.id ORDER BY group_category.id");
                  $group = "";
                  while($row = $stmt->fetch()){
                    if($group != $row["name_group"] && $group != "")
                      echo '</div>';
                    if($group != $row["name_group"]){
                      $group = $row["name_group"];
                      echo '<h3><a href="#">'.$group.'</a></h3><div class="accordion_height">';
                    }
                    $checked = "";
                    for($i = 0; $i < count($_GET["category"]); $i++){
                      if($row["id"] == (int)$_GET["category"][$i]){
                        $checked = "checked";
                        break;
                      }
                    }
                    echo '
                    <input '.$checked.' type="checkbox" id="category'.$row["id"].'" name="category[]" value="'.$row["id"].'">
                    <label for="category'.$row["id"].'">'.$row["name"].'</label><br>
                    ';
                  }
                  if($group != "")
                    echo '</div>';
                ?>

              </div>
            </div>
            <label class="filter_label" for="">Оплата</label>
            <input type="number" class="filter_price" name="price_start" min="0" value="<? echo $filter_price_start == 0 ? "" : $filter_price_start; ?>" placeholder="От" autocomplete="off">
            <input type="number" class="filter_price" name="price_end" min="0" value="<? echo $filter_price_end == 0 ? "" : $filter_price_end; ?>" placeholder="До" autocomplete="off">
            <input type="submit" class="sub_filter" name="" value="Применить">
            <div class="clear_filter">
              <a href="//jobs/">Очистить</a>
            </div>
          </div>
        </form>
      </div>
      <div class="block_list_jobs">
        <?
          $stmt = $dbh->query("SELECT jobs.id as id, title, description, price, users.name as name, category.img as img, jobs.lat as lat, jobs.lon as lon FROM jobs INNER JOIN users ON jobs.id_user = users.id INNER JOIN category ON jobs.id_category = category.id WHERE vip = 0 AND jobs.id_city = $id_city $filter AND id_status = 2 ORDER BY jobs.id DESC LIMIT 20");

          while($row = $stmt->fetch()){
            $name = split(" ", $row["name"]);
            $name = $name[1]." ".mb_substr($name[0], 0, 1).".";
            if(mb_strlen($row["title"]) > 40)
              $title = mb_substr($row["title"], 0, 40)."...";
            else
              $title = $row["title"];
            if(mb_strlen($row["description"]) > 160)
              $description = mb_substr($row["description"], 0, 160)."...";
            else
              $description = $row["description"];
            echo '
            <div class="job" >
            <a href="http://jobs/job/'.$row["id"].'" class="link_job">
              <div class="head_job">
                <div class="block_img_job">
                  <img src="//jobs/uploads/'.$row["img"].'" class="img_job" alt="">
                </div>
                <div class="name_job" title="'.$row["title"].'">'.$title.'</div>
              </div>
              <div class="text_job">
                '.$description.'
              </div>
              <div class="bottom_job">
                <div class="login_job">
                  '.$name.'
                </div>
                <div class="price_job">
                  '.$row["price"].' р.
                </div>
              </div>
              </a>
            </div>
            ';
          }
        ?>

        <div class="block_prepend">

        </div>
      <div class="empty_job">

      </div>

      <div class="empty_job">

      </div>

      <div class="empty_job">

      </div>
    </div>
    </div>
  </div>

<script type="text/javascript">
var lat, lon;
navigator.geolocation.getCurrentPosition(
    function(position) {
      lat = position.coords.latitude;
      lon = position.coords.longitude;
        var myMarker1 = L.sm.marker([lat, lon]); // создаем маркер с координатами
map1.addLayer(myMarker1); // первый способ: карта добавляет маркер
	}
);
<?
$stmt = $dbh->query("SELECT jobs.id as id, title, description, price, users.name as name, category.img as img, jobs.lat as lat, jobs.lon as lon, vip, date_end, date_start FROM jobs INNER JOIN users ON jobs.id_user = users.id INNER JOIN category ON jobs.id_category = category.id WHERE jobs.id_city = $id_city $filter AND id_status = 2");

while($row = $stmt->fetch()){
  if($row["vip"] == 1){
    $date_start = strtotime($row["date_start"]);
    $date_end = strtotime($row["date_end"]);
    $now = time();
    if($date_start > $now || $date_end < $now)
      continue;
  }
  if($row["lat"] != "")
    $geodata[] = array(
    "title" => $row["title"],
    "description" => $row["description"]." <br><a href='//jobs/job/".$row["id"]."'>Перейти</a>",
    "img" => $row["img"],
    "lat" => $row["lat"],
    "lon" => $row["lon"]
  );
}
  if(!empty($geodata)){
?>
var geoData1 = {
	"type": "FeatureCollection",
	"features": [
    <?
      foreach ($geodata as $data) {
        $geo .= '
        {
    		"type": "Feature",
    		"geometry": {"type": "Point", "coordinates": ['.$data["lon"].','.$data["lat"].']},
    		"popupTemplate": "<p>{shortTitle}</p>",
    		"properties": {"title": "'.$data["title"].'", "fullTitle": "'.$data["description"].'", "iconUrl": "//jobs/uploads/'.$data["img"].'", "iconSize" : [40, 40]
        }
      },
        ';
      }
      $geo = mb_substr($geo, 0, -1);
      echo $geo;
    ?>
  ]
};
<?
}
else{
  echo "var geoData1 = {}";
}
?>


var map1 = L.sm.map('map', {center: [<? echo "$city_lat,$city_lon"; ?>], zoom: 11});    // создаем карту
    var options = {maxClusterRadius: 70};    // параметры кластера
    var cluster = L.sm.cluster(options);    // создаем кластер
    var options = {
        popupTemplate: '<b>{fullTitle}</b>',       // можно использовать шаблон попапа для всех элементов GeoJson
        defaultPopupTemplate:'<u>{shortTitle}</u>',  // или шаблон попапа для элементов, у которых нет popupTemplate
        iconUrl: "{iconUrl}",
        iconSize: "{iconSize}"
    };
    <?
    if(!empty($geodata)){
      ?>
    var geoJsonLayer = L.sm.geoJson(geoData1, options); // создаем слой данных из GeoJSON

    map1.addLayer(geoJsonLayer);        // добавляем в кластер слой данных
    cluster.addTo(map1);
    <?
  }
  ?>
/* маршруты
    <?
    /*
    $responce = file_get_contents("http://footroutes.maps.sputnik.ru/?loc=53.38589,83.72244&loc=$lat,$lon");
    $responce = json_decode($responce);
    echo "var latlngs = polyline.decode('$responce->route_geometry');";
*/
    ?>
      var polyline1 = L.polyline(latlngs, {color: 'red'}).addTo(map1);
      map1.fitBounds(polyline1.getBounds());
*/
</script>
  </body>
</html>
