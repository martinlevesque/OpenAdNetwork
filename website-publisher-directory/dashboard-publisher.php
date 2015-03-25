<?

$account = "Publisher";
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
	<td>Today's earnings</td><td>$<?= money($db->todaysEarnings()) ?></td>
</tr>

<tr>
	<td>Today's clicks</td><td><?= $db->todaysClicks() ?></td>
</tr>

<tr>
	<td>Last 30 days' earnings</td><td>$<?= money($db->last30DaysEarnings()) ?></td>
</tr>

<tr>
	<td>Last 30 days' clicks</td><td><?= $db->last30DaysClicks() ?></td>
</tr>
</table>
</div>

<?

include("foot.php");

$db->close();

?>
