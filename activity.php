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
/*Starting modifying 'add activities'*/
if ($_COOKIE['civicedu'])
{
?>
<script src="http://code.jquery.com/jquery.min.js"></script>
<script>
    var num = 0;
    var activity_form = jQuery('<form/>', {
			method: 'post',
			action: 'javascript:',
			style: 'display: none'
		    })
		    .append('<input name="add_new_activity" type="submit" value="submit">');
    function add_activity()
    {
	num++;
	var add_new_activity_form = activity_form.clone();
	add_new_activity_form.attr('id', 'add_new_activity' + num);
	$('#add_new_activity' + num + ' > input[type="submit"]').before('<br><br><label>Name: </label><input name="name" type="text"><br><br><label>Date:</label><input name="date" type="text"><br><br><label>Time: </label><input name="time" type="text"><br><br><label>Further Information: </label><textarea name="info"></textarea><br><br><label>Application Ended: </label><input name="end" type="text"><br>');
	add_new_activity_form.prependTo('#add_new_activity').fadeIn();
	add_new_activity_form.submit(function()
	{
	   	var name = add_new_activity_form.children('input[name="name"]').val();
		var date = add_new_activity_form.children('input[name="date"]').val();
		var time = add_new_activity_form.children('input[name="time"]').val();
		var info = add_new_activity_form.children('input[name="info"]').val();
		var end = add_new_activity_form.children('input[name="end"]').val();
		if (name != '' && date != '' && time != '' && info != '' && end != '')
		{
		    submit_form('name=' + name + '&date=' + date + '&time=' + time + '&info=' + info + '&end=' +end);
		    add_new_activity_form.remove();
		}		
	});
    }
    function submit_form(s)
    {
	if (window.XMLHttpRequest)
	{
	    request = new XMLHttpRequest();
	}
	else if (window.ActiveXObject)
	{
	    request = new ActiveXObject("Microsoft.XMLHTTP");
	}
	request.open('POST', 'add_activity.php', true);
	request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	request.send(s);
	request.onreadystatechange = function()
	{
	    if (request.readyState == 4)
	    {
		$('#new_activity').prepend($('<div id="new_activity_div">' + request.responseText + '</div>').hide());
		$('#new_activity_div:first').show('slow');
	    }
	};
    }
</script>
<?
}
/*Ending modifying 'add activities'*/
?>
<?
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
<?echo $_COOKIE['civicedu'] ? '<button onclick="add_activity()">+</button>' : ''?>
<?
    for ($i = $xml->count() - 1; $i >= 0; $i--)
    {
	echo $_COOKIE['civicedu'] ? "<div id=\"activity$i\">" : '';
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