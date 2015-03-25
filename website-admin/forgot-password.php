<?

$account = "Administration";
$title = "Forgot password";

require_once("util.php");
require_once("controller.php");
require_once("db.php");
require_once("lang.php");

$db = new database();

$errors = ctrlForgotPassword();

include("head.php");
include("menu.php");

printErrors($errors);

if (count($errors) == 0 && isset($_POST["email"]))
{
	printSuccessComment("Your account information has been sent to the provided email.");
	echo "<p><a href='/'>Click here to login.</a></p>";
}
else
{

?>

<form method="POST" action="forgot-password.php">
<div align="center">

<p>Enter your email and your account information will be sent via email.</p>

<table>
<tr>
	<td>Email</td><td><input type="text" name="email" size="30" /></td>
</tr>

<tr>
	<td></td><td><input type="submit" value="Send" /></td>
</tr>
</table>
</div>
</form>

<?
}

include("foot.php");

$db->close();

?>
