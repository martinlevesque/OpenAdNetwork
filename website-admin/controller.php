<?

date_default_timezone_set("America/Montreal");
define("FOLDER_BANNERS", "/www/infinetia/banners/");
define("FOLDER_CACHE_MICROBLOGS", "/www/infinetia-microblog/cache/");

function checkHasAccess()
{
	global $isLogged;
	global $username;

	if ( ! $isLogged)
	{
		header("Location: index.php");
		exit;
	}
}

function checkIsAdmin()
{
	global $username;
	global $isLogged;

	if (!$isLogged || ($username != "spi.blog.com@gmail.com"))
	{
		header("Location: index.php");
		exit;
	}
}

function ctrlLogin()
{
	global $db;

	$errors = array();

	$username = (isset($_POST["username"])) ? $_POST["username"] : "";
	$password = isset($_POST["password"]) ? $_POST["password"] : "";

	if ($username != "" || $password != "")
	{
		$authValid = $db->authValid($username, $password);

		if ( ! $authValid)
		{
			$errors["Authentication"] = "Invalid username and/or password.";
		}
		else
		{
			$user = $db->getUserU($username);
			$_SESSION["username"] = $user["email"];
			header("Location: index.php");
			exit;
		}
	}

	return $errors;
}

function ctrlForgotPassword()
{
	global $db;

	$errors = array();

	$email = $_POST["email"];

	if ($email != "")
	{
		$user = $db->getUserByEmail($email);

		if ( ! $user)
		{
			$errors["Email"] = "The provided email is not registered in infiNetia.";
		}
		else
		{
			$pw = generatePassword(7);
			$db->updatePw($user["username"], $pw);
			sendRegistrationEmail($user["username"], $user["username"], $email, $pw);
		}
	}

	return $errors;
}

function ctrlAddMicroblog()
{
	global $db;

	checkHasAccess();

	$errors = array();

	$website_id = isset($_POST["website_id"]) ? intval($_POST["website_id"]) : 0;
	$title = isset($_POST["title"]) ? $_POST["title"] : "";
	$content = isset($_POST["content"]) ? $_POST["content"] : "";

	if ($website_id > 0)
	{
		if ($title == "")
		{
			$errors["Title"] = "A title must be filled.";
		}

		if ($content == "")
		{
			$errors["Post"] = "The post content must be filled.";
		}

	}

	if (count($errors) == 0 && $website_id > 0)
	{
		$db->insertMicroblog($website_id, $title, $content);
		//header("Location: websites-publisher.php");
		$website = $db->getWebsite($website_id);
		system("rm -f " . FOLDER_CACHE_MICROBLOGS . slug($website["name"]) . ".infinetia-*");
	}

	return $errors;
}

function ctrlAddWebsite()
{
	global $db;

	checkHasAccess();

	$errors = array();

	$name = isset($_POST["name"]) ? slug(trim($_POST["name"])) : "";
	$description = isset($_POST["description"]) ? trim($_POST["description"]) : "";
	$url = isset($_POST["url"]) ? trim($_POST["url"]) : "";
	$category = isset($_POST["category"]) ? intval($_POST["category"]) : 0;

	if ($name != "" || $description != "" || $url != "" || $category > 0)
	{
		if ($name == "")
		{
			$errors["Name"] = "A name must be filled.";
		}
		else
		if ($db->websiteExists($name))
		{
			$errors["Name"] = "This website's name already exists.";
		}

		if ($description == "")
		{
			$errors["Description"] = "A description must be filled.";
		}

		if ($url == "")
		{
			$errors["URL"] = "An url must be filled.";
		}
	}

	if (count($errors) == 0 && $category > 0)
	{
		$idWebsite = $db->insertWebsite($name, $description, $url, $category);

		// Create automatically microblog banners
		$ban1 = $db->getBannerFormatSize(468, 60);
		$ban2 = $db->getBannerFormatSize(250, 250);

		$db->insertMicroblogZone($idWebsite, "468x60", $ban1["id"], $category);
		$db->insertMicroblogZone($idWebsite, "250x250", $ban2["id"], $category);

		//$cmd = "/usr/bin/wget -O /tmp/res.html http://publisher-directory.infinetia.com/publisher.php?id=$idWebsite > /dev/null &";

		header("Location: add-zone-publisher.php?website_id=$idWebsite");
		exit;
	}

	return $errors;
}

