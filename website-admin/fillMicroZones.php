<?

require_once("db.php");

$db = new database();

$websites = $db->websitesWithoutMicroblogZones();

$ban1 = $db->getBannerFormatSize(468, 60);
$ban2 = $db->getBannerFormatSize(250, 250);


for ($i = 0; $i < sizeof($websites); ++$i)
{
	echo $websites[$i]["id"] . " " . $websites[$i]["category_id"] . "\n";
	$db->insertMicroblogZone($websites[$i]["id"], "468x60", $ban1["id"], $websites[$i]["category_id"]);
	$db->insertMicroblogZone($websites[$i]["id"], "250x250", $ban2["id"], $websites[$i]["category_id"]);
}



?>
