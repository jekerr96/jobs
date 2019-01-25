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

$my_id = $_SESSION["id"];

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


if(isset($_POST["sub"])){
  $name = $_POST["name"];
  $email = $_POST["email"];
  //$phone = $_POST["phone"];
  $pass = $_POST["pass"];
  $rPass = $_POST["rPass"];
  $prof = $_POST["prof"];
  $avatar = $_FILES["avatar"];

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
    $_SESSION["error_lk"] .= "Неверно указан E-mail<br>";
  }
  else if($_SESSION["email"] != $email){
    $stmt = $dbh->query("SELECT COUNT(*) as count FROM users WHERE email = '$email'");
    $row = $stmt->fetch();
    if($row["count"] != 0)
      $_SESSION["error_lk"] .= "Данный E-mail уже занят<br>";
  }

  /*if($_SESSION["phone"] != $phone){
  $stmt = $dbh->prepare("SELECT COUNT(*) as count FROM users WHERE phone = ?");
  $stmt->execute(array($phone));
  foreach ($stmt as $row) {
    if($row["count"] != 0)
      $_SESSION["error_lk"] .= "Данный номер телефона уже занят<br>";
  }
}
*/
  if($pass != ""){
  if(strlen($pass) >= 6){
    if($pass !== $rPass){
      $_SESSION["error_lk"] .= "Пароли не совпадают<br>";
    }
    else{
      $pass = "pass = '".sha1("qwDfBhYtZc".sha1("aZzVgr21!$^121.".$pass."hjyt5*&3%e")."po66(53)33@_-")."'";
    }
  }
  else{
    $_SESSION["error_lk"] .= "Пароль должен содержать минимум 6 символов<br>";
  }
}
else{
  $pass = "pass = pass";
}
  if(file_exists($avatar["tmp_name"])){
    $allowedTypes = array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF);
    $detectedType = exif_imagetype($avatar['tmp_name']);
    if(!in_array($detectedType, $allowedTypes))
      $_SESSION["error_lk"] .= "Ошибка при загрузке аватара<br>";
      //TODO не загружать если это не картинка
    $ext = pathinfo($avatar["name"], PATHINFO_EXTENSION);
    $new_name = getName();
    $new_path = "uploads/".$new_name.".".$ext;
    $img = $new_name.".".$ext;
    $upd_avatar = "avatar = '".$new_name.".".$ext."'";
    if (!@copy($avatar['tmp_name'], $new_path)){
      $_SESSION["error_lk"] .= "Ошибка при загрузке аватара<br>";
    }
}
else{
  $upd_avatar = "avatar = avatar";
  $img = $_SESSION["avatar"];
}

if($_SESSION["error_lk"] == ""){
  //С телефоном
  //$stmt = $dbh->prepare("UPDATE users SET name = ?, phone = ?, $pass, prof = ?, $upd_avatar WHERE id = $my_id");
  //$stmt->execute(array($name, $phone, $prof));

  $stmt = $dbh->prepare("UPDATE users SET name = ?, $pass, prof = ?, $upd_avatar WHERE id = $my_id");
  $stmt->execute(array($name, $prof));

  if($_SESSION["email"] != $email){
    $code = getName();
    $stmt = $dbh->query("INSERT INTO verify(id_user, email, code) VALUES($my_id, '$email', '$code')");

    $to = $email; //Кому
    $from = "EasyBusy <jekerr@finder>"; //От кого
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

    $_SESSION["email_info"] = "На указанный E-mail отправлено письмо для подтверждения";
  }


  $_SESSION["name"] = $name;
  //$_SESSION["phone"] = $phone;
  $_SESSION["prof"] = $prof;
  $_SESSION["avatar"] = $img;
}

header("Location: //jobs/profile");
exit;
}




  $id_profile = (int)$_GET["id"] == "" ? $my_id : (int)$_GET["id"];
  if($id_profile == $my_id)
    $its_my = true;

  if($id_profile == ""){
    header("Location: /");
    exit;
  }

  $_SESSION["open_profile"] = $id_profile;

  if(!$its_my){
    $stmt = $dbh->query("SELECT avatar, name, prof, positive, negative FROM users WHERE id = $id_profile");
    $row = $stmt->fetch();
    $avatar = $row["avatar"];
    $name = $row["name"];
    $prof = $row["prof"];
    $positive = $row["positive"];
    $negative = $row["negative"];
  }
  else{
    $stmt = $dbh->query("SELECT positive, negative FROM users WHERE id = $id_profile");
    $row = $stmt->fetch();
    $positive = $row["positive"];
    $negative = $row["negative"];
    $avatar = $_SESSION["avatar"];
    $name = $_SESSION["name"];
  }
  if($name == "")
    $block = true;
