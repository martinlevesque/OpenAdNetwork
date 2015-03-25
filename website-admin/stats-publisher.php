<?

$account = "Publisher";
$title = "Statistics";

require_once("util.php");
require_once("controller.php");
require_once("db.php");

$db = new database();

checkHasAccess();

$dateFrom = (isset($_POST["dateFrom"])) ? $_POST["dateFrom"] : "";

if ($dateFrom == "")
	$dateFrom = date("m/d/Y", strtotime("-1 month"));

$dateTo = (isset($_POST["dateTo"])) ? $_POST["dateTo"] : "";

if ($dateTo == "")
	$dateTo = date("m/d/Y");

$site = (isset($_POST["website_id"])) ? $_POST["website_id"] : "";

if ($site == "")
	$site = "all";

$stats = $db->getStatsPublisher(JDateToSqlDate($dateFrom), JDateToSqlDate($dateTo), $site);

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

<form method="POST" action="stats-publisher.php">

<table border="1">
<tr>

<td>Period</td>
<td><label for="dateFrom">From</label> <input type="text" id="dateFrom" name="dateFrom" size="10" /> <label for="dateTo">to</label> <input type="text" id="dateTo" name="dateTo" size="10" /></td>
</tr>

<tr>
<td>Site</td>
<td>

<? 
$selectWithAll = 1;
include("select-website.php"); 

?>
</td>
</tr>
</table>
<br />
<input type="submit" value="View stats" />

</form>

<? if (sizeof($stats) == 0) { ?>
<h4>No statistic is available yet.</h4>
<? } else { ?>

<table>
<tr>
<td>
<div id="chart_views" style="width: 100%;"></div>
<script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Day', 'Views'],
<?
for ($i = 0; $i < sizeof($stats); ++$i)
{
	echo "['" . $stats[$i]["created_on"] . "', ". $stats[$i]["nb_views"] . "],";
}
?>
        ]);

        var options = {
          title: 'Unique views'
        };

        var chart = new google.visualization.LineChart(document.getElementById('chart_views'));
        chart.draw(data, options);
      }
    </script>
</td>
</tr>
<tr>
<td>
<div id="chart_earnings" style="width: 100%;"></div>
<script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Day', 'Earnings (iPoints)'],
<?
for ($i = 0; $i < sizeof($stats); ++$i)
{
	echo "['" . $stats[$i]["created_on"] . "', ". floatval($stats[$i]["publisher_earnings"]) . "],";
}
?>
        ]);

        var options = {
          title: 'Earnings (iPoints)'
        };

        var chart = new google.visualization.LineChart(document.getElementById('chart_earnings'));
        chart.draw(data, options);
      }
    </script>
</td>
</tr>
</table>

<table align="center" border="1">
<tr>
<th style="width: 25%">Date</th><th style="width: 25%">Views</th><th style="width: 25%">Clicks</th><th style="width: 25%">Earnings (iPoints)</th>
</tr>
<?

$sumViews = 0;
$sumClicks = 0;
$sumEarnings = 0;

for ($i = 0; $i < sizeof($stats); ++$i)
{
?>

<tr>
<td><?= $stats[$i]["created_on"] ?></td><td><?= $stats[$i]["nb_views"] ?></td><td><?= $stats[$i]["nb_clicks"] ?></td><td><?= round(floatval($stats[$i]["publisher_earnings"]), 1) ?></td>
</tr>

<?
	$sumViews += intval($stats[$i]["nb_views"]);
	$sumClicks += intval($stats[$i]["nb_clicks"]);
	$sumEarnings += floatval($stats[$i]["publisher_earnings"]);
}
?>
<tr><td><b>----------</b></td><td><b>----------</b></td><td><b>----------</b></td><td><b>----------</b></td></tr>
<tr><td><b>Total</b></td><td><b><?= $sumViews ?></b></td><td><b><?= $sumClicks ?></b></td><td><b><?= round(floatval($sumEarnings), 1) ?></b></td></tr>

</table>

<? } ?>

</div>

<script>
$("#dateFrom").val("<?= $dateFrom ?>");
$("#dateTo").val("<?= $dateTo ?>");
</script>

<?

include("foot.php");

$db->close();

?>
