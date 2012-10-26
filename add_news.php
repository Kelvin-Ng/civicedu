<?
if ($_COOKIE['civicedu'])
{
    $fh = fopen('news.xml', 'r');
    $contents = fread($fh, filesize('news.xml'));
    fclose($fh);
    $news_xml = new SimpleXMLElement($contents);
    $i = $news_xml->count();
    echo "<div id=\"news$i\">";
    $new_news = $news_xml->addChild('sub_news');
    $new_news->addChild('type', $_POST['type']);
    if ($_POST['type'] == 'text')
    {
	$new_news->addChild('title', $_POST['title']);
	$new_news->addChild('text', $_POST['text']);
?>
	<h3><?echo $_POST['title']?></h3>
	<?echo str_replace("\n", '<br>', $_POST['text'])?>
<?
    }
    else
    {
	$new_news->addChild('item', $_POST['item']);
	$fh = fopen('activity.xml', 'r');
	$contents = fread($fh, filesize('activity.xml'));
	fclose($fh);
	$activity_xml = new SimpleXMLElement($contents);
	$activity = $activity_xml->activity[$_POST['item'] - 1];
?>
	<h3><?echo $activity->name?> is going to be held!</h3>
	For futher information, please <a href="activity.php?activity=<?echo $_POST['item'] - 1?>">Click here</a>
<?
    }
    echo "<br><button onclick=\"remove_news($i)\">-</button>";
?>
	<hr>
	</div>
<?
    $news_xml->asXML('news.xml');
}
else
{
    echo 'Please login first!';
}
?>