<?
  session_start();
  include '../include/db_connect.php';
  if(isset($_POST["sub"])){
    $name = $_POST["name"];
    $email = $_POST["email"];
    //$phone = $_POST["phone"];
    $pass = $_POST["pass"];
    $rPass = $_POST["repeat_pass"];
    $prof = $_POST["professions"];
    $avatar = $_FILES["avatar"];

    $_SESSION["reg_name"] = $_POST["name"];
    $_SESSION["reg_email"] = $_POST["email"];
    //$_SESSION["reg_phone"] = $_POST["phone"];
    $_SESSION["reg_pass"] = $_POST["pass"];
    $_SESSION["reg_rPass"] = $_POST["repeat_pass"];
    $_SESSION["reg_professions"] = $_POST["professions"];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
      $_SESSION["error_reg"] .= "Неверно указан E-mail<br>";
    }
    else{
      $stmt = $dbh->query("SELECT COUNT(*) as count FROM users WHERE email = '$email'");
      $row = $stmt->fetch();
      if($row["count"] != 0)
        $_SESSION["error_reg"] .= "Данный E-mail уже занят<br>";
    }

    /*$stmt = $dbh->prepare("SELECT COUNT(*) as count FROM users WHERE phone = ?");
    $stmt->execute(array($phone));
    foreach ($stmt as $row) {
      if($row["count"] != 0)
        $_SESSION["error_reg"] .= "Данный номер телефона уже занят<br>";
    }
*/
    if(strlen($pass) >= 6){
      if($pass !== $rPass){
        $_SESSION["error_reg"] .= "Пароли не совпадают<br>";
      }
      else{
        $pass = sha1("qwDfBhYtZc".sha1("aZzVgr21!$^121.".$pass."hjyt5*&3%e")."po66(53)33@_-");
      }
    }
    else{
      $_SESSION["error_reg"] .= "Пароль должен содержать минимум 6 символов<br>";
    }

    if(file_exists($avatar["tmp_name"])){
      $allowedTypes = array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF);
      $detectedType = exif_imagetype($avatar['tmp_name']);
      if(!in_array($detectedType, $allowedTypes))
        $_SESSION["error_reg"] .= "Ошибка при загрузке аватара";

      $ext = pathinfo($avatar["name"], PATHINFO_EXTENSION);
      $new_name = getName();
      $new_path = "../uploads/".$new_name.".".$ext;
      $img = $new_name.".".$ext;
      if (!@copy($avatar['tmp_name'], $new_path)){
        $_SESSION["error_reg"] .= "Ошибка при загрузке аватара";
      }
  }
  else{
    $img = "no-avatar.png";
  }

  if($_SESSION["error_reg"] == ""){
    //С телефоном
    //$stmt = $dbh->prepare("INSERT INTO users(name, email, phone, pass, prof, avatar) VALUES(?, ?, ?, ?, ?, '$img')");
    //$stmt->execute(array($name, $email, $phone, $pass, $prof));

    $stmt = $dbh->prepare("INSERT INTO users(name, email,  pass, prof, avatar) VALUES(?, ?, ?, ?, '$img')");
    $stmt->execute(array($name, $email, $pass, $prof));

    $code = getName();
    $new_id = $dbh->lastInsertId();

    $stmt = $dbh->query("INSERT INTO verify(id_user, code) VALUES($new_id, '$code')");

    unset($_SESSION["reg_name"]);
    unset($_SESSION["reg_email"]);
    //unset($_SESSION["reg_phone"]);
    unset($_SESSION["reg_pass"]);
    unset($_SESSION["reg_rPass"]);
    unset($_SESSION["reg_professions"]);

    $to = $email; //Кому
    $from = "EasyBusy <no-reply@EasyBusy>"; //От кого
    $subject = "Подтверждение почты"; //Тема
    $message = "Для подтверждения перейдите по <a href='//jobs/verify/$code'>ссылке</a>"; //Текст письма
    $boundary = "---"; //Разделитель
    /* Заголовки */
    $headers = "From: $from\nReply-To: $from\n";
    $headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"";
    $body = "--$boundary\n";
    /* Присоединяем текстовое сообщение */
    $body .= "Content-type: text/html; charset='utf-8'\n";
    $body .= "Content-Transfer-Encoding: quoted-printablenn";
    $body .= $message."\n";
    $body .= "--$boundary\n";
    mail($to, $subject, $body, $headers); //Отправляем письмо

    //$_SESSION["id"] = $dbh->lastInsertId();
    //$_SESSION["name"] = $name;
    //$_SESSION["email"] = $email;
    //$_SESSION["phone"] = $phone;
    //$_SESSION["prof"] = $prof;
    //$_SESSION["avatar"] = $img;
  }
  header("Location: ".$_SERVER['HTTP_REFERER']);
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
