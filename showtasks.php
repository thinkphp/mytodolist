<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
   <title>My ToDo List</title>
   <link rel="stylesheet" href="http://yui.yahooapis.com/2.7.0/build/reset-fonts-grids/reset-fonts-grids.css" type="text/css">
   <link rel="stylesheet" href="http://yui.yahooapis.com/2.7.0/build/base/base.css" type="text/css">
   <style type="text/css">
<style>

table {margin:1em 0; border:none;text-align: center}

table td {border: none;}

table {border:1px solid #036;}

th {background:#036; color:#fff; border:none; border-bottom:1px solid #fff;}

table tr:hover{background: #CCFFAE;cursor: pointer;color: #292;}

.hilite {background: #CCFFAE;cursor: pointer;color: #292;}

.addcolor {background: #E9E9E9}

h1{font-family: tohama,arial,verdana,sans-serif;font-size: 30px;color: #393;text-transform: capitalize;text-align: center}

#ft {text-align: right}
#ft a{color: #393;}
</style>


   </style>
</head>
<body>
<div id="doc" class="yui-t7">
   <div id="hd" role="banner"><h1>My ToDo List</h1></div>
   <div id="bd" role="main">
	<div class="yui-g">
	

<?php

    require_once('init.php');

    if($db->table_exists($table)) {

           $sql = "SELECT * FROM $table ORDER BY sort_order ASC";

           $db->query($sql);

                if($db->getRows() > 0) {


                           echo$db->display();


                } else {
                          echo"Table <strong>$table</strong> is empty.";  
                       }

           } else {
 
                echo"Table <strong>$table</strong> doesn`t exists.";

           }//end if-else
?>
</center>




	</div>
	</div>
   <div id="ft" role="contentinfo"><p>Created by @<a href="http://twitter.com/thinkphp">thinkphp</a> | <a href="create-table.php">create table</a></p></div>
</div>




<script type="text/javascript">

(function(){

          var trs = document.getElementsByTagName('tr');

              for(var i=0;i<trs.length;i++) {

                        trs[i].onmouseover = function() {

                              this.className += ' hilite';  
                        }    


                        trs[i].onmouseout = function() {

                              this.className = this.className.replace('hilite',' ');
                        }    

                   if(i%2!=0) {
 
                        trs[i].className = 'addcolor'; 
                   }

              }//end-for

})(); 

</script>



</body>
</html>
