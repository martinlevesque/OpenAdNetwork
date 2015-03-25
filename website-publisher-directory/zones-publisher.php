<?

$account = "Publisher";
$title = "Zones";

require_once("util.php");
require_once("controller.php");
require_once("db.php");

$db = new database();

checkHasAccess();

include("head.php");
include("menu.php");

$zones = $db->getZones();

?>

<div align="center">
<br />
<a href="add-zone-publisher.php">Create a new zone >></a>
<br /><br />
<table border="1">
<tr><th>Website name</th><th>Zone name</th><th>Format</th><th>Category</th><th>Actions</th></tr>

<? for ($i = 0; $i < count($zones); ++$i) { ?>

<tr>
	<td><?= $zones[$i]["website_name"] ?></td><td><?= $zones[$i]["zone_name"] ?></td><td><?= $zones[$i]["banner_format_name"] ?></td>
	<td><?= $zones[$i]["category_name"] ?></td>
	<td>
		<a href="edit-zone-publisher.php?id=<?= $zones[$i]["zone_id"] ?>">Edit</a>
		| <a onclick="return confirm('Are you sure ?');" href="delete-zone-publisher.php?id=<?= $zones[$i]["zone_id"] ?>">Delete</a>
		| <a href="tag-zone-publisher.php?id=<?= $zones[$i]["zone_id"] ?>">Tag HTML</a>
	</td>
</tr>

<? } ?>

</table>
</div>

<?

include("foot.php");

$db->close();

?>
