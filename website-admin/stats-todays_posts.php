<?

$account = "Admin";
$title = "Today's posts";

require_once("util.php");
require_once("controller.php");
require_once("db.php");

$db = new database();

checkIsAdmin();


$stats = $db->getTodaysPosts();

include("head.php");
include("menu.php");

?>

<div align="center">

<table>
<tr><th>Blog</th><th># posts</th></tr>

<? for ($i = 0; $i < sizeof($stats); ++$i) { 

	$url = "http://" . slug($stats[$i]["name"]) . ".infinetia.com/";
?>

	<tr><td><a href="<?= $url ?>"><?= $url ?></a></td><td><?= $stats[$i]["cnt"] ?></td></tr>
<? } ?>

</table>

</div>

<?

include("foot.php");

$db->close();

?>
