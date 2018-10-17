<?php
   $dbhost = "127.0.0.1";
   $dbuser = "root";
   $dbpass = "724274";
   $dbchar = "utf8mb4";
   $dboptions = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];

   
   try
   {
       $dbConnection = new PDO("mysql:host=$dbhost; charset=$dbchar", $dbuser, $dbpass, $dboptions);
       if (!$dbConnection)
       {
           echo 'no connection';
           exit();
       }
   }
   catch (PDOException $e)
   {
       echo 'Connection failed: ' . $e->getMessage() . '<br>';
       exit();
   }
   
   $exist = false;
   $checkDb = $dbConnection->query('SHOW DATABASES');
   while (($db = $checkDb->fetch()) !== false)
   {
       if ($db[0] == 'socialnetwork')
           $exist = true;
   }
   if (!$exist)
   {
       $sql = file_get_contents("SocialNetwork.txt");
       if (!$sql)
       {
           echo "Unable to get database setup info";
           exit();
       }
       $populateDb = $dbConnection->prepare($sql);
       $populateDb->execute();
       mkdir("uploads");
   }
   $dbConnection = null;
?>