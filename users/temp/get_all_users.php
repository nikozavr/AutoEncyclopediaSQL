<?php
 
$response = array();
 
require 'db_connect.php';
 
$db = new DB_CONNECT();
 
$result = mysql_query("SELECT * FROM users") or die(mysql_error());
 
if (mysql_num_rows($result) > 0) {
    $response["users"] = array();
 
    while ($row = mysql_fetch_array($result)) {
        $users = array();
        $users["login"] = $row["login"];
        $users["password"] = $row["password"];
        $users["access"] = $row["access"];
 
        array_push($response["users"], $users);
    }
    $response["success"] = 1;
 
    echo json_encode($response);
} else {
    $response["success"] = 0;
    $response["message"] = "No user found";
 
    echo json_encode($response);
}
?>