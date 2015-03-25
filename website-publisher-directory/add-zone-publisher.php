<?

$account = "Publisher";
$title = "Create a zone";

require_once("util.php");
require_once("controller.php");
require_once("db.php");

$db = new database();
$errors = ctrlAddZone();

$rowWebsite = array("website_id" => "0", "name" => "", "banner_format_id" => "0", "category" => "0");

include("head.php");
include("menu.php");

printErrors($errors);

?>

<div align="center">

<form method="POST" action="add-zone-publisher.php">

<? include("form-zone-publisher.php"); ?>

</form>

</div>

<?

include("foot.php");

$db->close();

?>