function ctrlAddZone()
{
	global $db;

	checkHasAccess();

	$errors = array();

	$website_id = isset($_POST["website_id"]) ? intval($_POST["website_id"]) : 0;
	$name = isset($_POST["name"]) ? trim($_POST["name"]) : "";
	$banner_format_id = isset($_POST["banner_format_id"]) ? intval($_POST["banner_format_id"]) : 0;
	$category_id = isset($_POST["category"]) ? intval($_POST["category"]) : 0;

	if ($name != "" || $website_id > 0 || $banner_format_id > 0 || $category_id > 0)
	{
		if ($website_id <= 0)
		{
			$errors["Website"] = "Make sure to select a website.";
		}

		if ($name == "")
		{
			$errors["Name"] = "A name must be filled.";
		}

		if ($banner_format_id <= 0)
		{
			$errors["Format"] = "Make sure to select a banner format.";
		}

		if ($category_id <= 0)
		{
			$errors["Category"] = "Make sure to select a category.";
		}
	}

	if (count($errors) == 0 && $website_id > 0)
	{
		$db->insertZone($website_id, $name, $banner_format_id, $category_id);
		header("Location: tag-zone-publisher.php?id=" . $db->getLastId());
		exit;
	}

	return $errors;
}

function ctrlAdminSupport()
{
	global $db;

	checkHasAccess();
	$user = $db->getUser();

	$errors = array();

	if ( ! isset($_POST["comments"]))
		return $errors;

	$comments = isset($_POST["comments"]) ? trim($_POST["comments"]) : "";

	if ($comments == "")
	{
		$errors["Comments"] = "Please make sure to fill the box below.";
	}

	if (count($errors) == 0)
	{
		$subject = "infiNetia admin support";

		$email = $user["email"];
		$headers = "From: support@infinetia.com" . "\r\n" .
		     "Reply-To: $email\r\n".
			"X-Mailer: PHP 4.x";

		$body = "Issue: $comments";

		//mail($user["email"], $subject, $body, $headers, "-f support@infinetia.com -F 'Support team'");
		mail("infinetia@gmail.com", $subject, $body, $headers, "-f support@infinetia.com -F 'Support team'");
	}

	return $errors;
}

function ctrlEditWebsite()
{
	global $db;

	checkHasAccess();

	$errors = array();

	$name = isset($_POST["name"]) ? trim($_POST["name"]) : "";
	$description = isset($_POST["description"]) ? trim($_POST["description"]) : "";
	$url = isset($_POST["url"]) ? trim($_POST["url"]) : "";
	$category = isset($_POST["category"]) ? intval($_POST["category"]) : 0;
	$id = isset($_GET["id"]) ? intval($_GET["id"]) : 0;

	if ($name != "" || $description != "" || $url != "" || $category > 0)
	{
		if ($name == "")
		{
			$errors["Name"] = "A name must be filled.";
		}

		if ($description == "")
		{
			$errors["Description"] = "A description must be filled.";
		}

		if ($url == "")
		{
			$errors["URL"] = "An url must be filled.";
		}
	}

	if (count($errors) == 0 && $category > 0)
	{
		$db->updateWebsite($id, $name, $description, $url, $category);

		header("Location: websites-publisher.php");
		exit;
	}

	return $errors;
}

