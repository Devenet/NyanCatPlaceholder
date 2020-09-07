<?php
  require 'nyancatplaceholder.php';
  define('SOCIAL_TITLE', 'Nyan Cat placeholder');
  define('SOCIAL_DESC', 'Replace your depressing placeholder image by a happy Nyan Cat placeholder image.');
  define('SOCIAL_IMG', URL.'/assets/img/nyan_social.png');
?><!doctype html>
<html lang="en" itemscope itemtype="http://schema.org/Article">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Nyan Cat placeholder</title>
  <link rel="shortcut icon" type="image/x-icon" href="<?php echo URL; ?>/favicon.ico">
  <link rel="icon" type="image/png" href="<?php echo URL; ?>/assets/icon/favicon.png">
  <link rel="stylesheet" href="<?php echo URL; ?>/assets/nyan.css">
  
  <meta name="robots" content="index, follow, archive">
  <link rel="canonical" href="<?php echo URL; ?>">
  <meta name="author" content="Nicolas Devenet">
  <meta name="description" content="<?php echo SOCIAL_DESC; ?>">

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

  <!--
    o          +
        o  +           +        +
    +        o     o       +        o
    -_-_-_-_-_-_-_,------,      o
    _-_-_-_-_-_-_-|   /\_/\
    -_-_-_-_-_-_-~|__( ^ .^)  +     +
    _-_-_-_-_-_-_-" "   " "
    +      o         o   +       o
        +         +
    o        o         o      o     +
  -->
</head>
<body>
  
  <header>
    <a href="<?php echo URL; ?>" class="img-link"><img src="<?php echo URL; ?>/assets/img/nyan.png" alt="Nyan Cat" /></a>
    <h1>Nyan Cat placeholder</h1>
    <p><?php echo SOCIAL_DESC; ?></p>
  </header>

  <article>
    <section class="cat">
      <p>For a square <strong>100px</strong> image</p>
      <pre><a href="<?php echo URL; ?>/100" rel="external"><?php echo URL; ?>/<b>100</b></a></pre>
      <img src="<?php echo URL; ?>/100" alt="Nyan Cat Placeholder">
    </section>

    <section>
      <p>For a <strong>100&times;80px</strong> image</p>
      <pre><a href="<?php echo URL; ?>/100/80" rel="external"><?php echo URL; ?>/<b>100</b>/<b>80</b></a></pre>
    </section>

    <section class="cat">
      <p>For a <strong>gray</strong> image</p>
      <pre><a href="<?php echo URL; ?>/g/100" rel="external"><?php echo URL; ?>/<b>g</b>/100</a></pre>
      <img src="<?php echo URL; ?>/g/40" alt="Nyan Cat Placeholder">
    </section>

    <section class="cat">
      <p>For a <strong>blur</strong> image</p>
      <pre><a href="<?php echo URL; ?>/b/100" rel="external"><?php echo URL; ?>/<b>b</b>/100</a></pre>
      <img src="<?php echo URL; ?>/b/40" alt="Nyan Cat Placeholder">
    </section>
  </article>

  <footer>
    <p><a href="https://github.com/Devenet/NyanCatPlaceholder">Nyan Cat placeholder</a> built with 🌈 by <a href="https://nicolas.devenet.info">Nicolas Devenet</a>.</p>
  </footer>

  <script>
    var a = document.getElementsByTagName('a');
    for (var i = 0; i < a.length; i++) { if (a[i].getAttribute('rel') == 'external') { a[i].setAttribute('target', '_blank'); a[i].setAttribute('rel', 'noopener'); } }
  </script>
</body>
</html>
