<?


require_once("dbwordpress.php");

$db = new database();

function sendSpiClickAd($email)
{
        $to = "$email";
        $subject = "spi-blog.com - New service for webmasters and publishers: spiClick.com! Monetize your websites.";

        $headers = "From: spi.blog.com@gmail.com" . "\r\n" .
             "Reply-To: spi.blog.com@gmail.com\r\n".
                "X-Mailer: PHP 4.x";

        $body = "Dear spiBlog user,\n\n".
                "We are pleased to let you know our newest creation: http://www.spiClick.com/, our new innovative advertising network!\n\n".
                "Since you created a blog at spi-blog.com, you might be interested to join http://www.spiClick.com/ and start now to MONETIZE YOUR WEBSITES.\n\n".
                "Earn 1 cent per click, no variable earnings!\n".
                "Minimum payout: $5! The lowest ad network minimum payout on the Internet.\n\n".
                "Join now spiClick.com at: http://www.spiclick.com/sign-up/.\n\n".
                "We are looking forward to see you on spiClick.com! If you need any support, don't hesitate to contact us.\n\n".
                "Best Regards,\n".
                "spiClick.com support team\n".
                "http://www.spiclick.com/";
        if (mail($to, $subject, $body, $headers))
        {
                echo("Sent to $email\n");
        }
        else
        {
                echo("<p>Message delivery failed...</p>");
        }
}

sendSpiClickAd("levesque.martin@gmail.com");

$signups = $db->getBlogs();

?>

<? for ($i = 0; $i < sizeof($signups); ++$i){ ?>
<? echo "$i / " . sizeof($signups) . " "; ?>
<? sendSpiClickAd($signups[$i]["user_email"]); ?>
<? sleep(1); ?>
<? } ?>

<?

$db->close();

?>
