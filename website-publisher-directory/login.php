<?

$account = "Administration";
$title = "Login";

require_once("util.php");
require_once("controller.php");
require_once("db.php");

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
	<td>Username</td><td><input type="text" name="username" size="20" /></td>
</tr>

<tr>
	<td>Password</td><td><input type="password" name="password" size="20" /></td>
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
