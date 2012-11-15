
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<style type="text/css">
.submenu-content
{
  display: none;
}
</style>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>

<link rel="stylesheet" type="text/css" href="jkmegamenu.css" />

<script type="text/javascript" src="jkmegamenu.js">

/***********************************************
* jQuery Mega Menu- by JavaScript Kit (www.javascriptkit.com)
* This notice must stay intact for usage
* Visit JavaScript Kit at http://www.javascriptkit.com/ for full source code
***********************************************/

</script>

<script type="text/javascript">

//jkmegamenu.definemenu("anchorid", "menuid", "mouseover|click")
jkmegamenu.definemenu("activities", "menu1", "mouseover")

</script>
</head>

<body>

<!--Mega Menu Anchor-->
<p id="activities">Activities</p>
<!--Mega Drop Down Menu HTML. Retain given CSS classes-->
<div id="menu1" class="megamenu">

	<div class="column">
		<h3>Activities to be held soon</h3>
		<ul>
		<?
		include "header.php";
		$lastmod = max(getlastmod(), filemtime('activity.xml'));
		$fh = fopen('activity.xml', 'r');
		$contents = fread($fh, filesize('activity.xml'));
		fclose($fh);
		$xml = new SimpleXMLElement($contents);
	    	for ($i = $xml->count() - 1; $i >= 0; $i--)
    		{
				$node = $xml->activity[$i];
				if ($deadline->getTimestamp() >= time())
					{
		?>
						<li><a href="activity.php?activity=<?echo $i + 1?>"><?echo $node->name?></a></li>
		<?
					}
			}
	?>
		</ul>
	</div>
	<br style="clear: left" /> <!--Break after 3rd column. Move this if desired-->
	<div class="column">
	<h3>Recent Activities</h3>
	<ul>
	<?
		for ($i = $xml->count() - 1; $i >= 0; $i--)
    		{
				$node = $xml->activity[$i];
				if ($deadline->getTimestamp() < time())
					{
		?>
						<li><?echo $node->name?></a></li>
		<?
					}
			}
	?>
	</ul>
	</div>
</div>
<div id="menu1" class="megamenu">
	<div class="column">
		<h3>Documents</h3>
		<ul>
			<li><a src="Annual_Plan.php">Annual Plan</a></li>
		</ul>
	</div>
</body>
</html>
