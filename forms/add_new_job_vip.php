<?
  session_start();
  if(isset($_POST["sub"]) && isset($_SESSION["id"])){
    include '../include/db_connect.php';
    $id_user = $_SESSION["id"];
    $id_city = $_SESSION["city_id"];
    $caty_name = $_SESSION["city_name"];

    $title = $_POST["title_new_job"];
    $description = $_POST["description_new_job"];
    $addr = $_POST["address_new_job"];
    $id_category = (int)$_POST["category_new_job_vip"];
    $price = (int)$_POST["price_new_job"];
    $img = $_FILES["images_new_job"];
    $date = $_POST["date_new_job"];

    $date = split(" - ", $date);
    $date_start = date("Y-m-d", strtotime($date[0]));
    $date_end = date("Y-m-d", strtotime($date[1]));

    $_SESSION["add_new_job_vip_title"] = $title;
    $_SESSION["add_new_job_vip_description"] = $description;
    $_SESSION["add_new_job_vip_addr"] = $addr;
    $_SESSION["add_new_job_vip_price"] = $price;
    $_SESSION["add_new_job_vip_date"] = $_POST["date_new_job"];

    $stmt = $dbh->query("SELECT COUNT(*) as count FROM jobs WHERE ((date_start <= '$date_start' AND date_end >= '$date_start') OR (date_start <= '$date_end' AND date_end >= '$date_end')) AND vip = 1 AND id_city = $id_city");
    $row = $stmt->fetch();

    if($row["count"] >= 5){
      $_SESSION["error_add_job_vip"] .= "Дата уже занята<br>";
      header("Location: " . $_SERVER["HTTP_REFERER"]);
      exit;
    }

    if(file_exists($img["tmp_name"][0])){
    for($i = 0; $i < count($img["name"]); $i++) {
      $allowedTypes = array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF);
      $detectedType = exif_imagetype($img['tmp_name'][$i]);
      if(!in_array($detectedType, $allowedTypes))
        continue;

      $ext = pathinfo($img["name"][$i], PATHINFO_EXTENSION);
      $new_name = getName();
      $new_path = "../uploads/".$new_name.".".$ext;
      $arr_path[] = $new_name;
      if (copy($img['tmp_name'][$i], $new_path)){

      }
    }
  }

  if(mb_strlen($title) > 100){
    $_SESSION["error_add_job_vip"] .= "Слишком длинное название<br>";
  }
  if($id_category == -1 || $id_category == ""){
    $_SESSION["error_add_job_vip"] .= "Не выбрана категория<br>";
  }
  if($title == ""){
    $_SESSION["error_add_job_vip"] .= "Не введено название<br>";
  }
  if($description == ""){
    $_SESSION["error_add_job_vip"] .= "Не введено описание<br>";
  }
  if($price == ""){
    $_SESSION["error_add_job_vip"] .= "Не указана цена<br>";
  }

  $arr_path = serialize($arr_path);

  if($addr != ""){
    $address = urlencode($caty_name." ".$addr);
    $responce = file_get_contents("http://search.maps.sputnik.ru/search?q=".$address);
    $responce = json_decode($responce);
    $lat = $responce->result[0]->position->lat;
    $lon = $responce->result[0]->position->lon;
  }

  if($_SESSION["error_add_job"] == ""){
    $stmt = $dbh->prepare("INSERT INTO jobs(title, description, img, price, id_user, id_category, id_city, addr, lat, lon, vip, date_start, date_end, id_status) VALUES(?, ?, '$arr_path', ?, $id_user, ?, $id_city, ?, '$lat', '$lon', 1, '$date_start', '$date_end', 1)");
    $stmt->execute(array($title, $description, $price, $id_category, $addr));
    $insId = $dbh->lastInsertId();
    header("Location: //jobs/job/".$insId);
  }
  else{
    header("Location: ".$_SERVER["HTTP_REFERER"]);
  }
}
else{
  $_SESSION["error_add_job_vip"] .= "Вы не авторизированы<br>";
  header("Location: ".$_SERVER["HTTP_REFERER"]);
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
