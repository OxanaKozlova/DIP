<?php
require_once('image.php');

$im1 = new Image();
$im1->getImage('/home/oxana/projects/TsOS/lectures/pictures/1.jpg'); //  загрузка фото

$im2 = new Image();
$im2->getImage('/home/oxana/projects/TsOS/lectures/pictures/2.jpg'); //  загрузка фото

$im3 = new Image();
$im3->getImage('/home/oxana/projects/TsOS/lectures/pictures/6.jpg');

$im4 = new Image();
$im4->getImage('/home/oxana/projects/TsOS/lectures/pictures/7.jpg');

$im5 = new Image();
$im5->getImage('/home/oxana/projects/TsOS/lectures/pictures/5.jpg');

$im6 = new Image();
$im6->getImage('/home/oxana/projects/TsOS/lectures/pictures/8.jpg');

$im7 = new Image();
$im7->getImage('/home/oxana/projects/TsOS/lectures/pictures/3.jpg'); //  загрузка фото

$im8 = new Image();
$im8->getImage('/home/oxana/projects/TsOS/lectures/pictures/4.jpg'); // загрузка фото

 function checkRange($color) { //если значение канала выше 255 или ниже 0 присвоить 255 или 0 соответственно
    if($color > 255)
        return 255;
    if($color < 0)
        return 0;

    return $color;
}

function multiplicateConstant($image, $const){ //умножение каждого канала на константу
  $newRgb = [];
  for($i = 0; $i < $image->getHeight(); $i++ ){
    for($j = 0; $j < $image->getWidth(); $j++){
      $newRgb[$i][$j]['red'] = checkRange($image->getRgb()[$i][$j]['red'] * $const);
      $newRgb[$i][$j]['green'] = checkRange($image->getRgb()[$i][$j]['green'] * $const);
      $newRgb[$i][$j]['blue'] = checkRange($image->getRgb()[$i][$j]['blue'] * $const);
      $newRgb[$i][$j]['alpha'] = $image->getRgb()[$i][$j]['alpha'];
    }
  }
  return $newRgb;

}

function addConstant($image, $const){ //добавление к каждому каналу констатну
  $newRgb = [];
  for($i = 0; $i < $image->getHeight(); $i++ ){
    for($j = 0; $j < $image->getWidth(); $j++){
      $newRgb[$i][$j]['red'] = checkRange($image->getRgb()[$i][$j]['red'] + $const);
      $newRgb[$i][$j]['green'] = checkRange($image->getRgb()[$i][$j]['green'] + $const);
      $newRgb[$i][$j]['blue'] = checkRange($image->getRgb()[$i][$j]['blue'] + $const);
      $newRgb[$i][$j]['alpha'] = $image->getRgb()[$i][$j]['alpha'];
    }
  }
  return $newRgb;

}

function addImages($im1, $im2){ //сложение двух изображение
  $newRgb = [];
  for($i = 0; $i < $im1->getHeight(); $i ++){
    for($j = 0; $j < $im1->getWidth(); $j++){
      $newRgb[$i][$j]['red'] = checkRange($im1->getRgb()[$i][$j]['red'] + $im2->getRgb()[$i][$j]['red']); //сложение красного канала
      $newRgb[$i][$j]['green'] = checkRange($im1->getRgb()[$i][$j]['green'] + $im2->getRgb()[$i][$j]['green']);//сложение зеленого канала
      $newRgb[$i][$j]['blue'] = checkRange($im1->getRgb()[$i][$j]['blue'] + $im2->getRgb()[$i][$j]['blue']);//сложение синего канала
      $newRgb[$i][$j]['alpha'] = $im1->getRgb()[$i][$j]['alpha'] ; // присвоить прозрачность первого изображения
    }
  }
  return $newRgb;
}


function subtractImages($im1, $im2){ // вычитание изображений
  $newRgb = [];
  for($i = 0; $i < $im1->getHeight(); $i ++){
    for($j = 0; $j < $im1->getWidth(); $j++){
      $newRgb[$i][$j]['red'] = checkRange($im1->getRgb()[$i][$j]['red'] - $im2->getRgb()[$i][$j]['red']);
      $newRgb[$i][$j]['green'] = checkRange($im1->getRgb()[$i][$j]['green'] - $im2->getRgb()[$i][$j]['green']);
      $newRgb[$i][$j]['blue'] = checkRange($im1->getRgb()[$i][$j]['blue'] - $im2->getRgb()[$i][$j]['blue']);
      $newRgb[$i][$j]['alpha'] = $im1->getRgb()[$i][$j]['alpha'] ;
    }
  }
  return $newRgb;

}

