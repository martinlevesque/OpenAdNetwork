<?

$account = "Publisher";
$title = "Edit a zone";

require_once("util.php");
require_once("controller.php");
require_once("db.php");

$db = new database();

$errors = ctrlEditZone();

$id = isset($_GET["id"]) ? intval($_GET["id"]) : 0;

$rowWebsite = $db->getZone($id);


include("head.php");
include("menu.php");

printErrors($errors);

?>

<div align="center">

<form method="POST" action="edit-zone-publisher.php?id=<?= $id ?>">

<? include("form-zone-publisher.php"); ?>

</form>

</div>

<?

include("foot.php");

$db->close();

?>
