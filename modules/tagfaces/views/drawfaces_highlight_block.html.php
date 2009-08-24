<?php defined("SYSPATH") or die("No direct script access.") ?>
<?
  // Check and see if the current photo has any faces associated with it.
  $existingFaces = ORM::factory("items_face")
                        ->where("item_id", $item->id)
                        ->find_all();

  // If it does, then insert some javascript and display an image map
  //   to show where the faces are at.
  if (count($existingFaces) > 0) {
?>
<style>
.transparent30
{
   filter:alpha(opacity=30); 
   -moz-opacity: 0.3; 
   opacity: 0.3; 
}
.transparent80
{
   filter:alpha(opacity=80); 
   -moz-opacity: 0.8; 
   opacity: 0.8; 
}
</style>

<script type="text/JavaScript">
  function setfacemap() {
	// Insert the usemap element into the resize photo's image tag.
    var photoimg = document.getElementById('gPhotoId-<?=$item->id ?>');
    photoimg.useMap = '#faces';
  }

  function highlightbox(x1, y1, x2, y2, str_title, str_url) {
    var divtext = document.getElementById('divtagtext');
    var photodiv = document.getElementById('gPhoto');
    var photoimg = document.getElementById('<?="gPhotoId-{$item->id}"?>');
    var divface = document.getElementById('divsquare');
    
    divface.style.display = 'block';
    divface.style.left = (photoimg.offsetLeft + x1) + 'px';
    divface.style.top = (photodiv.offsetTop + y1) + 'px';
    divface.style.width=(x2-x1) + 'px';
    divface.style.height=(y2-y1) + 'px';
    divface.onclick = function() {self.location.href = str_url;}
    
    divtext.style.display = 'block';
    divtext.style.left = divface.style.left;
    divtext.innerText = str_title;
    divtext.textContent = str_title;
    divtext.style.top = (parseInt(divface.style.top.split('p')[0]) + parseInt(divface.style.height.split('p')[0]) + 2) + 'px';
  }

  function hidebox() {
    // Hide the divs when the mouse moves off of the face.
    document.getElementById('divsquare').style.display = 'none';
    document.getElementById('divtagtext').style.display = 'none';
  }
  
  // Call setfacemap when the page loads.
  window.onload = setfacemap();
</script>

<div id="divtagtext" class="transparent80" style="position:absolute;display:none;border:2px #000000 outset;background-color:#ffffff;font-weight:bold;"></div> 
<div id="divsquare" class="transparent30" onMouseOut="hidebox()" style="position:absolute;display:none;border:2px solid #000000;background-color:#ffffff;" onclick="self.location.href = '';"></div>

<map name="faces">
<?
    // For each face, add a rectangle area to the page.
    foreach ($existingFaces as $oneFace) {
      $oneTag = ORM::factory("tag", $oneFace->tag_id)
?>
  <area shape="rect" coords="<?=$oneFace->x1 ?>,<?=$oneFace->y1 ?>,<?=$oneFace->x2 ?>,<?=$oneFace->y2 ?>" href="<?=url::site("tags/$oneFace->tag_id") ?>" title="<?=p::clean($oneTag->name); ?>" alt="<?=p::clean($oneTag->name); ?>" onMouseOver="highlightbox(<?=$oneFace->x1 ?>,<?=$oneFace->y1 ?>,<?=$oneFace->x2 ?>,<?=$oneFace->y2 ?>,'<?=p::clean($oneTag->name); ?>', '<?=url::site("tags/$oneFace->tag_id") ?>')" />
<? } ?>
</map>
<?
  }
?>
