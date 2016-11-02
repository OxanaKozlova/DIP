<?php
require_once('GD2Imaging.php');
require_once('image_object.php');
require_once('claster.php');


class UserImage{

  private $width = 0;
  private $height = 0;
  private  $rgb = [];
  private $image = 0;
  private $areas = [];
  private $squares = [];
  private $figuresNumber = [];
  private $perimeters = [];
  private $densities = [];
  private $imageObjects = [];
  private $centerOfMass = [];
  private $claster1;
  private $claster2;
  private $filename = '/home/oxana/projects/TsOS/test.jpeg';

  public function getImage($path)
  {
    $size = getimagesize($path);

    $this->width = $size[0];
    $this->height = $size[1];

    if (exif_imagetype($path) === IMAGETYPE_JPEG) {
        $this->image = imagecreatefromjpeg($path);            //возвращает идентификатор изображения
    }
    elseif (exif_imagetype($path) === IMAGETYPE_PNG) {
            $this->image = imagecreatefrompng($path);
    }
  }

  public function getRgbArray()
   {
       $pixels = [];
        for ($i = 0; $i < $this->height; $i++) {
           for ($j = 0; $j < $this->width; $j++) {
               $pixels[$i][$j] = imagecolorat($this->image, $j, $i); //получение цвета пикселя
               $colors[$i][$j] = imagecolorsforindex($this->image, $pixels[$i][$j]); //получение rgb массива для каждого пикселя
           }
       }
       for ($i = 0; $i < $this->height; $i++) {
          for ($j = 0; $j < $this->width; $j++) {

              $bin_colors[$i][$j]['color'] = $colors[$i][$j]['red'];
              $bin_colors[$i][$j]['area'] = 0;
          }
      }


       return $bin_colors;
   }

   public function createBlackWhiteImage()
   {

    imagefilter($this->image, IMG_FILTER_GRAYSCALE);

    imagefilter($this->image, IMG_FILTER_CONTRAST, -500);
    imagejpeg($this->image, '/home/oxana/projects/TsOS/test.jpeg');

    $im = new Image('/home/oxana/projects/TsOS/test.jpeg');
    $this->image =  $im->useMedian(7,7)->image;


    imagefilter($this->image, IMG_FILTER_CONTRAST, -1000);
    imagejpeg($this->image, '/home/oxana/projects/TsOS/test1.jpeg');



   }



   public function scanning()
   {

     $n = 0;
     $eq = [];
     $color = $this->getRgbArray();
     for( $i = 1; $i<$this->height; $i++){
       for($j = 1; $j<$this->width; $j++){
         if($color[$i][$j]['color']!=0){
           if($color[$i][$j-1]['area'] == 0 && $color[$i-1][$j]['area'] == 0){
             $n++;
             $color[$i][$j]['area'] = $n;
           }
           elseif($color[$i-1][$j]['area'] == $color[$i][$j-1]['area']  && $color[$i][$j-1]['area'] != 0){
             $color[$i][$j]['area'] = $color[$i-1][$j]['area'];
           }
           elseif($color[$i-1][$j]['area'] != 0 &&  $color[$i][$j-1]['area'] != 0  && $color[$i][$j-1]['area'] != $color[$i-1][$j]['area']){
             $color[$i][$j]['area'] = $color[$i-1][$j]['area'];

             for( $k = 1; $k<=$i; $k++){
               for($m = 1; $m<$this->width; $m++){
                 if($k == $i && $m == $j ){
                   break;
                 }
                 if($color[$k][$m]['area'] == $color[$i][$j-1]['area']){
                   $color[$k][$m]['area'] = $color[$i-1][$j]['area'];
                 }

               }
             }

           }
           elseif($color[$i][$j-1]['area'] != 0  ){
             $color[$i][$j]['area'] = $color[$i][$j-1]['area'];

           }
           elseif($color[$i-1][$j]['area'] != 0 ){
             $color[$i][$j]['area'] = $color[$i-1][$j]['area'];
           }
         }
       }

     }


     $this->areas = $color;
     $this->getNumber();



   }

   public function createImageObject(){
     $temp = array_unique($this->figuresNumber);
     foreach($temp as $item){
       $this->imageObjects [] = new ImageObject($item);
     }


   }

public function getNumber(){

  for( $i = 0; $i<$this->height; $i++){
    for($j = 0; $j<$this->width; $j++){
      if($this->areas[$i][$j]['area']!= 0){
        $this->figuresNumber[] = $this->areas[$i][$j]['area'];
      }
    }
  }
}

   public function getSquare(){

     $this->squares = array_count_values($this->figuresNumber);
     foreach($this->imageObjects as $ob){
       $ob->square = $this->squares[$ob->area];
     }


   }



   public function getPerimeters(){

     $temp = array_unique($this->figuresNumber);
     foreach($this->imageObjects as $ob){
       $perimeter = 0;
       for( $i = 1; $i<$this->height-1; $i++){
         for($j = 1; $j<$this->width-1; $j++){
           if($this->areas[$i][$j]['area'] == $ob->area && ($this->areas[$i-1][$j]['area'] == 0 || $this->areas[$i+1][$j]['area'] == 0
           || $this->areas[$i][$j-1]['area'] == 0 || $this->areas[$i][$j+1]['area'] == 0)){
             $ob->perimeter++ ;
           }
         }
       }
     }
   }

