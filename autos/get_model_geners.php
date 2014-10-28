<?php
  require("config.php");
  
  $result = array();
  
  if(empty($_POST))
  {
    $result["success"] = -1;
  } else
  {
    $query = " 
                SELECT geners.id_gener, geners.number, geners.name, geners.prod_date, geners.out_date, pic_auto.id_pic_auto, pic_auto.pic_url
                FROM geners
                INNER JOIN autos ON autos.id_gener = geners.id_gener
                INNER JOIN pic_auto ON pic_auto.id_auto = autos.id_auto
                WHERE autos.id_model = ?
                AND pic_auto.priority <=1
                GROUP BY geners.number
        "; 
   	
    $db = new mysqli($host, $username, $password, $dbname);

  	if (mysqli_connect_errno()) {
  	    printf("Can't connect: %s\n", mysqli_connect_error());
  	    exit();
  	}

  	$stmt = $db->stmt_init();

  	if ($stmt->prepare($query)) {

	    $stmt->bind_param("i", $_POST['id_model']);

      $stmt->execute();

      $res = $stmt->get_result();

      $row = $res->fetch_all(MYSQLI_ASSOC);

  		$stmt->close();
  		if(count($row) > 0)
  		{ 
          $result["success"] = 1;
  		    $result["count"] = count($row);
  		    $result["geners"] = $row;
  		} else {
  		    $result["success"] = 0;
  		}
    }
    $db->close();
  }
  
  echo json_encode($result);

?>