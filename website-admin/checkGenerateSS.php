<?


require_once("db.php");

$db = new database();

$id = $db->getWebsiteWithoutSS();

$cmd = "/usr/bin/wget -O /tmp/res.html http://publisher-directory.infinetia.com/publisher.php?id=$id > /dev/null &";

echo "Computing... cmd = " . $cmd;
system("rm -f /tmp/res.html");
system($cmd);
echo "ok.";

$db->markWebsiteSSComputed($id);

?>

<?

$db->close();

?>
