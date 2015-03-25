<?

$account = "Admin";
$title = "Pending campaigns";

require_once("util.php");
require_once("controller.php");
require_once("db.php");

$db = new database();

checkIsAdmin();
ctrlAdminPendingCampaign();


include("head.php");
include("menu.php");

?>

<div align="center">

<table align="center" border="1">
<tr>
<th>ID</th><th>Category</th><th>Banners</th><th>Actions</th>
</tr>
<?

for ($i = 0; $i < sizeof($pendingCampaigns); ++$i)
{
	$banners = $db->getBannersOf($pendingCampaigns[$i]["id"]);
?>

<tr>
<td><?= $pendingCampaigns[$i]["id"] ?></td>
<td><?= $pendingCampaigns[$i]["category_name"] ?></td>
<td>
<? for ($j = 0; $j < sizeof($banners); ++$j) { 
	$image = "http://images.spiclick.com:8080/" . $banners[$j]["created_on"] . "/" . $banners[$j]["id"] . ".jpg";
?>
<?= $banners[$j]["status"] ?> <a href="<?= $banners[$i]["url"] ?>"><img src='<?= $image ?>' /></a><br />
<? } ?>
</td>

<td>
<form method="POST" action="admin-pending-campaigns.php?id=<?= $pendingCampaigns[$i]["id"] ?>">
<select name="status">
<option value="active">active</option>
<option value="rejected">rejected</option>
</select>
<textarea cols="10" rows="5" name="status_comment">
</textarea>
<input type="submit" value="OK" />
</form>
</td>

</tr>

<?
}
?>

</table>

</div>

<?

include("foot.php");

$db->close();

?>
