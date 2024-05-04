<?php
require_once('dbOperations.php');
require_once ('./../php/upload_user_pfp.php');
function update_entry( $updateColumn, $updateColumnValue, $updateData, $tableToUpdate )
{
  if( $_SERVER['REQUEST_METHOD'] != 'POST' ) {
    return false;
  }

  $safe_update_values = "";

  foreach ($updateData as $key => $value) {
    $safe_update_values  .=
    DataBaseOperations::convert_to_mysqli_safe_string($key) ."='".
    DataBaseOperations::convert_to_mysqli_safe_string($value) . "',";
  }

  // Weird pattern to remove all unnecessary comma for the update query string 
  $regPattern = '/,(?=[^,]*$)/';
  $safe_update_values = preg_replace( $regPattern, "",$safe_update_values);

  // $safe_update_values = DataBaseOperations::convert_to_mysqli_safe_string($update_values);
  $safe_updateColumn = DataBaseOperations::convert_to_mysqli_safe_string($updateColumn);
  $safe_tableToUpdate = DataBaseOperations::convert_to_mysqli_safe_string($tableToUpdate);
  $safe_updateColumnValue = DataBaseOperations::convert_to_mysqli_safe_string($updateColumnValue);

  $sql = "UPDATE " . $safe_tableToUpdate . 
  " SET " . $safe_update_values . 
  " WHERE " . $safe_updateColumn . " = '".$safe_updateColumnValue."';";

  return DataBaseOperations::execute_and_return_error_msg($sql);
}


$name_of_new_user_pfp = save_files_from_user_to_database($_FILES['new-edit-user-profile-picture']);

$update_user_data =   [ 
    "name" => $_POST['edit-user-name'], 
    "picture" => $name_of_new_user_pfp
];

$update_process_result = update_entry(
  $_POST['update_column'],
  $_POST['update_column_value'],
  $update_user_data,
  "user"
);

if($update_process_result["status"]){
  $update_process_result["recent_changed_entry_id"] = $_POST['update_column_value'];
}

echo json_encode($update_process_result);
// $_SERVER['REQUEST_METHOD'] = 'POST';
// update_entry(
//   "id",
//   "1",
//   ["name"=>"test update", "picture" => "test update"], 
//   "user"
// );