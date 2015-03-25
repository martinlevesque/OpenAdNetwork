<?

$account = "Publisher";
$title = "HTML code";

require_once("util.php");
require_once("controller.php");
require_once("db.php");

$db = new database();

checkHasAccess();

include("head.php");
include("menu.php");

// $zones = $db->getZones();
$id = intval($_GET["id"]);

?>

<div align="center">

<a href="zones-publisher.php"><< Create another zone</a>

<br />
<p>Copy-Paste the following HTML code to your website:</p>
<textarea rows="5" cols="70" onclick="this.focus(); this.select();">
<!-- BEGIN http://infiNetia.com/ Ad Code -->
<script type="text/javascript" src="http://syndication.infinetia.com/?wzi=<?= $id ?>"></script>
<noscript>Your browser does not support JavaScript. Update it for a better user experience.</noscript>
<!-- END http://infiNetia.com/ Ad Code -->
</textarea>
<p>Preview:</p>
<!-- BEGIN http://infiNetia.com/ Ad Code -->
<script type="text/javascript" src="http://syndication.infinetia.com/?wzi=<?= $id ?>"></script>
<noscript>Your browser does not support JavaScript. Update it for a better user experience.</noscript>
<!-- END http://infiNetia.com/ Ad Code -->
</div>

<?

include("foot.php");

$db->close();

?>
