$(document).ready(function(){
  $(".btn_load_chat").click(function(){
    $(".input_load_files_chat").click();
  });

    var link_files = "";
    $(".input_load_files_chat").change(function(){
      $(".block_name_files").empty();
      var name_file = [];
      for(var i = 0; i < $(this).get(0).files.length; ++i) {
        name_file.push($(this).get(0).files[i].name);
      }
      $(".block_name_files").text(name_file.join(", "));

      var files;
      files = this.files;
      if(files != null){
        var data = new FormData();
        $.each( files, function( key, value ){
          data.append( key, value );
        });

        $.ajax({
          url: '//jobs/ajax/load_files.php',
          type: 'POST',
          data: data,
          cache: false,
          dataType: 'html',
          processData: false, // Не обрабатываем файлы (Don't process the files)
          contentType: false, // Так jQuery скажет серверу что это строковой запрос
          success: function( data ){
             link_files = data;
          }
  });

      }
      else link_files = "";

    });

    const id_job = $(".get_param").attr("get");
    const my_id = $(".get_param").attr("my_id");
    const name_msg = $(".get_param").attr("name");
    var now_msg = new Date();




    $(".block_write").keydown(function(e){
      if(e.keyCode == 13){
        sendMsg();
        return false;
      }
    });
    $(".btn_send").click(function(){
      sendMsg();
    });
    var refMsg = firebase.database().ref('messages/' + id_job);
    refMsg.on("child_added", function(data){
      var msg = data.val().msg;
      var author = data.val().author;
      var name_msg = data.val().name_author;
      var date = data.val().date_send;
      if(author!=my_id){
        var updates = {};
        updates["messages/" + id_job + "/" + data.key + "/reed"] = 1;
        setTimeout(function(){
          firebase.database().ref().update(updates);
        }, 0);
      }

      var who_class = "my_msg";
      if(author != my_id){
        who_class = "not_my_msg";

        if(date > now_msg)
        audio.play();
      }

        date=new Date (date);
        now = new Date();
        if(now.getDate() == date.getDate() && now.getMonth() == date.getMonth() && now.getFullYear() == date.getFullYear())
          date = date.getHours() + ":" + (date.getMinutes() < 10 ? ( "0" + date.getMinutes()) : date.getMinutes());
        else
          date = date.getDate() + "." + (date.getMonth() < 10 ? "0" + date.getMonth() : date.getMonth()) + "." + date.getFullYear() + " " + date.getHours() + ":" + (date.getMinutes() < 10 ? ( "0" + date.getMinutes()) : date.getMinutes());
      $(".block_chat").append('<div class="block_msg ' + who_class + '"><div class="msg_container"><div class="head_msg">' + name_msg + " " + date + '</div><div class="msg">' + msg + '</div></div></div>');

      var div = $(".block_chat");
      div.scrollTop(div.prop('scrollHeight'));
    });

    function sendMsg(){
      if($(".block_write").html() != "" || link_files != ""){
        firebase.database().ref('messages/' + id_job).push().set({
          msg : $(".block_write").html() + link_files,
          author : my_id,
          name_author: name_msg,
          date_send: firebase.database.ServerValue.TIMESTAMP,
          id_job: id_job,
          reed : 0
          });
        $(".block_write").html("");
        $(".block_name_files").html("");
        link_files = "";
      }
    }
});
