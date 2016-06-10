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
   $collection = $db->country2;
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

$directory="/var/www/html/Mongo/imported_file/"; 
$archive_files = "/var/www/html/Mongo/archive_file/"; 

##########( Import CSV files )########## End
?>
<hr>
<?php
   /* START - INITIALIZE UPLOADING */
   if(isset($_FILES['image'])){

      echo $_POST['textBox'];

      /* START - DELETE ALL THE FILES INSIDE DIRECTORY FOLDER */
      if ($dirhandler = opendir($directory)) 
      {
         while ($file = readdir($dirhandler)) 
         {
            $delete[] = $directory.$file;
            foreach ( $delete as $file ) {
               unlink( $file );
            }
         }
      }
      /* END - DELETE ALL THE FILES INSIDE DIRECTORY FOLDER */

      // ARRAY ERROR MESSAGE
      $errors= array();

      // GET THE FILE INFO'S
      $file_name = $_FILES['image']['name'];
      $file_size =$_FILES['image']['size'];
      $file_tmp =$_FILES['image']['tmp_name'];
      $file_type=$_FILES['image']['type'];
      $file_ext=strtolower(end(explode('.',$_FILES['image']['name'])));

      // REQUIRED FILE EXTENSIONS
      $expensions= array("jpeg","jpg","png","csv");

      /* START - VALIDATION FOR FILE EXTENSION */
      if(in_array($file_ext,$expensions)=== false){
         $errors[]="extension not allowed, please choose a JPEG or PNG file.";
      }
      /* END - VALIDATION FOR FILE EXTENSION */

      /* START - VALIDATION FOR FILE SIZE */
      if($file_size > 2097152){
         $errors[]='File size must be excately 2 MB';
      }
      /* END - VALIDATION FOR FILE SIZE */
      
      /* START - VALID FILE UPLOADING */
      if(empty($errors)==true){
         move_uploaded_file($file_tmp,"imported_file/".$file_name);
         echo "Success";

         // SET VARIABLE TO CHECK THE DIRECTORY
         $checkEmpty  = (count(glob($directory.'*')) === 0) ? 'Empty' : 'Not empty';

         /* START - CHECKING THE DIRECTORY  */
         if ($checkEmpty == "Empty")
         {
            echo "No file inside directory!";
            exit();
         }
         else
         {
            /* START - OPEN THE DIRECTORY */  
            if ($dirhandler = opendir($directory)) 
            {
               /* START - LOOPING THE FILES */
               while ($file = readdir($dirhandler)) 
               {
                  $file_ext = explode('.',$file);
                  $max_val = count($file_ext);
                  $file_ext = $file_ext[($max_val-1)];
                  /* START - READ THE CSV FILES ONLY */
                  if($file_ext == "csv")
                  {
                     // SET VARIABLE FOR CSV FILES
                     $csv_file = $directory.$file;
                     /* START - OPEN THE CSV FILE CONTENT */
                     if (($handle = fopen($csv_file, "r")) !== FALSE) 
                     {
                        /* START - LOOPING THE CSV FILES */
                        while (($data = fgetcsv($handle, 10000000, "|")) !== FALSE) 
                        {
                           $num = count($data);
                           $row++;
                           
                           /* START - LOOPING THE CSV FILE CONTENT */
                           for ($c=0; $c < $num; $c++) 
                           {
                              $col1 = trim($data[0]);
                              $document = array( 
                                 "name" => $col1, 
                                 "continent" => $col1
                              );
                           }
                           /* END - LOOPING THE CSV FILE CONTENT */

                           /* START - INSERT THE CONTENT TO MONGO DATABASE */
                           if($collection->insert($document))
                           {
                              /* START - COPY THE FILES TO ARCHIVE FOLDER */
                              if(copy($directory.$file, $archive_files.$file))
                              {
                                 // SET VARIABLE FOR FILE TO BE DELETED
                                 $delete[] = $directory.$file;
                                 /* START - DELETION FILE */
                                 foreach ( $delete as $file ) {
                                      unlink( $file );
                                 }
                                 /* END - DELETION FILE */
                              }
                              /* END - COPY THE FILES TO ARCHIVE FOLDER */
                           }
                           else
                           {
                              echo "not ok";
                           }
                           /* END - INSERT THE CONTENT TO MONGO DATABASE */
                        }
                        /* END - LOOPING THE CSV FILES */
                     }
                     /* END - OPEN THE CSV FILE CONTENT */
                  }
                  /* END - READ THE CSV FILES ONLY */
               }
               /* END - LOOPING THE FILES */
            }
            /* END - OPEN THE DIRECTORY */  
         }
         /* START - CHECKING THE DIRECTORY  */
      }
      else{
         print_r($errors);
      }
      /* END - VALID FILE UPLOADING */
   }
   /* END - INITIALIZE UPLOADING */
?>
<html>
   <body>
      
      <form action="" method="POST" enctype="multipart/form-data">
         <input type="file" name="image" />
         <br/>
         Textbox: <input type="text" name="textBox">
         <br/>
         <input type="submit"/>

         <ul>
            <li>Sent file: <?php echo $_FILES['image']['name'];  ?>
            <li>File size: <?php echo $_FILES['image']['size'];  ?>
            <li>File type: <?php echo $_FILES['image']['type'] ?>
         </ul>
      </form>
      
   </body>
</html>