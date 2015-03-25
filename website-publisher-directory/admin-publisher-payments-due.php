<?

$account = "Admin";
$title = "Payments due";

require_once("util.php");
require_once("controller.php");
require_once("db.php");

$db = new database();

checkIsAdmin();


$stats = $db->getPaymentsDuePublisher();

include("head.php");
include("menu.php");

?>

<div align="center">

<table align="center" border="1">
<tr>
<th>User</th><th>Email</th><th>Paypal</th><th>Minimum payout</th><th>Unpaid earnings</th><th>Amount to reach minimum payout</th>
</tr>
<?

for ($i = 0; $i < sizeof($stats); ++$i)
{
?>

<tr>
<td><?= $stats[$i]["username"] ?> (<?= $stats[$i]["created_at"] ?>)</td><td><?= $stats[$i]["email"] ?></td><td><?= $stats[$i]["publisher_paypal"] ?></td><td>$<?= money($stats[$i]["minimum_payout"]) ?></td><td>$<?= money($stats[$i]["unpaid_earnings"]) ?></td><td>$<?= money((floatval($stats[$i]["minimum_payout"]) - floatval($stats[$i]["unpaid_earnings"]))) ?></td>
</tr>

<?
}
?>

</table>

</div>

<?

include("foot.php");

$db->close();

?>
