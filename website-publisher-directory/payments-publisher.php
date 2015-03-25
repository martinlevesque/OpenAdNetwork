<?

$account = "Publisher";
$title = "Payments (earnings)";

require_once("util.php");
require_once("controller.php");
require_once("db.php");

$db = new database();

checkHasAccess();

$row = $db->getUser();


include("head.php");
include("menu.php");

?>

<div align="center">
<p>Account information</p>

<table>
<tr>
<td>Payment terms</td><td>Monthly</td>
</tr>

<tr>
<td>Minimum payout</td><td>$<?= money($row["minimum_payout"]) ?></td>
</tr>

<tr>
<td>Unpaid earnings</td><td>$<?= money($row["unpaid_earnings"]) ?></td>
</tr>

<? if (floatval($row["unpaid_earnings"]) < floatval($row["minimum_payout"])) { ?>
<tr>
<td>Amount to reach the minimum payout</td><td>$<?= money(floatval($row["minimum_payout"]) - floatval($row["unpaid_earnings"])) ?></td>
</tr>
<? } ?>

</table>

<p>Payments history</p>

<?

$payments = $db->getPublisherPayments();

?>

<table>
<tr>
	<th>Date</th><th>Status</th><th>Transaction ID</th><th>Amount</th>
</tr>

<? for($i = 0; $i < count($payments); ++$i) { ?>
<tr>
	<td><?= $payments[$i]["created_on"] ?></td><td>Paid</td><td><?= $payments[$i]["transaction_id"] ?></td><td>$<?= money($payments[$i]["amount"]) ?></td>
</tr>
<? } ?>

</table>

</div>

<?

include("foot.php");

$db->close();

?>
