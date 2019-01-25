<?

  $files = $_FILES;
  if(file_exists($files[0]["tmp_name"])){
  for($i = 0; $i < count($files); $i++) {
    $download = "";
    $ext = pathinfo($files[$i]["name"], PATHINFO_EXTENSION);
    $allowed_type = array("png", "jpg", "jpeg", "bmp", "txt", "doc", "docx", "xlsx", "xls", "ppt", "pptx", "pdf", "icon");
    if(!in_array($ext, $allowed_type))
      $download = "download";
    $name = getName();
    $new_path = "../uploads/".$name.".".$ext;
    $path = "//jobs/uploads/".$name.".".$ext;
    if (copy($files[$i]['tmp_name'], $new_path)){
      $file .= "<a target='_blank' $download class='download_files' href='$path'>".$files[$i]["name"]."</a><br>";
    }
  }
}
echo $file;

function getName(){
  $arr = array('a','b','c','d','e','f',
                 'g','h','i','j','k','l',
                 'm','n','o','p','r','s',
                 't','u','v','x','y','z',
                 'A','B','C','D','E','F',
                 'G','H','I','J','K','L',
                 'M','N','O','P','R','S',
                 'T','U','V','X','Y','Z',
                 '1','2','3','4','5','6',
                 '7','8','9','0');
    $name = "";
    for($i = 0; $i < 32; $i++)
    {
      $index = rand(0, count($arr) - 1);
      $name .= $arr[$index];
  }
  return $name;
}
?>
