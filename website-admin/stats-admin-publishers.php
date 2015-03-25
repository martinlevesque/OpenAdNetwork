<?

$account = "Admin";
$title = "Statistic publishers";

require_once("util.php");
require_once("controller.php");
require_once("db.php");
require_once("fraud.php");

$db = new database();

checkIsAdmin();

$meanImpression = $db->meanImpression();

$stats = $db->getStatsAdminPublishers();
$statsRegis = $db->getAdminRegistrations();
$statsActives = $db->getAdminActivePublishers();
$nbPublishers = $db->getAdminNbPublishers();
$statsViews = $db->getAdminPublisherViews();
$statsMicroblog = $db->getAdminMicroblogStats();

include("head.php");
include("menu.php");

?>

<div align="center">
<p><strong># publishers:</strong> <?= $nbPublishers ?></p>
<p><strong>Impression duration (ms):</strong> <?= $meanImpression ?></p>

<div id="chart_views" style="width: 100%;"></div>
<script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Day', 'Views'],
<?
for ($i = 0; $i < sizeof($statsViews); ++$i)
{
        echo "['" . $statsViews[$i]["date"] . "', ". $statsViews[$i]["cnt"] . "],";
}
?>
        ]);

        var options = {
          title: 'Total views'
        };

        var chart = new google.visualization.LineChart(document.getElementById('chart_views'));
        chart.draw(data, options);
      }
    </script>
<div id="chart_registrations" style="width: 100%;"></div>
<script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Day', 'Registrations'],
<?
for ($i = 0; $i < sizeof($statsRegis); ++$i)
{
        echo "['" . $statsRegis[$i]["date"] . "', ". $statsRegis[$i]["cnt"] . "],";
}
?>
        ]);

        var options = {
          title: 'Registrations'
        };

        var chart = new google.visualization.LineChart(document.getElementById('chart_registrations'));
        chart.draw(data, options);
      }
    </script>

<div id="chart_active_publishers" style="width: 100%;"></div>
<script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Day', '# Active Publishers'],
<?
for ($i = 0; $i < sizeof($statsActives); ++$i)
{
        echo "['" . $statsActives[$i]["date"] . "', ". $statsActives[$i]["cnt"] . "],";
}
?>
        ]);

        var options = {
          title: '# Active Publishers'
        };

        var chart = new google.visualization.LineChart(document.getElementById('chart_active_publishers'));
        chart.draw(data, options);
      }
    </script>

<div id="chart_microblog" style="width: 100%;"></div>
<script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Day', '# blog posts'],
<?
for ($i = 0; $i < sizeof($statsMicroblog); ++$i)
{
        echo "['" . $statsMicroblog[$i]["date"] . "', ". $statsMicroblog[$i]["cnt"] . "],";
}
?>
        ]);

        var options = {
          title: '# blog posts'
        };

        var chart = new google.visualization.LineChart(document.getElementById('chart_microblog'));
        chart.draw(data, options);
      }
    </script>
<p><a href="stats-todays_posts.php">Today's posts &gt; &gt;</a></p>

<p><br /><b>Today's activities</b></p>
<table align="center" border="1">
<tr>
<th>User</th><th>Views</th><th>Clicks</th><th>Points</th>

</tr>
<?


$sumViews = 0;
$sumClicks = 0;
$sumPoints = 0;

for ($i = 0; $i < sizeof($stats); ++$i)
{
?>

<tr>
<td><a href="admin-publishers-ips.php?username=<?= $stats[$i]["email"] ?>"><?= $stats[$i]["email"] ?></a> (<?= $stats[$i]["created_at"] ?>) <a onclick="return confirm('Are you sure?')" href="ban-publisher.php?username=<?= $stats[$i]["email"] ?>">Ban publisher</a> - Suspicious (IPs related, <?= hasSuspiciousIps($db->getIpsViewsUser($stats[$i]["email"])) ?>)</td><td><?= $stats[$i]["nb_views"] ?></td><td><?= $stats[$i]["nb_clicks"] ?> </td><td><?= $stats[$i]["nb_points"] ?> </td>
</tr>

<?
	$sumViews += intval($stats[$i]["nb_views"]);
	$sumClicks += intval($stats[$i]["nb_clicks"]);
	$sumPoints += intval($stats[$i]["nb_points"]);
}
?>
<tr><td><b>----------</b></td><td><b>----------</b></td><td><b>----------</b></td><td><b>----------</b></td></tr>
<tr><td><b>Total</b></td><td><b><?= $sumViews ?></b></td><td><b><?= $sumClicks ?></b></td><td><b><?= $sumPoints ?></b></td></tr>

</table>

</div>

<?

include("foot.php");

$db->close();

?>
