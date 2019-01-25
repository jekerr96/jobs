<?
  session_start();
  include '../include/db_connect.php';
  $limit = (int)$_POST["count"];
  $filter = $_SESSION["filter_jobs"];
  $id_city = $_SESSION["city_id"];
  $stmt = $dbh->query("SELECT jobs.id as id, title, description, price, users.name as name, category.img as img, jobs.lat as lat, jobs.lon as lon FROM jobs INNER JOIN users ON jobs.id_user = users.id INNER JOIN category ON jobs.id_category = category.id WHERE vip = 0 AND jobs.id_city = $id_city $filter AND id_status = 2 ORDER BY jobs.id DESC LIMIT $limit, 20");
  while($row = $stmt->fetch()){
    $name = split(" ", $row["name"]);
    $name = $name[1]." ".mb_substr($name[0], 0, 1).".";
    if(mb_strlen($row["title"]) > 40)
      $title = mb_substr($row["title"], 0, 40)."...";
    else
      $title = $row["title"];
    if(mb_strlen($row["description"]) > 160)
      $description = mb_substr($row["description"], 0, 160)."...";
    else
      $description = $row["description"];
    echo '
    <div class="job load_jobs" >
    <a href="http://jobs/job/'.$row["id"].'" class="link_job">
      <div class="head_job">
        <div class="block_img_job">
          <img src="//jobs/uploads/'.$row["img"].'" class="img_job" alt="">
        </div>
        <div class="name_job" title="'.$row["title"].'">
          '.$title.'
        </div>
      </div>
      <div class="text_job">
        '.$description.'
      </div>
      <div class="bottom_job">
        <div class="login_job">
          '.$name.'
        </div>
        <div class="price_job">
          '.$row["price"].' р.
        </div>
      </div>
      </a>
    </div>
    ';
  }
?>
