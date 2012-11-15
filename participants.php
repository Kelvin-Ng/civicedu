<?
include "header.php";
?>
<?
if ($_COOKIE['civicedu'])
{
?>
<script src="http://code.jquery.com/jquery.min.js"></script>
<script type="text/javascript">
function remove_participants(a, i)
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
    request.send('activity=' + a + '&index=' + i);
    request.onreadystatechange = function()
    {
	if (request.readyState == 4)
	{
	    $('#participants' + a + '_' + i).hide('slow', function() {$(this).remove();});
	    
	    for (var j = i + 1; $('#participants' + a + '_' + j).val() !== undefined; j++)
	    {
		$('#participants' + a + '_' + j + ' > button').attr('onclick', 'remove_participants(' + a + ',' + (j - 1) + ')');
		$('#participants' + a + '_' + j).attr('id', 'participants' + a + '_' +(j - 1));
	    }
	    /*for (var j = a + 1; $('#participants' + j + '_0').val() !== undefined; j++)
	    {
		for (var k = 0; $('#participants' + j + '_' + k).val() !== undefined; k++)
		{
		    $('#participants' + j + '_' + k + ' > button').attr('onclick', 'remove_participants(' + (j - 1) + ', ' + k + ')');
		    $('#participants' + j + '_' + k).attr('id', 'participants' + (j - 1) + '_' + k);
		}
	    }*/
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
/*$node = $xml->activity[$_GET['activity'] - 1];*/
?>
<h3>Participants</h3>
<?
    $activities_num = $xml->activity->count();
    for ($a = 0; $a < $activities_num; $a++)
    {
	$node=$xml->activity[$a];
?>
<table>
	<tr>
<?
	echo $node->name;
?>
	</tr>
<?
	$item_num = $node->apply[0]->q->count();
?>
	<tr>
<?
	for ($t = 0; $t < $item_num; $t++)
	{		
?>		
		<td><?echo $node->apply[0]->q[$t]->short?></td>
<?
	}
?>
	</tr>
<?
	$participants = $node[$a]->apply[0]->participants;
	for ($i = 0; $i < $participants[0]->count(); $i++)
	{
	    echo "<tr id=\"participants{$a}_{$i}\">";
	    $items = $participants[0]->participant[$i]->children();
	    for ($j = 0; $j < $item_num; $j++)
	    {
?>
		<td><?echo $items[$j]?></td>
<?
	    }
?>
	    <td><?echo "<br><button onclick=\"remove_participants($a, $i)\">-</button>"?></td>
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