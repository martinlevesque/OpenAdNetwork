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

$balance = $db->advertiserBalance();

?>

<div align="center">
<table>

<tr>
	<td>iPoints</td><td><?= round(floatval($balance), 1) ?></td>
</tr>

<tr>
	<td>Spent today</td><td><?= $db->advertiserSpentToday() ?></td>
</tr>

<tr>
	<td>Today's clicks</td><td><?= $db->advertiserTodaysClicks() ?></td>
</tr>

<tr>
	<td>Spent last 30 days</td><td><?= round(floatval($db->advertiserSpentLast30Days()), 1) ?></td>
</tr>

<tr>
	<td>Last 30 days' clicks</td><td><?= $db->advertiserClicks30Days() ?></td>
</tr>
</table>

<? if ($balance <= 0) { ?>
<h4>Notice: </h4><p>You currently do not have any iPoints yet. <br />The advertiser campaigns work by using available iPoints. <br />Make sure to earn iPoints in the Publisher panel.<br />You can also get 5000 free iPoints by <a href="recommend-advertiser.php">recommending us</a> and by <a href='add-microblog-publisher.php'>creating microblogs</a> integrated with our Ad network.</p>
<? } ?>

</div>

<?

include("foot.php");

$db->close();

?>
