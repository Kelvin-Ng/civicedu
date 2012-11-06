<?
if ($_COOKIE['civicedu'])
{
    $fh = fopen('activity.xml', 'r');
    $contents = fread($fh, filesize('activity.xml'));
    fclose($fh);
    $activity_xml = new SimpleXMLElement($contents);
    $i = $activity_xml->count();
    echo "<div id=\"activity$i\">";
    $new_activity = $news_xml->addChild('activity');
    $new_activity->addChild('name', $_POST['name']);
    $new_activity->addChild('date', $_POST['date']);
    $new_activity->addChild('time', $_POST['time']);
    $new_activity->addChild('info', $_POST['info']);
    $question = $new_activity->addChild('apply');
    $q_name = $question->addChild('q');
    $q_name->attributes()->name = 'name';
    $q_name->addChild('short', 'Name');
    $q_name->addChild('text', 'What is your name?');
    $q_name->addChild('type', 'text');
    $q_class = $question->addChild('q');
    $q_class->attributes()->name = 'class';
    $q_class->addChild('short', 'Class');
    $q_class->addChild('text', 'What is your class?');
    $q_class->addChild('type', 'text');
    $q_number = $question->addChild('q');
    $q_number->attributes()->name = 'number';
    $q_number->addChild('short', 'Class No.');
    $q_number->addChild('text', 'What is your class number?');
    $q_number->addChild('type', 'text');
    $q_member_yn = $question->addChild('q');
    $q_member_yn->attributes()->name = 'member_yn';
    $q_member_yn->addChild('short', 'Member?');
    $q_member_yn->addChild('text', 'Are you a member of Civic Education Society in academic year 2012-2013?');
    $q_member_yn->addChild('type', 'radio');
    $q_member_yn->addChild('option', 'Yes');
    $q_member_yn->addChild('option', 'No');
    $new_activity->addChild('end', $_POST['end']);
    $new_activity->addChild('participants');
	
    $activity_xml->asXML('activity.xml');
}
else
{
    echo 'Please login first!';
}
?>