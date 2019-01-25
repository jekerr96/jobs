<?
  session_start();
  include '../include/db_connect.php';
  $limit = (int)$_POST["count"];
  $id_profile = $_SESSION["open_profile"];

  $stmt = $dbh->query("SELECT jobs.id as id, title, date_add, price, color, category.name as category, category.img as img, id_status, status_name, id_user, id_worker, id_category FROM jobs INNER JOIN category ON jobs.id_category = category.id INNER JOIN status ON id_status = status.id WHERE id_user = $id_profile OR id_worker = $id_profile ORDER BY jobs.id DESC LIMIT $limit, 8");
  while($row = $stmt->fetch()){
    $date = date("d.m.Y", strtotime($row["date_add"]));

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
