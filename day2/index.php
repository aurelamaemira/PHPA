<?php

$num=5;

// if($num<10){
//     echo "$num is less than 10";

// }else{

//     echo "$num";
// }

// if($num<0){
//     echo "The value of \$num is a negative number";
// }elseif($num==0){
//     echo "The value of \$num is 0";
// }else{
//     echo "The value of \$num is a positive number";
// }

// $age=15;
// switch($age){
//     case($age>=0 && $age<18);
//     echo "You are a minor (0-18 years old ) <br>";
//     break;
//     case($age>=18 && $age<=25)
//      echo "You are an young adult <br>";
//      break;
//      case($age>25);
//       echo "You are an adult <br>";
//       break;
//       default:
//       echo "Invalid age input";
//     }

// $x=1;
// while($x<=5){
//     echo "The number is :$x <br>"
//     $x++;
// }

// do{
//     echo "The nummber is :$x <br>";
//     $x++;
// }while ($x<=5);

// for($y=1;$y<=10;$y++){
//     echo "The number is :$y <br>";
// }

// $cars+["BMW", "Mercedes", "Audi"];
// // $cars=array()
// foreach ($cars as $value){
//     echo "$value <br>";
// }

$age=["Vlera"=>"23", "Leutrim"=>"18", "Ensar"=>"17", "Rinis"=>"18", "Aurela"=>"17"];

foreach($age as $x=>$val){
    echo "$x = $val <br>";
}