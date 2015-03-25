<?

require_once("db.php");
$id = intval($_GET["id"]);

$db = new database();

$website = $db->website($id);

header("Location: " . $website["url"]);

?>

<?

$db->close();

?>
