$(document).ready(function(){
  $(".btn_open_map").click(function(){
    cluster.clearLayers();
    setTimeout(function(){
      cluster.addLayer(geoJsonLayer);        // добавляем в кластер слой данных
      cluster.addTo(map1);
    }, 3000);
  });
  $(".dialog_new_job").dialog({show: "clip", hide: "clip", modal: true, autoOpen : false, title: "Новое объявление +", resizable: false, width: 750, closeText: "Закрыть"});
  $(".give_ad").click(function(){
    if(!_auth){
      $(".dialog_reg").dialog("open");
      return;
    }
    $(".dialog_new_job").dialog("open");
  });
  $(".cancel_dialog").click(function(){
    $(".dialog_new_job").dialog("close");
    $(".dialog_new_job_VIP").dialog("close");
    $(".dialog_reg").dialog("close");
    $(".dialog_change_job").dialog("close");
    $(".dialog_new_comment").dialog("close");
    $(".dialog_change_city").dialog("close");
  });
  $(".dialog_reg").dialog({show: "clip", hide: "clip", modal: true, autoOpen : false, title: "Регистрация", resizable: false, width: 750, closeText: "Закрыть"});
  $(".link_new_account").click(function(){
    $(".block_auth").slideToggle("slow");
    $(".dialog_reg").dialog("open");
  });

  $(".dialog_new_comment").dialog({show: "clip", hide: "clip", modal: true, autoOpen : false, title: "Новый отзыв", resizable: false, width: 750, closeText: "Закрыть"});
  $(".btn_new_comment").click(function(){
    $(".dialog_new_comment").dialog("open");
  });

  $(".dialog_new_job_VIP").dialog({show: "clip", hide: "clip", modal: true, autoOpen : false, title: "Новое объявление VIP", resizable: false, width: 750, closeText: "Закрыть"});
  $(".corp").click(function(){
    if(!_auth){
      $(".dialog_reg").dialog("open");
      return;
    }
    $(".dialog_new_job_VIP").dialog("open");
  });

  $(".dialog_change_job").dialog({show: "clip", hide: "clip", modal: true, autoOpen : false, title: "Изменение объявления", resizable: false, width: 750, closeText: "Закрыть"});
  $(".btn_change_job").click(function(){
    $(".dialog_change_job").dialog("open");
  });

  $(".dialog_change_city").dialog({show: "clip", hide: "clip", modal: true, autoOpen : false, title: "Выбор города", resizable: false, width: 750, closeText: "Закрыть"});
  $(".city").click(function(){
    $(".dialog_change_city").dialog("open");
  });

  $(".btn_load_images_new_job").click(function(){
    $('.input_load_img_new_job').click();

  });


  $(".btn_load_images_change_job").click(function(){
    $('.input_load_img_change_job').click();
  });

  $(".btn_load_avatar_lk").click(function(){
    $(".input_load_avatar_lk").click();
  });



  $(".input_load_img_new_job_VIP").change(function(){
  $('.preload_images_new_job_vip').empty();
  $.each(this.files, function(i, j){
      var fr = new FileReader();
      fr.onload = function(e){
        $(".preload_images_new_job_vip").append("<img src='" + e.target.result + "' style='width: 100px;' />");
        fr.abort();
      };
      if (j.type.match('image.*'))
        fr.readAsDataURL(j);
  });
});

  $(".btn_load_img_new_job_VIP").click(function(){
    $('.input_load_img_new_job_VIP').click();
  });

  $.ajax({
    type: "POST",
    url: "//jobs/ajax/load_date_jobs_vip.php",
    data: "",
    dataType: "html",
    cache: false,
    success: function(data) {
      setPeriodPicker(JSON.parse(data));
    }
  });

  function setPeriodPicker(arr_date){
    $('.periodPicker').daterangepicker({
      opens: "left",
      drops: "up",
      minDate: new Date(),
      isInvalidDate : function(e){
        if(!Array.isArray(arr_date))
          return false;
        var de = new Date(e);
        var d = Date.parse(de.getFullYear() + "-" + (de.getMonth() + 1) + "-" + de.getDate());
        var date = new Date();
        var count = 0;
        for(var i = 0; i < arr_date.length; i++){
          var job_dateS = Date.parse(arr_date[i]["date_start"]);
          var job_dateE = Date.parse(arr_date[i]["date_end"]);
          if(job_dateS <= d && job_dateE >= d)
            count++;
        }
        if(count >= 5)
          return true;
        return false;
      },
      locale: {
        format: 'DD.MM.YYYY',
        cancelLabel: 'Закрыть',
        applyLabel: 'Сохранить',
        daysOfWeek: [
              "Пн",
              "Вт",
              "Ср",
              "Чт",
              "Пт",
              "Сб",
              "Вс"
          ],
          monthNames: [
              "Январь",
              "Февраль",
              "Март",
              "Апрель",
              "Май",
              "Июнь",
              "Июль",
              "Август",
              "Сентябрь",
              "Октябрь",
              "Ноябрь",
              "Декабрь"
          ],
      }
    }, function(start, end){
      var period = Math.ceil(Math.abs(end - start) / (1000 * 3600 * 24));
      $(".calculated_price_new_job_VIP").html("Стоимость объявления составила: " + period * 100 + " руб.");
    });
  }



  setNumber();
 function setNumber(){
   var number_img = 0;
   $(".block_nav_images img").each(function(){
      $(this).attr("number", number_img);
      number_img++;
   });
 }


$(".block_nav_images img").click(function(){
  if($(this).hasClass("selected_img"))
    return;
  var time = 300;
  var number = $(this).attr("number");
  var width = $(this).width();
  var src = $(this).attr("src");
  $(".selected_img").removeClass("selected_img");
  $(this).addClass("selected_img");
  //$(".active_img img").fadeOut(time, function(){
    //$(this).attr("src", src).fadeIn(time);
//  });
$(".active_img img").effect("drop", time, function(){
  $(this).attr("src", src).effect("drop", {direction: "right", mode : "show"}, time);
});
  $(".block_nav_images").animate({scrollLeft: number * width - width + (number * 4)});
});

$(".btn_auth").click(function(){//TODO потом сменить на кнопку войти
  $(".block_auth").slideToggle("slow");
});
$(".btn_chats").click(function(){
  $(".block_chats_header").slideToggle("slow");
});

$(".load_avatar_reg").click(function(){
  $(".input_load_avatar_reg").click();
});

$(".restore_password_auth").click(function(){
  $(".auth_container").fadeOut("fast", function(){
    $(".restore_auth_container").fadeIn("slow");
  });
});

$(".btn_back_auth").click(function(){
  $(".restore_auth_container").fadeOut("fast", function(){
    $(".auth_container").fadeIn("slow");
  });
});

$('.radio_new_job').change(function() {
    var src = $(this).attr("img");
    $(".img_new_job").attr("src", src);
    $(".block_select_category_new_job").slideUp();
    $(".btn_category_new_job span").html($(this).next().html());
    $(".btn_category_img_new_job").toggleClass("scale_category");
});


$(".select_category_new_job_vip").change(function(){
  $(".img_new_job_vip").attr("src", $(this).find(":selected").attr("img"));
});

  setChangeCategory();
 function setChangeCategory(){
   $(".btn_category_change_job span").html($(".radio_change_job:checked").next().html());
 }
 $('.radio_change_job').change();
$('.radio_change_job').change(function() {
    var src = $(this).attr("img");
    $(".img_change_job").attr("src", src);
    $(".block_select_category_change_job").slideUp();
    $(".btn_category_change_job span").html($(this).next().html());
    $(".btn_category_img_change_job").toggleClass("scale_category");
});


  function preloadImages(key, j){
        var fr = new FileReader();
        fr.onload = function(e){
          $(".btn_load_images_new_job").after("<div class='block_prepend_img'><img src='//jobs/images/del.png' key=" + key + " class='del_prepand_img' title='Не прикреплять'/><img class='prepand_img' src='" + e.target.result + "' /></div>");
          fr.abort();
        };
        if (j.type.match('image.*'))
          fr.readAsDataURL(j);
  }

var new_job_data = new FormData();
var keyFile = 0;
$('.input_load_img_new_job').change(function(){
  var files = this.files;
  $.each( files, function( key, value ){
    new_job_data.append( keyFile, value );
    setTimeout(function(){
      preloadImages(keyFile, value);
    });

    keyFile++;
  });
});
$(".btn_load_img_new_job").on("click", ".del_prepand_img", function(){
  var key = $(this).attr("key");
  new_job_data.delete(key);
  $(this).parent().remove();
});
$(".save_new_job").click(function(){
    new_job_data.append("title_new_job", $(".title_new_job").val());
    new_job_data.append("description_new_job", $(".description_new_job").val());
    new_job_data.append("address_new_job", $(".address_new_job").val());
    new_job_data.append("price_new_job", $(".input_price_new_job").val());
    new_job_data.append("category_new_job", $("input[name=category_new_job]:checked").val());
    $.ajax({
      type: "POST",
      url: "//jobs/ajax/add_new_job.php",
      processData: false,
      contentType: false,
      data: new_job_data,
      dataType: "html",
      cache: false,
      success: function(data) {
        var res = JSON.parse(data);
        if(res["code"] == 0){
          location.href = "//jobs/job/" + res["id"];
        }
        else{
          $(".error_add_new_job").html(res["error"]);
        }
      }
    });
});



var drop_zone = $(".drop_zone_new_job");
drop_zone[0].ondragover = function(e){
  e.preventDefault();
}
drop_zone[0].ondrop = function(e){
  e.preventDefault();
  var files = e.dataTransfer.files;
  $.each( files, function( key, value ){
    new_job_data.append( keyFile, value );
    setTimeout(function(){
      preloadImages(keyFile, value);
    }, 0);

    keyFile++;
  });
}

////

  function preloadImagesChange(key, j){
        var fr = new FileReader();
        fr.onload = function(e){
          $(".btn_load_images_change_job").after("<div class='block_prepend_img'><img src='//jobs/images/del.png' key=" + key + " class='del_prepand_img_change' title='Не прикреплять'/><img class='prepand_img' src='" + e.target.result + "' /></div>");
          fr.abort();
        };
        if (j.type.match('image.*'))
          fr.readAsDataURL(j);
  }

var change_job_data = new FormData();
var keyFileChange = 0;
$('.input_load_img_change_job').change(function(){
  var files = this.files;
  $.each( files, function( key, value ){
    if(value.type.match(/image.*/)){
      change_job_data.append( keyFileChange, value );
      setTimeout(function(){
        preloadImagesChange(keyFileChange, value);
      }, 0);
      keyFileChange++;
      }
  });
});
$(".btn_load_img_change_job").on("click", ".del_prepand_img_change", function(){
  var key = $(this).attr("key");
  change_job_data.delete(key);
  $(this).parent().remove();
});

$(".save_change_job").click(function(){
    change_job_data.append("title_change_job", $(".title_change_job").val());
    change_job_data.append("description_change_job", $(".description_change_job").val());
    change_job_data.append("address_change_job", $(".address_change_job").val());
    change_job_data.append("price_change_job", $(".input_price_change_job").val());
    change_job_data.append("category_change_job", $("input[name=category_change_job]:checked").val());
    $.ajax({
      type: "POST",
      url: "//jobs/ajax/change_job.php",
      processData: false,
      contentType: false,
      data: change_job_data,
      dataType: "html",
      cache: false,
      success: function(data) {
        var res = JSON.parse(data);
        if(res["code"] == 0){
          location.reload();
        }
        else{
          $(".error_change_job").html(res["error"]);
        }
      }
    });
});

var drop_zone_change = $(".drop_zone_change_job");
drop_zone_change[0].ondragover = function(e){
  e.preventDefault();
}
drop_zone_change[0].ondrop = function(e){
  e.preventDefault();
  var files = e.dataTransfer.files;
  $.each( files, function( key, value ){
    change_job_data.append( keyFile, value );
    setTimeout(function(){
      preloadImagesChange(keyFile, value);
    }, 0);

    keyFileChange++;
  });
}


$(".input_phone_reg, .input_phone_lk").mask("8(999) 999-9999");

  showDialogs();


  function showDialogs(){
    if(showReg){
      $(".dialog_reg").dialog("open");
    }

    if(showNewJobVip){
      $(".dialog_new_job_VIP").dialog("open");
    }
  }

  $(".btn_auth_form").click(function(){
    auth();
  });

  $('.input_email_auth, .input_pass_auth').keyup(function(){
    if(event.keyCode==13)
       {
          auth();
       }
})

  function auth(){
    var remember = 0;
    if($("#remember_me").prop("checked"))
      remember = 1;
    $.ajax({
      type: "POST",
      url: "//jobs/ajax/auth.php",
      data: "email=" + $(".input_email_auth").val() + "&pass=" + $(".input_pass_auth").val() + "&remember=" + remember,
      dataType: "html",
      cache: false,
      success: function(data) {
        if(data == 1)
          location.reload();
        else if(data === "0")
          $(".error_auth").html("Необходимо подтвердить E-mail");
        else
          $(".error_auth").html("Неверный E-mail или пароль");
        $(".error_auth").slideDown();
      }
    });
  }

  $(".btn_accept_job").click(function(){
    if(_auth == false){

      $(".dialog_reg").dialog("open");
      return;
    }
    $.ajax({
      type: "POST",
      url: "//jobs/ajax/accept_job.php",
      data: "",
      dataType: "html",
      cache: false,
      success: function(data) {
        if(data == 1)
          location.reload();
      }
    });
  });

  $(".btn_leave_job").click(function(){
    $.ajax({
      type: "POST",
      url: "//jobs/ajax/leave_job.php",
      data: "",
      dataType: "html",
      cache: false,
      success: function(data) {
          location.reload();
      }
    });
  });

  $(".del_worker").click(function(){
    var id = $(this).attr("id_worker");
    var worker = $(this);
    $.ajax({
      type: "POST",
      url: "//jobs/ajax/del_worker.php",
      data: "id_worker=" + id,
      dataType: "html",
      cache: false,
      success: function(data) {
          $(worker).parent().remove();
      }
    });
  });

  $(".accept_worker").click(function(){
    var id = $(this).attr("id_worker");
    $.ajax({
      type: "POST",
      url: "//jobs/ajax/accept_worker.php",
      data: "id_worker=" + id,
      dataType: "html",
      cache: false,
      success: function(data) {
          location.reload();
      }
    });
  });

  $(".btn_del_job").click(function(){
    $.ajax({
      type: "POST",
      url: "//jobs/ajax/del_job.php",
      data: "",
      dataType: "html",
      cache: false,
      success: function(data) {
        if(data == 1)
          location.href = "/";
      }
    });
  });

  $(".btn_close_job").click(function(){
    $.ajax({
      type: "POST",
      url: "//jobs/ajax/close_job.php",
      data: "",
      dataType: "html",
      cache: false,
      success: function(data) {
        if(data == 1)
          location.href = "/";
      }
    });
  });

  $(".select_city").click(function(){
    var id = $(this).attr("id_city");
    $.ajax({
      type: "POST",
      url: "//jobs/ajax/change_city.php",
      data: "id_city=" + id,
      dataType: "html",
      cache: false,
      success: function(data) {
        if(data == 1)
          location.reload();
      }
    });
  });

  $(".btn_all_my_comments, .btn_positive_my_comments, .btn_negative_my_comments").click(function(){

    positive = $(this).attr("positive");
    switch(positive){
      case "-1":
        $(".active_btn_filter_comments_lk_green").removeClass("active_btn_filter_comments_lk_green");
        $(".active_btn_filter_comments_lk_red").removeClass("active_btn_filter_comments_lk_red");
        $(this).addClass("active_btn_filter_comments_lk_all");
        break;
      case "0":
        $(".active_btn_filter_comments_lk_all").removeClass("active_btn_filter_comments_lk_all");
        $(".active_btn_filter_comments_lk_red").removeClass("active_btn_filter_comments_lk_red");
        $(this).addClass("active_btn_filter_comments_lk_green");
        break;
      case "1":
        $(".active_btn_filter_comments_lk_all").removeClass("active_btn_filter_comments_lk_all");
        $(".active_btn_filter_comments_lk_green").removeClass("active_btn_filter_comments_lk_green");
        $(this).addClass("active_btn_filter_comments_lk_red");
        break;
    }
    filter_comments();
  });


  $(".btn_accepts_my_jobs").click(function(){
    $(".active_btn_filter_jobs_lk").removeClass("active_btn_filter_jobs_lk");
    $(this).addClass("active_btn_filter_jobs_lk");
    filter_profile.accept = 1;
    filter_profile.create = 0;
    applyFilter();
  });

  $(".btn_exposed_my_jobs").click(function(){
    $(".active_btn_filter_jobs_lk").removeClass("active_btn_filter_jobs_lk");
    $(this).addClass("active_btn_filter_jobs_lk");
    filter_profile.create = 1;
    filter_profile.accept = 0;
    applyFilter();
  });

  $(".select_filter_category_lk").change(function(){
    filter_profile.category = $(this).val();
    $(".block_select_category_my_job").slideUp();
    applyFilter();
  });

  $(".filter_status_lk").change(function(){
    filter_profile.status = $(this).val();
    applyFilter();
  });

  $(".clear_filter_lk").click(function(){
    filter_profile = {
      accept: 0,
      create: 0,
      status: 0,
      category: 0
    };
    $(".filter_status_lk").val(0);
    $(".select_filter_category_lk").prop("checked", false);
    $(".btn_category_my_job span").html("Категория");
    $(".active_btn_filter_jobs_lk").removeClass("active_btn_filter_jobs_lk");
    applyFilter();
  });

  $(".btn_send_restore").click(function(){
    var email = $(".input_restore_pass").val();
    $.ajax({
      type: "POST",
      url: "//jobs/ajax/restore.php",
      data: "email=" + email,
      dataType: "html",
      cache: false,
      success: function(data) {
        $(".msg_send_restore").slideDown("slow");

      }
    });
  });

  $(".input_serach_city").keyup(function(){
    var val = $(this).val().toLowerCase();
    $(".select_city").each(function(){
      var name = $(this).html().toLowerCase();
      var re = new RegExp(".*" + val + ".*");
      if(!re.test(name)){
        $(this).css("display", "none");
        $(this).attr("hide", "1");
      }
      else{
        $(this).css("display", "inline-block");
        $(this).attr("hide", "0");
      }
    });

    $(".latter").css("display", "none");
    $(".select_city").each(function(){
      if($(this).attr("hide") == "1")
        return;
      var letter = $(this).html()[0];
      $(".latter[letter=" + letter + "]").css("display", "block");
    });

  });

  $(".btn_back").click(function(){
    history.back();
  });

  chatNorify();
  var audio = new Audio("//jobs/audio/new_message.wav");
  function chatNorify(){
    if(arr_msg == -1)
      return;
    for(var i = 0; i < arr_msg.length; i++){
      var refMsg = firebase.database().ref('messages/' + arr_msg[i]);
      refMsg.orderByChild("id_job").startAt(arr_msg[i]).on("child_added", function(data){
        if(data.val().reed == 0 && open_job != data.val().id_job && data.val().id_author != my_id){
          var count = parseInt($(".count_id_job" + data.val().id_job).html());
          if(count == "")
            count = 0;
          $(".count_id_job" + data.val().id_job).html((count + 1));
          $(".count_id_job" + data.val().id_job).css("background", "rgb(255, 155, 0)");
          $(".notify_msg").css("display", "inline-block");
          audio.play();
        }

      });
    }

  }


  $(".btn_category_filter").click(function(){
    $(".block_select_category").slideToggle();
    $(".block_select_category").accordion({autoHeight:true,collapsible:true});
    $(".btn_category_img_index").toggleClass("scale_category");
    $(".block_select_category").attr("tabindex", -1).focus();
  });

  $(".btn_category_new_job").click(function(){
    $(".block_select_category_new_job").slideToggle();
    $(".block_select_category_new_job").accordion({autoHeight:true,collapsible:true});
    $(".btn_category_img_new_job").toggleClass("scale_category");
  });

  $(".btn_category_change_job").click(function(){
    $(".block_select_category_change_job").slideToggle();
    $(".block_select_category_change_job").accordion({autoHeight:true,collapsible:true});
    $(".btn_category_img_change_job").toggleClass("scale_category");
  });

  $(".btn_category_my_job").click(function(){
    $(".block_select_category_my_job").slideToggle();
    $(".block_select_category_my_job").accordion({autoHeight:true,collapsible:true});
    $(".btn_category_img_lk").toggleClass("scale_category");
  });

  $(".select_filter_category_lk").change(function(){
    $(".btn_category_my_job span").html($(this).next().html());
    $(".btn_category_img_lk").toggleClass("scale_category");
  });

  $(".block_select_category").focusout(function(){
    $(".block_select_category").slideUp();
  });
  /////////////////////////////////////////////////////////////////////////////////////////////////////
});

