<?
include "header.php";
?>
<?
if ($_COOKIE['civicedu'])
{
?>
<script src="http://code.jquery.com/jquery.min.js"></script>
<script type="text/javascript">
function remove_participants(i)
    {
	if (window.XMLHttpRequest)
	{
	    request = new XMLHttpRequest();
	}
	else if (window.ActiveXObject)
	{
	    request = new ActiveXObject("Microsoft.XMLHTTP");
	}
	request.open('POST', 'remove_participants.php', true);
	request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	request.send('index=' + i);
	request.onreadystatechange = function()
	{
	    if (request.readyState == 4)
	    {
		$('#participants' + i).hide('slow', function() {$(this).remove();});
		for (var j = i + 1; $('#participants' + j).val() !== undefined; j++)
		{
		    $('#participants' + j + ' > button').attr('onclick', 'remove_participants(' + (j - 1) + ')');
		    $('#participants' + j).attr('id', 'participants' + (j - 1));
		}
	    }
	};
    }
</script>
<?
$lastmod = max(getlastmod(), filemtime('activity.xml'));
$fh = fopen('activity.xml', 'r');
$contents = fread($fh, filesize('activity.xml'));
fclose($fh);
$xml = new SimpleXMLElement($contents);
$node = $xml->activity[$_GET['activity'] - 1];
?>
<h3>Participants</h3>
<table>
<?
    activities_num = $node->count();
    for ($a = 0; $a < $activities_num; $a++)
    {
?>
	<tr>
<?
	echo $node[$a]->name;
?>
	</tr>
<?
	$item_num = $node[$a]->apply[0]->q->count();
?>
	<tr>
<?
	for ($i = 0; $i < $item_num; $i++)
	{
?>
		<td><?echo $node[$a]->apply[0]->q[$i]->short?></td>
<?
	}
?>
	</tr>
<?
	$participants = $node[$a]->apply[0]->participants;
	for ($i = 0; $i < $participants[0]->count(); $i++)
	{
?>
	    <tr>
<?
	    echo $_COOKIE['civicedu'] ? "<div id=\"participants$i\">" : '';
	    $items = $participants[0]->participant[$i]->children();
	    for ($j = 0; $j < $item_num; $j++)
	    {
?>
		<td><?echo $items[$j]?></td>
<?
	    }
?>
	    <td><?echo $_COOKIE['civicedu'] ? "<br><button onclick=\"remove_participants($i)\">-</button>" : ''?></td>
<?
	    echo $_COOKIE['civicedu'] ? "</div>" : '';
?>
            </tr>
<?
        }
?>
</table>
<?
    }
?>
<?
}
?>