<?
if ($_COOKIE['civicedu'])
{
    $fh = fopen('news.xml', 'r');
    $contents = fread($fh, filesize('news.xml'));
    fclose($fh);
    $news_xml = new SimpleXMLElement($contents);
    unset($news_xml->sub_news[(int)$_POST['index']]);
    $news_xml->asXML('news.xml');
}
else
{
    echo 'Please login first!';
}
?>