$(document).ready(function(){

  $(".block_list_my_jobs").scroll(function(){
     if ($(".block_list_my_jobs")[0].scrollHeight - $(".block_list_my_jobs").scrollTop() - 400 <= 20){
       loadJobs();
     }
});

  $(".block_content_my_comments").scroll(function(){
     if ($(".block_content_my_comments")[0].scrollHeight - $(".block_content_my_comments").scrollTop() - 440 <= 20){
       loadComments();
     }
});


});
var loadCountComments = 0;
var blockloadComments = true;
function loadComments(){
  if(blockloadComments){
    blockloadComments = false;
    loadCountComments += 6;
    $.ajax({
      type: "POST",
      url: "//jobs/ajax/load_comments_lk.php",
      data: "count=" + loadCountComments,
      dataType: "html",
      cache: false,
      success: function(data) {
        blockloadComments = true;
        $(".block_content_my_comments").append(data);
        filter_comments();
      },
      error: function(data){
        loadCountComments -= 6;
        blockloadComments = true;
      }
    });
  }

}

var loadCountJobs = 0;
var blockLoadJobs = true;
function loadJobs(){
  if(blockLoadJobs){
    blockLoadJobs = false;
    loadCountJobs += 8;
    $.ajax({
      type: "POST",
      url: "//jobs/ajax/load_jobs_lk.php",
      data: "count=" + loadCountJobs,
      dataType: "html",
      cache: false,
      success: function(data) {
        blockLoadJobs = true;
        $(".block_list_my_jobs").append(data);
        applyFilter();
      },
      error: function(data){
        loadCountJobs -= 8;
        blockLoadJobs = true;
      }
    });
  }
}
