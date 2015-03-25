<?   
require_once("dbwordpress.php");

$db = new database();

$nb = $db->cntBlogs();

echo "<font style='font-size: 20px;'>Now serving <font style='color: green;'>$nb</font> blogs!</font>";

$db->close();

?>
