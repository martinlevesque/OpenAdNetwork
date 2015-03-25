<?



function JDateToSqlDate($date)
{
	$elems = split("/", $date);

	if (sizeof($elems) != 3)
		return "";

	return intval($elems[2]) . "-" . intval($elems[0]) . "-" . intval($elems[1]);
}

function slug( $string ) {
    return strtolower( preg_replace( array( '/[^-a-zA-Z0-9\s]/', '/[\s]/' ), array( '', '-' ), $string ) );
}

$ok = @session_start();
if(!$ok){
session_regenerate_id(true); // replace the Session ID
session_start(); 
}

$isLogged = isset($_SESSION["username"]);

//echo "sess started " . $ok . "\n";

//if ($isLogged !== TRUE)
//{
	//session_unset();
	//echo "isloggued = " . $isLogged . "<br \>\n";
//}

$username = $isLogged ? $_SESSION["username"] : "";

function money($number)
{
	return number_format($number, 2, '.', '');
}

function printErrors($errors)
{
	if (count($errors) > 0)
	{
		echo "<div align='center'>\n";

		echo "<table style='color: red;'>";

		foreach ($errors as $key => $value) 
		{
			echo "<tr><td>$key: </td><td>$value</td></tr>";
		}

		echo "</table>";
		echo "</div>\n";
	}
}

function printSuccess()
{
	echo "<div align='center'><table style='color: green;'><tr><td>The modifications have been saved successfully.</td></tr></table></div>";
}

function printSuccessComment($msg)
{
	echo "<div align='center'><table style='color: green;'><tr><td>$msg</td></tr></table></div>";
}

?>
