<?
  session_start();
  include '../include/db_connect.php';

  $my_id = $_SESSION["id"];
  $city_name = $_SESSION["city_name"];

  $id_job = (int)$_POST["id_job"];
  $id_category = (int)$_POST["category_change_job"];
  $price = (int)$_POST["price_change_job"];
  $title = $_POST["title_change_job"];
  $description = $_POST["description_change_job"];
  $addr = $_POST["address_change_job"];
  $img = $_FILES["images_change_job"];

  $stmt = $dbh->query("SELECT COUNT(*) as count, img, addr, lat, lon FROM jobs WHERE id_user = $my_id AND id = $id_job");
  $row = $stmt->fetch();

  if($row["count"] == 1){
    $old_img = unserialize($row["img"]);
    $old_addr = $row["addr"];
    $lat = $row["lat"];
    $lon = $row["lon"];

    if(file_exists($img["tmp_name"][0])){
    for($i = 0; $i < count($img["name"]); $i++) {
      $allowedTypes = array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF);
      $detectedType = exif_imagetype($img['tmp_name'][$i]);
      if(!in_array($detectedType, $allowedTypes))
        continue;

      $ext = pathinfo($img["name"][$i], PATHINFO_EXTENSION);
      $new_path = "../uploads/".getName().".".$ext;

      if (copy($img['tmp_name'][$i], $new_path)){
        $old_img[] = $new_path;
      }
    }
  }
  if($id_category == -1 || $id_category == ""){
    $_SESSION["error_change_job"] .= "Не выбрана категория<br>";
  }
  if($title == ""){
    $_SESSION["error_change_job"] .= "Не введено название<br>";
  }
  if($description == ""){
    $_SESSION["error_change_job"] .= "Не введено описание<br>";
  }
  if($price == ""){
    $_SESSION["error_change_job"] .= "Не указана цена<br>";
  }

  $old_img = serialize($old_img);

  if($addr != "" && $old_addr != $addr){
    $address = urlencode($city_name." ".$addr);
    $responce = file_get_contents("http://search.maps.sputnik.ru/search?q=".$address);
    $responce = json_decode($responce);
    $lat = $responce->result[0]->position->lat;
    $lon = $responce->result[0]->position->lon;
  }
  else if($addr == "")
  {
    $lat = "";
    $lon = "";
  }

  if($_SESSION["error_change_job"] == ""){
    $stmt = $dbh->prepare("UPDATE jobs SET id_category = ?, price = ?, title = ?, description = ?, addr = ?, img = ?, lat = ?, lon = ? WHERE id = $id_job");
    $stmt->execute(array($id_category, $price, $title, $description, $addr, $old_img, $lat, $lon));
  }
  header("Location: //jobs/job/".$id_job);
  }
  else{
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
