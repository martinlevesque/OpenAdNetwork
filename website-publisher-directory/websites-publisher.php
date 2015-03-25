<?

$account = "Publisher";
$title = "Websites";

require_once("util.php");
require_once("controller.php");
require_once("db.php");

$db = new database();

checkHasAccess();

include("head.php");
include("menu.php");

$websites = $db->getWebsites();

?>

<div align="center">
<br />
<a href="add-website-publisher.php">Add a website >></a>
<br /><br />
<table>
<tr><th>Name</th><th>URL</th><th>Category</th><th>Actions</th></tr>

<? for ($i = 0; $i < count($websites); ++$i) { ?>

<tr>
	<td><?= $websites[$i]["name"] ?></td><td><?= $websites[$i]["url"] ?></td><td><?= $websites[$i]["category_name"] ?></td>
	<td>
		<a href="edit-website-publisher?id=<?= $websites[$i]["id"] ?>">Edit</a>

		<? if ($websites[$i]["nb_zones_active"] == 0) { ?>
			| <a onclick="return confirm('Are you sure ?');" href="delete-website-publisher?id=<?= $websites[$i]["id"] ?>">Delete</a>
		<? } ?>
	</td>
</tr>

<? } ?>

</table>
</div>

<?

include("foot.php");

$db->close();

?>
