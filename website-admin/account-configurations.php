<?

$account = "General";
$title = "Account configurations";

require_once("util.php");
require_once("controller.php");
require_once("db.php");

$db = new database();

$errors = ctrlEditUser();

$row = $db->getUser();


include("head.php");
include("menu.php");

printErrors($errors);

?>

<div align="center">

<form method="POST" action="account-configurations.php?id=<?= isset($id) ? $id : "" ?>">

<? include("form-account-configurations.php"); ?>

</form>

</div>

<?

include("foot.php");

$db->close();

?>
