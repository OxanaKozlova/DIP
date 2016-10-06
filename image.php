<?php
class Image{

  private $filename = '';
  private $width = 0;
  private $height = 0;
  public  $rgb = [];
  public $image = null;

  function __construct( $filename = '', $width = 0, $height = 0, $rgb = [])
  {
    $this->height = $height;
    $this->width = $width;
    $this->rgb = $rgb;
    $this->filename = $filename;
    if($this->height != 0 && $this->width != 0){
        $this->image = imagecreatetruecolor($this->width, $this->height);

        for($i = 0; $i < $this->height; $i++) {
            for($j = 0; $j < $this->width; $j++) {
                imagesetpixel($this->image, $j, $i, imagecolorallocatealpha($this->image,
                    $rgb[$i][$j]['red'],
                    $rgb[$i][$j]['green'],
                    $rgb[$i][$j]['blue'],
                    $rgb[$i][$j]['alpha']
                ));
              }
            }
        imagejpeg($this->image, $this->filename);
    }

}


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
    $this->filename = $path;
    $this->setRgbArray();
  }

  public function setRgbArray()
  {
      $size = getimagesize($this->filename);
      $this->width = $size[0];
      $this->height = $size[1];

      if (exif_imagetype($this->filename) === IMAGETYPE_JPEG) {
          $this->image = imagecreatefromjpeg($this->filename);            //возвращает идентификатор изображения
      }
      elseif (exif_imagetype($this->filename) === IMAGETYPE_PNG) {
              $this->image = imagecreatefrompng($this->filename);
      }

      $pixels = [];
      for ($i = 0; $i < $this->height; $i++) {
          for ($j = 0; $j < $this->width; $j++) {
              $pixels[$i][$j] = imagecolorat($this->image, $j, $i); //получение цвета пикселя
              $this->rgb[$i][$j] = imagecolorsforindex($this->image, $pixels[$i][$j]); //получение rgb массива для каждого пикселя
          }
      }

  }


  public function getFilename(){
    return $this->filename;
  }

  public function setFilename($filename){
    $this->filename = $filename;
  }

  public function getWidth(){
    return $this->width;
  }

  public function setWidth($width){
    $this->width = $width;
  }

  public function getHeight(){
    return $this->height;
  }

  public function setHeight($height){
    $this->height = $height;
  }

  public function getRgb(){
    return $this->rgb;
  }

  public function setImage($image){
    $this->image = $image;
  }




}