function createBlackWhite($im){ // создание чернобелого изображения
  $newRgb = [];

  for($i = 0; $i < $im->getHeight(); $i ++){
    for($j = 0; $j < $im->getWidth(); $j++){
      if($im->getRgb()[$i][$j]['red'] > 125 || $im->getRgb()[$i][$j]['blue'] > 125 || $im->getRgb()[$i][$j]['green'] > 125){
        $newRgb[$i][$j]['red'] = 255; // если значение канала Ю 125 то присвоить значение 255, иначе 0
        $newRgb[$i][$j]['green'] = 255;
        $newRgb[$i][$j]['blue'] =255;
        $newRgb[$i][$j]['alpha'] = 255 ;
      }
      else {
        $newRgb[$i][$j]['red'] = 0;
        $newRgb[$i][$j]['green'] = 0;
        $newRgb[$i][$j]['blue'] =0;
        $newRgb[$i][$j]['alpha'] = 0 ;
      }

    }
  }
  $im->rgb = $newRgb;

}

function andFunc($im1, $im2){ // логическое И для бинарных изображений
  $newRgb = [];
  createBlackWhite($im1);
  createBlackWhite($im2);
  for($i = 0; $i < $im1->getHeight(); $i ++){
    for($j = 0; $j < $im1->getWidth(); $j++){

      if(($im1->getRgb()[$i][$j]['red'] == 0 && $im2->getRgb()[$i][$j]['red'] == 0)
        || ($im1->getRgb()[$i][$j]['blue'] == 0 && $im2->getRgb()[$i][$j]['blue'] == 0)
        || ($im1->getRgb()[$i][$j]['green'] == 0 && $im2->getRgb()[$i][$j]['green'] == 0)){
          $newRgb[$i][$j]['red'] = 0; // если пиксели первого и второго изображения равны 0 , то присвоить 0
          $newRgb[$i][$j]['green'] = 0;
          $newRgb[$i][$j]['blue'] = 0;
          $newRgb[$i][$j]['alpha'] = 0 ;
        }
        else{
          $newRgb[$i][$j]['red'] = 255;
          $newRgb[$i][$j]['green'] = 255;
          $newRgb[$i][$j]['blue'] = 255;
          $newRgb[$i][$j]['alpha'] = 0;

        }
    }
  }
  return $newRgb;

}

function orFunc($im1, $im2){ // логическое ИЛИ
  $newRgb = [];
  createBlackWhite($im1);
  createBlackWhite($im2);
  for($i = 0; $i < $im1->getHeight(); $i ++){
    for($j = 0; $j < $im1->getWidth(); $j++){

      if(($im1->getRgb()[$i][$j]['red'] == 0 || $im2->getRgb()[$i][$j]['red'] == 0)
        || ($im1->getRgb()[$i][$j]['blue'] == 0 || $im2->getRgb()[$i][$j]['blue'] == 0)
        || ($im1->getRgb()[$i][$j]['green'] == 0 || $im2->getRgb()[$i][$j]['green'] == 0)){
          $newRgb[$i][$j]['red'] = 0;
          $newRgb[$i][$j]['green'] = 0;
          $newRgb[$i][$j]['blue'] = 0;
          $newRgb[$i][$j]['alpha'] = 0 ;
        }
        else{
          $newRgb[$i][$j]['red'] = 255;
          $newRgb[$i][$j]['green'] = 255;
          $newRgb[$i][$j]['blue'] = 255;
          $newRgb[$i][$j]['alpha'] = 0;

        }
    }
  }
  return $newRgb;

}

