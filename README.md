# zenphoto-osm-fotomap
Tutorial for a Zenphoto overview OSM Fotomap with all photos.   

Required Plugins:   
`multiple_layouts`   
`zp_openstreetmap v1.1.2`   

Info:   
The Github `albummap.php` contains only a content for a `albumxxx.php` in a zenphoto theme.   

TUTORIAL   

1.) FTP   
Duplicate your `/themes/[themename]/album.php` to `/themes/[themename]/albummap.php`   

2.) FTP   
Insert the PHP code from the Github `albummap.php` in the HTML `<body>` of the `/themes/[themename]/albummap.php`  
If you use database table prefixes, then you have to insert it in the right place in the PHP code.   
Example:    
<pre><code>$result = query("SELECT `prefix_images`.`albumid`, `prefix_images`.`id` AS imageid, `prefix_images`.`EXIFGPSLatitude` AS exiflat, `prefix_images`.`EXIFGPSLongitude` AS exiflon, `prefix_images`.`filename`, `prefix_albums`.`id`, `prefix_albums`.`folder` FROM `prefix_images` INNER JOIN `prefix_albums` ON `prefix_images`.`albumid`=`prefix_albums`.`id` WHERE `prefix_images`.`show` = '1'");</code></pre>    
   
Info: You can freely design the `albummap.php`.   
Remove all code to display albums and pictures.   
These code parts can be removed:   
<pre><code>&lt;div id="images"&gt;
&lt;?php while (next_image()): ?&gt;
...
&lt;/div&gt;</code></pre>
<pre><code>&lt;div id="albums"&gt;
&lt;?php while (next_album()): ?&gt;
...
&lt;/div&gt;</code></pre>   

3.) Zenphoto admin area   
Activate the required Plugins   
`multiple_layouts` = enable "Albums"   
`zp_openstreetmap v1.1.2` = height 100%, width 100% (more infos further down)   

4.)  Zenphoto admin area   
Create a new static album `New Album` with a name of your choice.      

5.)  Zenphoto admin area   
Go to the created new static album and select the `albummap.php` as the layout for this album.   
- Albums `->` (Albumname) `->` Utilities (sidebar right) `->` Select album layout: "albummap"   

6.) Style the OSM map   
In the `zp_openstreetmap` Plugin settings set the width and height to "100%".   
Use the `zp_openstreetmap` PHP function `printOpenStreetMap();` in your `image.php` (and/or `album.php` and/or `index.php` and/or whatever.php) with a HTML div box to set the width and height.   
Example:   
<pre><code>&lt;div class="zposmdivbox" style="width:800px;height:450px;"&gt;
&lt;?php printOpenStreetMap(); ?&gt;
&lt;/div&gt;</code></pre>   
A "height" in px or similar (not percent) is required.    
    
------------------------------------------------------------------------------------- 
Additionals   
       
7.) OSM Map Popups with/without title and/or description and/or thumbnail   
Use the following linked `zp_openstreetmap.php` file for the `zp_openstreetmap` plugin.   
Replace the original with it.  
This file expand the `zp_openstreetmap` plugin to individually select the display of `title`, `description` and `thumbnail`.   
https://github.com/mebels/zp_openstreetmap/blob/master/zp_openstreetmap.php

8.) Additional feature   
With several thousand photos, loading the page with the overview OSM fotomap can take a long time.   
If it supports your server, use PHP `ob_flush()` and `flush()` with a Javascript code and a Loading Spacer gif or JavaScript progress bar.   
   
Put this code in the `/themes/[themename]/albummap.php` directly above the code from the Github albummap.php:   
<pre><code>&lt;div class="osmmapspacer" style="width: 100%;margin: 0 auto;text-align: center;"&gt;
&lt;span&gt;Please wait. The Fotomap is loading.&lt;/span&gt;&lt;br&gt;
&lt;img src="/themes/basiczen/images/osmmapspacer.gif"&gt;
&lt;/div&gt;
&lt;?php
// source: http://www.joeyrivera.com/2008/ob_start-ob_flush-flush-set_time_limit-give-user-feedback-during-execution/
if (ob_get_level() == 0) { ob_start(); }
for($i=0; $i<70; $i++) {
print str_pad('',4096)."\n";
ob_flush();
flush();
usleep(30000);
//set_time_limit(30); 
}
?&gt;
&lt;script&gt;
document.addEventListener("DOMContentLoaded", function(event) { 
document.getElementsByClassName("osmmapspacer")[0].style.display = "none";
});
&lt;/script&gt;
</code></pre>
Don't forget to save a `img src="/themes/basiczen/images/osmmapspacer.gif`.   
Yo can find a spacer gif in `/zp-core/zp-extensions/bxslider_thumb_nav/images/bx_loader.gif`, or use your own.   
   
An another nice gimmick for page load waiting is a fake progress bar.   
<pre><code>&lt;div class="osmfotomapwait" style="width: 100%;background-color: #ddd;"&gt;
&lt;div class="osmfotomapwaitbar" style="width: 0.1%;height: 15px;background-color: #4CAF50;"&gt;&lt;/div&gt;
&lt;/div&gt;
&lt;script&gt;
var width = 0.1;
var id = setInterval(frame, 30);  // Seconds to load
function frame() {
if (width &gt;= 100) {
clearInterval(id);
} else {
width = width + 0.1;
document.getElementsByClassName("osmfotomapwaitbar")[0].style.width = width + '%';
}}
&lt;/script&gt;</code></pre>   
You can put it between the text and the spacer gif.    
JSFiddle: https://jsfiddle.net/9x6c57af/   
Source: https://www.w3schools.com/howto/howto_js_progressbar.asp
