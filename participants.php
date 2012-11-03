<?
include "header.php";
$lastmod = max(getlastmod(), filemtime('activity.xml'));
$fh = fopen('activity.xml', 'r');
$contents = fread($fh, filesize('activity.xml'));
fclose($fh);
$xml = new SimpleXMLElement($contents);
$node = $xml->activity[$_GET['activity'] - 1];
?>
<h3>Participants</h3>
<table>
    <tr>
<?
    $item_num = $node->apply->q->count();
    for ($i = 0; $i < $item_num; $i++)
    {
?>
	<td><?echo $node->apply[0]->q[$i]->attributes()->name?></td>
<?
    }
    $participants = $node->apply[0]->participants;
    for ($i = 0; $i < $participants[0]->count(); $i++)
    {
?>
    <tr>
<?
	$items = $participants->participant[$i]->children();
	for ($j = 0; $j < $item_num; $j++)
	{
?>
	<td><?echo $items[$j]?></td>
<?
	}
?>
    </tr>
<?
    }
?>
</table>