<?
  session_start();
  if(isset($_SESSION["id"])){
    include '../include/db_connect.php';
    $id_user = $_SESSION["id"];
    $id_city = $_SESSION["city_id"];
    $city_name = $_SESSION["city_name"];


    $title = $_POST["title_new_job"];
    $description = $_POST["description_new_job"];
    $addr = $_POST["address_new_job"];
    $id_category = (int)$_POST["category_new_job"];
    $price = (int)$_POST["price_new_job"];
    $images = $_FILES;

    foreach ($images as $img) {
      $allowedTypes = array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF);
      $detectedType = exif_imagetype($img['tmp_name']);
      if(!in_array($detectedType, $allowedTypes))
        continue;

      $ext = pathinfo($img["name"], PATHINFO_EXTENSION);
      $new_path = "../uploads/".getName().".".$ext;

      if (copy($img['tmp_name'], $new_path)){
        $arr_path[] = $new_path;
      }
    }

  if(mb_strlen($title) > 100){
    $message["error"] .= "Слишком длинное название<br>";
  }
  if($id_category == -1 || $id_category == ""){
    $message["error"] .= "Не выбрана категория<br>";
  }
  if($title == ""){
    $message["error"] .= "Не введено название<br>";
  }
  if($description == ""){
    $message["error"] .= "Не введено описание<br>";
  }
  if($price == ""){
    $message["error"] .= "Не указана цена<br>";
  }


  $arr_path = serialize($arr_path);

  if($addr != ""){
    $address = urlencode($city_name." ".$addr);
    $responce = file_get_contents("http://search.maps.sputnik.ru/search?q=".$address);
    $responce = json_decode($responce);
    $lat = $responce->result[0]->position->lat;
    $lon = $responce->result[0]->position->lon;
  }

  if($message["error"] == ""){
    $stmt = $dbh->prepare("INSERT INTO jobs(title, description, img, price, id_user, id_category, id_city, addr, lat, lon, id_status) VALUES(?, ?, '$arr_path', ?, $id_user, ?, $id_city, ?, '$lat', '$lon', 2)");
    $stmt->execute(array($title, $description, $price, $id_category, $addr));
    $insId = $dbh->lastInsertId();
    $message["code"] = 0;
    $message["id"] = $insId;
  }
  else{
    $message["code"] = 1;
  }
}
else{
  $message["code"] = 1;
  $message["error"] .= "Вы не авторизированы<br>";
}
echo json_encode($message);
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