function ctrlEditZone()
{
	global $db;

	checkHasAccess();

	$errors = array();

	$website_id = isset($_POST["website_id"]) ? intval($_POST["website_id"]) : 0;
	$name = isset($_POST["name"]) ? trim($_POST["name"]) : "";
	$banner_format_id = isset($_POST["banner_format_id"]) ? intval($_POST["banner_format_id"]) : 0;
	$category_id = isset($_POST["category"]) ? intval($_POST["category"]) : 0;
	$id = isset($_GET["id"]) ? intval($_GET["id"]) : 0;

	if ($name != "" || $website_id > 0 || $banner_format_id > 0 || $category_id > 0)
	{
		if ($website_id <= 0)
		{
			$errors["Website"] = "Make sure to select a website.";
		}

		if ($name == "")
		{
			$errors["Name"] = "A name must be filled.";
		}

		if ($banner_format_id <= 0)
		{
			$errors["Format"] = "Make sure to select a banner format.";
		}

		if ($category_id <= 0)
		{
			$errors["Category"] = "Make sure to select a category.";
		}
	}

	if (count($errors) == 0 && $website_id > 0)
	{
		$db->updateZone($id, $website_id, $name, $banner_format_id, $category_id);
		header("Location: zones-publisher.php");
		exit;
	}

	return $errors;
}

function ctrlEditUser()
{
	global $db;

	checkHasAccess();

	$errors = array();

	$firstname = isset($_POST["firstname"]) ? trim($_POST["firstname"]) : "";
	$lastname = isset($_POST["lastname"]) ? trim($_POST["lastname"]) : "";
	$company = isset($_POST["company"]) ? trim($_POST["company"]) : "";
	$address = isset($_POST["address"]) ? trim($_POST["address"]) : "";
	$city = isset($_POST["city"]) ? trim($_POST["city"]) : "";
	$zipcode = isset($_POST["zipcode"]) ? trim($_POST["zipcode"]) : "";
	$phone_number = isset($_POST["phone_number"]) ? trim($_POST["phone_number"]) : "";
	$publisher_paypal = isset($_POST["publisher_paypal"]) ? trim($_POST["publisher_paypal"]) : "";
	$country_id = isset($_POST["country_id"]) ? intval($_POST["country_id"]) : 0;
	$minimum_payout = isset($_POST["minimum_payout"]) ? intval($_POST["minimum_payout"]) : 0;

	if ($minimum_payout <= 0)
		$minimum_payout = 20;

	if (count($errors) == 0 && $country_id > 0)
	{
		$db->updateUser($firstname, $lastname, $company, $address, $city, $zipcode, $phone_number,
			$publisher_paypal, $country_id, $minimum_payout);
		header("Location: account-configurations.php");
		exit;
	}

	return $errors;
}

function ctrlDeleteWebsite()
{
	global $db;

	checkHasAccess();

	$id = intval($_GET["id"]);

	if ($id > 0)
	{
		$db->deleteWebsite($id);
		
	}
	
	header("Location: websites-publisher.php");
	exit;
}

function ctrlDeleteMicroblog()
{
	global $db;

	checkHasAccess();

	$id = intval($_GET["id"]);

	if ($id > 0)
	{
		$website_id = $db->getWebsiteIdOfMicroblog($id);
		$website = $db->getWebsite($website_id);
		$cmd = "rm -f " . FOLDER_CACHE_MICROBLOGS . slug($website["name"]) . ".infinetia-*";
		system($cmd);

		$db->deleteMicroblog($id);
	}
	
	header("Location: add-microblog-publisher.php");
	exit;
}

function ctrlDeleteCampaign()
{
	global $db;

	checkHasAccess();

	$id = intval($_GET["id"]);

	if ($id > 0)
	{
		$db->deleteCampaign($id);
		
	}
	
	header("Location: campaigns-advertiser.php");
	exit;
}

function ctrlDeleteZone()
{
	global $db;

	checkHasAccess();

	$id = intval($_GET["id"]);

	if ($id > 0)
	{
		$db->deleteZone($id);
		
	}
	
	header("Location: zones-publisher.php");
	exit;
}

function ctrlActivateCampaign()
{
	global $db;
	
	checkHasAccess();

	$id = intval($_GET["id"]);
	$activate = intval($_GET["activate"]);

	if ($id > 0)
	{
		$db->activateCampaign($id, ($activate == 0) ? 1 : 0);
		
	}
	
	header("Location: campaigns-advertiser.php");
	exit;
}

function ctrlIndex()
{
	global $isLogged;


	if ( ! $isLogged)
	{
		header("Location: login.php");
		exit;
	}
	else
	{
		header("Location: dashboard-publisher.php");
		exit;
	}
}

