<?php
  
  require("config.php");
  
  $result = array();
  
  if(empty($_GET))
  {
    $result["success"] = -1;
  } else
  {
    $query = " 
                SELECT models.id_model, models.name, pic_auto.id_pic_auto, pic_auto.pic_url
                FROM models
                INNER JOIN autos ON autos.id_model = models.id_model
                INNER JOIN pic_auto ON pic_auto.id_auto = autos.id_auto
                WHERE autos.id_marq = ?
                AND pic_auto.priority =0
        "; 
   	
    $db = new mysqli($host, $username, $password, $dbname);

  	if (mysqli_connect_errno()) {
  	    printf("Can't connect: %s\n", mysqli_connect_error());
  	    exit();
  	}

  	$stmt = $db->stmt_init();

  	if ($stmt->prepare($query)) {

	    $stmt->bind_param("i", $_GET['id_marq']);

      $stmt->execute();

      $res = $stmt->get_result();

      $row = $res->fetch_all(MYSQLI_ASSOC);

  		$stmt->close();
  		if(count($row) > 0)
  		{ 
          $result["success"] = 1;
  		    $result["count"] = count($row);
  		    $result["pics"] = $row;
  		} else {
  		    $result["success"] = 0;
  		}
    }
    $db->close();
  }
  
  echo json_encode($result);
?> 
