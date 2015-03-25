<?

$account = "";
$title = "Technical support";

require_once("util.php");
require_once("controller.php");
require_once("db.php");

$db = new database();

$errors = ctrlAdminSupport();

include("head.php");
include("menu.php");

printErrors($errors);

if (count($errors) == 0 && isset($_POST["comments"]))
{
	printSuccessComment("Your message was sent successfully!");
}

?>

<div align="center">

Do you need any help to set up your account/campaigns ? Let us know, we generally respond within 1 business day.

<form method="POST" action="support.php">

<table>
<tr>
<td>Question/Comment: </td>
</tr>
<tr>
<td>
<textarea name="comments" cols="50" rows="5">
</textarea>
</td>
</tr>
<tr><td>
<input type="submit" value="Send" />
</td>
</tr>
</table>

</form>

</div>

<?

include("foot.php");

$db->close();

?>
