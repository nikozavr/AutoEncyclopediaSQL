<?php
  
    require("config.php");
      
    $result = array();

      
    if(empty($_GET))
    {
        $result["success"] = -1;
    } else
    {
        $query = " 
            SELECT 
                id, 
                username, 
                password, 
                salt, 
                email,
                permission 
            FROM users 
            WHERE 
                username = ? 
        "; 

    
        $db = new mysqli($host, $username, $password, $dbname);

        if (mysqli_connect_errno()) {
            printf("Can't connect: %s\n", mysqli_connect_error());
            exit();
        }

        $stmt = $db->stmt_init();

        if ($stmt->prepare($query)) {

            $stmt->bind_param("s", $_GET['username']);

            $stmt->execute();

            $res = $stmt->get_result();

            $row = $res->fetch_array(MYSQLI_ASSOC);

            $stmt->close();
        
            $login_ok = false;
            
            if($row)
            {
                $check_password = hash('sha256', $_GET['password'] . $row['salt']); 
                for($round = 0; $round < 65536; $round++) 
                { 
                    $check_password = hash('sha256', $check_password . $row['salt']); 
                } 
                 
                if($check_password === $row['password']) 
                { 
                    // If they do, then we flip this to true 
                    $login_ok = true; 
                }
            }
            
            if($login_ok)
            {
               unset($row['salt']);
               unset($row['password']);
          
               $result['success'] = 1;
               $result['userinfo'] = $row;
          
            } else {
               $result['success'] = 0;
            }
        }
        

        
  }
  
  echo json_encode($result);

?>