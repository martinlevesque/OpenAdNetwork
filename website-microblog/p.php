<?

$account = "Free Ad Network Directory";
$title = "Publishers";

require_once("util.php");

include("top-cache.php");

require_once("db.php");

$db = new database();



include("head.php");
include("menu.php");

//$categories = $db->categories()

if (is_null($p) === TRUE)
{
	echo "TRUE";
	header("Location: index.php");
	exit;
}

?>

<h3><?= $p["title"] ?></a></h3>
<div>
<?= $p["content"] ?>
<br /><br />
Added on <?= $p["created_at"]?>.
<hr />
</div>

<?

include("foot.php");

$db->close();

include("bottom-cache.php");

?>
