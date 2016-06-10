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
   $collection = $db->brandPart;
   echo "Collection selected succsessfully\n";
   echo "<br/>"; 
	
   $document = array( 
      "name" => "Singapore", 
      "continent" => "Asia",
      "date_add" => new MongoDate()
   );
	
   /*
   #Insert data above
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
   */
   

echo "<hr>";
?>
<b>Display Mongo Data:</b>
<br/>
<?php
##########( Import CSV files )########## Start  


echo "Import CSV to mongo";
echo "<br/>";

$directory="/var/www/html/Mongo/imported_file/brandPart/";  

$checkEmpty  = (count(glob($directory.'*')) === 0) ? 'Empty' : 'Not empty';

if ($checkEmpty == "Empty")
{
   echo "No file inside directory!";
   exit();
}
else
{
   if ($dirhandler = opendir($directory)) 
   {
      while ($file = readdir($dirhandler)) 
      {
         $file_ext = explode('.',$file);
         $max_val = count($file_ext);
         $file_ext = $file_ext[($max_val-1)];
         if($file_ext == "csv")
         {
            $csv_file = $directory.$file;
            if (($handle = fopen($csv_file, "r")) !== FALSE) 
            {
               while (($data = fgetcsv($handle, 10000000, ",")) !== FALSE) 
               {
                  $num = count($data);
                  $row++;
                  for ($c=0; $c < $num; $c++) 
                  {
                     $col1 = trim($data[0]);
                     $col2 = trim($data[1]);
                     $col3 = trim($data[2]);
                     $document = array( 
                        "brandPart" => array(
                           $col1 => [
                              $col2,$col3
                           ]
                        ), 
                     );
                  }
                  $collection->insert($document);
               }
            }
         }
      }
   }
}

##########( Import CSV files )########## End
?>