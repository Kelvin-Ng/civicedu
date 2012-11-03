<?
if ($_COOKIE['civicedu'])
{
?>
<script src="http://code.jquery.com/jquery.min.js"></script>
<script>
    var num = 0;
    var news_form = jQuery('<form/>', {
			method: 'post',
			action: 'javascript:',
			style: 'display: none'
		    })
		    .append('<input name="type" type="radio" value="text">Text')
		    .append('<input name="type" type="radio" value="activity">Activity<br><br>')
		    .append('<input name="add_new_news" type="submit" value="submit">');
    function add_news()
    {
	num++;
	var add_new_news_form = news_form.clone();
	add_new_news_form.attr('id', 'add_new_news' + num);
	add_new_news_form.children('input[value="text"]').attr('onclick', 'add_text_news(' + num + ')');
	add_new_news_form.children('input[value="activity"]').attr('onclick', 'add_activity_news(' + num + ')');
	add_new_news_form.prependTo('#add_new_news').fadeIn();
	add_new_news_form.submit(function()
	{
	    var type = add_new_news_form.children('input[name="type"]:checked').val();
	    if (type == 'text')
	    {
		var title = add_new_news_form.children('input[name="title"]').val();
		var text = add_new_news_form.children('textarea').val();
		if (title != '' && text != '')
		{
		    submit_form('type=text&title=' + title + '&text=' + text);
		    add_new_news_form.remove();
		}
	    }
	    else
	    {
		var item = add_new_news_form.children('input[name="item"]').val();
		if (item != '')
		{
		    submit_form('type=activity&item=' + title);
		    add_new_news_form.remove();
		}
	    }
	});
    }
    function add_text_news(id)
    {
	$('#add_new_news' + id + ' > [type!="radio"][type!="submit"]').remove();
	$('#add_new_news' + id + ' > input[type="submit"]').before('<br><br><label>Title: </label><br><input name="title" type="text"><br><label>Contents:</label><br><textarea name="text"></textarea><br>');
    }
    function add_activity_news(id)
    {
	$('#add_new_news' + id + ' > [type!="radio"][type!="submit"]').remove();
	$('#add_new_news' + id + ' > input[type="submit"]').before('<br><br><label>Activity id: </label><input name="item" type="text"><br>');
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
	request.open('POST', 'add_news.php', true);
	request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	request.send(s);
	request.onreadystatechange = function()
	{
	    if (request.readyState == 4)
	    {
		$('#new_news').prepend($('<div id="new_news_div">' + request.responseText + '</div>').hide());
		$('#new_news_div:first').show('slow');
	    }
	};
    }
    function remove_news(i)
    {
	if (window.XMLHttpRequest)
	{
	    request = new XMLHttpRequest();
	}
	else if (window.ActiveXObject)
	{
	    request = new ActiveXObject("Microsoft.XMLHTTP");
	}
	request.open('POST', 'remove_news.php', true);
	request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	request.send('index=' + i);
	request.onreadystatechange = function()
	{
	    if (request.readyState == 4)
	    {
		$('#news' + i).hide('slow', function() {$(this).remove();});
		for (var j = i + 1; $('#news' + j).val() !== undefined; j++)
		{
		    $('#news' + j + ' > button').attr('onclick', 'remove_news(' + (j - 1) + ')');
		    $('#news' + j).attr('id', 'news' + (j - 1));
		}
	    }
	};
    }
</script>
<?
}
include "header.php";
$lastmod = max(getlastmod(), filemtime('news.xml'), filemtime('activity.xml'));
?>
	<link rel="stylesheet" type="text/css" href="index.css" />
	<h2>News</h2><?echo $_COOKIE['civicedu'] ? '<button onclick="add_news()">+</button>' : ''?>
	<a id="add_new_news"></a>
	<a id="new_news"></a>
<?
$fh = fopen('activity.xml', 'r');
$contents = fread($fh, filesize('activity.xml'));
fclose($fh);
$activity_xml = new SimpleXMLElement($contents);
$fh = fopen('news.xml', 'r');
$contents = fread($fh, filesize('news.xml'));
fclose($fh);
$news_xml = new SimpleXMLElement($contents);
for ($i = $news_xml->count() - 1; $i >= 0; $i--)
{
    echo $_COOKIE['civicedu'] ? "<div id=\"news$i\">" : '';
    $news = $news_xml->sub_news[$i];
    if ($news->type == 'text')
    {
?>
	<h3 class="title"><?echo $news->title?></h3>
	<p class="contents">
	<?echo str_replace("\n", '<br>', substr($news->text->asXML(), 6, -7))?>
	</p>
<?
    }
    elseif ($news->type == 'activity')
    {
	$activity = $activity_xml->activity[$news->item - 1];
?>
	<h3 class="title"><?echo $activity->name?> is going to be held!</h3>
	<p class="contents">For futher information, please <a href="activity.php?activity=<?echo $news->item?>">Click here</a></p>
<?
    }
?>
	<?echo $_COOKIE['civicedu'] ? "<br><button onclick=\"remove_news($i)\">-</button>" : ''?>
	<hr>
<?
    echo $_COOKIE['civicedu'] ? "</div>" : '';
}
include "footer.php";
?>