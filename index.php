<?php
 //values
  $dif='';
  $pList=array();//page list
   $pList[]=array( 'file'=> $dif.'_pgs/doctype.php', 'name'=>'doctype' );//doctype
   $pList[]=array( 'file'=> $dif.'_pgs/head.php', 'name'=>'head' );//head
   $pList[]=array( 'file'=> $dif.'_pgs/bod.php', 'name'=>'bod' );//body
   $pList[]=array( 'file'=> $dif.'_pgs/footer.php', 'name'=>'footer' );//footer
 //includes
  include("pages.php");//includes the pages class_alias
 //classes
  $pge=new pg(array());//pages
 $pge->load($pList);//loads pages
   $pge->make(array( 'list'=>'doctype, head, bod, footer', 'return'=>'string' ));//creates the current page
  $curPage=$pge->doc(array( 'obj'=>'current' ));//gets current page
 echo $curPage;//outputs current page
?>