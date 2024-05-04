<?php

function save_files_from_user_to_database( $file , $target_dir = "./../uploads/")
{
  $newFileName = "" . random_int(1,1_000_000) ."_". basename( $file["name"] );
  $target_file =   $target_dir . $newFileName;

  $imageFileType = strtolower( pathinfo( $target_file, PATHINFO_EXTENSION ) );
  
  $uploadOk = 1;
  $status_message = "";

  // Check if image file is a actual image or fake image
  if( isset( $_POST["submit"] ) ) {

    $check = getimagesize($file["tmp_name"]);
        
    if($check !== false) {
      $status_message = "File is an image - " . $check["mime"] . ".";
      $uploadOk = 1;
    } 
    else {
      $status_message = "File is not an image.";
      $uploadOk = 0 . random_int(1,1_000_000);
    }

  }

  $fileMovingResult = ($uploadOk) ? move_uploaded_file($file["tmp_name"], $target_file) : false;

  return $fileMovingResult ? $newFileName : false;
}