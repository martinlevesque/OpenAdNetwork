<?

$account = "Advertiser";
$title = "Add a campaign";

require_once("util.php");
require_once("controller.php");
require_once("db.php");

$db = new database();

$errors = ctrlAddCampaign();

$row = array("name" => "", "category" => "0", "max_per_day" => 20);
$rowWebsite = $row;

include("head.php");
include("menu.php");

printErrors($errors);

?>

<div align="center">

<form method="POST" action="add-campaign-advertiser.php">

<? include("form-campaign-advertiser.php"); ?>

</form>

</div>

<?

include("foot.php");

$db->close();

?>
