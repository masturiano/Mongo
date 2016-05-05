<?php
   // connect to mongodb
   $m = new MongoClient('localhost');
   if($m)
   {
      echo "Connection to database successfully\n";   
      echo "<br/>"; 
   }
   else
   {
      echo "Connection to database failed\n";
      echo "<br/>"; 
   }
   
   // select a database
   $db = $m->country_db;
   echo "Database country_db selected\n";
   echo "<br/>"; 
   $collection = $db->country;
   echo "Collection selected succsessfully\n";
   echo "<br/>"; 
	
   $document = array( 
      "name" => "Phil", 
      "continent" => "Asia"
   );
	
   
   if($collection->insert($document))
   {
      echo "Document inserted successfully\n";  
      echo "<br/>"; 
   }
   else
   {
      echo "Document insert failed\n"; 
      echo "<br/>"; 
   }

?>