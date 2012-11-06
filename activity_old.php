<html>
<head>
<link rel="stylesheet" type="text/css" href="activity.css">
</head>
<body>
<?
include "header.php";
$lastmod = max(getlastmod(), filemtime('activity.xml'));
$fh = fopen('activity.xml', 'r');
$contents = fread($fh, filesize('activity.xml'));
fclose($fh);
$xml = new SimpleXMLElement($contents);
if ($_GET['activity'] == '')
{
?>
<h3>Our activities</h3>
<b class="info">You may click 'Apply' to apply for joining the activities!</b><br><br>
<table>
    <tr>
	<td class="topic"><a>Name</a></td>
	<td class="topic"><a>Date</a></td>
	<td class="topic"><a>Time</a></td>
    </tr>
<?
    for ($i = $xml->count() - 1; $i >= 0; $i--)
    {
	$node = $xml->activity[$i];
?>
    <tr>
	<td class="activity"><a><?echo $node->name?></a></td>
	<td class="activity"><a><?echo $node->date?></a></td>
	<td class="activity"><a><?echo $node->time?></a></td>
<?
	$deadline = date_create_from_format('j/n/Y H:i', $node->apply->end);
	if ($deadline->getTimestamp() >= time())
	{
?>
	<td class="info"><a href="activity.php?activity=<?echo $i + 1?>">Apply</a></td>
    </tr>
<?
	}
    }
?>
<script type="text/javascript">
alert("You may apply our activities online! Just click 'Apply' button and fill in the application form! Try it!")
</script>
</table>
<?
}
else if ($_REQUEST['apply'] == 'Apply')
{
    $node = $xml->activity[$_GET['activity'] - 1];
    $new_participant = $node->apply->participants->addChild('participant');
    for ($i = 0; $i < $node->apply[0]->count() - 2; $i++)
    {
	$question = $node->apply[0]->q[$i];
	$name = (string)$question->attributes()->name;
	$new_participant->addChild($name, $_POST[$name]);
    }
    $xml->asXML('activity.xml') or die('Fail to open the data file');
?>
<p class="info">You have applied for this activity successfully. Thank you for your participation.<br>
You will be redirected to last page after 5 seconds.</p>
<script>
    function redirect()
    {
	window.location = "activity.php?activity=<?echo $_GET['activity']?>";
    }
    setTimeout("redirect()", 5000);
</script>
<?
}
else
{
    $node = $xml->activity[$_GET['activity'] - 1];
?>
<table>
    <tr>
	<td class="type"><a>Activity Name:</a></td>
	<td class="info"><a><?echo $node->name?></a></td>
    </tr>
    <tr>
	<td class="type"><a>Date:</a></td>
	<td class="info"><a><?echo $node->date?></a></td>
    </tr>
    <tr>
	<td class="type"><a>Time:</a></td>
	<td class="info"><a><?echo $node->time?></a></td>
    </tr>
    <tr>
	<td class="type"><a>Further Information:</a></td>
	<td class="info"><?echo str_replace("\n", '<br>', substr($node->info->asXML(), 6, -7))?></td>
    </tr>
</table>
<br><br>
<h3>Application Form</h3>
<form action="activity.php?activity=<?echo $_GET['activity']?>" method="post">
    <table>
<?
    for ($i = 0; $i < $node->apply->q->count(); $i++)
    {
	$question = $node->apply[0]->q[$i];
?>
	<tr>
	    <td class="type"><label><?echo $question->text?></label></td>
<?
	if ($question->type == 'radio')
	{
?>
	    <td>
<?
	    for ($j = 0; $j < $question->option->count(); $j++)
	    {
?>
	    <input name="<?echo $question->attributes()->name?>" type="radio" value="<?echo $question->option[$j]?>"><?echo $question->option[$j]?>
<?
	    }
?>
	    </td>
<?
	}
	else
	{
?>
	    <td class="type"><input name="<?echo $question->attributes()->name?>" type="<?echo $question->type?>" value="<?echo $question->default?>"></td>
<?
	}
?>
	</tr>
<?
    }
?>
    </table>
    <br><br>
    <input name="apply" type="submit" value="Apply">
</form>
<?
}
include "footer.php";
?>
</body>
</html>