function ctrlLogout()
{
	$_SESSION["username"] = NULL;
	header("Location: index.php");
	exit;
}

function sendPendingNotification($msg)
{
	$subject = "infiNetia pending notification";

	$headers = "From: support@infinetia.com" . "\r\n" .
	     "Reply-To: infinetia@gmail.com\r\n".
		"X-Mailer: PHP 4.x";

	$body = "Msg = $msg";

	mail("infinetia@gmail.com", $subject, $body, $headers, "-f support@infinetia.com -F 'Support team'");
}

function ctrlAddCampaign()
{
	global $db;

	checkHasAccess();

	$errors = array();

	$name = isset($_POST["name"]) ? $_POST["name"] : "";
	$category_id = isset($_POST["category"]) ? intval($_POST["category"]) : 0;
	$max_per_day = isset($_POST["max_per_day"]) ? intval($_POST["max_per_day"]) : 0;

	if ($category_id > 0)
	{
		if ($name == "")
		{
			$errors["Name"] = "A name must be filled.";
		}
	}

	if (count($errors) == 0 && $category_id > 0)
	{
		$idCampaign = $db->insertCampaign($name, $category_id, $max_per_day);

		sendPendingNotification("Campaign added");

		//header("Location: campaigns-advertiser.php");
		header("Location: banners-advertiser.php?id=$idCampaign");
		exit;
	}

	return $errors;
}

function ctrlEditCampaign()
{
	global $db;

	checkHasAccess();

	$errors = array();

	$id = intval($_GET["id"]);
	$name = $_POST["name"];
	$category_id = isset($_POST["category"]) ? intval($_POST["category"]) : 0;
	$max_per_day = isset($_POST["max_per_day"]) ? intval($_POST["max_per_day"]) : 0;

	if ($category_id > 0)
	{
		if ($name == "")
		{
			$errors["Name"] = "A name must be filled.";
		}
	}

	if (count($errors) == 0 && $category_id > 0)
	{
		$db->updateCampaign($id, $name, $category_id, $max_per_day);
		header("Location: campaigns-advertiser.php");
		exit;
	}

	return $errors;
}

function AddImageBanner($campaign_id)
{
	global $db;

	$errors = array();

	$banner_format_id = isset($_POST["banner_format_id"]) ? intval($_POST["banner_format_id"]) : 0;
	$url = isset($_POST["url"]) ? $_POST["url"] : "";

	if ($banner_format_id > 0)
	{
		if ( ! isset($_FILES["banner_file"]) || intval($_FILES["banner_file"]["size"]) >= 500000 || intval($_FILES["banner_file"]["size"]) <= 10)
		{
			$errors["Bannerimage"] = "A valid banner image must be selected.";
		}

		// Check the hover img (if any)
		if (isset($_FILES["hover_file"]) && (intval($_FILES["hover_file"]["size"]) >= 500000))
		{
			$errors["Hoverimage"] = "The uploaded hover image must be valid (note that it is optional).";
		}
	
		if ($url == "" || $url == "http://")
		{
			$errors["URL"] = "An URL must be specified.";
		}
	}

	if (count($errors) == 0 && $banner_format_id > 0)
	{
		$folder = FOLDER_BANNERS . date("Y-m-d") . "/";

		if ( ! file_exists($folder))
			$res = mkdir($folder, 0777);

		$has_hover = 0;

		if (isset($_FILES["hover_file"]) && intval($_FILES["hover_file"]["size"]) > 0)
		{
			$has_hover = 1;
		}

		$db->insertBanner($campaign_id, $banner_format_id, $url, $has_hover);

		$bannerId = $db->getLastId();
		$bannerFormat = $db->getBannerFormat($banner_format_id);

		$res = exec("convert -quality 90% " . $_FILES['banner_file']['tmp_name'] . " $folder$bannerId.jpg");

		if ($has_hover == 1)
		{
			$resHover = exec("convert -quality 90% " . $_FILES['hover_file']['tmp_name'] . " $folder$bannerId-hover.jpg");
			$infosImageHover = getimagesize("$folder$bannerId-hover.jpg");
		}

		$infosImage = getimagesize("$folder$bannerId.jpg");

		if (($res != "" || sizeof($infosImage) < 2 || !(intval($bannerFormat["width"]) == intval($infosImage[0]) && intval($bannerFormat["height"]) == intval($infosImage[1]))) || ($has_hover == 1 && ($resHover != "" || sizeof($infosImageHover) < 2 || !(intval($bannerFormat["width"]) == intval($infosImageHover[0]) && intval($bannerFormat["height"]) == intval($infosImageHover[1])))))
		{
			$db->deleteBanner($bannerId);
			exec("rm -f $folder$bannerId.jpg");
			$errors["Banner image"] = "Invalid image format.";
		}
		else
		{
			//header("Location: banners-advertiser.php?id=" . $campaign_id);
			//exit;
		}
	}


	return $errors;
}

