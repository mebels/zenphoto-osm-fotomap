<?php
$images = array();
$result = query("SELECT `images`.`albumid`, `images`.`id` AS imageid, `images`.`EXIFGPSLatitude` AS exiflat, `images`.`EXIFGPSLongitude` AS exiflon, `images`.`filename`, `albums`.`id`, `albums`.`folder` FROM `images` INNER JOIN `albums` ON `images`.`albumid`=`albums`.`id` WHERE `images`.`show` = '1'");

while ($row = db_fetch_assoc($result)) {

if (isset($row["exiflat"]) && isset($row["exiflon"])) {

$imageid = $row["imageid"];
$filename = $row["filename"];
$folder = $row["folder"];

$images[$imageid] = array (
"filename" => $filename,
"folder" => $folder
);
} // if lat,lon

} // while

$geodata = array();
$geodata_i = 0;
foreach ($images as $imagesarray) {

$albumobject = newAlbum($imagesarray["folder"]);
$imageobject = newImage($albumobject, $imagesarray["filename"]);

$image_exif_lat = $imageobject->get('EXIFGPSLatitude');
$image_exif_lon = $imageobject->get('EXIFGPSLongitude');
$image_link_thumb = "<a href='" . $imageobject->getLink() . "'><img src='" . $imageobject->getThumb() . "'></a>";

$geodata[$geodata_i] = array(
'lat' => $image_exif_lat,
'long' => $image_exif_lon,
'title' => shortenContent($imageobject->getTitle(),50,'...').'<br />',
'desc' => shortenContent($imageobject->getDesc(),100,'...'),
'thumb' => $image_link_thumb,
'current' => '0'
);

$geodata_i++;
} // foreach

printOpenStreetMap($geodata);
?>
