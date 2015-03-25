<?

$account = "Free Ad Network Directory";
$title = "Publishers";

require_once("util.php");
require_once("db.php");

$db = new database();

include("head.php");
include("menu.php");

$categories = $db->categories();

?>

<ul>
<? for ($i = 0; $i < sizeof($categories); ++$i) { ?>
<li><? echo "<a href='list.php?cat=" . $categories[$i]["name"] . "'>" . $categories[$i]["name"] . "</a>"; ?></li>
<? } ?>
</ul>

<?

include("foot.php");

$db->close();

?>
