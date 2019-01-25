<div class="dialog_new_job" hidden>
  <div class="error_add_new_job">

  </div>
  <form class="form_new_job" action="//jobs/forms/add_new_job.php " enctype="multipart/form-data" method="post">
  <div class="block_category_and_price">
    <div class="category_new_job">
      <div class="block_btn_category_new_job">
        <div class="btn_category_new_job">
          <span>Категория</span>
          <img class="btn_category_img btn_category_img_new_job" src="//jobs/images/strelka.png" alt="">
        </div>
        <div class="block_select_category_new_job hide_select_category">
          <?
            $stmt = $dbh->query("SELECT category.id as id, img, category.name as name, group_category.name as name_group FROM category INNER JOIN group_category ON category.id_group = group_category.id ORDER BY group_category.id");
            $group = "";
            while($row = $stmt->fetch()){
              if($group != $row["name_group"] && $group != "")
                echo '</div>';
              if($group != $row["name_group"]){
                $group = $row["name_group"];
                echo '<h3><a href="#">'.$group.'</a></h3><div class="accordion_height_new_job">';
              }
              echo '
              <input class="radio_new_job" img="//jobs/uploads/'.$row["img"].'" '.$checked.' type="radio" id="category_new_job'.$row["id"].'" name="category_new_job" value="'.$row["id"].'">
              <label for="category_new_job'.$row["id"].'">'.$row["name"].'</label><br>
              ';
            }
            if($group != "")
              echo '</div>';
          ?>
        </div>
      </div>
    </div>
    <div class="price_new_job">
      <input class="input_price_new_job" type="number" name="price_new_job" placeholder="Введите цену..." value="<? echo $_SESSION["add_new_job_price"]; ?>"  min="0" required>
    </div>
  </div>

  <div class="block_content_new_job">
    <div class="block_img_new_job">
      <img class="img_new_job" src="http://icons.iconarchive.com/icons/paomedia/small-n-flat/256/map-marker-icon.png" alt="">
    </div>
    <div class="content_new_job">
      <div class="block_input_new_job">
      <label for="">Введите название работы</label>
      <input type="text" name="title_new_job" class="title_new_job" maxlength="100" value="<? echo $_SESSION["add_new_job_title"]; ?>" required placeholder="Название работы" autocomplete="off">
    </div>
      <div class="block_input_new_job">
      <label for="">Введите описание</label>
      <textarea name="description_new_job" class="description_new_job" rows="8" cols="80" placeholder="Опишите проблему" required><? echo $_SESSION["add_new_job_description"]; ?></textarea>
    </div>
      <div class="block_input_new_job">
      <label for="">Введите адрес</label>
      <input type="text" name="address_new_job" class="address_new_job" value="<? echo $_SESSION["add_new_job_addr"]; ?>" placeholder="Не обязательно" autocomplete="off">
    </div>
    <div class="block_load_img_new_job">

      <input class="input_load_img_new_job" type="file" multiple hidden value="" accept="image/jpeg,image/png,image/gif">
    </div>
    <div class="preload_images_new_job">
      <div class="btn_load_img_new_job drop_zone_new_job">
        <div class="btn_load_images_new_job" alt="" title="Загрузить изображения"><span>+</span></div>
      </div>
    </div>
    </div>
  </div>
  <div class="block_control_new_job">
    <input class="save_new_job" type="button" name="sub" value="Сохранить">
    <div class="cancel_new_job cancel_dialog">
      Отмена
    </div>
  </div>
</form>
</div>


