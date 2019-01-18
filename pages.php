<?php
 //pge - page handler
  if(!class_exists(pg)){
   class pg{
	//variables
     public $pages=array();//public interface
     public $pList=array();//pages list
     protected $cRef=array();//code-base reference
     private $curPage=array();//current page
     private $pArray=array();//page array
     private $pInfo=array();//page info
     private $chamb=array();//page related info
    
    //set-up
     public function __construct($attr){//page constructor
     }
    
    //functions
     //doc: public interfacer
      public function doc($att){
       //vars
        $oupt=null;
        $obj=($att['obj']) ?? ($att['objective']) ?? null;
       //checks what the objective is
        if($obj=='current_page' || $obj=='current page' || $obj='current' || $obj=='curPage'){ $oupt=$this->curPage; }//returns the current page
       return $oupt;//returns the output value
      }
         
     //load: loads the file from the directory
      public function load($att){
       //vars
        $oupt=null;//function output
        $attl=$att;//playable version of atts
       //attempts the proccess
        try{
         //checks to see if the page data have been included as an array
          $atK=array_keys($attl); $fiA=is_array($attl[($atK[0])]);
          if($fiA!=true){ $attl=array($attl); }//converts the att list variable into an array
         //loops through the file list and adds the files
          foreach($attl as$ai=>$av){
           //vals
            $pACnt=(count($this->pArray)>0) ? count($this->pArray) : 0;//page array count
            //file, text, & storage name
             $fileP=($av['file']) ?? null;//fiile page data
             $textP=($av['text']) ?? null; $textB=($av['text_break']) ?? ($av['text break']) ?? null;//text page data
             $name=$av['name'] ?? ($pACnt) ?? 0;//page name
            $ops=($av['ops']) ?? ($av['options']) ?? ($av['option']) ?? null;//page options
             $op['html_encode']=((strpos($ops,'html_encode')!=false) || (strpos($ops,'html_encode')===0)) ? 1 : 0;//if the page should be encodeded
             $op['iml']=((strpos($ops,'iml')!=false) || (strpos($ops,'iml')===0)) ? 1 : null;//if the iml should be parsed on the page
              $op['iml']=($op['iml']) ?? 1;//default
            $dtStream=null; $fileE=null;//file & data values
            $psSrh=null; $peSrh=null; $cdeFrag=null;
           //page data
            //retrives the data
             //file data
              //checks if the file exits
               if(file_exists($fileP)){ $fileE=1; }
              if($fileE==1){//checks to see if the file exits
               $dStream=array();//convert
               $file=fopen($fileP,'r'); if($file){//attempts to open the file & then checks if the file was opened
                while(!feof($file)) {//reads till the end of file
                 $line=fgets($file);//gets the line & stores it
                 $dStream[]=$line;//stores the line data
                }
               } fclose($file);//closes the file
              }
             //none file data stream
              if(!empty($textP)){
               if(!is_array($textP)){
                if(!empty($textB)){ $dStream=explode($textB, $textP); } 
                else{ $dStream=array($textP); }
               } else{ $dStream=$textP; }
              }
            //parses the page data
             if(is_array($dStream)){foreach($dStream as $di=>$dv){
              $dLine=$dv;//gets the line & stores it
              if($op['html_encode']==1){ $dLine=htmlentities($dLine); }//coverts the html characters if need be
              $this->pArray[$name][]=$dLine;//stores the value in the page array
              $pAC=count($this->pArray[$name])-1;//gets the page array count
             }}
            
           //stores page info
            if(isset($this->pArray[$name])){//makes sure the page has been added to the page array
             $this->pInfo[]=array( 'path'=>$fileP, 'name'=>$name );
             $oupt=true;//sets the output to true if the file was added to the page array
            }
          }
         //code fragments
          //php - gets php code
           foreach($this->pArray as $ppi=>$ppv){//loops through the page array and finds php code fragments
            //vals
             $psSrh=null; $cFrags=array();
             $cfId=null; $cfIdo=null; $lcData=array();
            foreach($ppv as $pli=>$plv){//loops through the page lines
             //vals
              $pText=$plv;
             //searches the text for php code fragments
              $psSrh=strpos($pText, '<?php'); if(($psSrh<0)&&(($psSrh===0)!=true)){ $psSrh=strpos($pText, '<?'); }
              if($psSrh>=1||$psSrh===0){ $lcData['start']['line']=$pli; $lcData['start']['pos']=$psSrh; }//if php code start fragments was found stores the value
              if(!empty($lcData['start']['pos'])||$lcData['start']['pos']===0){//if the start tag was found
               $peSrh=strpos($pText, '?>', $lcData['start']['pos']);//searches for the actual end tag
               if($peSrh>=1||$peSrh===0){//if the end tag was found
                $lcData['end']['line']=$pli;//stores the page line
                $lcData['end']['pos']=$peSrh;//stores the end position
                $lcData['lang']='php';//langauge info
                //sets the fragment id
                 $cfId=$lcData['start']['line'];//.'-'.$lcData['end']['line'];
                $cFrags[$cfId]=$lcData;//stores the data in the fragment array
                $cfIdo=$cfId; $cfId=null;//keeps track of the fragment id
                $lcData=array();//empties the data array
               }
              }
            }
            if(!empty($cFrags)){ $this->cRef[$ppi]=$cFrags; }//stores the fragment data 
           }
          //process page code fragments
           foreach($this->cRef as $cdPage=>$cdv){//loops through the page fragment info
            foreach($cdv as $ci=>$cv){//loops through the fragment data
             //vals
              $cdeFrag=null;
             //gets the code fragment & stores it
              $cdeFrag=$this->modi(array( 'obj'=>'get_fragment', 'fragment_info'=>$cv, 'data'=>$this->pArray[$cdPage] ));//gets the code fragment
              $this->cRef[$cdPage][$ci]['code_fragment']=$cdeFrag;//stores the fragment info
            }
           }
        
         //build the public page array
          //vals
           $stPage=array();//page data
          //loops through the the page array & transfers the need the needed data
           foreach($this->pArray as $pgi=>$pgv){
            //vals
             $pgName=$pgi; $pgSrc=$pgv;//page name & source
             $skList=array();//skip list
            foreach($pgSrc as $pgli=>$pglv){//loops through the page lines
             //vals
              $pgLine=$pgli;//line number
              $pgLData=$pglv;//line data
              $trts=array( 'iml'=>0, 'code'=>0 );
              $codeLang=$this->cRef[$pgName][$pgLine]['lang'];//code fragment language
               $lbStart=null; $lbStart2=null; $lbEnd=null;//language start & end bit
               if($codeLang=='php'){ $lbStart="<?php"; $lbStart2='<?'; $lbEnd='?>'; }
              $codeFrag=null; $codeEx=null;
			 //checks if the line needs treatment
              $trts['code']=(!empty($this->cRef[$pgName][$pgLine])) ? 1 : 0;//code fragment treatment
             //runs the treatments
              //parses the code fragment
               if($trts['code']==1){
              	 //vals
              	  $codeInfo=$this->cRef[$pgName][$pgLine];//code info
              	  $codeFrag=$codeInfo['code_fragment'];
              	  $codeEx=$this->modi(array( 'obj'=>'code_runner', 'code'=>$codeFrag ));
              	  $pgLineStat=($codeInfo['end']['line']!=$pgLine) ? 'm' : 's';//gets the code fragment line state
              	 //proccess code
              	  if($pgLineStat=='m'){//multi-line
              	   //code fragment removal
              	    for($rci=$codeInfo['start']['line']; $rci<=($codeInfo['end']['line']); $rci++){
              	     $rciNum=$rci;//safe version to save
              	     if($rci==$codeInfo['start']['line'] || $rci==$codeInfo['end']['line']){//starting & ending part
              	      //vals
              	       $curPs=($rci==$codeInfo['start']['line']) ? 's' : 'e';//determines if start or end
              	       $kep=null;//determines if the data should be kept or discarded
              	       $sptId=($curPs=='s') ? $lbStart : $lbEnd;//the spliter
              	        $dtaPoint=($curPs=='s') ? 0 : 1;//data point
              	      //checks if there is data near the start/end
              	       $bdExp=explode($sptId, $pgLData);//splits the value
              	       $kep=(!empty($bdExp[$dtaPoint])) ? 1 : 0;//makes the choice
              	      //keep or discard
              	       if($kep==1){ $pgLData=$bdExp[$dtaPoint]; $rciNum=null; }//keep
              	     }
              	     if(!empty($rciNum)&&($rci!=$codeInfo['start']['line'])){ $skList[]=$rciNum; }//adds the number to the skip list
              	    }
              	   //formatting
              	    $pgLData=str_replace($lbStart, '', $pgLData);//removes the starting tags
             	    $pgLData=$pgLData.''.$codeEx;
              	  }
              	  else{//single line
              	   //vals
              	    $nwTxt=null; $neTxt=null; $cout=null;
              	   //builds a new version of the text 
              	    for($bnt=0; $bnt<=(strlen($pgLData)); $bnt++){
              	     if($bnt<$codeInfo['start']['pos']){ $nwTxt.=$pgLData[$bnt]; }//text before code
              	      if($bnt===$codeInfo['start']['pos']){ $cout=$this->modi(array( 'obj'=>'code_runner', 'code'=>$codeFrag )); }//runs the code & stores it
              	     if($bnt>($codeInfo['end']['pos']+strlen($lbEnd))){ $neTxt.=$pgLData[$bnt]; }//text after code
              	    }
              	   $pgLData=$nwTxt.''.$cout.''.$neTxt;//places the new text
              	  }
               }
             //stores the page array data in the page
			  if(!in_array($pgLine,$skList)){ $this->pages[$pgName][$pgLine]=$pgLData; }
            }
		   }
		  $oupt=null;//sets the outpout
        } catch(Exception $e){ $oupt=$e->getMessage(); }//failure message
       
       return $oupt;//returns the ouput
      }
    
     //modi: handles page formatting and processing
      private function modi($att){
       //vals
        $oupt=null;
        $obj=($att['obj']) ?? ($att['objective']) ?? null;
       //checks the objective
        if($obj=='code_runner' || $obj=='code runner' || $obj=='runner'){//proccess & returns the code
         //vals
          $code=null; $cdve=null;
          $uCode=($att['code']) ?? null;
          $cLang=($att['lang']) ?? 'php';
         if(!empty($uCode)){//if code was provided
          //lang info
           if($cLang=='php'){ $lgStart='<?php'; $lgEnd='?>'; }
          //gets the "code"
           $cdve=explode($lgStart, $uCode); $cdve=$cdve[1];
           $cdve=explode($lgEnd, $cdve); $code=$cdve[0];
           ob_start();//output buffer
            eval($code);//runs the code
            $oupt=ob_get_contents();//pushes the execution to a stirng
           //ob_end_flush();//ends output buffer & turns off the output stream
		   ob_end_clean();//ends output buffer
     	   return $oupt;
       	 }
       	}
       	elseif($obj=='get_frag' || $obj=='get_fragment' || $obj='get frag' || $obj=='get fragment'){//replaces code fragment with output
         //values
          $oupt=null;
          $cdPage=($att['page']) ?? null;
          $fData=($att['data']) ?? null;
          $fInfo=($att['info']) ?? ($att['fragment_info']) ?? ($att['fragment info']) ?? null;//code fragment list
         //proccess the fragment list
          if(!empty($fData)&&!empty($fInfo)){
           //vals
          	$cout=null; $nwTxt=null; $neText=null;
         	$cLang=$fInfo['lang'];//code language
            $code=($fInfo['code']) ?? null;
         	 $pos=array( 'start'=>$fInfo['start']['pos'], 'end'=>$fInfo['end']['pos'] );//fragment position info
         	 $lin=array( 'start'=>$fInfo['start']['line'], 'end'=>$fInfo['end']['line'] );//fragment line info
         	 //lang data
         	  if($cLang=='php'){ $stPc='<?php'; $edPc='?>'; }
           //gets the code fragment
         	$lineStat=null; $wPage=0; $cdFrag=null; $pcText=null; $pcBlock=null;
         	//checks if the code is single or multi-lined
         	 if($lin['start']===$lin['end']){ $lineStat='s'; }else{ $lineStat='m'; }
         	 if($lineStat=='m'&&( $pos['start']===0 && $pos['end']===0 )){ $wPage=1; }//if the whole page is code
            //grabs the frragment depending on the line status
         	 if($lineStat=='s'){//single-line
         	  $pcText=$fData[$lin['start']];//gets the code text
         	  //gets the code fragment
         	   for($bcf=$pos['state']; $bcf<($pos['end']+strlen($edPc)); $bcf++){ $cdFrag.=$pcText[$bcf]; }//gets the code fragment
         	   $oupt=$cdFrag;//sets the fragment as the output
         	 }
         	 elseif($lineStat=='m'){//multi-line
         	  $pcBlock=$fData;//gets the page block
         	  //gets the code fragment
         	   for($bcf=$lin['start']; $bcf<=($lin['end']); $bcf++){ $cdFrag.=$pcBlock[$bcf]; }//gets the code fragment
         	  $oupt=$cdFrag;//sets the fragment as the output	 
         	 }
          } else{ throw new Exception( $this->langu('invalid values', 'en') ); }
       }
       return $oupt;//returns the output
      }
     
     //make: short-hand for the obj=out of the read function
      public function make($att){
       //vars
        $oupt=null; $lSplit=null;
        $oType=($att['otype']) ?? ($att['output type']) ?? ($att['output_type']) ?? null;//sets the output type
         $oType=(empty($oType)) ? 0 : $oType;//default
         if((strpos($oType,'self')!=false)||(strpos($oType,'self')===0)){ $oType=1; }//if the output should be saved to the $curPage or spit out
        $list=($att['list']) ?? ($att['pages']) ?? ($att['page']) ?? $att;//output list
         if(empty($list) || !isset($list) || $list=='all' || ( (strpos($list,'all')!=false)||(strpos($list,'all')===0) ) ){ $list=array_keys($this->pArray); }//sets default list of all page names
         elseif(!is_array($list)){//if the list was provided in a non array format
          $lSrh=strpos($list, ',');//searches the list for the delimiter
          $lSplit=($lSrh==false) ? ';' : ',';//dictates the delimiter
          $list=explode($lSplit, $list);//splits the list accordingly
          foreach($list as$li=>$lv){ $list[$li]=trim($list[$li]); }//removes the whitespaces at the start & end of the values
         }
        $reT=($att['return']) ?? ($att['return type']) ?? ($att['return_type']) ?? null;//return type
         if((strpos($reT,'string')!=false)||(strpos($reT,'string')===0)){ $reT=1; }//if the return type should be a string
       //calls the read function
        $oupt=$this->read(array( 'obj'=>'output', 'list'=>$list, 'return_type'=>$reT, 'output_type'=>$oType ));
       return $oupt;//sets the return output
      }
     
     //read: reads the page into the curPage value
      private function read($att){
       //vars
        $oupt=null;
        $obj=($att['obj']) ?? ($att['objective']);//objective
        $list=$att['list'];//page list
        $reT=($att['return']) ?? ($att['return type']) ?? ($att['return_type']) ?? 1;//return type
        $ouT=($att['output_type']) ?? ($att['output type']) ?? null;
       //checks the objective
        if($obj=='output'||$obj=='out'|| $obj=='paint'||$obj=='make'){//reads page & then creates an output
         //vals
          $npage=array();//new page holder
          $lpool=array();//list pool
          $opage=null;//old new page holder
         //loops through the list and gets the pool data
          foreach($list as$li=>$lv){ $lpool[$lv]=$this->pages[$lv]; }
         //builds the new page
          foreach($lpool as$pi=>$pv){//pool
           foreach($pv as$pgi=>$pgv){//page
            $npage[]=htmlspecialchars_decode($pgv);//stores the line value
           }
          }
         //process the new page
          /* ++ other features ++ */
          //page outputing
           if($reT==1){//turns the new page value into a string
            $opage=$npage; $npage=null;//swtiches the value
            foreach($opage as$pi=>$pv){ $npage.=$pv; }//stores the text values
           }
           else{ foreach($npage as$pi=>$pv){ echo $pv; } }//outputs the page text values outright
         //determines what to do with the new page (output)
          if($ouT==1){ $oupt=$npage; }//sets the output
          else{ $this->curPage=$npage; }//stores the page  in the curPage value
        }
       
      //returns the output
	   if(!empty($oupt)){ return $oupt; }
     }
   }
  }
?>