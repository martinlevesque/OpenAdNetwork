<?
ob_start();

echo "11";
flush();
ob_flush();
sleep(5);
echo "22";
flush();

ob_flush();
file_put_contents("log", "patate", FILE_APPEND | LOCK_EX);
?>
