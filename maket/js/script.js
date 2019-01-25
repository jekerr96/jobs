$(document).ready(function(){
  $(".add_div").click(function(){
      var scroll = document.body.scrollTop;
      var scrollLeft = document.body.scrollLeft;
    $("body").append("<div contenteditable='true' class='append_div' style='top:" + scroll + "px; left:" + scrollLeft + "'><img class='del' src='images/del.png'/></div>");
    $(".append_div").draggable();
    $(".append_div").resizable();
    //$(".append_div").css("position", "fixed");
  });
  $(".append_div").children(".ui-resizable-handle, .ui-resizable-e").remove();
  $(".append_div").draggable();
  $(".append_div").resizable();
  $(".append_div").css("position", "absolute");

    $("body").on("click", ".append_div", function(){
       $(this).css("position", "absolute");
    });

  $(".save").click(function(){
    var base64 = encodeURIComponent($("*").html());
    $.ajax({
      type: "POST",
      url: "save.php",
      data: "txt=" + base64,
      dataType: "html",
      cache: false,
      success: function(data) {

      }
    });
  });

  $("body").on("click", ".del", function(){
    $(this).parent().remove();
  })
});