function xorFunc($im1, $im2){ //логическая операция XOR
  $newRgb = [];
  createBlackWhite($im1);
  createBlackWhite($im2);
  for($i = 0; $i < $im1->getHeight(); $i ++){
    for($j = 0; $j < $im1->getWidth(); $j++){

      if(($im1->getRgb()[$i][$j]['red'] != $im2->getRgb()[$i][$j]['red'] )
        || ($im1->getRgb()[$i][$j]['blue'] != $im2->getRgb()[$i][$j]['blue'] )
        || ($im1->getRgb()[$i][$j]['green'] != $im2->getRgb()[$i][$j]['green'])){
          $newRgb[$i][$j]['red'] = 0;
          $newRgb[$i][$j]['green'] = 0;
          $newRgb[$i][$j]['blue'] = 0;
          $newRgb[$i][$j]['alpha'] = 0 ;
        }
        else{
          $newRgb[$i][$j]['red'] = 255;
          $newRgb[$i][$j]['green'] = 255;
          $newRgb[$i][$j]['blue'] = 255;
          $newRgb[$i][$j]['alpha'] = 0;

        }
    }
  }
  return $newRgb;

}

function notFunc($im1){ //инверсия чернобелого изображения
  $newRgb = [];
  createBlackWhite($im1);
  for($i = 0; $i < $im1->getHeight(); $i ++){
    for($j = 0; $j < $im1->getWidth(); $j++){

      if($im1->getRgb()[$i][$j]['red'] == 0  && $im1->getRgb()[$i][$j]['blue'] == 0
         && $im1->getRgb()[$i][$j]['green'] == 0 ){
          $newRgb[$i][$j]['red'] = 255;
          $newRgb[$i][$j]['green'] = 255;
          $newRgb[$i][$j]['blue'] = 255;
          $newRgb[$i][$j]['alpha'] = 0 ;
        }
        else{
          $newRgb[$i][$j]['red'] = 0;
          $newRgb[$i][$j]['green'] = 0;
          $newRgb[$i][$j]['blue'] = 0;
          $newRgb[$i][$j]['alpha'] = 0;

        }
    }
  }
  return $newRgb;

}

function generateRandomMask($h, $w){ // генерация рандомной маски для маскирования
  $mask = [];
  for($i = 0; $i < $h; $i ++){
    for($j = 0; $j < $w; $j++){
      $mask[$i][$j] = rand(0, 1);
    }
  }
  return $mask;
}

function masking($im){ //маскирование
  $mask = generateRandomMask($im->getHeight(), $im->getWidth());

  $newRgb = [];
  for($i = 0; $i < $im->getHeight(); $i ++){
    for($j = 0; $j < $im->getWidth(); $j++){ //умножение каждого канада на значение маски (0 или 1)
      $newRgb[$i][$j]['red'] =  $im->getRgb()[$i][$j]['red'] * $mask[$i][$j] ;
      $newRgb[$i][$j]['green'] = $im->getRgb()[$i][$j]['green'] * $mask[$i][$j];
      $newRgb[$i][$j]['blue'] = $im->getRgb()[$i][$j]['blue'] * $mask[$i][$j] ;
      $newRgb[$i][$j]['alpha'] = $im->getRgb()[$i][$j]['alpha'];
    }
  }

  return $newRgb;
}


function createBarChart($canal){ // построение гистограммы для одного канала(для эквализации)
  $barChart = [];
  for($k = 0; $k < 255; $k++){
    $barChart[$k] = 0;
    for($i = 0; $i < count($canal); $i++){
      if($canal[$i] == $k){
        $barChart[$k] ++;
      }
    }
  }
  return $barChart;
}

function getCanal($im, $canal){ // получение массива значений одного канала для изображения(для эквализации)

  $canal_array = [];
  for($i = 0; $i < $im->getHeight(); $i ++){
    for($j = 0; $j < $im->getWidth(); $j++){
      $canal_array[] = $im->getRgb()[$i][$j][$canal];
    }
  }
  return $canal_array;

}
function normalize($arr, $count){ //нормализация гистограммы (для эквализации)
  $norm = [];
  foreach($arr as $a){
    $norm[] = $a * 255/$count;
  }

  return $norm;

}

function newBarChart($barChart){ // вычисление новых значений гистограммы путем суммирования(для эквализации)
  $newBarChart = array_fill(0, 256, 0);
  for($i = 0; $i < count($newBarChart); $i++){
    for($j = 0; $j < $i; $j++){
      $newBarChart[$i] += $barChart[$j];
    }
    $newBarChart[$i] = (integer)$newBarChart[$i];
  }
  return $newBarChart;

}