var positive = -1;
function filter_comments(){
  var countShow = 0;
  $(".my_comment").each(function(){
    if($(this).attr("positive") == positive)
      $(this).css("display", "none");
    else{
      $(this).css("display", "flex");
      countShow++;
    }

  });
  if(countShow < 5)
    loadComments();
}



var filter_profile = {
  accept: 0,
  create: 0,
  status: 0,
  category: 0
};
function applyFilter(){
  $(".my_job").each(function(){
    var hidden = false;
    if(filter_profile.status != 0){
      if(filter_profile.status == $(this).attr("status"))
        $(this).css("display", "block");
      else{
        $(this).css("display", "none");
        hidden = true;
      }

    }

    if(filter_profile.category != 0){
      if(filter_profile.category == $(this).attr("category") && !hidden)
        $(this).css("display", "block");
      else
        $(this).css("display", "none");
    }


    if(filter_profile.accept != 0){
      if(filter_profile.accept == $(this).attr("accept")){
        $(this).css("display", "block");
        if(filter_profile.status != 0){
          if(filter_profile.status == $(this).attr("status"))
            $(this).css("display", "block");
          else
            $(this).css("display", "none");
        }

        if(filter_profile.category != 0){
          if(filter_profile.category == $(this).attr("category"))
            $(this).css("display", "block");
          else
            $(this).css("display", "none");
        }
      }

      else
        $(this).css("display", "none");
    }

    if(filter_profile.create != 0){
      if(filter_profile.create == $(this).attr("create")){
        $(this).css("display", "block");
        if(filter_profile.status != 0){
          if(filter_profile.status == $(this).attr("status"))
            $(this).css("display", "block");
          else
            $(this).css("display", "none");
        }

        if(filter_profile.category != 0){
          if(filter_profile.category == $(this).attr("category"))
            $(this).css("display", "block");
          else
            $(this).css("display", "none");
        }
      }
      else
        $(this).css("display", "none");
    }

    if(filter_profile.status == 0 && filter_profile.category == 0 && filter_profile.create == 0 && filter_profile.accept == 0)
      $(this).css("display", "block");
  });
}
