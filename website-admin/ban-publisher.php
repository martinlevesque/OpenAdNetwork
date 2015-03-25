<?

$account = "Admin";
$title = "Statistic publishers";

require_once("util.php");
require_once("controller.php");
require_once("db.php");

$db = new database();

checkIsAdmin();


$db->banPublisher($_GET["username"]);

$user = $db->getUserByUsername($_GET["username"]);
sendBanEmail($_GET["username"], $user["email"]);

include("head.php");
include("menu.php");

?>

<p>DELETED.</p>

<?

include("foot.php");

$db->close();

?>
