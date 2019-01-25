<?
  include '../include/db_connect.php';
  $email = $_POST["email"];

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
    exit;
  }

  $code = getCode();

  $stmt = $dbh->query("SELECT id FROM users WHERE email = '$email'");
  $row = $stmt->fetch();
  $my_id = $row["id"];

  $stmt = $dbh->exec("INSERT INTO restore_password(id_user, code) VALUES($my_id, '$code')");

    $to = "jekerr96@gmail.com"; //Кому
    $from = "EasyBusy <jekerr@finder>"; //От кого
    $subject = "Восстановление пароля"; //Тема
    $message = "Для создания нового пароля перейдите по <a href='//jobs/restore/$code'>ссылке</a>"; //Текст письма
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

    function getCode(){
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
