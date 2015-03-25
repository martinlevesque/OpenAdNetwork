<?

$account = "Admin";
$title = "Dashboard";

require_once("util.php");
require_once("controller.php");
require_once("db.php");

$db = new database();


include("head.php");
include("menu.php");

?>

<div align="center">


</div>

<?

include("foot.php");

$db->close();

?>
