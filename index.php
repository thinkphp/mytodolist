<?php require('init.php'); ?>
<?php

//delete
if(isset($_GET['exec_delete'])) {

   sleep(1);

   $id = $_GET['id'];

   $query = "DELETE from $table WHERE id='$id'";

   if(!$db->query($query)) {

      echo$db->error(); 
   }

   if(isset($_GET['byajax'])) {

      exit();  
   }
}//endif


//insert
if(isset($_POST['add_task'])) {

   sleep(1);

   $id = $_POST['id'];

   $title = $_POST['new_task'];

   $sort_order = $_POST['sort_order'];

   $query = "INSERT into $table (id,title,sort_order) VALUES ($id,'$title',$sort_order)";

   if(!$db->query($query)) {

      echo$db->error(); 
   }

   if(isset($_POST['byajax'])) {

      exit();  
   }
}//endif

//Update
if(isset($_POST['do_submit'])) {
sleep(1);
   /* split the value of the sortation */
   $ids = explode(",",$_POST['sort_order']);

   /* run the update query for each ID */
   foreach($ids as $index=>$id) {

      $id = (int)$id;   
      if($id != '') {

         $query = "UPDATE $table SET sort_order=".($index+1)." WHERE id=".$id;

         if(!$db->query($query)) {

             echo$db->error(); 
         }
      }

   }

   if(isset($_POST['byajax'])) {

      exit();  

   } else {
 
      $message = 'Sortation has been saved.';
   }
    
}//end if

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"  dir="ltr">
<head>
   <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
   <title>My ToDo List</title>
   <link rel="stylesheet" href="style.css" type="text/css">
   <link rel="stylesheet" href="css/MooDialog.css" type="text/css">
   <script type="text/javascript" src="http://www.google.com/jsapi"></script> 
   <script type="text/javascript"> google.load('mootools','1.2.4');</script>
   <script type="text/javascript" src="more.js"></script> 
   <script type="text/javascript" src="js/overlay.js"></script> 
   <script type="text/javascript" src="js/mooDialog.js"></script> 
   <script type="text/javascript" src="js/mooDialog.confirm.js"></script> 
   <script type="text/javascript" src="js/mooDialog.request.js"></script> 
   <script type="text/javascript">
