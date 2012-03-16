<?php
require('init.php');

if(isset($config['mysql'])) {

$sql = "create table $table (
        id int NOT NULL PRIMARY KEY,
        title varchar(200) not null,
        sort_order int(3) not null
        ) ENGINE=MYISAM;";

} else {

$sql = "create table $table (
        id int NOT NULL auto_increment PRIMARY KEY,
        title varchar2(200) not null,
        sort_order int(3) not null
        ) ENGINE=MYISAM;";

}
  
  if(!$db->table_exists($table)) {

      $db->query($sql);
      echo"Table <strong>$table</strong> created!"; 
 
  } else {

      echo"Table <strong>$table</strong> exists!"; 
  }


  $insert1 = "INSERT into $table (id,title,sort_order) VALUES (1,'Drag.js',1)";
  $insert2 = "INSERT into $table (id,title,sort_order) VALUES (2,'Drag.Move.js',2)";
  $insert3 = "INSERT into $table (id,title,sort_order) VALUES (3,'Sortables.js',3)";

  if($db->table_exists($table)) { 

      $db->query($insert1);
      echo"<br/>".$insert1.'<br/>';
      $db->query($insert2);
      echo"<br/>".$insert2.'<br/>';
      $db->query($insert3);
      echo"<br/>".$insert3;
  }

?>