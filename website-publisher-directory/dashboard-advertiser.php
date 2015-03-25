<?

$account = "Advertiser";
$title = "Dashboard";

require_once("util.php");
require_once("controller.php");
require_once("db.php");

$db = new database();

checkHasAccess();

include("head.php");
include("menu.php");

?>

<div align="center">
<table>

<tr>
	<td>Balance</td><td>$<?= money($db->advertiserBalance()) ?></td>
</tr>

<tr>
	<td>Spent today</td><td>$<?= money($db->advertiserSpentToday()) ?></td>
</tr>

<tr>
	<td>Today's clicks</td><td><?= $db->advertiserTodaysClicks() ?></td>
</tr>

<tr>
	<td>Spent last 30 days</td><td>$<?= money($db->advertiserSpentLast30Days()) ?></td>
</tr>

<tr>
	<td>Last 30 days' clicks</td><td><?= $db->advertiserClicks30Days() ?></td>
</tr>
</table>
</div>

<?

include("foot.php");

$db->close();

?>
