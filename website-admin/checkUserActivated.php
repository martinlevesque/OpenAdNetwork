<?


require_once("db.php");

$db = new database();

function sendMail($email)
{
        $to = "$email";
        $subject = "Getting Started with infiNetia";

        $headers = "From: support@infinetia.com" . "\r\n" .
             "Reply-To: infinetia@gmail.com\r\n".
                "X-Mailer: PHP 4.x";

        $body = "Hello from infiNetia,\n\n".
                "Congratulations! You've successfully signed up for infiNetia!\n".
                "It's now time to connect to your account at http://admin.infiNetia.com/.\n\n".
                "Start blogging using our free hosting service:\n".
		" - We provide free blog services to promote your websites.\n".
		" - And you automatically earn iPoints which can be used to Advertise.\n\n".
                "Publish and earn iPoints:\n".
                " - Add a website: Click Publish > Websites > Add a website.\n".
                " - Add a zone with the proper banner dimension.\n".
                " - Copy and paste the HTML code to your website.\n\n".
                "Advertise and receive free traffic:\n".
                " - Create a campaign: Click Advertise > Campaigns > Add a Campaign.\n".
                " - Create one or several banners (text or image) associated to your campaign.\n\n".
		"Recommend infiNetia and earn 5000 extra points: http://infinetia.com/en/earn-5000-points-by-recommending-infinetia/.\n\n".
                "If you need any support, don't hesitate to contact us.\n\n".
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

sendMail("infinetia@gmail.com");

$users = $db->getUsersCreatedYesterday();

for ($i = 0; $i < count($users); ++$i) {
	sendMail($users[$i]["email"]);
	sleep(1);
}

?>

<?

$db->close();

?>
