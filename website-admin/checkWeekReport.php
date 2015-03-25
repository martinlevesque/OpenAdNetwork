<?


require_once("db.php");

$db = new database();

function sendMail($user_id, $email, $points)
{
	global $db;

        $to = "$email";
        $subject = "Weekly report of infiNetia.com, The free Ad and Blog Network";

        $headers = "From: support@infinetia.com" . "\r\n" .
             "Reply-To: infinetia@gmail.com\r\n".
                "X-Mailer: PHP 4.x";

	$row = $db->getLastWeekPublisher($user_id);
	$nb_views_publisher = $row["nb_views"];
	$nb_clicks_publisher = $row["nb_clicks"];
	$rowAdvertiser = $db->getLastWeekAdvertiser($user_id);
	$nb_views_advertiser = $rowAdvertiser["nb_views"];
	$nb_clicks_advertiser = $rowAdvertiser["nb_clicks"];

        $body = "Hello from infiNetia,\n\n".
                "This is the weekly report from http://infiNetia.com/. You currently have $points point(s) ready to be used for advertisement.\n\n".
                "Publisher report:\n".
                "---\n".
                "# impressions: $nb_views_publisher\n".
                "# clicks: $nb_clicks_publisher\n\n".
                "Advertiser report:\n".
                "---\n".
                "# impressions: $nb_views_advertiser\n".
                "# clicks: $nb_clicks_advertiser\n\n".
                "If you need any support, don't hesitate to contact us.\n\n".
                "Best Regards,\n".
                "infiNetia.com support team\n".
                "http://www.infiNetia.com/\n\nTo unsubscribe from the infiNetia weekly report: http://admin.infinetia.com/unsubscribe.php?m=$email.";
        if (mail($to, $subject, $body, $headers, "-f support@infinetia.com -F 'Support team'"))
        {
                echo("Sent to $email\n");
        }
        else
        {
                echo("<p>Message delivery failed...</p>");
        }
}

$users = $db->getActiveUsersLastWeek();

for ($i = 0; $i < count($users); ++$i) 
{
	sendMail($users[$i]["id"], $users[$i]["email"], round(floatval($users[$i]["points"]), 1));

	if (intval($users[$i]["id"]) == 1)
	{
		sendMail($users[$i]["id"], "infinetia@gmail.com", round(floatval($users[$i]["points"]), 1));
	}

	usleep(10000);
}

?>

<?

$db->close();

?>
