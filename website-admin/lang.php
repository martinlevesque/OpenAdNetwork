<?

$lang = "en"; // Default

$validLangs = array("en", "ru", "hi", "de", "ja", "es", "fr", "zh");

if (isset($_SESSION["lang"]) && $_SESSION["lang"] !== NULL && $_SESSION["lang"] != "")
{
	$lang = $_SESSION["lang"];
}

if (isset($_GET["lang"]) && $_GET["lang"] !== NULL && $_GET["lang"] != "")
{
	$lang = $_GET["lang"];
	$_SESSION["lang"] = $lang;
}

if ( ! in_array($lang, $validLangs))
{
	$lang = "en";
	$_SESSION["lang"] = $lang;
}

$trads = array("en" => array(), "ru" => array(), "hi" => array(), "de" => array(), "ja" => array(), "es" => array(), "fr" => array(), "zh" => array());

$trads["en"]["sign_up_username"] = "Username";
$trads["ru"]["sign_up_username"] = "&#1048;&#1084;&#1103; &#1087;&#1086;&#1083;&#1100;&#1079;&#1086;&#1074;&#1072;&#1090;&#1077;&#1083;&#1103;";
$trads["hi"]["sign_up_username"] = "&#2346;&#2381;&#2352;&#2351;&#2379;&#2325;&#2381;&#2340;&#2366; &#2344;&#2366;&#2350;";
$trads["de"]["sign_up_username"] = "Benutzername";
$trads["ja"]["sign_up_username"] = "&#12518;&#12540;&#12470;&#21517;";
$trads["es"]["sign_up_username"] = "Nombre de usuario";
$trads["fr"]["sign_up_username"] = "Nom d'utilisateur";
$trads["zh"]["sign_up_username"] = "&#29992;&#25143;&#21517;";

$trads["en"]["sign_up_company_name"] = "Company name";
$trads["ru"]["sign_up_company_name"] = "&#1085;&#1072;&#1079;&#1074;&#1072;&#1085;&#1080;&#1077; &#1092;&#1080;&#1088;&#1084;&#1099;";
$trads["hi"]["sign_up_company_name"] = "&#2325;&#2306;&#2346;&#2344;&#2368; &#2325;&#2366; &#2344;&#2366;&#2350;";
$trads["de"]["sign_up_company_name"] = "Firmenname";
$trads["ja"]["sign_up_company_name"] = "&#20250;&#31038;&#21517;";
$trads["es"]["sign_up_company_name"] = "Nombre de compa&ntilde;&iacute;a";
$trads["fr"]["sign_up_company_name"] = "Compagnie";
$trads["zh"]["sign_up_company_name"] = "&#20844;&#21496;&#21517;&#31216;";

$trads["en"]["sign_up_first_name"] = "First name";
$trads["ru"]["sign_up_first_name"] = "&#1080;&#1084;&#1103;";
$trads["hi"]["sign_up_first_name"] = "&#2346;&#2381;&#2352;&#2341;&#2350; &#2344;&#2366;&#2350;";
$trads["de"]["sign_up_first_name"] = "Vorname";
$trads["ja"]["sign_up_first_name"] = "&#12501;&#12449;&#12540;&#12473;&#12488;&#12493;&#12540;&#12512;";
$trads["es"]["sign_up_first_name"] = "Nombre de pila";
$trads["fr"]["sign_up_first_name"] = "Pr&eacute;nom";
$trads["zh"]["sign_up_first_name"] = "&#21517;&#23383;";

$trads["en"]["sign_up_last_name"] = "Last name";
$trads["ru"]["sign_up_last_name"] = "&#1092;&#1072;&#1084;&#1080;&#1083;&#1080;&#1103;";
$trads["hi"]["sign_up_last_name"] = "&#2309;&#2306;&#2340;&#2367;&#2350; &#2344;&#2366;&#2350;";
$trads["de"]["sign_up_last_name"] = "Nachname";
$trads["ja"]["sign_up_last_name"] = "&#21517;&#23383;";
$trads["es"]["sign_up_last_name"] = "apellido";
$trads["fr"]["sign_up_last_name"] = "Nom";
$trads["zh"]["sign_up_last_name"] = "&#22995;";

