<?

$account = "Admin";
$title = "Make a payment";

require_once("util.php");
require_once("controller.php");
require_once("db.php");

$db = new database();

$errors = ctrlMakePublisherPayment();

include("head.php");
include("menu.php");

printErrors($errors);

?>

<div align="center">

<form method="POST" action="make-payment.php">

Username: <input type="text" name="username" /><br/>
Amount: <input type="text" name="amount" /><br/>
Transaction ID: <input type="text" name="transaction_id" /><br/>

<input type="submit" value="Send" />

</form>

</div>

<?

include("foot.php");

$db->close();

?>
