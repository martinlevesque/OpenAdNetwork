<?

$account = "Publisher";
$title = "Edit a website";

require_once("util.php");
require_once("controller.php");
require_once("db.php");

$db = new database();

$errors = ctrlEditWebsite();

$id = isset($_GET["id"]) ? intval($_GET["id"]) : 0;

$rowWebsite = $db->getWebsite($id);


include("head.php");
include("menu.php");

printErrors($errors);

?>

<div align="center">

<form method="POST" action="edit-website-publisher.php?id=<?= $id ?>">

<? include("form-website-publisher.php"); ?>

</form>

</div>

<?

include("foot.php");

$db->close();

?>