  public function getDensity(){
     foreach($this->imageObjects as $ob){
       $ob->density = pow($ob->perimeter, 2)/$ob->square;
     }
   }

public function getCentreOfMass(){
  foreach($this->imageObjects as $ob){
    $x = 0;
    $y = 0;
    for( $i = 0; $i<$this->height; $i++){
      for($j = 0; $j<$this->width; $j++){
        if($this->areas[$i][$j]['area'] == $ob->area){
          $x += $i;
          $y += $j;
        }
      }
    }
    $ob->center_x = (integer)($x/$ob->square);
    $ob->center_y = (integer)($y/$ob->square);
  }
}

function getImageObjects(){
  return $this->imageObjects;
}


function clasterAnalysis(){

  $indexes = [];


  for($i = 0; $i < count($this->imageObjects); $i++){
    if($this->imageObjects[$i]->square < 500){
      $indexes[] = $i;
    }
  }
  foreach($indexes as $i){
    unset($this->imageObjects[$i]);

  }


  $claster1 = new Claster();

  $claster1->current_point->square = 0;
  $claster1->current_point->perimeter = 0;
  $claster1->current_point->density = 0;

  $claster2 = new Claster();

  $claster2->current_point->square = 100;
  $claster2->current_point->perimeter = 100;
  $claster2->current_point->density = 100;




  while(true){


    $perimeters1 = [];
    $squares1 = [];
    $densities1 = [];

    $perimeters2 = [];
    $squares2 = [];
    $densities2 = [];


    foreach($this->imageObjects as $im){
      if($im != $claster1->current_point && $im != $claster2->current_point ){
        $distance1 = $this->calculateDistance($claster1, $im);
        $distance2 = $this->calculateDistance($claster2, $im);


        if($distance1 <= $distance2){
          $claster1->values[] = $im;


          $perimeters1[] = $im->perimeter;
          $squares1[] = $im->square;
          $densities1[] = $im->density;
        }
        else{



          $claster2->values[] = $im;

          $perimeters2[] = $im->perimeter;
          $squares2[] = $im->square;
          $densities2[] = $im->density;
        }

      }
    }


      $claster1->last_point = clone $claster1->current_point;


      $claster1->current_point->square = $this->calculate_median($squares1);
      $claster1->current_point->perimeter = $this->calculate_median($perimeters1);
      $claster1->current_point->density = $this->calculate_median($densities1);



      $claster2->last_point = clone $claster2->current_point;


      $claster2->current_point->square = $this->calculate_median($squares2);
      $claster2->current_point->perimeter = $this->calculate_median($perimeters2);
      $claster2->current_point->density = $this->calculate_median($densities2);

      if( $claster1->last_point->square == $claster1->current_point->square
        &&  $claster1->last_point->perimeter == $claster1->current_point->perimeter
        &&  $claster1->last_point->density == $claster1->current_point->density
        &&  $claster2->last_point->square == $claster2->current_point->square
        &&  $claster2->last_point->perimeter == $claster2->current_point->perimeter
        &&  $claster2->last_point->density == $claster2->current_point->density){


          $this->claster1 = $claster1;
          $this->claster2 = $claster2;


          break;
        }

      $claster1->values = [];
      $claster2->values = [];


  }

  $this->createImage($this->getRgbArray(), '/home/oxana/projects/TsOS/test.jpeg' );
}


   public function createImage($rgbArray, $filename)
    {

        $image = imagecreatetruecolor($this->width, $this->height);

        for($i = 0; $i < $this->height; $i++) {
            for($j = 0; $j < $this->width; $j++) {
              foreach($this->claster1->values  as $v){
                if($this->areas[$i][$j]['area'] == $v->area){
                  imagesetpixel($image, $j, $i, imagecolorallocatealpha($image,
                      0,
                      100,
                      0,
                      0
                      ));
                }
              }
              foreach($this->claster2->values  as $v){
                if($this->areas[$i][$j]['area'] == $v->area){
                  imagesetpixel($image, $j, $i, imagecolorallocatealpha($image,
                      132,
                      112,
                      225,
                      0
                      ));
                }
              }
            }
        }

        imagejpeg($image, $filename);
    }





function calculate_median($arr) {
    $count = count($arr); //total numbers in array
    $middleval = floor(($count-1)/2); // find the middle value, or the lowest middle value
    if($count % 2) { // odd number, middle is the median
        $median = $arr[$middleval];
    } else { // even number, calculate avg of 2 medians
        $low = $arr[$middleval];
        $high = $arr[$middleval+1];
        $median = (($low+$high)/2);
    }
    return $median;
}


function calculateDistance($claster, $point){
  return pow( pow(($claster->current_point->square - $point->square), 2)
              + pow(($claster->current_point->perimeter - $point->perimeter), 2)
              + pow(($claster->current_point->density - $point->density), 2),0.5);
}


}

function checkGarbage($values){
  $temp = [];
 foreach($values as  $value){
   if($value->square > 500 && $value->square != 1000 && $value->square !=3863 &&$value->square !=998 ){
      $temp [] = $value;
   }

 }

 return $temp;

}


function returnImage(){
  $im = new UserImage();
  $im->getImage('/home/oxana/projects/TsOS/P0001461.jpg');
  $im->createBlackWhiteImage();
  $im->scanning();
  $im->createImageObject();
  $im->getSquare();
  $im->getPerimeters();
  $im->getDensity();
  $im->getCentreOfMass();
  $temp = $im->getImageObjects();
  $temp = checkGarbage($temp);
  $im->clasterAnalysis();
  return $temp;
}
