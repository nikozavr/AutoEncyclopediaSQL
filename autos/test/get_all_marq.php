<?php

	require("config.php");

	$pics = array();

	$query = "
		SELECT a.id_marq, a.name, b.id_pic_marq, b.pic_url 
                FROM marqs as a, pic_marq as b
                WHERE a.id_marq = b.id_marq 
                GROUP by a.name
            ";

	
    $db = new mysqli($host, $username, $password, $dbname);

    if (mysqli_connect_errno()) {
        printf("Can't connect: %s\n", mysqli_connect_error());
        exit();
    }

     $stmt = $db->stmt_init();

    if ($stmt->prepare($query)) {

	$stmt->execute();

	$res = $stmt->get_result();

	$row = $res->fetch_all(MYSQLI_ASSOC);

	$stmt->close();
	if(count($row) > 0)
	{ 
	    $response["success"] = 1;
	    $response["count"] = count($res);
	    $response["marqs"] = $row;
	} else {
	    $response["success"] = 0;
	}
    }
    $db->close();
    echo json_encode($response);
?>