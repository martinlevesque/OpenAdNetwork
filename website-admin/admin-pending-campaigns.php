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
<? for ($j = 0; $j < sizeof($banners); ++$j) 
{ 

	if ($banners[$j]["type"] == "image")
	{
		$image = "http://images.infinetia.com/" . $banners[$j]["created_on"] . "/" . $banners[$j]["id"] . ".jpg";
		?>
		<?= $banners[$j]["status"] ?> <a href="<?= $banners[$i]["url"] ?>"><img src='<?= $image ?>' /></a><br />
		<? 
	}
	else
	{
		$title = $banners[$j]["text_title"];
                $url = $banners[$j]["url"];
                $url_label = $banners[$j]["text_url_label"];
                $line1 = $banners[$j]["text_line1"];
                $line2 = $banners[$j]["text_line2"];

                echo "<div id=\"banner_" . $banners[$j]["id"] . "\"></div>";
                echo "<script type='text/javascript'>refreshCampaignTextBanner(\"banner_" . $banners[$j]["id"] . "\", \"$title\", \"$url\", \"$url_label\", \"$line1\", \"$line2\");</script>";
	}

} ?>
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
