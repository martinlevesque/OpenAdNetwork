<?

$account = "Publisher";
$title = "Add a website";

require_once("util.php");
require_once("controller.php");
require_once("db.php");

$db = new database();
$errors = ctrlAddWebsite();

$rowWebsite = array("category" => "1", "name" => "", "description" => "", "url" => "http://");

include("head.php");
include("menu.php");

printErrors($errors);

?>

<div align="center">

<form method="POST" action="add-website-publisher.php">

<? include("form-website-publisher.php"); ?>

</form>

</div>

<?

include("foot.php");

$db->close();

?>