function AddTextBanner($campaign_id)
{
	global $db; 

	$errors = array();

	// Validation
	if ( ! (isset($_POST["title"]) && isset($_POST["url"]) && isset($_POST["url_label"]) && isset($_POST["line1"]) && isset($_POST["line2"]) && $_POST["title"] != "" && $_POST["url"] != "" && $_POST["url_label"] != "" && $_POST["line1"] != "" && $_POST["line2"] != ""))
	{
		$errors["Banner infos"] = "The provided information are incomplete.";
		return $errors;
	}

	$db->insertTextBanner($campaign_id, $_POST["title"], $_POST["url"], $_POST["url_label"], $_POST["line1"], $_POST["line2"]);

	return $errors;
}

function ctrlAddBanner()
{
	global $db;

	checkHasAccess();

	$campaign_id = intval($_GET["id"]);
	$type = (isset($_GET["type"])) ? $_GET["type"] : "";

	$errors = array();


	if ($type == "image")
	{
		$errors = AddImageBanner($campaign_id);
	}
	else
	if ($type == "text" && isset($_POST["title"]))
		$errors = AddTextBanner($campaign_id);

	if (isset($_POST["url"]) && $_POST["url"] != "" && $errors == array())
	{
		sendPendingNotification("Banner added");
	}

	return $errors;
}

function generatePassword ($length = 8)
  {

    // start with a blank password
    $password = "";

    // define possible characters - any character in this string can be
    // picked for use in the password, so if you want to put vowels back in
    // or add special characters such as exclamation marks, this is where
    // you should do it
    $possible = "123456789abcdefghijklmnopqrstuvwxyz";

    // we refer to the length of $possible a few times, so let's grab it now
    $maxlength = strlen($possible);
  
    // check for length overflow and truncate if necessary
    if ($length > $maxlength) {
      $length = $maxlength;
    }
	
    // set up a counter for how many characters are in the password so far
    $i = 0; 
    
    // add random characters to $password until $length is reached
    while ($i < $length) { 

      // pick a random character from the possible ones
      $char = substr($possible, mt_rand(0, $maxlength-1), 1);
        
      // have we already used this character in $password?
      if (!strstr($password, $char)) { 
        // no, so it's OK to add it onto the end of whatever we've already got...
        $password .= $char;
        // ... and increase the counter by one
        $i++;
      }

    }

    // done!
    return $password;

  }

function sendBanEmail($username, $email)
{
	$to = "$email";
	$subject = "infiNetia account suspended";

	$headers = "From: support@infinetia.com" . "\r\n" .
	     "Reply-To: infinetia@gmail.com\r\n".
		"X-Mailer: PHP 4.x";

	$body = "Dear infiNetia user,\n\n".
		"We recently verified your websites of your infiNetia account ($username) and found that your website(s) do not respect our policies.\n".
		"Toward this end, we suspended your infiNetia account.\n".
		"Our policy is available at http://infiNetia.com/infiNetia-policy/.\n".
		"If you think this is a mistake, please contact us.\n\n".
		"Best Regards,\n".
		"infiNetia.com support team\n".
		"http://www.infiNetia.com/";
	if (mail($to, $subject, $body, $headers, "-f support@infinetia.com -F 'Support team'")) 
	{
		mail("infinetia@gmail.com", $subject, $body, $headers, "-f support@infinetia.com -F 'Support team'"); // copy
		echo("<p>Message successfully sent!</p>");
	} 
	else 
	{
		echo("<p>Message delivery failed...</p>");
	}
}

