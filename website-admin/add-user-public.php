<html>
<head>
<title>infiNetia.com</title>

<link rel="stylesheet" href="css/calendar/jquery.ui.all.css">
<script src="js/jquery-1.7.2.js"></script>
<script type="text/javascript" src="js/modernizr-1.5.min.js"></script>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script src="js/campaignBanner.js"></script>
<script src="js/ui/jquery.ui.core.js"></script>
<script src="js/ui/jquery.ui.datepicker.js"></script>
<script type="text/javascript" src="js/jquery.easing-sooper.js"></script>
  <script type="text/javascript" src="js/jquery.sooperfish.js"></script>

<link rel="profile" href="http://gmpg.org/xfn/11" />

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-50080907-1', 'infinetia.com');
  ga('send', 'pageview');

</script>

<link rel='stylesheet' id='contact-form-7-css'  href='http://infinetia.com/wp-content/plugins/contact-form-7/includes/css/styles.css?ver=3.3' type='text/css' media='all' />
<link rel='stylesheet' id='cntctfrm_stylesheet-css'  href='http://infinetia.com/wp-content/plugins/contact-form-plugin/css/style.css?ver=3.8.2' type='text/css' media='all' />
<link rel='stylesheet' id='responsive-style-css'  href='http://infinetia.com/wp-content/themes/responsive/core/css/style.css?ver=1.9.5.4' type='text/css' media='all' />
<link rel='stylesheet' id='responsive-media-queries-css'  href='http://infinetia.com/wp-content/themes/responsive/core/css/responsive.css?ver=1.9.5.4' type='text/css' media='all' />

</head>
<body style="background-color: transparent;">
<?

$account = "";
$title = "";

require_once("util.php");
require_once("lang.php");
require_once("controller.php");
require_once("db.php");

$db = new database();


$errors = ctrlAddUserPublic();
//printErrors($errors);

$username = isset($_POST["username"]) ? trim($_POST["username"]) : "";
$email = isset($_POST["email"]) ? trim($_POST["email"]) : "";


?>

<div align="center">
<style>
input{
	float:right;
	width:60%;
	
}
fieldset{
	padding:1% !important;
	margin:0;
	border:none;
	background:url(images/bg.png) repeat-x;
	background-size: 1px 384px
}
th, td, table{
	border:none;
}
.btn_s{
	width: 130px; 
	height: 35px; 
	float:left; 
	margin-left:1%;
	background:url(images/btn_bg.png) repeat-x !important;
	color: #fff !important;
}
</style>
<form method="POST" action="add-user-public.php">
<fieldset>
<legend></legend>

<table>

<!--
<tr>
<td>
<span style="color:#FFF"><?= getTrad("sign_up_username"); ?>*</span>

<input type="text" size="18" value="<?= $username ?>" name="username" />
<?
if (array_key_exists("Username", $errors))
{
	echo "<br /><font color='white' size=2 style='float:left; padding-left:40%'>" . $errors["Username"] . "</font><br />";
}
?>

</td>
</tr>
-->

<tr>
<td>
<span style="color:#FFF"><?= getTrad("sign_up_email"); ?>*</span>

<input type="text" size="18" value="<?= $email ?>" name="email" />
<?
if (array_key_exists("Email", $errors))
{
	echo "<br /><font color='white' size=2 style='float:left; padding-left:40%'>" . $errors["Email"] . "</font>";
}
?>
</td>
</tr>

<tr>
<td>
<span style="color:#FFF"><?= getTrad("sign_up_password"); ?>*</span>

<input type="password" size="18" name="password" />
<?
if (array_key_exists("Password", $errors))
{
	echo "<br /><font color='white' size=2 style='float:left; padding-left:40%'>" . $errors["Password"] . "</font>";
}
?>

</td>
</tr>

<tr>
<td>
<span style="color:#FFF"><?= getTrad("sign_up_password_confirm"); ?>*</span>
<input type="password" size="18" name="password_confirm" />
<input type="hidden" name="sent" value="1" />
</td>
</tr>

</table>

<input type="submit" value="<?= getTrad("sign_up_join_now"); ?>" class="btn_s" />

<p></p>
</fieldset>
</form>

</div>

<?

$db->close();

?>
</body>
</html>
