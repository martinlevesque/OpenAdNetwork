<?

$account = "Admin";
$title = "Add a user";

require_once("util.php");
require_once("controller.php");
require_once("db.php");

$db = new database();

$errors = ctrlAddUser();

include("head.php");
include("menu.php");

printErrors($errors);

?>

<div align="center">

<form method="POST" action="add-user.php">

<textarea cols="80" rows="20" name="users">
</textarea>
<br />

<input type="submit" value="Send" />

</form>

</div>

<?

include("foot.php");

$db->close();

?>