<div class="dialog_new_job_VIP" hidden>
  <?
    if($_SESSION["error_add_job_vip"] != ""){
      echo "
      <script>var showNewJobVip = true;</script>
      <div class='error_add_job'>
      ".$_SESSION['error_add_job_vip']."
      </div>
      ";
    }
    else{
      echo "<script>var showNewJobVip = false;</script>";
    }
    unset($_SESSION["error_add_job_vip"]);
  ?>
  <form enctype="multipart/form-data" action="//jobs/forms/add_new_job_vip.php" method="post">
  <div class="block_category_and_price">
    <div class="category_new_job">
      <select class="select_category_new_job_vip" name="category_new_job_vip" required>
        <option value="" selected disabled>Категория</option>
        <?
          $stmt = $dbh->query("SELECT * FROM category");
          while($row = $stmt->fetch()){
            echo '
            <option img="//jobs/uploads/'.$row["img"].'" value="'.$row["id"].'">'.$row["name"].'</option>"
            ';
          }
        ?>
      </select>
    </div>
    <div class="price_new_job">
      <input class="input_price_new_job" type="number" name="price_new_job" placeholder="Введите цену..." value="<? echo $_SESSION["add_new_job_vip_price"]; ?>" min="0" required>
    </div>
  </div>

  <div class="block_content_new_job">
    <div class="block_img_new_job">
      <img class="img_new_job_vip" src="http://icons.iconarchive.com/icons/paomedia/small-n-flat/256/map-marker-icon.png" alt="">
    </div>
    <div class="content_new_job">
      <div class="block_input_new_job">
      <label for="">Введите название работы</label>
      <input type="text" name="title_new_job" maxlength="100" value="<? echo $_SESSION["add_new_job_vip_title"]; ?>" required autocomplete="off" placeholder="Название работы">
    </div>
      <div class="block_input_new_job">
      <label for="">Введите описание</label>
      <textarea name="description_new_job" rows="8" cols="80" placeholder="Опишите проблему" required><? echo $_SESSION["add_new_job_vip_description"]; ?></textarea>
    </div>
      <div class="block_input_new_job">
      <label for="">Введите адрес</label>
      <input type="text" name="address_new_job" value="<? echo $_SESSION["add_new_job_vip_addr"]; ?>" placeholder="Не обязательно">
    </div>
      <div class="block_input_new_job">
      <label for="">Выберите дату объявления</label>
      <input type="text" class="periodPicker" name="date_new_job" value="<? echo $_SESSION["add_new_job_vip_date"]; ?>" required autocomplete="off">
    </div>
    <div class="block_load_img_new_job">
      <div class="btn_load_img_new_job_VIP">
        Загрузить изображения
      </div>
      <input class="input_load_img_new_job_VIP" type="file" multiple hidden name="images_new_job[]" value="" accept="image/*">
    </div>
    <div class="preload_images_new_job_vip">

    </div>
    <div class="block_input_new_job">
    <span class="calculated_price_new_job_VIP" for="">Стоимость объявления составила: 100 рублей</span>
  </div>
    </div>
  </div>
  <div class="block_control_new_job">
    <input type="submit" name="sub" value="Сохранить" class="save_new_job_vip">
    <div class="cancel_new_job cancel_dialog">
      Отмена
    </div>
  </div>
  <?
  unset($_SESSION["add_new_job_vip_title"]);
  unset($_SESSION["add_new_job_vip_description"]);
  unset($_SESSION["add_new_job_vip_addr"]);
  unset($_SESSION["add_new_job_vip_price"]);
  unset($_SESSION["add_new_job_vip_date"]);
  ?>
</form>
</div>



<div class="dialog_reg" hidden>

  <form enctype="multipart/form-data" method="post" action="//jobs/forms/reg.php">
  <div class="">
    <span class="impotent_input">*</span> отмечены обязательные поля
  </div>
  <div class="error_reg">
    <?
    if(isset($_SESSION["error_reg"])){
      echo "<script>var showReg = true;</script>";
      echo $_SESSION["error_reg"];
      unset($_SESSION["error_reg"]);
    }
    else{
      echo "<script>var showReg = false;</script>";
    }
    ?>
  </div>
  <div class="block_input_reg">
    <label for="">Введите фамилию, имя <span class="impotent_input">*</span></label>
    <input type="text" name="name" value="<? echo $_SESSION["reg_name"]; ?>" placeholder="Иванов Иван" required>
  </div>
  <div class="block_input_reg">
    <label for="">Введите E-mail <span class="impotent_input">*</span></label>
    <input type="email" name="email" value="<? echo $_SESSION["reg_email"]; ?>" placeholder="example@domain.com" required>
  </div>
  <? /*
  <div class="block_input_reg">
    <label for="">Введите телефон <span class="impotent_input">*</span></label>
    <input type="text" name="phone" value="<? echo $_SESSION["reg_phone"]; ?>" class="input_phone_reg" placeholder="8(800) 800-8888" required>
  </div>
  */
  ?>
  <div class="block_input_reg">
    <label for="">Введите пароль <span class="impotent_input">*</span></label>
    <input type="password" class="reg_pass" name="pass" minlength="6" pattern="[\S]{0,}" value="<? echo $_SESSION["reg_pass"]; ?>" placeholder="Придумайте пароль" required>
  </div>
  <div class="block_input_reg">
    <label for="">Введите пароль повторно <span class="impotent_input">*</span></label>
    <input type="password" name="repeat_pass" minlength="6" class="reg_rPass" value="<? echo $_SESSION["reg_rPass"]; ?>" placeholder="Повторите пароль" required>
  </div>
  <div class="block_input_reg">
    <label for="">Введите профессию(ии)</label>
    <input type="text" name="professions" value="<? echo $_SESSION["reg_professions"]; ?>" placeholder="Надо сюда что-то придумать">
  </div>
  <div class="block_input_reg">
    <label for="">Загрузите фотографию</label>
    <input class="input_load_avatar_reg" type="file" hidden accept="image/*" name="avatar" value="">
    <span class="load_avatar_reg">Загрузить</span>
  </div>
  <div class="block_btn_reg">
    <input type="submit" name="sub" value="Сохранить" class="btn_save_reg">
    <div class="btn_cancel_reg cancel_dialog">
      Отмена
    </div>
  </div>
  <?
  unset($_SESSION["reg_name"]);
  unset($_SESSION["reg_email"]);
  unset($_SESSION["reg_phone"]);
  unset($_SESSION["reg_pass"]);
  unset($_SESSION["reg_rPass"]);
  unset($_SESSION["reg_professions"]);
  ?>
