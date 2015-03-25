<?

require_once("db.php");
$cat = database::getEscaped($_GET["cat"]);

$noPage = 1;

if (isset($_GET["page"]))
{
	$noPage = intval($_GET["page"]);
}

$account = "Free Ad Network Directory";
$title = "$cat publishers | Page $noPage";

require_once("util.php");

$db = new database();

include("head.php");
include("menu.php");



$nbWebsites = $db->nbWebsites($cat);

$nbPages = intval(ceil(floatval($nbWebsites) / 30.0));

$websites = $db->websites($cat, $noPage);

?>



<ul>
<? for ($i = 0; $i < sizeof($websites); ++$i) { ?>
<li><? echo "<a href='publisher.php?id=" . $websites[$i]["id"] . "'>" . $websites[$i]["name"] . "</a>: " . $websites[$i]["description"] . ""; ?></li>
<? } ?>
</ul>

<p>
PAGE
<? for ($i = 1; $i < $nbPages -1; ++$i) { ?>
<? 
	if ($i == $noPage)
		echo "" . $i . " "; 
	else
		echo "<a href='list.php?cat=$cat&page=$i'>" . $i . "</a> "; 
?>
<? } ?>
</p>

<?

include("foot.php");

$db->close();

?>
