<?


exit();
require_once("db.php");

$db = new database();

function sendSpiClickAd($email)
{
        $to = "$email";
        $subject = "infiNetia - The new Online Advertising Network!";

        $headers = "From: support@infinetia.com" . "\r\n" .
             "Reply-To: infinetia@gmail.com\r\n".
                "X-Mailer: PHP 4.x";

        $body = "Dear Webmaster,\n\n".
                "We are pleased to let you know our new free innovative advertising network: http://www.infiNetia.com/ !\n\n".
                "Earn iPoints by placing our ads on your websites and receive infinite quality traffic!\n".
                "Get your free account now at http://infinetia.com/en/sign-up/ and start using our Free Ad Network.\n\n".
                "We are looking forward to see you on infiNetia.com! If you need any support, don't hesitate to contact us.\n\n".
                "Best Regards,\n".
                "infiNetia.com support team\n".
                "http://www.infiNetia.com/";
        if (mail($to, $subject, $body, $headers, "-f support@infinetia.com -F 'Support team'"))
        {
                echo("Sent to $email\n");
        }
        else
        {
                echo("<p>Message delivery failed...</p>");
        }
}

sendSpiClickAd("martinl18@hotmail.fr");

$lines = file("mail.csv");
$cpt = 1;
$nb = sizeof($lines);

foreach($lines as $line_num => $line)
{
$mail=trim($line);
echo "$cpt/$nb-$mail-\n";
file_put_contents("log2", $mail . "\n", FILE_APPEND | LOCK_EX); 
sendSpiClickAd($mail, "Webmaster"); 
$cpt += 1;
sleep(5);
}
//$users = $db->getUsersbak();

echo "titi";
//$signups = $db->getBlogs();

?>

<? //for ($i = 0; $i < sizeof($users); ++$i){ ?>
<? //file_put_contents("log", $users[$i]["email"] . "\n", FILE_APPEND | LOCK_EX); ?>
<? //sendSpiClickAd($users[$i]["email"], $users[$i]["firstname"] . " " . $users[$i]["lastname"]); ?>
<? //sleep(1); ?>
<? //} ?>

<?

$db->close();

?>
