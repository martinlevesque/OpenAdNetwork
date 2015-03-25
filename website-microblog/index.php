<?

$account = "Free Ad Network Directory";
$title = "Publishers";

require_once("util.php");

// Check here for caching
include("top-cache.php");

require_once("db.php");

$db = new database();


include("head.php");
include("menu.php");

//$categories = $db->categories()

$page = 1;

if (isset($_GET["page"]))
{
	$page = intval($_GET["page"]);
}

$posts = $db->posts($website, $page);
$nbPosts = $db->nbPosts($website);

?>

<? for ($i = 0; $i < sizeof($posts); ++$i) { ?>

<h3><a href="p.php?post=<?= slug($posts[$i]["title"]) ?>&id=<?= $posts[$i]["id"] ?>"><?= $posts[$i]["title"] ?></a></h3>
<div>
<?= $posts[$i]["content"] ?>
<br /><br />
Added on <?= $posts[$i]["created_at"]?>.
<hr />
</div>

<? } ?>

<!--<ul>-->
<? // for ($i = 0; $i < sizeof($categories); ++$i) { ?>
<!--<li><? echo "<a href='list.php?cat=" . $categories[$i]["name"] . "'>" . $categories[$i]["name"] . "</a>"; ?></li>-->
<? //} ?>
<!--</ul>-->

<?

if ($page * 5 < $nbPosts)
{
	$next = $page + 1;
	echo "<div align='center'><a href='?page=$next'>More postings >></a></div>";
}

include("foot.php");

$db->close();

include("bottom-cache.php");

?>