//utilities
Array.prototype.max = function() {
var max = parseInt(this[0]);
var n = this.length;
for (var i=1; i < n; i++) if (parseInt(this[i]) > max) max = this[i];
return max;
}


   //once DOM is ready the go ahead
   window.addEvent('domready', function() {

        //settings vars 
        var sortInput = document.id('order-sort'), messageBox = document.id('message-box'), list = document.id("todo"), autoSubmit = document.id('autoSubmit');
         
       //This code initalizes the Sortables list.
	var sort = new Sortables('#todo', {
		handle: '.drag-handle',
		//This will constrain the list items to the list.
		constrain: true,
		//We'll get to see a nice cloned element when we drag.
		clone: true,
                opacity: 0.4,
                revert: true,
		//This function will happen when the user 'drops' an item in a new place.
		onComplete: function(elem){ 
                      handleSubmit(autoSubmit.checked);
                      $(elem.get('id')).highlight();
                      if(window.console){console.log('complete');}
                }
	});


      /* prepare and create object AJAX for sending vars POST */       
      var insert = new Request({
          url: '<?php echo$_SERVER['REQUEST_URI']?>',
          link: 'cancel',
          method: 'post',
          onRequest: function(){
            messageBox.set('text','Inserting...');
          },        
          onSuccess: function(){
            messageBox.set('text','Database has been updated.');
          } 
      }); 



       /*

        Adding task

       */

      var i,j;

      $('addTask').addEvent('submit',function(e){

        e.stop();

            if(list.getElements('li').length > 0) {
 
               var sortOrder = [];

               list.getElements('li').each(function(li){

                    sortOrder.push(li.get('id').replace('item-','')); 
               });

               i = parseInt(sortOrder.max())+1;j = list.getElements('li').length+1;

             } else {i = j = 1;}
  
        //get the value of the input textfield
        var val = $('newTask').get('value');

        //if the textfield is empty then execute following code
        if(!val) {
            $('newTask').highlight('#f00').focus();
            //return will skip the rest of the code in the function
            return;
        }
        var li = new Element('li',{id: 'item-'+i});

        var handle = new Element('div',{id: 'handle-'+i,'class':'drag-handle',text: val});

            handle.inject(li,'top');

        var checkbox = new Element('input',{'type': 'checkbox','class': 'deletion',value:'item-'+i});

            checkbox.inject(li,'top'); 

            $('newTask').set('value','');

            //add LI to our List
            $('todo').adopt(li);

            //do a fancy effect on the <li>
            li.highlight(); 

            sort.addItems(li);

            insert.send('id='+i+'&new_task='+val+'&add_task=1&sort_order='+j+'&byajax=1');  

            var sortOrder = [];

            list.getElements('li').each(function(li){

               sortOrder.push(li.get('id').replace('item-','')); 
            });

            sortInput.value = sortOrder.join(",");
      });

      $$('h2')[0].addEvent('click',function(){
                   console.log(sort.serialize(1));
      }); 



       /*

        Updating task

       */


      $('todo-form').addEvent('submit',function(e){

         if(e) {e.stop();}

         handleSubmit(true);
      });


      /* prepare and create object AJAX for sending vars POST */       
      var request = new Request({
          url: '<?php echo$_SERVER['REQUEST_URI']?>',
          link: 'cancel',
          method: 'post',
          onRequest: function(){
            messageBox.set('text','Updating Sort Order...');
          },        
          onSuccess: function(){
            messageBox.set('text','Database has been updated.');
          } 
      }); 
     
      /* Handler for Event 'submit' */
      var handleSubmit = function(save){

          var sortOrder = [];

          list.getElements('li').each(function(li){

              sortOrder.push(li.get('id').replace('item-','')); 
          });

          sortInput.value = sortOrder.join(",");

          if(save) {
           //make a AJAX request for save items
           request.send('sort_order='+sortInput.value+'&ajax='+autoSubmit.checked+'&do_submit=1&byajax=1');  
          }//endif
    
      }//end function handleSubmit


       /*

        Deletation using Event Delegation

       */
         
      list.addEvent('click',function(e){

           if(e.target.get('type') == 'checkbox') {

              new MooDialog.Confirm('Are you sure you want to permanently delete?!',function(){

                //grab ID
                var ID = e.target.get('value').replace('item-','');

                //get elem'parent
                var parent = e.target.getParent();

                //make a request AJAX from deletion item from database
                new Request({

                    url: '<?php echo$_SERVER['REQUEST_URI']?>',  

                    link: 'cancel',

                    method: 'get',

                    data: {id: ID,byajax: 1,random: Math.random()*100,exec_delete: 1},  

                    onRequest: function() {

                          messageBox.set('text','Deleting...');

                          var effect = new Fx.Tween(parent,{duration: 300});

                              effect.start('opacity','0');  
                    },

                    onSuccess: function() {

                          messageBox.set('text','Waiting for sortation submission...');

                          var myfunc = function(){parent.dispose();}.delay(1000);
                    }  
                
                }).send();//end request

                var sortOrder = [];

                list.getElements('li').each(function(li){ 
                     sortOrder.push(li.get('id').replace('item-','')); 
                });

                sortInput.value = sortOrder.erase(ID).join(",");

              },function(){},{size:{height: 120,width: 400},title: 'The page <?php echo$_SERVER['REQUEST_URI'];?> says:'});
                  
          }//end if e.target

      });//end if list addEvent



      $('showtask').addEvent('click',function(e){

         if(e) {e.stop();} 

         new MooDialog.Request(this.href,{evalScripts: true},{size: {width: 500,height: 400},title: 'Request show.php'});

      });

  });//end function DOMready
  </script>
</head>
<body>
<h2>My To Do List</h2>

<div id="message-box">Waiting for sortation submission...</div>

<form id="addTask">
 <input type="text" id="newTask">
 <input type="submit" id="submit" value="Add Task">
</form>

<form id="todo-form" action="<?php echo$_SERVER['REQUEST_URI'];?>" method="post">
<div id="listArea">
<ul id="todo">

<?php

//select
$query = "SELECT id, title FROM $table ORDER BY sort_order ASC";

$db->query($query);

if($db->getRows() > 0) {

   $order = array();

   $result = $db->getResult();

   foreach($result as $r) {

     echo"<li id='item-".$r['id']."'><input type=\"checkbox\" class=\"deletion\" value='item-".$r['id']."'><div id='handle-".$r['id']."' class='drag-handle'>".$r['title']."</div></li>";

     $order[] = $r['id'];
   }

  echo"</ul>";

  echo"<input type=\"hidden\" id=\"order-sort\" name=\"order-sort\" value='".implode(',',$order)."'/>";    

} else {

   echo"</ul>";
}


?>

<input type="submit" id="do-submit" name="do-submit" value="Save Order"/>
<p><input type="checkbox" id="autoSubmit" name="autoSubmit" value="1"><label for="autoSubmit">Automatically auto submit on drop event</label></p>
</form>
</div>
<div id="ft"><p>Created By <a href="http://twitter.com/thinkphp">@thinkphp</a> <a id="showtask" href="show.php" style="float:left;margin-left: 10px">Show Tasks</a></p></div>
</body>
</html>

