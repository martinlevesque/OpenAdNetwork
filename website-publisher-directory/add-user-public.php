<html>
<head>
<title>spiClick.com</title>

<link rel="stylesheet" href="css/calendar/jquery.ui.all.css">
<link rel="stylesheet" type="text/css" href="css/style.css" />
<script src="js/jquery-1.7.2.js"></script>
<script type="text/javascript" src="js/modernizr-1.5.min.js"></script>
<script src="js/ui/jquery.ui.core.js"></script>
<script src="js/ui/jquery.ui.datepicker.js"></script>
<script type="text/javascript" src="js/jquery.easing-sooper.js"></script>
  <script type="text/javascript" src="js/jquery.sooperfish.js"></script>
</head>
<body style="background-color: white;">
<?

$account = "";
$title = "";

require_once("util.php");
require_once("controller.php");
require_once("db.php");

$db = new database();


printErrors(ctrlAddUserPublic());

$username = isset($_POST["username"]) ? trim($_POST["username"]) : "";
$company_name = isset($_POST["company_name"]) ? trim($_POST["company_name"]) : "";
$firstname = isset($_POST["firstname"]) ? trim($_POST["firstname"]) : "";
$lastname = isset($_POST["lastname"]) ? trim($_POST["lastname"]) : "";
$email = isset($_POST["email"]) ? trim($_POST["email"]) : "";
$website = isset($_POST["website"]) ? trim($_POST["website"]) : "";


?>

<div align="center">

<form method="POST" action="add-user-public.php">

<table>

<tr>
<td>
Username*<br />
<input type="text" size="30" value="<?= $username ?>" name="username" />
</td>
<td>
Company name*<br />
<input type="text" size="30" value="<?= $company_name ?>" name="company_name" />
</td>
</tr>

<tr>
<td>
First name*<br />
<input type="text" size="30" value="<?= $firstname ?>" name="firstname" />
</td>
<td>
Last name*<br />
<input type="text" size="30" value="<?= $lastname ?>" name="lastname" />
</td>
</tr>

<tr>
<td>
E-mail*<br />
<input type="text" size="30" value="<?= $email ?>" name="email" />
</td>
<td>
Password*<br />
<input type="password" size="30" name="password" />
</td>
</tr>

<tr>
<td>
Website*<br />
<input type="text" size="30" value="<?= $website ?>" name="website" />
</td>
<td>
Password (confirm)*<br />
<input type="password" size="30" name="password_confirm" />
<input type="hidden" name="sent" value="1" />
</td>
</tr>

</table>

<input type="submit" value="Create >>" style="width: 130px; height: 50px;" />

</form>

</div>

<?

$db->close();

?>
</body>
</html>
