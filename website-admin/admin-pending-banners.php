<?

$account = "Admin";
$title = "Pending banners";

require_once("util.php");
require_once("controller.php");
require_once("db.php");

$db = new database();

checkIsAdmin();
ctrlAdminPendingBanner();


include("head.php");
include("menu.php");

?>

<div align="center">

<table align="center" border="1">
<tr>
<th>Category</th><th>Banner</th><th>Actions</th>
</tr>
<?

for ($i = 0; $i < sizeof($pendingBanners); ++$i)
{
	$image = "http://images.infinetia.com/" . $pendingBanners[$i]["created_on"] . "/" . $pendingBanners[$i]["id"] . ".jpg";

	$imageHover = "";

	if (intval($pendingBanners[$i]["has_hover"]) == 1)
	{
		$imageHover = "http://images.infinetia.com/" . $pendingBanners[$i]["created_on"] . "/" . $pendingBanners[$i]["id"] . "-hover.jpg";
	}
?>

<tr>
<td><?= $pendingBanners[$i]["category_name"] ?></td>
<td>
	<? if ($pendingBanners[$i]["type"] == "image") { ?>
		<a href="<?= $pendingBanners[$i]["url"] ?>"><img src="<?= $image ?>" /></a>

		<? if ($imageHover != ""){ ?>
			<br />Hover: <img src="<?= $imageHover ?>" /></a>
		<? } ?>
	<? }
	else
	{
		$title = $pendingBanners[$i]["text_title"];
                $url = $pendingBanners[$i]["url"];
                $url_label = $pendingBanners[$i]["text_url_label"];
                $line1 = $pendingBanners[$i]["text_line1"];
                $line2 = $pendingBanners[$i]["text_line2"];

                echo "<div id=\"banner_" . $pendingBanners[$i]["id"] . "\"></div>";
                echo "<script type='text/javascript'>refreshCampaignTextBanner(\"banner_" . $pendingBanners[$i]["id"] . "\", \"$title\", \"$url\", \"$url_label\", \"$line1\", \"$line2\");</script>";
        }
	 ?>
</td>

<td>
<form method="POST" action="admin-pending-banners.php?id=<?= $pendingBanners[$i]["id"] ?>">
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
