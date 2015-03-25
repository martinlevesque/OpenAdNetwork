<?

require_once("db.php");
$id = intval($_GET["id"]);

$db = new database();

$website = $db->website($id);

$account = "Free Ad Network Directory";
$title = $website["name"] . " advertising publisher";

require_once("util.php");


include("head.php");
include("menu.php");

?>

<h2><? echo $website["name"]; ?></h2>

<?

$file = "ss/" . $website["id"] . ".jpg";
$hasTried = 0;

if ( ! file_exists ($file) && strstr($website["url"], "http") !== FALSE)
{
	$cmd = "xvfb-run -a -s \"-screen 0 640x480x16\" wkhtmltopdf " . $website["url"] . " ss/" . $website["id"] . ".pdf";

	exec($cmd);
	exec("convert ss/$id.pdf[0] ss/$id.jpg");
	$hasTried = 1;
}
$hasImage = file_exists ($file);

if ($hasImage !== TRUE && $hasTried == 1)
{
	exec("convert -size 1x1 xc:white ss/$id.jpg");
}

?>


<p><strong>Description:</strong> <? echo $website["description"]; ?><br />
<? $microblogURL = "http://" . slug($website["name"]) . ".infinetia.com/"; ?>
<strong>Microblog:</strong> <a href="<?= $microblogURL ?>"><?= $microblogURL ?></a><br />

<? if (strstr($website["url"], "http") !== FALSE) { ?>
<strong>URL:</strong> <a href="visit-publisher.php?id=<? echo $website["id"]; ?>"><? echo $website["url"]; ?></a>
<? } ?>

</p>

<div align="center">
<a href="http://infinetia.com/">Boost your online traffic with the infiNetia free advertising network >></a>
</div>

<? if ($hasImage) { ?>
<div align="center">
	<a href="visit-publisher.php?id=<? echo $website["id"]; ?>"><img style="" src="ss/<? echo "$id.jpg"; ?>" /></a>
</div>
<? } ?>

<?

include("foot.php");

$db->close();

?>
