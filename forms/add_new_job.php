<?
  session_start();
  var_dump($_FILES);
  var_dump($_POST);
  exit;
  if(isset($_POST["sub"]) && isset($_SESSION["id"])){
    include '../include/db_connect.php';
    $id_user = $_SESSION["id"];
    $id_city = $_SESSION["city_id"];
    $city_name = $_SESSION["city_name"];


    $title = $_POST["title_new_job"];
    $description = $_POST["description_new_job"];
    $addr = $_POST["address_new_job"];
    $id_category = (int)$_POST["category_new_job"];
    $price = (int)$_POST["price_new_job"];
    $img = $_FILES["images_new_job"];

    $_SESSION["add_new_job_title"] = $title;
    $_SESSION["add_new_job_description"] = $description;
    $_SESSION["add_new_job_addr"] = $addr;
    $_SESSION["add_new_job_price"] = $price;

    if(file_exists($img["tmp_name"][0])){
    for($i = 0; $i < count($img["name"]); $i++) {
      $allowedTypes = array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF);
      $detectedType = exif_imagetype($img['tmp_name'][$i]);
      if(!in_array($detectedType, $allowedTypes))
        continue;

      $ext = pathinfo($img["name"][$i], PATHINFO_EXTENSION);
      $new_path = "../uploads/".getName().".".$ext;

      if (copy($img['tmp_name'][$i], $new_path)){
        $arr_path[] = $new_path;
      }
    }
  }

  if(mb_strlen($title) > 100){
    $_SESSION["error_add_job"] .= "Слишком длинное название<br>";
  }
  if($id_category == -1 || $id_category == ""){
    $_SESSION["error_add_job"] .= "Не выбрана категория<br>";
  }
  if($title == ""){
    $_SESSION["error_add_job"] .= "Не введено название<br>";
  }
  if($description == ""){
    $_SESSION["error_add_job"] .= "Не введено описание<br>";
  }
  if($price == ""){
    $_SESSION["error_add_job"] .= "Не указана цена<br>";
  }


  $arr_path = serialize($arr_path);

  if($addr != ""){
    $address = urlencode($city_name." ".$addr);
    $responce = file_get_contents("http://search.maps.sputnik.ru/search?q=".$address);
    $responce = json_decode($responce);
    $lat = $responce->result[0]->position->lat;
    $lon = $responce->result[0]->position->lon;
  }

  if($_SESSION["error_add_job"] == ""){
    $stmt = $dbh->prepare("INSERT INTO jobs(title, description, img, price, id_user, id_category, id_city, addr, lat, lon, id_status) VALUES(?, ?, '$arr_path', ?, $id_user, ?, $id_city, ?, '$lat', '$lon', 2)");
    $stmt->execute(array($title, $description, $price, $id_category, $addr));
    $insId = $dbh->lastInsertId();
    header("Location: //jobs/job/".$insId);
  }
  else{
    header("Location: ".$_SERVER["HTTP_REFERER"]);
  }

}
else{
  header("Location: ".$_SERVER["HTTP_REFERER"]);
  $_SESSION["error_add_job"] .= "Вы не авторизированы<br>";
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
