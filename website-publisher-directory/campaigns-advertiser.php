<?

$account = "Advertiser";
$title = "Campaigns";

require_once("util.php");
require_once("controller.php");
require_once("db.php");

$db = new database();

checkHasAccess();

include("head.php");
include("menu.php");

$campaigns = $db->getCampaigns();

?>

<div align="center">
<br />
<a href="add-campaign-advertiser.php">Add a campaign >></a>
<br /><br />
<table border="1">
<tr><th>Name</th><th>Category</th><th>Limit per day</th><th>Status</th><th>Today's clicks</th><th>Today's costs</th><th>Actions</th></tr>

<? for ($i = 0; $i < count($campaigns); ++$i) { ?>

<tr>
	<td><?= $campaigns[$i]["name"] ?></td>
	<td><?= $campaigns[$i]["category_name"] ?></td>
	<td>$<?= money($campaigns[$i]["max_per_day"]) ?></td>
	<td>Paused: <?= intval($campaigns[$i]["paused"]) == 1 ? "Yes" : "No" ?><br />
		A: <?= $campaigns[$i]["status"] ?> <?= $campaigns[$i]["status_comment"] ?>
	</td>
	<td><?= $campaigns[$i]["nb_clicks"] ?></td>
	<td>$<?= money($campaigns[$i]["costs"]) ?></td>
	<td>
		<a href="edit-campaign-advertiser.php?id=<?= $campaigns[$i]["id"] ?>">Edit</a>
		| <a href="banners-advertiser.php?id=<?= $campaigns[$i]["id"] ?>">Banners</a>

		<? if (intval($campaigns[$i]["paused"]) == 1) { ?>
			| <a onclick="return confirm('Are you sure ?');" href="activate-campaign-advertiser.php?id=<?= $campaigns[$i]["id"] ?>&activate=1">Activate</a>
		<? 
		} 
		else
		{
		?>
			| <a onclick="return confirm('Are you sure ?');" href="activate-campaign-advertiser.php?id=<?= $campaigns[$i]["id"] ?>&activate=0">Deactivate</a>
		<?
		}
		?>

		| <a onclick="return confirm('Are you sure ?');" href="delete-campaign-advertiser.php?id=<?= $campaigns[$i]["id"] ?>">Delete</a>
	</td>
</tr>

<? } ?>

</table>
</div>

<?

include("foot.php");

$db->close();

?>