?>
<!DOCTYPE html>
<html lang="ru" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Личный кабинет</title>
    <link rel="stylesheet" href="//jobs/css/style.css">
    <link rel="stylesheet" href="//jobs/css/jquery-ui.min.css">
    <link rel="stylesheet" href="//jobs/css/jquery-ui.structure.min.css">
    <link rel="stylesheet" href="//jobs/css/jquery-ui.theme.min.css">
    <script type="text/javascript" src="//code.jquery.com/jquery-3.3.1.min.js"></script>
    <script type="text/javascript" src="//jobs/js/jquery-ui.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script type="text/javascript" src="//jobs/js/jquery.maskedinput.min.js"></script>
    <script type="text/javascript" src="//jobs/js/script.js"></script>
    <script type="text/javascript" src="//jobs/js/load_in_lk.js"></script>
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
  <div class="block_lk">
    <div class="block_text_lk">

        <?
          if($its_my){
            echo '
<div class="btn_logout">
<a href="//jobs/exit.php"> Выход из учетной записи</a>
</div>
            ';
          }
          else{
            echo "<div></div>";
          }
        ?>

      <div class="text_lk">
        Личный кабинет
      </div>
    </div>
    <div class="block_content_lk">
      <?
        if($block){
          echo '<div class="profile_not_found">Профиль не существует</div>';
          exit;
        }

      ?>
      <div class="container_content_lk">

        <div class="block_avatar_lk">
          <div class="container_avatar_lk">
          <div class="avatar_lk">
            <img src="//jobs/uploads/<? echo $avatar; ?>" alt="">
          </div>
          <div class="login_lk">
            <?
              echo $name;
            ?>
          </div>
          <div class="block_reiting">
            <img src="https://cdn.discordapp.com/attachments/371232645138350083/496641524050821120/icons8---16.png" alt="">
            <span><? echo $positive;  ?></span>
            <img src="https://cdn.discordapp.com/attachments/371232645138350083/496641545596829699/icons8---16.png" alt="">
            <span><? echo $negative;  ?></span>
          </div>
        </div>
        </div>
        <div class="block_new_info_lk">
          <?
            if($its_my){
              echo '
              <div class="block_btn_lk">
                <div class="take_money">
                  Вывести деньги
                </div>
                <div class="history_money">
                  История платежей
                </div>
              </div>
              ';
            }
          ?>

          <div class="block_inputs_lk">
            <?
              echo $_SESSION["error_lk"];
              echo $_SESSION["email_info"];
              unset($_SESSION["error_lk"]);
              unset($_SESSION["email_info"]);
            ?>
            <form method="post" enctype="multipart/form-data">
              <?
                if($its_my){
                  ?>
            <div class="block_input_lk">
              <label for="">Фамилия, имя</label>
              <input type="text" name="name" placeholder="Иванов Иван" value="<? echo $_SESSION["name"]; ?>">
            </div>
            <div class="block_input_lk">
              <label for="">E-mail</label>
              <input type="email" name="email" placeholder="example@domain.com" value="<? echo $_SESSION["email"]; ?>">
            </div>
            <? /*
            <div class="block_input_lk">
              <label for="">Телефон</label>
              <input type="text" name="phone" class="input_phone_lk" value="<? echo $_SESSION["phone"]; ?>">
            </div>
            */ ?>
            <div class="block_input_lk">
              <label for="">Пароль</label>
              <input type="password" name="pass" placeholder="Без изменений" value="">
            </div>
            <div class="block_input_lk">
              <label for="">Повторите пароль</label>
              <input type="password" name="rPass" placeholder="Без изменений" value="">
            </div>
            <div class="block_input_lk">
              <label for="">Профессия(ии)</label>
              <input type="text" name="prof" placeholder=""  value="<? echo $_SESSION["prof"]; ?>">
            </div>
            <div class="block_input_lk">
              <label for="">Аватар</label>
              <input type="file" class="input_load_avatar_lk" name="avatar" placeholder="" hidden value="" accept="image/jpeg,image/png,image/gif">
              <div class="block_btn_load_avatar_lk">
                <span class="btn_load_avatar_lk">
                  Загрузить
                </span>
              </div>

            </div>
          </div>
          <div class="block_save_btn_lk">
            <input type="submit" name="sub" value="Сохранить" class="btn_save_lk">
            <?
          }
          else{
            echo '
            <div class="profile_prof_info">
            '.$prof.'
            </div>';
            if($my_id != ""){
              echo '<div class="btn_new_comment">
                Написать отзыв о человеке
              </div>';
            }

          }
          ?>

          </div>
        </form>
        </div>
        <?
          if($its_my){
            echo '
            <div class="block_balance_lk">
              <div class="balance_lk">
                3000 руб.
              </div>
            </div>
            ';
          }
        ?>

      </div>
    </div>

    <div class="block_my_jobs">
      <div class="block_head_name_my_jobs">
        <div class="head_name_my_jobs">
          Список заявок
        </div>
      </div>
      <div class="block_btns_my_jobs">
        <div class="btn_accepts_my_jobs">
          Приятые
        </div>
        <div class="btn_exposed_my_jobs">
          Выставленные
        </div>
          <select class="filter_status_lk" name="">
            <option value="0" selected disabled>Статус</option>
            <option value="0">Все</option>
            <?
              $stmt = $dbh->query("SELECT * FROM status");
              while($row = $stmt->fetch()){
                echo '
                <option value="'.$row["id"].'">'.$row["status_name"].'</option>
                ';
              }
            ?>
          </select>
          <div class="block_btn_category_my_job">
            <div class="btn_category_my_job">
              <span>Категория</span>
              <img class="btn_category_img btn_category_img_lk" src="//jobs/images/strelka.png" alt="">
            </div>
            <div class="block_select_category_my_job hide_select_category">
              <?
                $stmt = $dbh->query("SELECT category.id as id, img, category.name as name, group_category.name as name_group FROM category INNER JOIN group_category ON category.id_group = group_category.id ORDER BY group_category.id");
                $group = "";
                while($row = $stmt->fetch()){
                  if($group != $row["name_group"] && $group != "")
                    echo '</div>';
                  if($group != $row["name_group"]){
                    $group = $row["name_group"];
                    echo '<h3><a href="#">'.$group.'</a></h3><div class="accordion_height_my_job">';
                  }
                  echo '
                  <input class="select_filter_category_lk" img="//jobs/uploads/'.$row["img"].'" '.$checked.' type="radio" name="filter_lk" id="category_my_job'.$row["id"].'"  value="'.$row["id"].'">
                  <label for="category_my_job'.$row["id"].'">'.$row["name"].'</label><br>
                  ';
                }
                if($group != "")
                  echo '</div>';
              ?>
            </div>

      </div>
      <div class="clear_filter clear_filter_lk">
        Очистить
      </div>
    </div>
      <div class="block_list_my_jobs">
        <?
          $stmt = $dbh->query("SELECT jobs.id as id, title, date_add, price, color, category.name as category, category.img as img, id_status, status_name, id_user, id_worker, id_category FROM jobs INNER JOIN category ON jobs.id_category = category.id INNER JOIN status ON id_status = status.id WHERE id_user = $id_profile OR id_worker = $id_profile ORDER BY jobs.id DESC LIMIT 8");
          while($row = $stmt->fetch()){
            $date = date("d.m.Y", strtotime($row["date_add"]));
            $create = "";
            $accept = "";
            if($row["id_user"] == $id_profile)
              $create = "create = '1'";
            if($row["id_worker"] == $id_profile)
              $accept = "accept = '1'";
            $status = "status = '".$row["id_status"]."'";
            $category = "category='".$row["id_category"]."'";
            echo '
            <div class="my_job" '.$create.$accept.$status.$category.'>
            <a class="link_job" href="//jobs/job/'.$row["id"].'">
              <div class="name_status_my_job">
                '.$row["status_name"].'
              </div>
              <div class="container_info_my_jobs">
              <div class="block_img_category_my_jobs">
                <img src="//jobs/uploads/'.$row["img"].'" alt="">
              </div>
              <div class="block_info_my_jobs">
                <div class="block_name_my_jobs">
                  '.$row["title"].'
                </div>
                <div class="info_my_jobs">
                  <div class="date_my_jobs">
                    '.$date.'
                  </div>
                  <div class="price_my_jobs">
                    '.$row["price"].' р.
                  </div>
                  <div class="category_name_my_job" style="background: #'.$row["color"].';">
                    '.$row["category"].'
                  </div>
                </div>
              </div>
            </div>
            </a>
          </div>
            ';
          }
        ?>
      </div>
    </div>


    <div class="block_my_comments">
      <div class="block_head_name_my_comments">
        <div class="head_name_my_comments">
          Отзывы
        </div>
      </div>
      <div class="block_btns_my_comments">
        <div class="btn_all_my_comments" positive="-1">
          Все
        </div>
        <div class="btn_positive_my_comments" positive = "0">
          Позитивные
        </div>
        <div class="btn_negative_my_comments" positive="1">
          Негативные
        </div>
      </div>
      <div class="block_content_my_comments">

        <?
          $stmt = $dbh->query("SELECT users.id as id, avatar, name, comment, comments.positive as positive, users.positive as posCom, users.negative as negCom FROM comments INNER JOIN users ON comments.id_author = users.id WHERE id_user = $id_profile ORDER BY comments.id DESC LIMIT 6");
          while($row = $stmt->fetch()){
            if($row["positive"] == 1){
              $type = "positive_comment";
            }
            else{
              $type = "negative_comment";
            }
            $name = split(" ", $row["name"]);
            $name = $name[1]." ".mb_substr($name[0], 0, 1).".";
            echo '
            <div class="my_comment" positive="'.$row["positive"].'">
              <div class="type_my_comments '.$type.'">

              </div>
            <div class="block_avatar_my_comments">
            <a class="link_profile" href="//jobs/profile/'.$row["id"].'">
              <div class="avatar_my_comments">
                <img src="//jobs/uploads/'.$row["avatar"].'" alt="">
              </div>
              <div class="login_my_comments">
                '.$name.'
              </div>
              <div class="reiting_my_comments">
                <span class="positive_my_comments">'.$row["posCom"].'</span>
                <span class="negative_my_comments">'.$row["negCom"].'</span>
              </div>
              </a>
            </div>
            <div class="block_text_my_comments">
              '.$row["comment"].'
            </div>
          </div>
            ';
          }
        ?>
    </div>
    </div>


  </div>
</div>
  </body>
</html>
