<?

$account = "Advertiser";
$title = "Edit a campaign";

require_once("util.php");
require_once("controller.php");
require_once("db.php");

$db = new database();

$errors = ctrlEditCampaign();

$id = intval($_GET["id"]);

$campaign = $db->getCampaign($id);

$row = array("name" => $campaign["name"], "category" => $campaign["category_id"], "max_per_day" => $campaign["max_per_day"]);
$rowWebsite = $row;

include("head.php");
include("menu.php");

printErrors($errors);

?>

<div align="center">

<form method="POST" action="edit-campaign-advertiser.php?id=<?= $id ?>">

<? include("form-campaign-advertiser.php"); ?>

</form>

</div>

<?

include("foot.php");

$db->close();

?>
