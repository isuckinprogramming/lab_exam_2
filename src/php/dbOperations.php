<?php
function getHRDBConnection() {
  // Create connection
  $conn = new mysqli("localhost","root", "", "hr1");

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  //echo "Database Connected successfully";
  return $conn;
}

/**
 * The function will handle the error in case something goes wrong with the 
 * mysql query and makes debugging easier. No actual code is executed to handle 
 * the error just returning details about the error.
 * @param mysqli $conn
 * @param mixed $sql
 * @return array
 */
function executeQueryHandleError( mysqli $conn, $sql)  {
  
  $result = $conn->query($sql);
  return ($result) ? 
  [ 
    "status" => true,
    "result" => $result,
    "mysqlQuery" => $sql
  ]
   : 
  [
    "status" => false,
    "errorNo" => $conn->errno,
    "errorMessage" => $conn->error,
    "mysqlQuery" => $sql
  ];
}


// I think I should put the single database connection to be used for all the query

class DataBaseOperations
{
  
  static function create_db_connection($host = "localhost",$user = "root", $password = "", $dbName ="lab_exam_2_lim")
  {
    return new mysqli($host,$user, $password, $dbName);
  }

  // static mysqli $connection = DataBaseOperations::create_db_connection();
  static $is_database_connection_created = false;
 
  /**
   * The function will handle the error in case something goes wrong with the 
   * mysql query and makes debugging easier. No actual code is executed to handle 
   * the error just returning details about the error.
   * @param mysqli $conn
   * @param mixed $sql
   * @return array
   */
  static function execute_and_return_error_msg(String $sql)  {
    $conn = null;

    if( !DataBaseOperations::$is_database_connection_created){
      $conn = DataBaseOperations::create_db_connection();
      DataBaseOperations::$is_database_connection_created = true;
    } else {
      $conn = $_SESSION['db_connection'];
    }


    $result = $conn->query($sql);

    if (session_status() == PHP_SESSION_NONE) {
      session_start();
    }
    $_SESSION['db_connection'] = $conn;

    return ($result) ? 
    [ 
      "status" => true,
      "status_message" => "success",
      "result" => $result,
      "mysqlQuery" => $sql
    ]
    : 
    [
      "status" => false,
      "status_message" =>"unsuccessful query.",
      "errorNo" => $conn->errno,
      "errorMessage" => $conn->error,
      "mysqlQuery" => $sql,
      "display_error_message" => $conn->errno . '\n' . $conn->error  . '\n' . $sql
    ];
  } 

  static function is_user_logged_in()
  {
    $is_all_parameters_set = isset($_SESSION['user_name']) && 
    isset($_SESSION['user_password']) &&
    isset($_SESSION['user_login_success']) && 
    
    session_status() == PHP_SESSION_ACTIVE;

    // if( $is_all_parameters_set && $_SESSION['user_login_success'] === true){
    //   return true;
    // }

    return $is_all_parameters_set && $_SESSION['user_login_success'] === true;
  }

  static function convert_to_mysqli_safe_string($sql)
  {
      
    if ( !DataBaseOperations::$is_database_connection_created ) {

      if ( session_status() == PHP_SESSION_NONE ) {
        session_start();  
      }

      $_SESSION['db_connection'] = DataBaseOperations::create_db_connection();
      $is_database_connection_created = true;
    }
    $converted = mysqli_real_escape_string($_SESSION['db_connection'], $sql);

    return $converted;
  }
}