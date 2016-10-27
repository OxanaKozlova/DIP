<?php


require_once('lab2.php');

$v = [];
$w = [];
$q = [];
$t = [];
$d = [];
$g_count = 2;
$k_count = 2;
$temp_d = [];

function teach($x_r, $y_r){
  global $v, $w, $q, $t, $d, $temp_d;

  $g = setG($x_r);
  $y = setY($g);

  $d = setD($y_r, $y);
  $e = setE($d, $y);

  correctW($d, $y, $g);
  correctT($y, $d);
  correctV($g, $e, $x_r);
  correctQ($g, $e);

}

function setG($x){
  global $v, $q, $g_count;
  $g = [];
  for($j = 0; $j < $g_count; $j++ ){
    $g[$j] = 0;
    for($i = 0; $i < count($x); $i++){
      $g[$j] += $v[$i][$j] * $x[$i] + $q[$j];
    }
    $g[$j] = activate($g[$j]);
  }
  return $g;
}

function setY($g){
  global $w, $t, $g_count, $k_count;
  for($k = 0; $k < $k_count; $k++){
    $y[$k] = 0;
    for($j = 0; $j < $g_count; $j++ ){
      $y[$k] += $w[$j][$k] * $g[$j] + $t[$k];
    }
    $y[$k] = activate($y[$k]);
  }

  return $y;

}

function setD($y_r, $y){
  global $temp_d;

$d = [];
  for($i = 0; $i < count($y_r); $i++){
    $d[$i] = $y_r[$i] - $y[$i];
    $temp_d[] = abs($y_r[$i] - $y[$i]);
  }

  return $d;
}

function setE($d, $y){
  global $w, $g_count, $k_count;

  $e = [];
  for($j = 0;  $j < $g_count; $j++){
    $e[$j] = 0;
    for($k = 0; $k < $k_count; $k++){
      $e[$j] += $d[$k] * $y[$k] * (1 - $y[$k]) * $w[$j][$k];
    }
  }

  return $e;

}

function correctW($d, $y, $g){
  global $w, $g_count, $k_count;

  for($j = 0; $j < $g_count; $j++){
    for($k = 0; $k < $k_count; $k++){
      $w[$j][$k] += $y[$k] * (1 - $y[$k]) * $d[$k] * $g[$j];
    }
  }

}

function correctT($y, $d){
  global $t, $k_count;
  for($k = 0; $k < $k_count; $k++){
    $t[$k] += $y[$k] * (1 - $y[$k]) * $d[$k];
  }

}

function correctV($g, $e, $x){
  global $v, $g_count;
  for($i = 0; $i < count($x); $i++){
    for($j = 0; $j < $g_count; $j++){
      $v[$i][$j] += $g[$j] * (1 - $g[$j]) * $e[$j] * $x[$i];
    }
  }

}

function correctQ($g, $e){
  global $q, $g_count;
  for($j = 0; $j < $g_count; $j++){
    $q[$j] += $g[$j] * (1 - $g[$j]) * $e[$j];
  }
}



function activate($item){
  return 1/(1 + exp(-$item));

}

function recognition($x){
  global $v, $w, $q, $t;
  echo "suqare {$x[0]}\n";
  echo "perimeter {$x[1]}\n";
  echo "density {$x[2]}\n";
  $g_temp = setG( $x);
  $y_temp = setY($g_temp);
  if($y_temp[0] < $y_temp[1])
  {
    echo "sugar\n";
  }
  else{
    echo "spoon\n";
  }
  echo "\n";

}
function firstInitializationMatrix($n, $m){
  $temp = [];
  for($i = 0; $i < $n; $i++){
    for($j = 0; $j < $m; $j++){
      $temp[$i][$j] = 1;
    }
  }
  return $temp;
}

function firstInitializationArray($n){
  $temp = [];
  for($i = 0; $i < $n; $i++){
    $temp[$i] = 1;
  }
  return $temp;
}

// $xx = array(array(870,-1,-1), array(870,-1,1), array(870,1,-1), array(870,-1,-1),
//  array(1,1,1), array(1,1,-1), array(1,-1,1));
// $yy = array(array(1,0),array(1,0),array(1,0),
// array(0,1),array(0,1),array(0,1), array(0,1));


 $xx = array(array(1040, 115,12.7),
      array(1080,116, 12.45),
    array(942, 100, 10.62),
array(1000, 95, 9.025),
array(3809, 343, 30.88),
array(4057, 410, 41.43),
array(3863, 404, 42.25),
array(4054, 411, 41.67));
 $yy = array(array(1,0),array(1,0),array(1,0),array(1,0),
  array(0,1), array(0,1), array(0,1), array(0,1));



$v = firstInitializationMatrix(3, 2);
$w = firstInitializationMatrix(2, 2);
$q = firstInitializationArray(2);
$t = firstInitializationArray(2);

$D = 0.6;
do{
  for($i = 0; $i < count($xx); $i++){
    $temp_d = [];
    teach($xx[$i], $yy[$i]);

  }
}while(max($temp_d) > $D);



$images = returnImage();
foreach($images as $image){
  recognition(array($image->square, $image->perimeter, $image->density));
}