$trads["en"]["sign_up_email"] = "Email";
$trads["ru"]["sign_up_email"] = "E-mail";
$trads["hi"]["sign_up_email"] = "&#2312;&#2350;&#2375;&#2354;";
$trads["de"]["sign_up_email"] = "E-Mail";
$trads["ja"]["sign_up_email"] = "E&#12513;&#12540;&#12523;";
$trads["es"]["sign_up_email"] = "Email";
$trads["fr"]["sign_up_email"] = "Courriel";
$trads["zh"]["sign_up_email"] = "&#30005;&#23376;&#37038;&#20214;";

$trads["en"]["sign_up_password"] = "Password";
$trads["ru"]["sign_up_password"] = "&#1087;&#1072;&#1088;&#1086;&#1083;&#1100;";
$trads["hi"]["sign_up_password"] = "&#2346;&#2366;&#2360;&#2357;&#2352;&#2381;&#2337;";
$trads["de"]["sign_up_password"] = "Passwort";
$trads["ja"]["sign_up_password"] = "&#12497;&#12473;&#12527;&#12540;&#12489;";
$trads["es"]["sign_up_password"] = "contrase&ntilde;a";
$trads["fr"]["sign_up_password"] = "Mot de passe";
$trads["zh"]["sign_up_password"] = "&#23494;&#30721;";

$trads["en"]["sign_up_website"] = "Website";
$trads["ru"]["sign_up_website"] = "&#1089;&#1072;&#1081;&#1090;";
$trads["hi"]["sign_up_website"] = "&#2357;&#2375;&#2348;&#2360;&#2366;&#2311;&#2335;";
$trads["de"]["sign_up_website"] = "Webseite";
$trads["ja"]["sign_up_website"] = "&#12454;&#12455;&#12502;&#12469;&#12452;&#12488;";
$trads["es"]["sign_up_website"] = "sitio web";
$trads["fr"]["sign_up_website"] = "Site web";
$trads["zh"]["sign_up_website"] = "&#32593;&#31449;";

$trads["en"]["sign_up_password_confirm"] = "Password (confirm)";
$trads["ru"]["sign_up_password_confirm"] = "&#1055;&#1072;&#1088;&#1086;&#1083;&#1100; (&#1087;&#1086;&#1076;&#1090;&#1074;&#1077;&#1088;&#1078;&#1076;&#1077;&#1085;&#1080;&#1077;)";
$trads["hi"]["sign_up_password_confirm"] = "&#2346;&#2366;&#2360;&#2357;&#2352;&#2381;&#2337; (&#2346;&#2369;&#2359;&#2381;&#2335;&#2367;)";
$trads["de"]["sign_up_password_confirm"] = "Passwort (Best&auml;tigung)";
$trads["ja"]["sign_up_password_confirm"] = "&#12497;&#12473;&#12527;&#12540;&#12489;&#65288;&#30906;&#35469;&#65289;";
$trads["es"]["sign_up_password_confirm"] = "Contrase&ntilde;a (confirmar)";
$trads["fr"]["sign_up_password_confirm"] = "Mot de passe (confirmation)";
$trads["zh"]["sign_up_password_confirm"] = "&#23494;&#30721;&#65288;&#30830;&#35748;&#65289;";

$trads["en"]["sign_up_join_now"] = "Join NOW!";
$trads["ru"]["sign_up_join_now"] = "&#1055;&#1088;&#1080;&#1089;&#1086;&#1077;&#1076;&#1080;&#1085;&#1103;&#1081;&#1090;&#1077;&#1089;&#1100;";
$trads["hi"]["sign_up_join_now"] = "&#2309;&#2348; &#2360;&#2350;&#2381;&#2350;&#2367;&#2354;&#2367;&#2340; &#2361;&#2379;&#2306;";
$trads["de"]["sign_up_join_now"] = "Werden Sie jetzt Mitglied";
$trads["ja"]["sign_up_join_now"] = "&#20170;&#12377;&#12368;&#30331;&#37682;";
$trads["es"]["sign_up_join_now"] = "&Uacute;nete ahora";
$trads["fr"]["sign_up_join_now"] = "Inscris-toi maintenant!";
$trads["zh"]["sign_up_join_now"] = "&#29616;&#22312;&#21152;&#20837;";


function getTrad($var)
{
	global $lang;
	global $trads;

	return $trads[$lang][$var];
}

?>
