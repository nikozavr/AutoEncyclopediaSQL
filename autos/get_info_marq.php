<?php
  
  require("config.php");
  
  $result = array();
  
  if(empty($_POST))
  {
    $result["success"] = -1;
  } else
  {
    $query = " 
                SELECT prod_date,
                       out_date,
                       country,
                       info,
                       web
                FROM 
                      marqs
                WHERE 
                      name = ?
        "; 

        $db = new mysqli($host, $username, $password, $dbname);

	if (mysqli_connect_errno()) {
	    printf("Can't connect: %s\n", mysqli_connect_error());
	    exit();
	}

	$stmt = $db->stmt_init();

	if ($stmt->prepare($query)) {

	    $stmt->bind_param("s", $_POST['name']);

            $stmt->execute();

            $res = $stmt->get_result();
            
            //$stmt->bind_result($prod, $out, $country, $info, $web);

	    $row = $res->fetch_assoc();

	    $stmt->close();
	    
	    
	    if($row){
		$result['success'] = 1;
		
		$row['info'] = iconv("CP1251","UTF-8",$row['info']);
		$result['marq_info'] = $row;
	    } else {
		$result['success'] = 0;
	    }
	 }
	 
	 $db->close();
    }
  echo json_encode($result);

?>