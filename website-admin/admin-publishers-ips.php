<?

$account = "Admin";
$title = "Statistic publishers";

require_once("util.php");
require_once("controller.php");
require_once("db.php");

$db = new database();

checkIsAdmin();


$ips = $db->getIpsPublisher($_GET["username"]);

include("head.php");
include("menu.php");

?>

<p><br />IPs<b></b></p>
<table align="center" border="1">
<tr>
<th>IP</th><th>Zone ID</th><th>Website</th>
</tr>
<?

for ($i = 0; $i < sizeof($ips); ++$i)
{
?>

<tr>
<td><?= $ips[$i]["ip"] ?></td><td><?= $ips[$i]["zone_id"] ?></td><td><a href="<?= $ips[$i]["url"] ?>">View website</a></td>
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
