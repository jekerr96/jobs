<?
  if($_SESSION["id"] == "")
    echo '<script>const _auth = false;</script>';
  else
    echo '<script>const _auth = true;</script>';
?>
<div class="header">
  <div class="top_header">
    <div class="city">
      <?
        echo $_SESSION["city_name"];
      ?>
    </div>
    <div class="block_corp">
      <div class="corp">
        Корпоративным клиентам
      </div>
    </div>
    <div class="phone">
      <a href="#">8 (800) 555 35 35</a>
    </div>
  </div>
  <div class="bottom_header">
    <img class="logo" src="https://v.od.ua/uploads/92/logo.png" alt="" >
    <div class="logo_name">
      <a href="/" class="link_main">EasyBusy</a>
    </div>

    <div class="block_lk_and_auth">
      <?
        if(isset($_SESSION["id"])){
          echo '
          <div class="btn_chats">Чаты <img src="//jobs/images/msg_notify.png" class="notify_msg"/></div>
          <div class="give_ad">
            Подать объявлние
          </div>
          <div class="lk">
            <a href="//jobs/profile">Личный кабинет</a>
          </div>';
        }
        else{
          echo '<div class="give_ad">
            Подать объявлние
          </div>
          <div class="btn_auth">Войти</div>';
        }
      ?>
      <div class="block_chats_header">
        <div class="wrap_chats_header">

        <?
        if($my_id != ""){
          $stmt = $dbh->query("SELECT id, title FROM jobs WHERE (id_user = $my_id OR id_worker = $my_id) AND id_status > 2");
          $count = 0;
          while($row = $stmt->fetch()){
            $count++;
            $arr_id[] = $row["id"];
            echo '
            <div class="block_link_chat id_job'.$row["id"].'"><span class="count_msg count_id_job'.$row["id"].'"> 0 </span><a href="//jobs/chat/'.$row["id"].'">'.$row["title"].'</a></div>
            ';
          }
          if($count == 0){
            echo '
            <div class="block_link_chat empty_chats">Нету активных чатов</div>
            ';
          }
        }
        if($arr_id != "" && $my_id != ""){
          echo '<script>var arr_msg = '.json_encode($arr_id).';
          var my_id = '.$my_id.';
          var open_job = -1;
          </script>';
        }
        else{
          echo '<script>var arr_msg = -1;
          var my_id = -1;
          var open_job = -1;
          </script>';
        }

        ?>

      </div>
      </div>
      <div class="block_auth">
        <div class="auth_container">
        <div class="error_auth" hidden>
        </div>
        <input type="email" class="input_email_auth" name="" value="" placeholder="E-mail">
        <input type="password" class="input_pass_auth" name="" value="" placeholder="Пароль">
        <input type="checkbox" name="" value="" id="remember_me">
        <label for="remember_me">Запомнить меня</label>
        <div class="btn_auth_form">
          Войти
        </div>
        <span class="restore_password_auth">Забыли пароль?</span>

        <div class="link_new_account" >Регистрация аккаунта</div>
      </div>
      <div class="restore_auth_container">
        <input type="text" class="input_restore_pass" name="" placeholder="E-mail" autocomplete="off">
        <div class="btn_send_restore">
          Отправить
        </div>
        <br>
        <div class="btn_back_auth">
          Назад
        </div>
        <div class="msg_send_restore">
          Письмо отправлено на электронную почту
        </div>
      </div>
    </div>
    </div>

  </div>
</div>
