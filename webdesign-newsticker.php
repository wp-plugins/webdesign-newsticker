<?php
/*
Plugin Name: Webdesign-Newsticker
Plugin URI: http://www.brengo.de/
Description: Adds a customizeable widget which displays the latest news about webdesign. It can be integrated anywhere in the blog. This newsticker shows up the last five news. This is a very nice solution to everybody who wants to show news integrated in the blog about design.
Version: 1.0
Author: Sven Hausdorf
Author URI: http://www.wdee.de/
License: GPL3
*/

function webdesignnews()
{
  $options = get_option("widget_webdesignnews");
  if (!is_array($options)){
    $options = array(
      'title' => 'Webdesign News',
      'news' => '5',
      'chars' => '30'
    );
  }

  // RSS Objekt erzeugen 
  $rss = simplexml_load_file( 
  'http://www.wdee.info/feed/'); 
  ?> 
  
  <ul> 
  
  <?php 
  // maximale Anzahl an News, wobei 0 (Null) alle anzeigt
  $max_news = $options['news'];
  // maximale Länge, auf die ein Titel, falls notwendig, gekürzt wird
  $max_length = $options['chars'];
  
  // RSS Elemente durchlaufen 
  $cnt = 0;
  foreach($rss->channel->item as $i) { 
    if($max_news > 0 AND $cnt >= $max_news){
        break;
    }
    ?> 
    
    <li>
    <?php
    // Titel in Zwischenvariable speichern
    $title = $i->title;
    // Länge des Titels ermitteln
    $length = strlen($title);
    // wenn der Titel länger als die vorher definierte Maximallänge ist,
    // wird er gekürzt und mit "..." bereichert, sonst wird er normal ausgegeben
    if($length > $max_length){
      $title = substr($title, 0, $max_length)."...";
    }
    ?>
    <a href="<?=$i->link?>"><?=$title?></a> 
    </li> 
    
    <?php 
    $cnt++;
  } 
  ?> 
  
  </ul>
<?php  
}

function widget_webdesignnews($args)
{
  extract($args);
  
  $options = get_option("widget_webdesignnews");
  if (!is_array($options)){
    $options = array(
      'title' => 'Webdesign News',
      'news' => '5',
      'chars' => '30'
    );
  }
  
  echo $before_widget;
  echo $before_title;
  echo $options['title'];
  echo $after_title;
  webdesignnews();
  echo $after_widget;
}

function webdesignnews_control()
{
  $options = get_option("widget_webdesignnews");
  if (!is_array($options)){
    $options = array(
      'title' => 'Webdesign News',
      'news' => '5',
      'chars' => '30'
    );
  }
  
  if($_POST['webdesignnews-Submit'])
  {
    $options['title'] = htmlspecialchars($_POST['webdesignnews-WidgetTitle']);
    $options['news'] = htmlspecialchars($_POST['webdesignnews-NewsCount']);
    $options['chars'] = htmlspecialchars($_POST['webdesignnews-CharCount']);
    update_option("widget_webdesignnews", $options);
  }
?> 
  <p>
    <label for="webdesignnews-WidgetTitle">Widget Title: </label>
    <input type="text" id="webdesignnews-WidgetTitle" name="webdesignnews-WidgetTitle" value="<?php echo $options['title'];?>" />
    <br /><br />
    <label for="webdesignnews-NewsCount">Max. News: </label>
    <input type="text" id="webdesignnews-NewsCount" name="webdesignnews-NewsCount" value="<?php echo $options['news'];?>" />
    <br /><br />
    <label for="webdesignnews-CharCount">Max. Characters: </label>
    <input type="text" id="webdesignnews-CharCount" name="webdesignnews-CharCount" value="<?php echo $options['chars'];?>" />
    <br /><br />
    <input type="hidden" id="webdesignnews-Submit"  name="webdesignnews-Submit" value="1" />
  </p>
  
<?php
}

function webdesignnews_init()
{
  register_sidebar_widget(__('Webdesign News'), 'widget_webdesignnews');    
  register_widget_control('Webdesign News', 'webdesignnews_control', 300, 200);
}
add_action("plugins_loaded", "webdesignnews_init");
?>