function sendNewsletter($content)
{
	global $db;

	$users = $db->getUsersForNewsletter();

	for ($i = 0; $i < sizeof($users); ++$i)
	{
		$to = $users[$i]["email"];
		$subject = "News from infiNetia.com: The free Advertising and Blog Network";

		$headers = "From: support@infinetia.com" . "\r\n" .
		     "Reply-To: infinetia@gmail.com\r\n".
			"X-Mailer: PHP 4.x";

		$body = "$content\n\nFacebook: https://www.facebook.com/infinetia\nTwitter: https://twitter.com/infinetia\n\nTo unsubscribe from the infiNetia newsletter: http://admin.infinetia.com/unsubscribe.php?m=$to.";

		if (mail($to, $subject, $body, $headers, "-f support@infinetia.com -F 'Support team'")) 
		{
			echo("<p>Message successfully sent to $to</p>");
		} 
		else 
		{
			echo("<p>Message delivery failed...$to</p>");
		}

		//break;
		usleep(100000);
	}
}

function sendRegistrationEmail($username, $firstname, $email, $pw)
{
	$to = "$email";
	$subject = "Your infiNetia account";

	$headers = "From: support@infinetia.com" . "\r\n" .
	     "Reply-To: infinetia@gmail.com\r\n".
		"X-Mailer: PHP 4.x";

	$body = "Dear infiNetia user,\n\n".
		"Please find below your infiNetia.com account information:\n\n".
		"Your Login: $email\n".
		"Your Password: $pw\n".
		"URL to login: http://admin.infiNetia.com/\n\n".
		"Create blogs to earn points and get backlinks: http://admin.infinetia.com/add-microblog-publisher.php\n".
		"Recommend infiNetia and earn 5000 extra points: http://infinetia.com/en/earn-5000-points-by-recommending-infinetia/\n\n".
		"We are looking forward to see you on infiNetia.com! If you need any support, don't hesitate to contact us.\n\n".
		"Best Regards,\n".
		"infiNetia.com support team\n".
		"http://www.infiNetia.com/\nFacebook: https://www.facebook.com/infinetia\nTwitter: https://twitter.com/infinetia";
	if (mail($to, $subject, $body, $headers, "-f support@infinetia.com -F 'Support team'")) 
	{
		mail("infinetia@gmail.com", $subject, $body, $headers, "-f support@infinetia.com -F 'Support team'"); // copy
		//echo("<p>Message successfully sent!</p>");
	} 
	else 
	{
		echo("<p>Message delivery failed...</p>");
	}
}

function ctrlSendNewsletter()
{
	global $db;

	$errors = array();
	
	checkIsAdmin();


	$content = isset($_POST["content"]) ? $_POST["content"] : "";

	// registration [username],[company],[firstname],[lastname],[email],[website]
	if ($content == "")
		return $errors;

	sendNewsletter($content);

	return $errors;
}


function ctrlAddUser()
{
	global $db;

	$errors = array();
	
	checkIsAdmin();


	$users = isset($_POST["users"]) ? $_POST["users"] : "";

	$infos = split(",", $users);

	// registration [username],[company],[firstname],[lastname],[email],[website]
	if ($users == "")
		return $errors;

	if (sizeof($infos) != 6)
	{
		$errors["Cmd line"] = "invalid nb of arguments";
		return $errors;

	}
	$user = $infos[0];
	$company = $infos[1];
	$firstname = $infos[2];
	$lastname = $infos[3];
	$email = $infos[4];
	$website = $infos[5];

	$pw = generatePassword(7);
	$db->insertUser($user, $company, $firstname, $lastname, $email, $website, $pw);
	
	if ($db->getLastId() <= 0)
	{
		$errors["DB"] = "error with insert...";
	}
	else
	{
		sendRegistrationEmail($user, $firstname, $email, $pw);
		header("Location: add-user.php");
		exit;
	}

	return $errors;
}