</form>
</div>

<div class="dialog_new_comment" hidden>
  <form action="//jobs/forms/new_comment.php" method="post">
  <div class="block_radio_new_comment">
  <div class="radio_new_comment">
    <input type="radio" id="radio_positive_new_comment" checked name="radio_new_comment" value="1">
    <label for="radio_positive_new_comment">Позитивный</label>
  </div>
  <div class="radio_new_comment">
    <input type="radio" id="radio_negative_new_comment" name="radio_new_comment" value="0">
    <label for="radio_negative_new_comment">Негативный</label>
  </div>
</div>
<div class="block_textarea_new_comment">
  <textarea name="text_comment" class="textarea_new_comment"></textarea>
</div>
<div class="block_btn_save_new_comment">
  <input type="submit" name="sub" value="Сохранить">
</div>
</form>
</div>

<div class="dialog_change_job" hidden>
<div class="error_change_job">

</div>
  <form class="form_new_job" action="//jobs/forms/change_job.php " enctype="multipart/form-data" method="post">
    <input type="hidden" name="id_job" value="<? echo $id_job; ?>">
  <div class="block_category_and_price">
    <div class="category_new_job">
      <div class="block_btn_category_change_job">
        <div class="btn_category_change_job">
          <span>Категория</span>
          <img class="btn_category_img btn_category_img_change_job" src="//jobs/images/strelka.png" alt="">
        </div>
        <div class="block_select_category_change_job hide_select_category">
          <?
            $stmt = $dbh->query("SELECT category.id as id, img, category.name as name, group_category.name as name_group FROM category INNER JOIN group_category ON category.id_group = group_category.id ORDER BY group_category.id");
            $group = "";
            while($row = $stmt->fetch()){
              if($group != $row["name_group"] && $group != "")
                echo '</div>';
              if($group != $row["name_group"]){
                $group = $row["name_group"];
                echo '<h3><a href="#">'.$group.'</a></h3><div class="accordion_height_change_job">';
              }
              $selected = "";
              if($id_category == $row["id"])
                $selected = "checked";
              echo '
              <input class="radio_change_job" '.$selected.' img="//jobs/uploads/'.$row["img"].'" '.$checked.' type="radio" id="category_change_job'.$row["id"].'" name="category_new_job" value="'.$row["id"].'">
              <label for="category_change_job'.$row["id"].'">'.$row["name"].'</label><br>
              ';
            }
            if($group != "")
              echo '</div>';
          ?>
        </div>
      </div>
    </div>
    <div class="price_new_job">
      <input class="input_price_change_job" type="number" name="price_change_job" placeholder="Введите цену..." value="<? echo $price; ?>"  min="0" required>
    </div>
  </div>

  <div class="block_content_new_job">
    <div class="block_img_new_job">
      <img class="img_change_job" src="//jobs/uploads/<? echo $category_img; ?>" alt="">
    </div>
    <div class="content_new_job">
      <div class="block_input_new_job">
      <label for="">Введите название работы</label>
      <input type="text" name="title_change_job" class="title_change_job" maxlength="100" value="<? echo $title; ?>" required placeholder="Название работы" autocomplete="off">
    </div>
      <div class="block_input_new_job">
      <label for="">Введите описание</label>
      <textarea name="description_change_job" class="description_change_job" rows="8" cols="80"  placeholder="Опишите проблему" required><? echo $description; ?></textarea>
    </div>
      <div class="block_input_new_job">
      <label for="">Введите адрес</label>
      <input type="text" name="address_change_job" class="address_change_job" value="<? echo $addr; ?>" placeholder="Не обязательно" autocomplete="off">
    </div>
    <div class="block_load_img_new_job">

      <input class="input_load_img_change_job" type="file" multiple hidden value="" accept="image/jpeg,image/png,image/gif">
    </div>
    <div class="preload_images_change_job">
      <div class="btn_load_img_change_job drop_zone_change_job">
        <div class="btn_load_images_change_job" alt="" title="Загрузить изображения"><span>+</span></div>
      </div>
    </div>
    </div>
  </div>
  <div class="block_control_new_job">
    <input class="save_change_job" type="button" name="sub" value="Сохранить">
    <div class="cancel_new_job cancel_dialog">
      Отмена
    </div>
  </div>
</form>
</div>


<div class="dialog_change_city" hidden>
  <div class="block_search_city">
    <input type="text" class="input_serach_city" name="" value="" placeholder="Из какого вы города?">
  </div>
  <div class='block_list_city'>
  <?
  $stmt = $dbh->query("SELECT id, name FROM city ORDER BY name");
  while($row = $stmt->fetch()){
    $first_letter = mb_substr($row["name"], 0, 1);
    if($letter != $first_letter){
      $letter = $first_letter;
      $city_latter = "<div class='latter' letter='$letter'>$letter</div>";
    }
    else {
      $city_latter = "";
    }
    echo $city_latter.'<div id_city='.$row["id"].' class="select_city">'.$row["name"].'</div>';
  }
  ?>
</div>
</div>
