<?php

  define('URL', (empty($_SERVER['REQUEST_SCHEME']) ? 'http' : $_SERVER['REQUEST_SCHEME']).'://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['SCRIPT_NAME']));
  define('IMG_SOURCE_JPEG', './nyan/default.jpg');
  define('IMG_SOURCE_WIDTH', 2500);
  define('IMG_SOURCE_HEIGHT', 1500);
  define('IMG_SOURCE_RATIO', 1.66);
  define('IMG_COMPRESSION', 85);
  define('IMG_DEST_NAME', 'nyan_%sx%s');
  define('IMG_DEST_EXTENSION', '.jpeg');
  define('CACHE_FOLDER', './cache/');
  define('CACHE_ENABLED', true);

  function parsingError()
  {
    http_response_code(400);
    echo '400 Bad Request';
    exit;
  }
  function parseParameter($parameter)
  {
    $value = intval($parameter);
    if ($value > 0 && $value <= IMG_SOURCE_WIDTH)
      return $value;
    
    parsingError();
  }

  // do we want a nyan cat image?
  if (!empty($_SERVER['QUERY_STRING']))
  {
    $r = explode('/', $_SERVER['QUERY_STRING']);

    $img = new NyanPlaceholder();

    // do we want gray image?
    if ($r[0] === 'g')
    {
      $img->setIsGray(true);
      array_shift($r);
    }
    // do we want blur image?
    else if ($r[0] === 'b')
    {
      $img->setIsBlur(true);
      array_shift($r);
    }

    // no parameters omitted?
    if (empty($r[0]))
      parsingError();
    
    // get width
    $img->setWidth(parseParameter($r[0]));
    
    // is there another parameter?
    array_shift($r);
    if (empty($r[0]))
      $img->display();

    // get height
    $img->setHeight(parseParameter($r[0]));

    $img->display();
  }

  class NyanPlaceholder
  {
    private $width;
    private $height;
    private $isGray = false;
    private $isBlur = false;

    public function setWidth($width) { $this->width = $width; }
    public function setHeight($height) { $this->height = $height; }
    public function setIsGray($isGray) { $this->isGray = $isGray; }
    public function setIsBlur($isBlur) { $this->isBlur = $isBlur; }

    private function fixHeight() { $this->height = empty($this->height) ? $this->width : $this->height; }

    private function filename()
    {
      $name = sprintf(IMG_DEST_NAME, $this->width, $this->height);
      if ($this->isGray || $this->isBlur)
      {
        $name .= sprintf('_%s', $this->isGray ? 'gray' : 'blur');
      }
      return $name.IMG_DEST_EXTENSION;
    }
    
    public function display()
    {
      $this->fixHeight();

      header('Content-Type: image/jpeg');
      header('Content-Disposition: inline; filename="'.$this->filename().'"');

      // can we use cached image?
      $image_path = CACHE_FOLDER.$this->filename();
      if (CACHE_ENABLED && file_exists($image_path))
      {
        exit(file_get_contents($image_path));
      }

      $src = imagecreatefromjpeg(IMG_SOURCE_JPEG);

      $ratio = round($this->width / $this->height, 2);
      if ($ratio <= IMG_SOURCE_RATIO)
      {
        $height = min($this->width, floor(IMG_SOURCE_HEIGHT * $this->width / IMG_SOURCE_WIDTH));
        $height_gap = floor(($this->height - $height) / 2);
        
        $width = $this->width;
        $width_gap = 0;
      }
      else
      {
        $height = $this->height;
        $height_gap = 0;

        $width = max($this->height, floor(IMG_SOURCE_WIDTH * $this->height / IMG_SOURCE_HEIGHT));
        $width_gap = ceil(($this->width - $width) / 2);
      }      

      $dest = imagecreatetruecolor($this->width, $this->height);
      
      $background = imagecolorallocate($dest, 1, 38, 77);
      imagefilledrectangle($dest, 0, 0, $this->width, $this->height, $background);
      imageinterlace($dest, 1);
      imagecopyresized($dest, $src, $width_gap, $height_gap, 0, 0, $width, $height, IMG_SOURCE_WIDTH, IMG_SOURCE_HEIGHT);

      if ($this->isGray)
      {
        imagefilter($dest, IMG_FILTER_GRAYSCALE);
      }
      else if ($this->isBlur)
      {
        imagefilter($dest, IMG_FILTER_GAUSSIAN_BLUR);
        imagefilter($dest, IMG_FILTER_PIXELATE, 5, true);
        imagefilter($dest, IMG_FILTER_GAUSSIAN_BLUR);
      }
      
      // hack to avoid bad image format for browser
      if (isset($_GET['cache']))
      {
        imagejpeg($dest, $image_path, IMG_COMPRESSION);
        imagedestroy($dest);
        exit;
      }
        
      imagejpeg($dest, NULL, IMG_COMPRESSION);
      imagedestroy($dest);
 
      // hack to avoid bad image format for browser
      if (CACHE_ENABLED) { file_get_contents(URL.'/'.$_SERVER['QUERY_STRING'].'?cache'); }  
      
      exit;
    }
  }

?>