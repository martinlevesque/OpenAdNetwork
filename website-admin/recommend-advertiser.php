<?

$account = "Advertiser";
$title = "Recommend us and get 5000 iPoints";

require_once("util.php");
require_once("controller.php");
require_once("db.php");

$db = new database();

checkHasAccess();

include("head.php");
include("menu.php");

?>

<p>New users can now earn 5000 points by referring our services !</p>

<p>Simply follow the following steps:</p>

<ul>
<li>Recommend our services on your website by placing a recommendation paragraph and a link to http://infinetia.com/.</li>
<li><a href="/support.php">Contact us</a> with the url where you recommended our services.</li>
<li>That's it! You will receive 5000 points to advertise on our network.</li>
</ul>

<?

include("foot.php");

$db->close();

?>
