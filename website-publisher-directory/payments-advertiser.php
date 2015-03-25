<?

$account = "Advertiser";
$title = "Payments";

require_once("util.php");
require_once("controller.php");
require_once("db.php");

$db = new database();

checkHasAccess();

include("head.php");
include("menu.php");

?>

<div align="center">

	To activate your campaigns in our Ad Network, select an amount. Your payment will be validated within 1 business day.

<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="DCX2D353LWW2U">
<table>
<tr><td><input type="hidden" name="on0" value="Prices"></td></tr><tr><td><select name="os0">
	<option value="Ads #1">Ads #1 $10.00 USD</option>
	<option value="Ads #2">Ads #2 $20.00 USD</option>
	<option value="Ads #3">Ads #3 $50.00 USD</option>
	<option value="Ads #4">Ads #4 $100.00 USD</option>
	<option value="Ads #5">Ads #5 $200.00 USD</option>
	<option value="Ads #6">Ads #6 $500.00 USD</option>
	<option value="Ads #7">Ads #7 $1,000.00 USD</option>
</select> </td></tr>
</table>
<input type="hidden" name="currency_code" value="USD">
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>


</div>

<?

include("foot.php");

$db->close();

?>
