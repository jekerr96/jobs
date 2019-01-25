<?
  session_start();
  include '../include/db_connect.php';
  $limit = (int)$_POST["count"];
  $id_profile = $_SESSION["open_profile"];

  $stmt = $dbh->query("SELECT users.id as id, avatar, name, comment, comments.positive as positive, users.positive as posCom, users.negative as negCom FROM comments INNER JOIN users ON comments.id_author = users.id WHERE id_user = $id_profile ORDER BY comments.id DESC LIMIT $limit, 6");
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
