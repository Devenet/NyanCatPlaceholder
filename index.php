<?php

  define('URL', (empty($_SERVER['REQUEST_SCHEME']) ? 'http' : $_SERVER['REQUEST_SCHEME']).'://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['SCRIPT_NAME']));
  define('IMG_SOURCE_JPEG', './nyan/default.jpg');
  define('IMG_SOURCE_WIDTH', 2500);
  define('IMG_SOURCE_HEIGHT', 1500);
  define('IMG_SOURCE_RATIO', 1.66);
  define('IMG_COMPRESSION', 85);
  define('IMG_DEST_NAME', 'nyan_%sx%s');
  define('IMG_DEST_EXTENSION', '.jpeg');
  define('SOCIAL_TITLE', 'Nyan Cat Placeholder');
  define('SOCIAL_DESC', 'Replace your depressing placeholder image by a happy Nyan Cat placeholder image.');
  define('SOCIAL_IMG', URL.'/assets/img/nyan_social.png');
  define('CACHE_FOLDER', './cache/');
  define('CACHE_ENABLED', true);

  function parsingError()
  {
    header('Location:'.URL);
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

?><!doctype html>
<html lang="en" itemscope itemtype="http://schema.org/Article">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Nyan Cat Placeholder &middot; Devenet</title>
  <meta name="author" content="Nicolas Devenet">
  <meta name="copyright" content="Nicolas Devenet">
  <link type="text/plain" rel="author" href="humans.txt">
  <meta name="robots" content="index, follow, archive">
  <meta name="description" content="<?php echo SOCIAL_DESC; ?>">
  <link rel="shortcut icon" type="image/x-icon" href="<?php echo URL; ?>/favicon.ico">
  <link rel="icon" type="image/png" href="<?php echo URL; ?>/assets/icon/favicon.png">
  <link rel="stylesheet" href="https://s.dvt.re/devenet.eu/assets/css/devenet.css">
	<!--[if lte IE 9]><link rel="stylesheet" href="https://s.dvt.re/devenet.eu/assets/css/devenet.ie.css"><![endif]-->
  <!--[if lt IE 9]>
  <script src="https://s.dvt.re/dvt.re/assets/js/html5shiv.min.js"></script>
  <script src="https://s.dvt.re/dvt.re/assets/js/respond.min.js"></script>
  <![endif]-->
  <link rel="canonical" href="<?php echo URL; ?>">
  <link rel="author" href="https://plus.google.com/+NicolasDevenet" />
  <meta name="twitter:card" content="summary" />
  <meta name="twitter:site" content="@Devenet" />
  <meta name="twitter:creator" content="@Devenet" />
  <meta name="twitter:title" content="<?php echo SOCIAL_TITLE; ?>" />
  <meta name="twitter:description" content="<?php echo SOCIAL_DESC; ?>" />
  <meta name="twitter:image" content="<?php echo SOCIAL_IMG; ?>">
  <meta property="og:title" content="<?php echo SOCIAL_TITLE; ?>" />
  <meta itemprop="og:headline" content="<?php echo SOCIAL_DESC; ?>" />
  <meta property="og:type" content="article" />
  <meta property="og:url" content="<?php echo URL; ?>" />
  <meta property="og:image" content="<?php echo SOCIAL_IMG; ?>" />
  <meta property="og:description" content="<?php echo SOCIAL_DESC; ?>" />
  <meta property="og:site_name" content="<?php echo SOCIAL_TITLE; ?>" />
  <meta itemprop="name" content="<?php echo SOCIAL_TITLE; ?>">
  <meta itemprop="headline" content="<?php echo SOCIAL_DESC; ?>" />
  <meta itemprop="description" content="<?php echo SOCIAL_DESC; ?>">
  <meta itemprop="image" content="<?php echo SOCIAL_IMG; ?>">
  <style>
    .social-desc { margin-top: -15px; font-style: italic; text-align: center; }
    article { margin: 50px auto; }
    section { margin: 30px 0 0 30px; clear: both; position: relative; }
    section:before { display: block; float: left; margin-left: -30px; width: 30px; content: '\261E'; font-size: 1.25em; }
    section pre { margin-top: -10px; font-size: .95em; }
    section img { position: absolute; right: 0; top: 0; max-width: 100%; }
    img.border { border-radius: 5px; box-shadow: 0 0 10px #aaa; }
    a.img-link { border-bottom: none; }
    .clearfix:before,.clearfix:after { content: " "; display: table; }
    .clearfix:after { clear: both; }
    @media screen and (max-width: 700px) {
      .social-desc { text-align: left; }
      section img { position: relative; }
    }
  </style>
</head>

<body>
  
<header class="dvt">
	<h1><a href="<?php echo URL; ?>" class="img-link"><img src="<?php echo URL; ?>/assets/img/nyan.png" alt="Nyan Cat" /></a></h1>
	<h2>Nyan Cat Placeholder</h2>
</header>

<div class="container">

<header class="social-desc">
  <p><?php echo SOCIAL_DESC; ?></p>
</header>

<article>
  <section>
    <p>For a square <strong>100</strong>px image</p>
    <pre><a href="<?php echo URL; ?>/100" rel="external"><?php echo URL; ?>/<b>100</b></a></pre>
    <img src="<?php echo URL; ?>/100" alt="Nyan Cat Placeholder" class="border">

    <p>For a <strong>100&times;80</strong>px image</p>
    <pre><a href="<?php echo URL; ?>/100/80" rel="external"><?php echo URL; ?>/<b>100</b>/<b>80</b></a></pre>
  </section>

  <section>
    <p>For a <strong>gray</strong> image</p>
    <pre><a href="<?php echo URL; ?>/g/100" rel="external"><?php echo URL; ?>/<b>g</b>/100</a></pre>
    <img src="<?php echo URL; ?>/g/40" alt="Nyan Cat Placeholder" class="border">
  </section>

  <section>
    <p>For a <strong>blur</strong> image</p>
    <pre><a href="<?php echo URL; ?>/b/100" rel="external"><?php echo URL; ?>/<b>b</b>/100</a></pre>
    <img src="<?php echo URL; ?>/b/40" alt="Nyan Cat Placeholder" class="border">
  </section>
</article>
	
</div><!-- .container -->

<footer class="container dvt">
	<p>
    <?php echo date('Y');?> &mdash; Made with 🌈 by <a href="https://nicolas.devenet.info" rel="external">Nicolas Devenet</a>.
    <br><small><a href="https://github.com/Devenet/NyanPlaceholder" rel="external">Source code</a></small>
  </p>
</footer>

<script src="https://s.dvt.re/devenet.eu/assets/js/devenet.js"></script>

</body>
</html>
