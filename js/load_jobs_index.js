$(document).ready(function(){
  var loadCount = 0;
  var load = true;
  if($(document).height() == $(window).height())
    loadJobs();

  $(window).scroll(function(){
     if ($(document).height() - $(window).scrollTop() - $(window).height() <= 70){
       loadJobs();
     }
});

  function loadJobs(){
    if(load){
      load = false;
      loadCount += 20;
      $.ajax({
        type: "POST",
        url: "//jobs/ajax/load_jobs.php",
        data: "count=" + loadCount,
        dataType: "html",
        cache: false,
        success: function(data) {
          load = true;
          $(".block_prepend").before(data);
          $(".load_jobs").effect("drop", {direction : "right", mode: "show"}, 1000, function(){
            $(this).removeClass("load_jobs");
          });
          if($(document).height() == $(window).height())
            loadJobs();
        },
        error : function(data){
          loadCount -= 20;
          load = true;
        }
      });
    }

  }
});
