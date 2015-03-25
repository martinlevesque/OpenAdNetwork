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

?>
