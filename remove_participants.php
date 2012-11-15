<?
if ($_COOKIE['civicedu'])
{
    $fh = fopen('activity.xml', 'r');
    $contents = fread($fh, filesize('activity.xml'));
    fclose($fh);
    $activity_xml = new SimpleXMLElement($contents);
    $node = $activity_xml->activity[(int)$_POST['activity']];
    unset($node->apply->participants->participant[(int)$_POST['index']]);
    $activity_xml->asXML('activity.xml');
}
?>