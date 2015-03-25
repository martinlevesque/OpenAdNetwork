<?

$account = "Publisher";
$title = "Add a website";

require_once("util.php");
require_once("controller.php");
require_once("db.php");

$db = new database();
$errors = ctrlAddWebsite();

$category = (isset($_POST["category"])) ? $_POST["category"] : "1";
$name = (isset($_POST["name"])) ? slug($_POST["name"]) : "";
$description = (isset($_POST["description"])) ? $_POST["description"] : "";
$url = (isset($_POST["url"])) ? $_POST["url"] : "http://";

$rowWebsite = array("category" => $category, "name" => $name, "description" => $description, "url" => $url);

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
