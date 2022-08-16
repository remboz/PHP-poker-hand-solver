<?php
 function splitElement($value){
   return str_split($value);
 }
 
 function cardToVal($arr){
   for($i=0;$i<count($arr);$i++){
    $val=$arr[$i];
    if($val=="T"){
        $arr[$i]=10;
    }
    else if($val=="J"){
        $arr[$i]=11;
    }
    else if($val=="Q"){
        $arr[$i]=12;
    }
    else if($val=="K"){
        $arr[$i]=13;
    }
    else if($val=="A"){
        $arr[$i]=14;
    }
   }
   return $arr;
 }
 
 function valToCard($arr){
  for($i=0;$i<count($arr);$i++){
    $val=$arr[$i];
    
    if($val==10){
        $arr[$i]="T";
    }
    else if($val==11){
        $arr[$i]="J";
    }
    else if($val==12){
        $arr[$i]="Q";
    }
    else if($val==13){
        $arr[$i]="K";
    }
    else if($val==14){
        $arr[$i]="A";
    }
   }
   return $arr;
 }
 
 function selisihValue($arr){
    sort($arr);
    $con=[];
    for($i=0;$i<count($arr)-1;$i++){
        $next=$arr[$i+1];
        $con[$i]=$next-$arr[$i];
    }
    return join($con);
 }
 
 function countVal($arr){
     $val= array_count_values($arr);
     arsort($val);
     return join($val);
 }
 
 function getPoint($arr){
     rsort($arr,SORT_NUMERIC);
     $val= array_count_values($arr);
     $jump = count($val);
     $output = [];
     $j = 0;
     foreach ($val as $item => $num) {
         
         for ($i=0; $i < $num; $i++) {
         $pos = $j + ($i * $jump);
         $output[$pos] = $item+(20*$num);
         }
       
        $j++;
     }
     
     arsort($output,SORT_NUMERIC);
     return join($output);
 }
 
 
 function solveHandCards5($cards){
   $data= array_map("splitElement",$cards);
   $value=array_column($data,0);
   $suits= array_column($data,1);
   $orderValue=cardToVal($value);
   sort($orderValue);
   $selVal=selisihValue($orderValue);
   $cVal= countVal($value);
   $cSuits=countVal($suits);
   $point=getPoint($orderValue);
   $res= new stdClass();
   $res->cards=$cards;
   
   // get rank cards 
   if($cSuits=="5" && $selVal=="1111" && (join($orderValue)=="1011121314")){
      $res->title= "royal flush";
      $res->point=7*10000000000+$point;
   }
   else if(($selVal=="1111"||$selVal=="1119") && $cSuits=="5"){
      $res->title= "straight flush";
      $res->point=9*10000000000+$point;
   }
   else if($cVal=="41"){
      $res->title= "4 of kind";
      $res->point=8*10000000000+$point;
   }
   else if($cVal=="32"){
      $res->title= "full house";
      $res->point=7*10000000000+$point;
   }
   else if($cSuits=="5"){
      $res->title= "flush";
      $res->point=6*10000000000+$point;
   }
   else if($selVal=="1111"||$selVal=="1119"){
      $res->title= "straight";
      $res->point=5*10000000000+$point;
   }
   else if($cVal=="311"){
      $res->title= "3 of kind";
      $res->point=4*10000000000+$point;
   }
   else if($cVal=="221"){
      $res->title= "2 Pairs";
      $res->point=3*10000000000+$point;
   }
   else if($cVal=="2111"){
      $res->title= "1 Pairs";
      $res->point=2*10000000000+$point;
   }
   else if($cVal=="11111"){
      $res->title= "high card";
      $res->point=1*10000000000+$point;
   }
   else{
      $res->title= "undefined";
      $res->point=0;
   }
   
   return $res;
}

function array_default_key($array) {
    $arrayTemp = array();
    $i = 0;
    foreach ($array as $key => $val) {
        $arrayTemp[$i] = $val;
        $i++;
    }
    return $arrayTemp;
}

function possible5($cards){
    $cards5=[];
    $num=[];
    
    $ind=0;
    for($h=0;$h<count($cards)-1;$h++){
      for($i=$h;$i<count($cards)-1;$i++){
        
        $c1=$cards[$h];
        $c2=$cards[$i+1];
        $num=[$c1,$c2];
        
        $cards5[$ind]= array_default_key(array_diff($cards,$num));
        
        $ind++;
       }
       
    }
    
    return $cards5;
}

function solveHandCards7($cards){
   $possible5= possible5($cards);
   $res=[];
   
   for($i=0;$i<count($possible5);$i++){
       $res[$i]= solveHandCards5($possible5[$i]);
   }
   
   usort($res,function($first,$second){
    return $first->point < $second->point;
   });
   return $res["0"];
}
?> 
