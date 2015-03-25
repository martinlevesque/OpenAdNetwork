<?

$account = "Advertiser";
$title = "Banners";

require_once("util.php");
require_once("controller.php");
require_once("db.php");

$db = new database();

$errors = ctrlAddBanner();

include("head.php");
include("menu.php");

printErrors($errors);

$id = intval($_GET["id"]);
$rowWebsite = array("banner_format_id" => "0");

$banners = $db->getBanners($id);

?>

<div align="center">
<br /><br />
<form method="POST" action="banners-advertiser.php?id=<?= $id ?>" enctype="multipart/form-data">	
<table>
	<tr>
		<td>Banner image (*.png, *.jpg, Max size: 200 Kb):</td><td><input type="file" name="banner_file" /></td>
	</tr>
	<tr>
		<td>URL: </td><td><input type="text" size="40" name="url" value="http://" /></td>
	</tr>
	<tr>
		<td>Format: </td><td><? include("select-banner-format.php"); ?></td>
	</tr>
	<tr>
		<td></td><td><input type="submit" value="Add" /><input type="hidden" name="MAX_FILE_SIZE" value="500000" /></td>
	</tr>
</table>
</form> 

<table>
<tr>
<th>ID</th><th>Banner</th><th>Status</th><th>Actions</th>
</tr>

<?

for ($i = 0; $i < sizeof($banners); ++$i)
{
	$image = "http://images.spiclick.com:8080/" . $banners[$i]["created_on"] . "/" . $banners[$i]["id"] . ".jpg";

	echo "<tr><td>" . $banners[$i]["id"] . "</td><td><img src='$image' /></td><td>" . $banners[$i]["status"] . " " . $banners[$i]["status_comment"] . "</td><td><a onclick='return confirm(\"Are you sure ?\");' href='delete-banner.php?id=" . $banners[$i]["id"] . "&campaign_id=$id'>Delete</a></td></tr>";
}

?>

</table>

</div>

<?

include("foot.php");

$db->close();

?>
