<?

$account = "Administration";
$title = "Login";

require_once("util.php");
require_once("controller.php");
require_once("db.php");
require_once("lang.php");

$db = new database();

$errors = ctrlLogin();

include("head.php");
include("menu.php");

printErrors($errors);

?>

<form method="POST" action="login.php">
<div align="center">
<table>
<tr>
	<td>Login (Email/Username)</td><td><input type="text" name="username" size="20" /></td>
</tr>

<tr>
	<td>Password</td>
	<td>
		<input type="password" name="password" size="20" /><br />
		<a href="forgot-password.php">Forgot password ?</a> | 
		<a href="http://infinetia.com/en/sign-up/">Register >></a>
	</td>
</tr>

<tr>
	<td></td><td><input type="submit" value="Login" /></td>
</tr>
</table>
</div>
</form>

<?

include("foot.php");

$db->close();

?>
