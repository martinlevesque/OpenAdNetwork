<?

$account = "Administration";
$title = "Unsubscribe from the mailing list";

require_once("util.php");
require_once("controller.php");
require_once("db.php");
require_once("lang.php");

$db = new database();

include("head.php");
include("menu.php");

$email = isset($_GET["m"]) ? $_GET["m"] : "";

$nAffect = $db->unsubscribeNewsletter($email);

if ($nAffect > 0)
{
	echo "<p>You have been successfully unsubscribed from the mailing list.</p>";
}
else
{
	echo "<p>The user has not been found, or it was already unsubscribed.</p>";
}

?>


<?

include("foot.php");

$db->close();

?>
