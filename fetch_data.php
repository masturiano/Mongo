<?php
    /**
    User name : admu
    Password : new_pass
    MongoDB host : localhost
    MongoDB port : 27017
    Database : university
    */
    //$server = "mongodb://root:new_pass@localhost:27017/country_db";
 
    try{
        // Connecting to server
        $c = new MongoClient( 'localhost' );
    }catch(MongoConnectionException $connectionException){
        print $connectionException;
        exit;
    }

    if($c)
    {
        echo "Connection to database successfully\n";   
        echo "<br/>"; 
    }
    else
    {
        echo "Connection to database failed\n";
        echo "<br/>"; 
    }
    
    
    $data  = "<table style='border:1px solid red;";
    $data .= "border-collapse:collapse' border='1px'>";
    $data .= "<thead>";
    $data .= "<tr>";
    $data .= "<th>Name</th>";
    $data .= "<th>Continent</th>";
    $data .= "<th>Date Added</th>";
    $data .= "</tr>";
    $data .= "</thead>";
    $data .= "<tbody>";
 
    try{
        $db = $c->country_db;
        $collection = $db->country;

        $start = new MongoDate(strtotime('1971-01-01 00:00:00'));
        $end = new MongoDate(strtotime('2016-12-31 23:59:59'));

        $cursor = $collection->find(
            array(
                "name" => "Singapore",
                //"date_add" => "2016-05-05"
                "date_add" => array('$gt' => $start, '$lte' => $end),
            )
        );
        foreach($cursor as $document){

            //$date_add = date("c",strtotime($document["date_add"]));
            $date_add = $document["date_add"];
            //$date_add = date('Y-M-d h:i:s', $date_add->sec);
            $date_add = date('Y-M-d h:i:s', $date_add->sec);

            $data .= "<tr>";
            $data .= "<td>" . $document["name"] . "</td>";
            $data .= "<td>" . $document["continent"]."</td>";
            //$data .= "<td>" . date('m d, Y', strtotime(substr($document["date_add"],0,10)))."</td>";
            $data .= "<td>" . substr($date_add,0,11)."</td>";
            $data .= "</tr>";
        }
        $data .= "</tbody>";
        $data .= "</table>";
        echo $data;
 
    }catch(MongoException $mongoException){
        print $mongoException;
        exit;
    }
?>