<?php
 //values
  $dif='';
  $pList=array(//page list
   'doctpe'=> $dif.'_pgs/doctype.php',
   'head'=> $dif.'_pgs/head.php',
   'bod'=> $dif.'_pgs/bod.php',
   'footer'=> $dif.'_pgs/footer.php'
  );
 //includes
  include("pages.php");//includes the pages class_alias
 //classes
  $pge=new pg(array());//pages
 $pge->load(array( 'pages'=>$pList ));//loads pages
   $pge->make(array( 'list'=>'doctype, head, bod, footer', 'return'=>'string' ));//creates the current page
  $curPage=$pge->doc(array( 'obj'=>'current' ));//gets current page
 echo $curPage;//outputs current page
?>