function eq($im){ // эквализация
  $newRgb = [];
  $redBarChart = createBarChart(getCanal($im, 'red')); // получение эквализации для каждого канала
  $blueGreenChart = createBarChart(getCanal($im, 'green'));
  $blueBarChart = createBarChart(getCanal($im, 'blue'));
  $normRed = normalize($redBarChart, $im->getHeight() * $im->getWidth()); // нормирование гистограммы для каждого канала
  $normGreen = normalize($blueGreenChart, $im->getHeight() * $im->getWidth());
  $normBlue = normalize($blueBarChart, $im->getHeight() * $im->getWidth());
  $newRedBarChart = newBarChart($normRed); // новая гистограмма
  $newGreenBarChart = newBarChart($normGreen);
  $newBlueBarChart = newBarChart($normBlue);
  for($i = 0; $i < $im->getHeight(); $i ++){
    for($j = 0; $j < $im->getWidth(); $j++){ //получение новых значний каждого канада
      $newRgb[$i][$j]['red'] = $newRedBarChart[$im->getRgb()[$i][$j]['red']];
      $newRgb[$i][$j]['green'] = $newGreenBarChart[$im->getRgb()[$i][$j]['green']];
      $newRgb[$i][$j]['blue'] = $newBlueBarChart[$im->getRgb()[$i][$j]['blue']];
      $newRgb[$i][$j]['alpha'] = $im->getRgb()[$i][$j]['alpha'];
    }
  }
  return $newRgb;


}

function sawtooth(){ // генерация пилообразного сигнала
  $sawtooth = [];
  for($i = 0, $j = 0; $i < 255; $i++){
    if($j == 128){
      $j = 0;
    }
      $sawtooth[$i] = $j*2;
      $j++;
    }
    return $sawtooth;
}

function luminanceSlice($im){ // получение новых значений каналов с учетом пилообразного среза
  $sawtooth = sawtooth();
  $newRgb = [];
  for($i = 0; $i < $im->getHeight(); $i ++){
    for($j = 0; $j < $im->getWidth(); $j++){
      $newRgb[$i][$j]['red'] = $sawtooth[$im->getRgb()[$i][$j]['red']];
      $newRgb[$i][$j]['blue'] = $sawtooth[$im->getRgb()[$i][$j]['blue']];
      $newRgb[$i][$j]['green'] = $sawtooth[$im->getRgb()[$i][$j]['green']];
      $newRgb[$i][$j]['alpha'] = $im->getRgb()[$i][$j]['alpha'];
    }
  }

  return $newRgb;

}

$add_images = new Image('/home/oxana/projects/TsOS/lectures/pictures/add_images.jpg', $im1->getWidth(), $im1->getHeight(), addImages($im1, $im2)); // сложение двух изображений
$sub_images = new Image('/home/oxana/projects/TsOS/lectures/pictures/sub_images.jpg', $im7->getWidth(), $im7->getHeight(), subtractImages($im8, $im7)); //вычитание двух изображений
$add = new Image('/home/oxana/projects/TsOS/lectures/pictures/add.jpg', $im8->getWidth(), $im8->getHeight(),addConstant($im8, 105)); //добавление константы
$multiplication = new Image('/home/oxana/projects/TsOS/lectures/pictures/multiplicate.jpg', $im1->getWidth(), $im1->getHeight(), multiplicateConstant($im1, 0.75)); // умножение на константу
$masling = new Image('/home/oxana/projects/TsOS/lectures/pictures/masking.jpg',$im5->getWidth(), $im5->getHeight(), masking($im5)); // маскирование

//логические операции
$not = new Image('/home/oxana/projects/TsOS/lectures/pictures/not.jpg',$im4->getWidth(), $im4->getHeight(), notFunc($im4));
$xor = new Image('/home/oxana/projects/TsOS/lectures/pictures/xor.jpg',$im4->getWidth(), $im4->getHeight(), xorFunc($im3, $im4));
$or = new Image('/home/oxana/projects/TsOS/lectures/pictures/or.jpg',$im4->getWidth(), $im4->getHeight(), orFunc($im3, $im4));
$and = new Image('/home/oxana/projects/TsOS/lectures/pictures/and.jpg',$im4->getWidth(), $im4->getHeight(), andFunc($im3, $im4));

//пилообразный срез
$luminanceSlice = new Image('/home/oxana/projects/TsOS/lectures/pictures/luminanceSlice.jpg',$im6->getWidth(), $im6->getHeight(), luminanceSlice($im6));

//эквализация
$eq = new Image('/home/oxana/projects/TsOS/lectures/pictures/eq.jpg',$im5->getWidth(), $im5->getHeight(), eq($im5));
