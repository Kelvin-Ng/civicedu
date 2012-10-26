<?
if ($_POST['pwd'] == 'c9v9c5d' || $_COOKIE['civicedu'] == 'login')
{
    setcookie('civicedu', 'login');
    header('Location: index.php');
}
else
{
    include "header.php";
?>
	<h2>Committee Area</h2>
<?
    if ($_REQUEST['login'] == 'Login')
    {
?>
	<a style="color: #006600">Wrong Password</a><br>
	<br>
<?
    }
?>
	<b>This is the area of committee members. If you are one of the committee members, please login with the password.</b>
	<form action="login.php" method="post">
	    <input name="pwd" type="password"><br>
	    <br>
	    <input name="login" type="submit" value="Login">
	</form>
<?
    include "footer.php";
}
?>