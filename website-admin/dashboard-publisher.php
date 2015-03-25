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

$hasMicroblog = $db->hasCreatedMicroblogs();

?>

<div align="center">

<? 
if ($hasMicroblog === FALSE)
{
	echo "<b>Tips: You did not create any blog yet. <a href='add-microblog-publisher.php'>Make sure to create blogs integrated with our Ad network to automatically earn iPoints.</a></b>";
}
?>

<table>
<tr style="">
	<td style="width: 50%">Today's earned iPoints</td><td style="width: 50%"><?= round(floatval($db->todaysEarnings()), 1) ?></td>
</tr>

<tr>
	<td>Today's clicks</td><td><?= $db->todaysClicks() ?></td>
</tr>

<tr>
	<td>Last 30 days' earned iPoints</td><td><?= round(floatval($db->last30DaysEarnings()), 1) ?></td>
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
