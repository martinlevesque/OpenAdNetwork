<?

$account = "Advertiser";
$title = "Statistics";

require_once("util.php");
require_once("controller.php");
require_once("db.php");

$db = new database();

checkHasAccess();

$dateFrom = $_POST["dateFrom"];

if ($dateFrom == "")
	$dateFrom = date("m/d/Y", strtotime("-1 month"));

$dateTo = $_POST["dateTo"];

if ($dateTo == "")
	$dateTo = date("m/d/Y");

$campaign = $_POST["campaign_id"];

if ($campaign == "")
	$campaign = "all";

$stats = $db->getStatsAdvertiser(JDateToSqlDate($dateFrom), JDateToSqlDate($dateTo), $campaign);

include("head.php");
include("menu.php");

?>

<script>
	$(function() {
		var dates = $( "#dateFrom, #dateTo" ).datepicker({
			defaultDate: "+1w",
			changeMonth: true,
			numberOfMonths: 1,
			onSelect: function( selectedDate ) {
				var option = this.id == "dateFrom" ? "minDate" : "maxDate",
					instance = $( this ).data( "datepicker" ),
					date = $.datepicker.parseDate(
						instance.settings.dateFormat ||
						$.datepicker._defaults.dateFormat,
						selectedDate, instance.settings );
				dates.not( this ).datepicker( "option", option, date );
				
			}
		});
	});



</script>

<div align="center">

<br />

<form method="POST" action="stats-advertiser.php">

<table border="1">
<tr>

<td>Period</td>
<td><label for="dateFrom">From</label> <input type="text" id="dateFrom" name="dateFrom" size="10" /> <label for="dateTo">to</label> <input type="text" id="dateTo" name="dateTo" size="10" /></td>
</tr>

<tr>
<td>Campaign</td>
<td>

<? 
$selectWithAll = 1;
include("select-campaign.php"); 

?>
</td>
</tr>
</table>
<br />
<input type="submit" value="View stats" />

</form>

<table align="center" border="1">
<tr>
<th>Date</th><th>Campaign</th><th>Format</th><th>Views</th><th>Clicks</th><th>Costs</th>
</tr>
<?

$sumViews = 0;
$sumClicks = 0;
$sumCosts = 0;

for ($i = 0; $i < sizeof($stats); ++$i)
{
?>

<tr>
<td><?= $stats[$i]["created_on"] ?></td><td><?= $stats[$i]["campaign_name"] ?></td><td><?= $stats[$i]["banner_name"] ?> #<?= $stats[$i]["campaign_banner_id"] ?></td><td><?= $stats[$i]["nb_views"] ?></td><td><?= $stats[$i]["nb_clicks"] ?></td><td>$<?= money($stats[$i]["costs"]) ?></td>
</tr>

<?
	$sumViews += intval($stats[$i]["nb_views"]);
	$sumClicks += intval($stats[$i]["nb_clicks"]);
	$sumCosts += floatval($stats[$i]["costs"]);
}
?>
<tr><td><b>----------</b></td><td><b>----------</b></td><td><b>----------</b></td><td><b>----------</b></td><td><b>----------</b></td><td><b>----------</b></td></tr>
<tr><td><b>Total</b></td><td></td><td></td><td><b><?= $sumViews ?></b></td><td><b><?= $sumClicks ?></b></td><td><b>$<?= money($sumCosts) ?></b></td></tr>

</table>

</div>

<script>
$("#dateFrom").val("<?= $dateFrom ?>");
$("#dateTo").val("<?= $dateTo ?>");
</script>

<?

include("foot.php");

$db->close();

?>
