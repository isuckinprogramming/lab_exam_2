
<?php

require_once('dbOperations.php');
require_once('upload_user_pfp.php');

function add_user_entry($file, $name, $table="user")
{
  $profile_picture_upload_result = save_files_from_user_to_database($file);

  if( !$profile_picture_upload_result ){
    // Invalid add new user process

    return;
  }

  $safe_picture = DataBaseOperations::convert_to_mysqli_safe_string($profile_picture_upload_result);
  $safe_name = DataBaseOperations::convert_to_mysqli_safe_string($name);

  $generated_primary_id = rand(1,900_000) + 100;


  $sql = "INSERT INTO user(id,name, picture) VALUES( '".$generated_primary_id ."','" . $safe_name ."','".$safe_picture."');";

  $queryResult = DataBaseOperations::execute_and_return_error_msg($sql);

  if($queryResult["status"]){
    $queryResult["recent_changed_entry_id"] = $generated_primary_id;
  }

  return $queryResult;
}

// No entry validations in place yet

$add_new_user_result = add_user_entry(
  $_FILES["add-user-picture"],
  $_POST["add-user-name"]
);

echo json_encode($add_new_user_result);
?>