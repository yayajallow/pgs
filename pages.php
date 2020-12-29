<?php
 /*
  errorX001 - file deosn't exists
  errorX002 - unable to load file
  errorX003 - unable to read file
  errorX005 - invalid properties
 */
 //pge - page handler
  if(!class_exists('pg')){
   class pg{
	//variables
     public $pages=array();//public interface
     public $pList=array();//pages list
     protected $cRef=array();//code-base reference
     private $curPage=array();//current page
     private $pArray=array();//page array
     private $pInfo=array();//page info
     private $chamb=array();//page related info
	 private $pLangs=array(//language references
	  'php'=> array( 'start'=>array( '<?php', '<?', ), 'end'=>array( 'php?>', '?>' ) )
	 );
	 private $error_box=array(//error box
	  'last'=>array(),
	  'pool'=>array()
	 );
	  private $error_last;
	  private $eMessages=array(//error message
	   '001'=> 'file doesn\'t exsits',
	   '002'=> 'unable to load file',
	   '003'=> 'unable to read file',
	   'fi01'=> '',
	   'u01'=> '',
	  );
	 
	public function langu(){}
	
    //set-up
     public function __construct($attr){//page constructor
	  $this->error_last =& $this->error_box['last'];//error last variable reference
     }
    
    //functions
	 //_hm: class cleaning
	  private function _hm($att){
	   //vars
	    $oupt=null; $output=null; $oBool=false;//output variables
	    $obj=($att['objective']) ?? ($att['obj']) ?? null;//objective
	   //checks what the objective is
	    if($obj=='error'||$obj=='error_keeping'){//error keeping
		 //vars
		  $eb =& $this->error_box;//error box
		  $error=($att['error']) ?? array();//error
		 //determines what to do with the error
		  if(!empty($eb['last'])){ $eb['pool']=$eb['last']; }//moves the last error to the pool
		  if(!empty($error)){//stores the current error
		   $eb['last']=$error;//sets the error
		   if($eb['last']===$error){ $oBool=true; }//checks if the error was set
		  }
		}
	   //returns the output
	    //sets the output variables
		 if($oBool==false){ $output=$oupt=$oBool; }
		 else{ $output=$oupt=$oBool; }
	    return$output;
	  }
	  
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
		 //stores the page info
		  $pgList=$attl['pages'] ?? $attl['page'] ?? array();//page list
		  foreach($pgList as $pi=>$pv){//loops through the page list
		   //values
		    $pName=$pi; $pgInfo=$pv;//page name & info
		   //stores the page info
		    $this->$pList[$pName]=null;//stores the page name in the page name in the list
			$this->pInfo[$pName]=array(//page info
			 'name'=> $pName, 'o_text'=>$pgInfo//name & original text
			);
		   //determines what to do with the page data
		    $pSrh=array(//page searches
			 'file'=> array(
			  '_count'=>0,
			  'trail'=>((strpos($pgInfo, '../'))===false ? false : strpos($pgInfo, '../')),
			  'ext'=>(((strpos($pgInfo, '.'))===false) ? false : strpos($pgInfo, '.')),
			  'dir'=>((strpos($pgInfo, '/'))===false ? false : strpos($pgInfo, '/')),
			  //'dir_2'=>((strpos($pgInfo, '/', $pSrh['dir']))===false ? false : strpos($pgInfo, '/', $pSrh['dir'])),
			 ),
			);
			 $psfC=0; foreach($pSrh['file'] as $psi=>$psv){ $psfC+=$psv; } $pSrh['file']['_count']=$psfC;
		   //determines what to do
		    if($pSrh['file']['_count']>=1){//if file, right file
			 if(file_exists($pgInfo)){//if the page exists
			  $page_data=$this->read(array( 'obj'=>'load', 'name'=>$pName, 'data'=>$pgInfo ));//stores the page data
			  $pStrip=(!empty($page_data)) ? $this->modi(array( 'obj'=>'parse', 'name'=>$pName, 'data'=>$page_data )) : false;//reads page data
			  if(!empty($page_data)&&$pStrip==true){//if the page was successfully loaded & stripped
			   $cmPage=$this->modi(array( 'obj'=>'markup', 'name'=>$pName, 'data'=>$page_data ));//complied page
			   if($cmPage==true){//if the page was successful marked up
				$aPage=$this->read(array( 'obj'=>'assemble', 'name'=>$pName, 'data'=>$page_data ));//builds the page array
			    if(!empty($aPage)&&(($aPage===true)==false)){//if the page was assembeled
				 $this->pArray[$pName]=$aPage;//stores the assmebled page
				 $oupt=true; $oBool=$ture;//sets the output to true
				}
			   }
			  }
			 } else{ throw new Exception('errorX001'); }//error thrown - file doesn't exists
			}
		    else{ $this->pList[$pName]=$pData; }//stores page text
		  }
		 //sets the outpout
        }
		catch(Exception $e){ $oupt=$e->getMessage(); }//failure message
       
       return $oupt;//returns the ouput
      }
    
     //modi: handles page formatting and processing
      private function modi($att){
       //vals
        $oupt=null; $output=null; $oBool=false;//output variables
		 $oErr=array();//otuput error list
        $obj=($att['obj']) ?? ($att['objective']) ?? null;//objective
		 $sObj=($att['sobj']) ?? ($att['s_obj']) ?? ($att['s obj']) ?? ($att['secondary_obj']) ?? ($att['secondary obj']) ?? ($att['secondary_objective']) ?? ($att['secondary objective']) ?? null;//secondary objective
        $rText=array(//reference texts
		 'self'=>'self&&',
		);
	   //checks the objective
        if($obj=='code_runner' || $obj=='code runner' || $obj=='runner'){//proccess & returns the code
         if($sObj=='code_split' || $sObj=='code split' || $sObj=='split'){//splits the code and tags
		  //vals
		  //splits the tag and code
		 }
		 else{//default code runner
		  //vals
           $code=null; $cdve=null;
           $uCode=($att['code']) ?? null;
           $cLang=($att['lang']) ?? 'php';
		   $lgStart=($att['start_tag']) ?? ($att['start tag']) ?? ($att['start']) ?? ($att['starter']) ?? null;//starting tag
		   $lgEnd=($att['end_tag']) ?? ($att['end tag']) ?? ($att['end']) ?? ($att['ender']) ?? null;//ending tag
          if(!empty($uCode)){//if code was provided
           //lang info
            if($cLang=='php'){
			 if(empty($lgStart)){ $lpSearch=strpos($uCode,'<?php'); $lgStart=(($lpSearch==true||($lpSearch===0&&($lpSearch===false)!=true))) ? '<?php' : '<?'; }
			 if(empty($lgEnd)){ $lpEnd=strpos($uCode,'php?>'); $lgEnd=($lpEnd==true||($lpEnd===0&&($lpEnd===false)!=true)) ? 'php?>' : '?>'; }
			}
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
		}
		elseif($obj=='markup'){//markup the page with the needed language
		 //values
		  $oBool=true;//runs until error occurs
		  $fName=($att['name']) ?? ($att['file_name']) ?? ($att['file name']) ?? ($att['file']) ?? null;//file name
		  $fData=($att['data']) ?? ($att['file_data']) ?? ($att['file data']) ?? ($att['data']) ?? null;//fiel data
		  $cdPockets=array();//code pockets
		  $pFrags =& $this->pInfo[$fName]['fragements'];//page code fragements
		   $pfInfo =& $pFrags['tags'];//fragement ifno
		   $fOutput =& $pFrags['output']; $fMap =& $pFrags['map'];//fragemnet output & map
		  $fKeys=(!empty($pfInfo)) ? array_keys($pfInfo) : null; $rfKeys=array();//gets the array keys for the key array
		   if(!empty($fKeys)){ foreach($pfInfo as$kyi=>$kyv){ $fKeys[$kyi]=$kyv['line']; }}//pairs the line number and index number
		   if(!empty($fKeys)){ foreach($fKeys as$kyri=>$kyrv){//switches the key & value to the lines and key number are matched
		    if(!empty($rfKeys[$kyrv])){//single line multi code fragements
			 if(!is_array($rfKeys[$kyrv])){ $pV=$rfKeys[$kyrv]; $rfKeys[$kyrv]=array($pV, $kyri); }//turns the value into am array
			 else{ $rfKeys[$kyrv][]=$kyri; }//stores the value
			}
			else{ $rfKeys[$kyrv]=$kyri; }//single line code fragement
		   }}
		  $in_code=0;//flag to determine if reading in-between code
		   $in_code_language=null;//in code language
		 //markups the page
		  if(!empty($fName)&&!empty($fData)){
		   //code fragment markup
		    if(!empty($fKeys)&&!empty($rfKeys)){
			 //code fragment markup
			  //inline
			   $iCode=array(); $iiCode=array();//inline code array & info array
			   foreach($rfKeys as$rki=>$rkv){//loops through the reverse key and
			    //vals
			     $lText=$fData;//stores the line text
			     if(!is_array($rkv)){ $rkv=array($rkv); }//makes the value into an array
				 foreach($rkv as$kvi=>$kvv){if(!empty($kvv)||( $kvv===0&&(($kvv===false)!=true) )){//loops through the keys and checks which is inline fragments
				  $frId=$kvv;//fragement id
				  $frInfo=$pfInfo[$frId];//pulls the fragement info
				  if(( empty($frInfo['start'])||!is_array($frInfo['start']) )||( empty($frInfo['end'])||!is_array($frInfo['end']) )){ $inLine=0; }
				  else{ $inLine=( (!empty($frInfo['start']['pos'])|| ($frInfo['start']['pos']===0&&($frInfo['start']['pos']===false)!=true)) && (!empty($frInfo['end']['pos'])|| ($frInfo['end']['pos']===0&&($frInfo['end']['pos']===false)!=true)) ) ? 1 : 0; }//determines if the code is inline
				   $this->pInfo[$fName]['fragements']['tags'][$frId]['in_line']=$inLine;//stores the inline code value
				  //marks & strips the code fragments
				   if($inLine==1){//if in-line code
				    $posInfo=array('start'=>$frInfo['start']['pos'], 'end'=>($frInfo['end']['pos']+strlen($frInfo['end']['value'])));//stores the start & end position for the text
				    $tagInfo=array( 'start'=>$frInfo['start']['value'], 'end'=>$frInfo['end']['value'] );//tag info
				    $cSnip=null;//code snippet data holder
				     for($gc=($posInfo['start']); $gc<$posInfo['end']; $gc++){//loops through the file data and puts the string data in an array
				      $cSnip.=$fData[$frInfo['line']][$gc];//reads the code fragement
				     }
				     $cOut=$this->modi(array( 'obj'=>'runner', 'code'=>$cSnip, 'lang'=>$frInfo['start']['lang'], 'start'=>$tagInfo['start'], 'end'=>$tagInfo['end'] ));//sends the fragment off for proccessing
				     $iCode[$rki][$kvi]=array(//incode fragment data
					  'code'=>$cSnip, 'output'=>$cOut, 'lang'=>$frInfo['start']['lang'],
					  'frag'=>array(
					   'start'=>$posInfo['start'], 'end'=>$posInfo['end'], 'frag_id'=>$frId,
					   'length'=>( ($posInfo['end']-$posInfo['start']) )
					  )
					 );
					  //stores the info inside the fragment array
					   $pfInfo[$frId]['length']=$iCode[$rki][$kvi]['frag']['length'];//stores the string length
				   }
			    }}
			   }
			  //multi
			   $mCode=array(); $miCode=array();//multi-line code array and info array
			   foreach($pfInfo as$ti=>$tv){//loops through the tags info and stores multi-line tags
			    //vars
				 $tgInfo=array(); $lnInfo=array();//tag & line info
				  $eInfo=array();//extra info
				 $isStart=0;//is starting tag flag
				 $edTag=($tv['end']) ?? null; $edITag=null;//end tag
				 $stTag=($tv['start']) ?? null; $stITag=null;//start tag
				if(!empty($edTag)&&!is_array($edTag)){//searches for the start tag of multi tags
				 $esTag=strpos($edTag, $rText['self']);
				 $efTag=($esTag>=1|| ($esTag===0&&(($esTag===false)!=true)) ) ? 1 : 0;//ending tag flag
			     $isStart=($efTag==1) ? 1 : 0;
				  $edITag=($isStart==1) ? $edTag : $edITag;//stores the end tag text
				 if($isStart==1){//if a starting tag
				  //stores the needed starting & ending tag info
				   $etId=null;//gets the ending tag info
				    $eeI=explode($rText['self'],$edTag); $etId=(is_array($eeI)) ? $eeI[1] : null;
				   $stTag=$pfInfo[$ti]['start']; $edTag=$pfInfo[$etId]['end'];//start & ending tag info
				   $tgInfo=array( 'start'=>$stTag, 'end'=>$edTag );//stores tag info
				   $lnInfo=array( 'start'=>$pfInfo[$ti]['line'], 'end'=>$pfInfo[$etId]['line'] );//stores the line numbers
				    $frlCount=($lnInfo['end']>$lnInfo['start']) ? ($lnInfo['end']-$lnInfo['start']) : null;//fragment line count
				     $frlCount=(!empty($frlCount)) ? ($frlCount) : $frlCount;
					  $lnInfo['_all']=$frlCount;//stores the total line count
				   //stores extra info
				    $eInfo['ref']=array( 'start'=>$pfInfo[$etId]['start'], 'end'=>$edITag );
					$eInfo['frag_id']=array( 'start'=>$ti, 'end'=>$etId );
				  //sets fragment info
				   $pfInfo[$ti]['multi_line']=1; $pfInfo[$etId]['multi_line']=1;//sets the multi line flag
				    $pfInfo[$ti]['_lines']=$frlCount; $pfInfo[$etId]['_lines']=$frlCount;//line code count
				  $mCode[$lnInfo['start']]=array(//stores the fragment info
				   'tag'=>$tgInfo, 'line'=>$lnInfo,
				   'ref'=>$eInfo['ref'], 'frag_id'=>$eInfo['frag_id']
				  );
				 }
				}
			   }
			   
			//proccess the code fragments
			  //vars 
			   $fLineC=sizeof($fData);//gets the line count
			  //loops through the file data and process the code fragments
			   for($flC=0; $flC<$fLineC; $flC++){
			    //vars
				 $fLine=$flC;//file line number
				 $lText=$fData[$fLine];//gets the file line data
				 $aString=array(); $aIndex=0; $akMap=array();//string array, index, & key map
				 $nString=null; $oString=$fData[$fLine];//new & old string
				//determines if the line has a code fragment
				 //inline code fragment
				  if(!empty($iCode[$fLine])){//if there is an inline code fragment
				   $ikPos=array(); $ikpInfo=array();//gets the key position
				    foreach($iCode[$fLine] as$ici=>$icv){
					  $ikPos[]=$icv['frag']['start']; $ikPos[]=$icv['frag']['end'];
					  $ikpInfo[$icv['frag']['start']]=array(
					   'start'=>$icv['frag']['start'], 'end'=>$icv['frag']['end'],
					   'len'=>$icv['frag']['length'], 'frag_id'=>$icv['frag']['frag_id'],
					   'code_frag_id'=>$ici
					  );
					   $ikpInfo[$icv['frag']['end']] =& $ikpInfo[$icv['frag']['start']];//end position info reference start info
					 }
				   //builds a new string
				    for($sc=0; $sc<strlen($oString); $sc++){//loops through the original string
					 $wR=1;//write flag
					 //determines if to skip or not
					  if(in_array($sc, $ikPos)===true){//if the position has a key in it
					    $pkInfo=$ikpInfo[$sc];//gets the position key info
					    if(!empty($pkInfo)){//if position has a key
					     if($sc==$pkInfo['start']){//start position
						  $aIndex++; $wR=0; $sc=($pkInfo['start']+$pkInfo['len'])-1;//increases the new srtring array index and changes the string counter
						   $aString[$aIndex]=$iCode[$fLine][$pkInfo['code_frag_id']]['output'];//places the output where the fragment should be
						 }
						 elseif($sc==($pkInfo['start']+$pkInfo['len'])){ $aIndex++; }//end position
					    }
					   }
					 if($wR==1){ $aString[$aIndex].=$oString[$sc]; }//stores the text inside the text array
				    }
				    $asString=null;//builds the new string variable
					 for($asc=0; $asc<count($aString); $asc++){ $asString.=$aString[$asc]; }
				    //stores the text & code fragments
					 $iCode[$fLine]['new_text']=$aString;//store new marked up text
					 $iCode[$fLine]['new_string']=$asString;//stores the string version of the new string
					 $iCode[$fLine]['old_text']=$oString;//stores old text
				  }
			     //multi-lined code fragments
				  if(!empty($mCode[$fLine])){//if there is a multi-line code fragment =
				   //vars
				    $tHolder=array();//text holder
				    $fInfo=$mCode[$fLine];//multi-line code fragment info
				    $mfInfo=array(//multi-line fragment info
					 'lang'=>$fInfo['tag']['start']['lang'],
					 'tags'=>array( 'start'=>$fInfo['tag']['start']['value'], 'end'=>$fInfo['tag']['end']['value']),
					  'pos'=>array( 'start'=>$fInfo['tag']['start']['pos'], 'end'=>$fInfo['tag']['end']['pos']),
					 'line_count'=>$fInfo['line']['_all'], 'lines'=>array( 'start'=>$fInfo['line']['start'], 'end'=>$fInfo['line']['end'] ),
					 'frag-ids'=>array( 'start'=>$fInfo['frag_id']['start'], 'end'=>$fInfo['frag_id']['end'] ),
					);
				   //creates the text holder array
				    for($tCnt=$mfInfo['lines']['start']; $tCnt<=$mfInfo['lines']['end']; $tCnt++){ $tHolder[$tCnt]=$fData[$tCnt]; }//loops through the file data and stores the text
					$nText=null;//new text placeholder
					foreach($tHolder as$ti=>$tv){//loops through the text
					 if($ti==$mfInfo['lines']['start'] || $ti==$mfInfo['lines']['end']){//if there is extra text before or after the opening tag
					  $ctSel=($ti==$mfInfo['lines']['start']) ? 's' : 'e';//current selector
					   $ctSele=($ctSel=='s') ? 'start' : 'end';
					  $sPos=($ctSel=='s') ? $mfInfo['pos']['start'] : $mfInfo['pos']['end'];//split position
					  if(($sPos===0)==false||$ctSel=='e'){//if there is text
					   $eText=null;//grabs the extra text
					    if($ctSel=='s'){for($stc=0; $stc<$mfInfo['pos']['start']; $stc++){ $eText.=$tv[$stc]; }}
						else{for($etc=($mfInfo['pos']['end']+strlen($mfInfo['tags']['end'])); $etc<strlen($tv); $etc++){ $eText.=$tv[$etc]; }}
					   $tHolder['_pros']['extra_text'][$ctSele]=$eText;//stores the text text
					  }
					 }
					 $nText.=$tv;//stores the text value
					}
					$tHolder['_pros']['_text']=$nText;//stores the new text
				   //proccess the text holder
				    $cSnip=$tHolder['_pros']['_text'];//get the code snippet
				    $cOut=$this->modi(array( 'obj'=>'runner', 'code'=>$cSnip, 'lang'=>$mfInfo['lang'], 'start'=>$mfInfo['tags']['start'], 'end'=>$mfInfo['tags']['end'] ));//sends the fragment off for proccessing
				    $tHolder['_pros']['_output']=$cOut;//stores the code output 
					$ntText=null;//builds the new text
					 $ntText.=$tHolder['_pros']['extra_text']['start'];//adds starting extra text
					  $ntText.=$tHolder['_pros']['_output'];//adds the procceded code fragment
					 $ntText.=$tHolder['_pros']['extra_text']['end'];//adds the ending extra text
					 $tHolder['_pros']['_final']=$ntText;//stores the value
				   //stores the fragment output info along with the map
				    $mCode[$fLine]['output']=array(//stores the output in the multi array
					 'new_text'=>$tHolder['_pros']['_final'], '_extra'=>$tHolder['_pros']['extra_text'],//new & extra text
					 '_data'=>$tHolder['_pros']['_output'], '_text'=>$tHolder['_pros']['_text']//stores the output and text
					);
				  }
			   }
			 
			//stores the proccessed code fragments	  
			 //loops through the inline code array and stores & maps the output
			  //inline
			   foreach($iCode as$ioi=>$iov){
				//vars
				 $oId=$ioi; $frId=null;//output & frag id
				 $ofPos=array(); $ptCount=0;//builds the key fragment id list
				 foreach($iov as$iii=>$iiv){ if(is_numeric($iii)&&is_array($iiv)){
				  $ofPos[]=$iiv['frag']['frag_id'];//stores the frag id
				  $ptCount++;//parts counter
				 }}
				//stores the code output
				 $fOutput[$oId]=array(
				  'new_text'=>$iov['new_text'], 'new_string'=>$iov['new_string'],
				   'old_text'=>$iov['old_text'], 'type'=>'inline', 'parts'=>$ptCount
				 );
				foreach($ofPos as$psi=>$psv){ $fMap[$psv]=$oId; }//maps the tags to the output
			   }
			  //multi
			   foreach($mCode as$moi=>$mov){
				//vars
				 $oId=$moi; $frId=null;//output & frag id
				 $frId=$mov['frag_id'];//fragment id
				//stores the code output
				 $fOutput[$oId]=array(
				  'new_string'=>$mov['output']['new_text'], '_text'=>$mov['output']['_text'],//stores the output and code
				   'type'=>'multi', 'lines'=>$mov['line']['_all'],//stores the line type info
				   'line'=>array( 'start'=>$mov['line']['start'], 'end'=>$mov['line']['end'] )
				 );
				$fMap[$frId['start']]=$oId; $fMap[$frId['end']]=$oId;//maps the tags to the output
			   }
		    }
		   //proccess the output
		    $oupt=true;
		  }
		   else{ $oErr[]=array( 'error_id'=>'X001' ); }
		 //returns the output
		   if(!empty($oErr)){ $this->_hm(array( 'obj'=>'error', 'error'=>$oErr[0] )); }//if an output error should be sent
		   elseif($oBool===false){ $output=$oBool; }//if the output shouldn't go through
		   else{ $output=$oupt; }//gets the output ready for 
		  return$output;
		}
		elseif($obj=='parse' || $obj=='strip' || $obj=='parse_page' || $obj=='parse page' || $obj=='strip_page' || $obj=='strip page'){//parse page data
		 //values
		  $fName=$att['name'];//page name
		  $fData=$att['data'];//file data
		  $pgData=array();//page data
		  $cFrags=array(//code fragments
		   'frags'=> 0, 'output'=> array(), 'tags'=>array(), 'map'=>array()
		  );
		  $set=array();//already set
		 //reads page data
		  if(!empty($fData)&&is_array($fData)){
		    $lCount=0;//line count
			$lgPairs=array();//language pairs
			foreach($fData as$lCount=>$line){//reads the page data
			 //values
			  $lText=$line;//line text
			 //parse the line text
			  $cfSrh=array();//search result array
		      $psList=array();//postiton list
			  $kyPairs=array();//key pairs
			  foreach($this->pLangs as$li=>$lv){//loops through page language keys & pulls code fragment data
			   $frag_store=0;//flag to store fragment info
			   //lang value
			    $lgKeys=array(
				 'start'=>$this->pLangs[$li]['start'], 'end'=>$this->pLangs[$li]['end']
				);
			   $kSrh=array( 'start'=>array(), 'end'=>array() );//key position
			   $kSrhR=array( 'start'=>array(), 'end'=>array() );//key search result
			   $kyPos=null;//key position
			   //builds the code fragment key legend
			    foreach($kSrh as$lsKi=>$lsKv){//loops through the search parameters and fills in the needed info
		         foreach($lgKeys[$lsKi] as$ki=>$kv){//loops through the keys until one is found
				  //vars
				   $kCounter=0;//key counter
				   $cSrh=null; $kySrh=null;//secondary search & key search
				  do{//key check loop
				   $breaker=0;//breaker flag
				   $oSet=($cSrh>$kySrh) ? $cSrh : 0;//sets the key offset
				   $kySrh=strpos($lText, $kv, $oSet); $kpSrh=0;//searches for the key in the text & keep searching flag
				    if(($kySrh===false)!=true){//if it should keep searching
					 $cSrh=strpos($lText, $kv, ($kySrh+1));//secondary key search
					 //keep searching flag
					  $kpSrh=((($cSrh===false)!=true)&&$cSrh>$kySrh) ? 1 : 0;
					 if($kpSrh==0&&$kCounter>0){ $cSrh=null; }
					}
				    if($kySrh>=1||( ($kySrh===0)&&($kySrh===false)!=true )){//if a positive search result was found
					 //sets the key position info
					  if($kCounter==0){ $kyPos=$kySrh; }
					  else{ if($kpSrh==1){ $kyPos=($cSrh>$kySrh) ? $cSrh : $kySrh; }else{ $kyPos=$kySrh; } }
					 $frag_store=1;
					 if(in_array($kyPos, $psList)===false){
  					  $kSrh[$lsKi][]=1;
					  $kSrhR[$lsKi][$kyPos]=array( 'pos'=>$kyPos, 'value'=>$kv, 'lang'=>$li );
					   $psList[]=$kyPos;//stores the position location
					  if($kpSrh==0&&$kCounter>0){ break 1; $breaker=1; }
					  $kCounter++;//key counter
					 }
					}
				  } while($kpSrh==1);//keeps searching until keys ends
				 }
				}
			    //position sorter
				 ksort($kSrhR['start']); ksort($kSrhR['end']);//sort by key - short hand to order by position
				 sort($kSrhR['start']); sort($kSrhR['end']);//regulat sort - by first element
			   //builds the key pairs
			    $pCount=count($kSrh['start']);//pair count
				 if($pCount==0&&( count($kSrh['end'])>=1)){ $pCount=count($kSrh['end']); }
				for($pC=0; $pC<$pCount; $pC++){//builds the pairs
				 $pCn=$pC+1;//human counter
				 $pCt=(count($kyPairs)>$pCount) ? (count($kyPairs)+$pCn) : $pC;//index counter
				 $kyPairs[$pCt]=array( 'line'=>$lCount, 'start'=>$kSrhR['start'][$pC], 'end'=>$kSrhR['end'][$pC] );
				}
			  }
			  if(!empty($kyPairs)){ $lgPairs=array_merge($lgPairs, $kyPairs); }
			}
			//stores the code fragment info
			 $cFrags['frags']=count($lgPairs);//stores the count of code fragements
			 //proccess fragments (miscu)
			  $pirs=$lgPairs;//pair var reference
			  foreach($lgPairs as $lpI=>$lpV){//loops through the pairs and groups spit tags (code fragments)
			   $cTag=(empty($lpV['start']['value'])) ? 's' : 'e';//determines which tags is empty
			   $clTag=($cTag=='s') ? 'start' : 'end';//current tag name
			   if(empty($lpV[$clTag])){//fills in empty attributes
				if($cTag=='s'){for($eC=($lpI-1); $eC>=0; $eC--){//counts downwards for ending tags
				 if(!empty($lgPairs[$eC][$clTag])){
				  $lgPairs[$lpI][$clTag]=$rText['self'].$eC;//stores the reference to the next tag index number
				  if(!empty($lgPairs[$lpI][$clTag])){ break; }//ends the loop if the tags where switch
				 }
				}}
				else{for($eC=($lpI+1); $eC<=count($lgPairs); $eC++){//counts upwards for starting tags
				 if(!empty($lgPairs[$eC][$clTag])){
				  $lgPairs[$lpI][$clTag]=$rText['self'].$eC;//stores the reference to the next tag index number
				  if(!empty($lgPairs[$lpI][$clTag])){ break; }//ends the loop if the tags where switch
				 }
				}}
			   }
			  }
			 $cFrags['tags']=$lgPairs;//stores the code fragment info
		  }
		 //parse work
		  if(!empty($fName)){ $this->pInfo[$fName]['fragements']=$cFrags; }//stores the code fragment info in the page info array
		 $oupt=true;//sets the output
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
        $oupt=null; $output=null; $oBool=false;//output variables
		 $erCnt=0;//error counter
		  $erInfo=array();//error info
        $obj=($att['obj']) ?? ($att['objective']);//objective
        $list=$att['list'];//page list
        $reT=($att['return']) ?? ($att['return type']) ?? ($att['return_type']) ?? 1;//return type
        $ouT=($att['output_type']) ?? ($att['output type']) ?? null;
       //checks the objective
        if($obj=='assemble' || $obj=='make' || $obj=='together' || $obj=='put_together' || $obj=='put together'){//assembles the page
		 //value
		  $oBool=true;//switches the bool to true, stops on error
		  $pName=($att['name']) ?? ($att['page_name']) ?? ($att['page name']) ?? null;//page name
		  $pData=($att['data']) ?? ($att['page_data']) ?? ($att['page data']) ?? null;//page data
		  $naPage=array(); $nsPage=null;//new page array & string
		 //
		  if(!empty($pName)&&!empty($pData)){//if the page name and data are provided
		   //get page info
		    $pFrags=$this->pInfo[$pName]['fragements']; $pfCount=$pFrags['frags'];//page code fragments & count
			 $pfOutput=$pFrags['output']; $pfMap=$pFrags['map'];//output and map key
			 $pfTags=$pFrags['tags'];//tags info
		   //loops through the page data
		    for($plCount=0; $plCount<sizeof($pData); $plCount++){
			 //values
			  $lskip=0; $fLine=0; $isStart=0;//line skip; frag line flags
			   $fLine=(isset($pfOutput[$plCount])&&!empty($pfOutput[$plCount])) ? 1 : 0;//checks if this line has code fragments
			   $fType=($fLine==1) ? $pfOutput[$plCount]['type'] : null;//fragment type
			  $lText=$pData[$plCount];//gets the line data
			 //builds the new page array
			  //determines if the line should be skipped
			   if($fType=='multi'){ $skip=1; if($plCount==$pfOutput[$plCount]['line']['start']){ $isStart=1; }}//if the starting tag for multi line skips to ending line
			  if($skip!=1){ $naPage[]=$lText; }//regulat text add
			  else{//non-regluar proccessing
			   if($isStart==1){ $naPage[]=$pfOutput[$plCount]['new_string']; $plCount=$pfOutput[$plCount]['line']['end']+1; }//starting tag - addes the processed fragment
			  }
			}
		   //sets the output to the the new page array
		    $oupt=$naPage;//sets the new page array for output
		  }
		   else{ $oErr[]=array( 'error_id'=>'X005' ); $oBool=false; }
		 //output
		   if(!empty($oErr)){ $this->_hm(array( 'obj'=>'error', 'error'=>$oErr[0] )); }//if an output error should be sent
		   elseif($oBool===false){ $output=$oBool; }//if the output shouldn't go through
		   else{ $output=$oupt; }//gets the output ready for 
		  return$output;//return the output
		}
		elseif($obj=='load' || $obj=='read'){//loads the page
		 //values
		  $pgName=$att['name'];//page name
		  $fiName=($att['data']) ?? ($att['info']) ?? ($att['page_info']) ?? ($att['page info']);//page info
		  $pgData=array();//page data
		 //reads page data
		  if(file_exists($fiName)){//makes sure the file exists
		   $handle=fopen($fiName, "r");if($handle){//file opener
		    $lCount=0;//line count
			$lgPairs=array();//language pairs
			while(($line=fgets($handle))!==false){//reads the file line by line
			 $lCount++;//line count
			 //values
			  $lText=$line;//line text
			 $pgData[]=$lText;//adds the line text to the page data
			 $oBool=true;//output safe flag
			}
		   } else{ $erCnt++; $erInfo['error_id']=2; $erInfo['message']='errorX002'; }//if the file couldn't be opened
		  } else{ $erCnt++; $erInfo['error_id']=1; $erInfo['message']='errorX001'; }//if the file doesn't exists
		 //sets the output to the page data
		  if($erCnt>=1){ $oupt=$erInfo; throw new Exception($erInfo['message']); }//push an error message if one occured
		  else{//error-free
		   $oupt=$pgData;//sets the output
		  }
		 //returns the output
		  if($oBool==false){ $output=$oBool; }//error output flag - if true send a flase output
		  else{ $output=$oupt; }//if no error occured
		  return $output;//return the output
		}
		elseif($obj=='output'||$obj=='out'|| $obj=='paint'||$obj=='make'){//reads page & then creates an output
         //vals
          $npage=array();//new page holder
          $lpool=array();//list pool
          $opage=null;//old new page holder
         //loops through the list and gets the pool data
          foreach($list as$li=>$lv){ $lpool[$lv]=$this->pArray[$lv]; }
		  
         //builds the new page
          foreach($lpool as$pi=>$pv){if(!empty($pi)){//pool
           foreach($pv as$pgi=>$pgv){//page
            $npage[]=htmlspecialchars_decode($pgv);//stores the line value
           }
          }}
		  
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