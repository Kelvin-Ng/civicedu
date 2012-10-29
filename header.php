<?
$file_name = explode('/', $_SERVER['PHP_SELF']);
$file_name = $file_name[count($file_name) - 1];
?>
<html>
    <head>
	<title>Civic Education Society</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link rel="stylesheet" href="index.css">
    </head>
    <body>
	<p align="center"><img src="WEB_HEAD.png"></p>
	<p class="navigation">
	    <a href="index.php" <?echo ($file_name == 'index.php') ? 'style="color: #FFFFFF"' : ''?>>Home</a>&nbsp&nbsp
	    <a href="activity.php" <?echo ($file_name == 'activity.php') ? 'style="color: #FFFFFF"' : ''?>>Our activities</a>&nbsp&nbsp<a href="activity.php" style="color: #00FF00">application forms</a>&nbsp&nbsp
	    <a href="Annual_Plan.html">Annual Plan</a>&nbsp&nbsp
<?
if (!$_COOKIE['civicedu'])
{
?>
	    <a href="login.php" <?echo ($file_name == 'login.php') ? 'style="color: #FFFFFF"' : ''?>>Committee Area</a>
<?
}
else
{
?>
	    <a href="logout.php">Logout</a>
<?
}
?>
	</p>