<?

  session_start();

  include '../include/db_connect.php';
  if(!isset($_SESSION["id"])){
    $email = $_POST["email"];
    $pass = $_POST["pass"];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL))
      exit;

    $pass = sha1("qwDfBhYtZc".sha1("aZzVgr21!$^121.".$pass."hjyt5*&3%e")."po66(53)33@_-");

    $stmt = $dbh->query("SELECT users.id as id, users.name as name, email, phone, prof, avatar, city.id as city_id, city.name as city_name, lat, lon, verify_email FROM users LEFT JOIN city ON users.id_city = city.id WHERE email = '$email' AND pass = '$pass'");
    $row = $stmt->fetch();
    if($row["id"] != "" && $row["verify_email"] != 0){
      $_SESSION["id"] = $row["id"];
      $_SESSION["name"] = $row["name"];
      $_SESSION["email"] = $row["email"];
      $_SESSION["phone"] = $row["phone"];
      $_SESSION["prof"] = $row["prof"];
      $_SESSION["avatar"] = $row["avatar"];
      $_SESSION["remember"] = $_POST["remember"];

      if($row["city_id"] !== null){
        $_SESSION["city_id"] = $row["city_id"];
        $_SESSION["city_name"] = $row["city_name"];
        $_SESSION["city_lat"] = $row["lat"];
        $_SESSION["city_lon"] = $row["lon"];
        $_SESSION["auto_city"] = true;
      }
      echo 1;
    }
    else if($row["verify_email"] == 0 && $row["id"] != "")
      echo 0;
  }
  else echo 1;
?>