function ctrlMakePublisherPayment()
{
	global $db;

	$errors = array();
	
	checkIsAdmin();


	$username = isset($_POST["username"]) ? $_POST["username"] : "";
	$amount = isset($_POST["amount"]) ? floatval($_POST["amount"]) : 0.0;
	$transaction_id = isset($_POST["transaction_id"]) ? $_POST["transaction_id"] : "";

	if ($amount <= 0)
		return $errors;

	// registration [username],[company],[firstname],[lastname],[email],[website]

	$db->insertPublisherPayment($username, $amount, $transaction_id);
	$db->payPublisher($username, $amount);
	
	header("Location: index.php");

	return $errors;
}

function ctrlAddUserPublic()
{
	global $db;

	$errors = array();

	//$username = isset($_POST["username"]) ? trim($_POST["username"]) : "";
	$email = isset($_POST["email"]) ? trim($_POST["email"]) : "";
	$password = isset($_POST["password"]) ? trim($_POST["password"]) : "";
	$password_confirm = isset($_POST["password_confirm"]) ? trim($_POST["password_confirm"]) : "";
	$sent = isset($_POST["sent"]) ? intval($_POST["sent"]) : 0;

	// registration [username],[company],[firstname],[lastname],[email],[website]
	if ($sent == 0)
		return $errors;

	/*
	if (strlen($username) < 6)
	{
		$errors["Username"] = "Must be at least 6 characters.";
	}
	else
	if ($db->usernameExists($username))
	{
		$errors["Username"] = "The username already exists.";
	}
	*/

	if ($email == "")
	{
		$errors["Email"] = "A valid e-mail must be specified.";
	}
	else
	if ($db->emailExists($email))
	{
		$errors["Email"] = "The email already exists.";
	}

	if (strlen($password) < 6)
	{
		$errors["Password"] = "Must be at least 6 characters.";
	}

	if ($password != $password_confirm)
	{
		$errors["Password"] = "The password and confirmation password must match.";
	}

	if (sizeof($errors) == 0)
	{
		$pw = $password;
		$db->insertUser($email, "", "", "", $email, "http://", $pw);
	
		if ($db->getLastId() <= 0)
		{
			$errors["Error"] = "An error occured while inserting user.";
		}
		else
		{
			sendRegistrationEmail($email, $email, $email, $pw);
			header("Location: add-user-public-confirmation.php");
			exit;
		}
	}

	return $errors;
}

function ctrlDeleteBanner()
{
	global $db;

	checkHasAccess();

	$id = intval($_GET["id"]);
	$campaign_id = intval($_GET["campaign_id"]);

	if ($id > 0)
	{
		$db->deleteBannerWeb($id);
		
	}
	
	header("Location: banners-advertiser.php?id=" . $campaign_id);
	exit;
}

function ctrlAdminPendingBanner()
{
	global $db;
	$errors = array();

	checkIsAdmin();

	$id = isset($_GET["id"]) ? intval($_GET["id"]) : 0;
	$status = isset($_POST["status"]) ? $_POST["status"] : "";
	$status_comment = isset($_POST["status_comment"]) ? $_POST["status_comment"] : "";

	if ($status != "")
	{
		$db->updatePendingBanner($id, $status, $status_comment);
	}
	else
		return $errors;
	
	header("Location: admin-pending-banners.php");
	exit;
	
}

function ctrlAdminPendingCampaign()
{
	global $db;
	$errors = array();

	checkIsAdmin();

	$id = isset($_GET["id"]) ? intval($_GET["id"]) : 0;
	$status = isset($_POST["status"]) ? $_POST["status"] : "";
	$status_comment = isset($_POST["status_comment"]) ? $_POST["status_comment"] : "";

	if ($status != "")
	{
		$db->updatePendingCampaign($id, $status, $status_comment);
	}
	else
		return $errors;
	
	header("Location: admin-pending-campaigns.php");

	exit;
}

?>
