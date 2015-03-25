<?

define("FOLDER_BANNERS", "/www/spiclick/banners/");

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

	if (!$isLogged || $username != "admin")
	{
		header("Location: index.php");
		exit;
	}
}

function ctrlLogin()
{
	global $db;

	$errors = array();

	$username = $_POST["username"];
	$password = $_POST["password"];

	if ($username != "" || $password != "")
	{
		$authValid = $db->authValid($username, $password);

		if ( ! $authValid)
		{
			$errors["Authentication"] = "Invalid username and/or password.";
		}
		else
		{
			$_SESSION["username"] = $username;
			header("Location: index.php");
			exit;
		}
	}

	return $errors;
}

function ctrlAddWebsite()
{
	global $db;

	checkHasAccess();

	$errors = array();

	$name = isset($_POST["name"]) ? trim($_POST["name"]) : "";
	$description = isset($_POST["description"]) ? trim($_POST["description"]) : "";
	$url = isset($_POST["url"]) ? trim($_POST["url"]) : "";
	$category = isset($_POST["category"]) ? intval($_POST["category"]) : 0;

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
		$db->insertWebsite($name, $description, $url, $category);
		header("Location: websites-publisher.php");
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

function ctrlAddCampaign()
{
	global $db;

	checkHasAccess();

	$errors = array();

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
		$db->insertCampaign($name, $category_id, $max_per_day);
		header("Location: campaigns-advertiser.php");
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

function ctrlAddBanner()
{
	global $db;

	checkHasAccess();

	$errors = array();

	$campaign_id = intval($_GET["id"]);
	$banner_format_id = isset($_POST["banner_format_id"]) ? intval($_POST["banner_format_id"]) : 0;
	$url = isset($_POST["url"]) ? $_POST["url"] : "";

	if ($banner_format_id > 0)
	{
		if ( ! isset($_FILES["banner_file"]) || intval($_FILES["banner_file"]["size"]) >= 500000 || intval($_FILES["banner_file"]["size"]) <= 10)
		{
			$errors["Bannerimage"] = "A valid banner image must be selected.";
		}
	
		if ($url == "" || $url == "http://")
		{
			$errors["URL"] = "An URL must be specified.";
		}
	}

	if (count($errors) == 0 && $banner_format_id > 0)
	{
		$folder = FOLDER_BANNERS . date("Y-m-d") . "/";
		$res = mkdir($folder, 0777);

		$db->insertBanner($campaign_id, $banner_format_id, $url);

		$bannerId = $db->getLastId();
		$bannerFormat = $db->getBannerFormat($banner_format_id);

		$res = exec("convert " . $_FILES['banner_file']['tmp_name'] . " $folder$bannerId.jpg");

		$infosImage = getimagesize("$folder$bannerId.jpg");

		if ($res != "" || sizeof($infosImage) < 2 || !(intval($bannerFormat["width"]) == intval($infosImage[0]) && intval($bannerFormat["height"]) == intval($infosImage[1])))
		{
			$db->deleteBanner($bannerId);
			exec("rm -f $folder$bannerId.jpg");
			$errors["Banner image"] = "Invalid image format.";
		}
		else
		{
			header("Location: banners-advertiser.php?id=" . $campaign_id);
			exit;
		}
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

function sendRegistrationEmail($username, $firstname, $email, $pw)
{
	$to = "$email";
	$subject = "Your spiClick account";

	$headers = "From: spi.blog.com@gmail.com" . "\r\n" .
	     "Reply-To: spi.blog.com@gmail.com\r\n".
		"X-Mailer: PHP 4.x";

	$body = "Dear $firstname,\n\n".
		"Please find below your spiClick.com account information:\n\n".
		"Your Login: $username\n".
		"Your Password: $pw\n".
		"URL to login: http://admin.spiclick.com/\n\n".
		"We are looking forward to see you on spiClick.com! If you need any support, don't hesitate to contact us.\n\n".
		"Best Regards,\n".
		"spiClick.com support team\n".
		"http://www.spiclick.com/";
	if (mail($to, $subject, $body, $headers)) 
	{
		mail("spi.blog.com@gmail.com", $subject, $body); // copy
		echo("<p>Message successfully sent!</p>");
	} 
	else 
	{
		echo("<p>Message delivery failed...</p>");
	}
}

function ctrlAddUser()
{
	global $db;

	$errors = array();
	
	checkIsAdmin();


	$users = isset($_POST["users"]) ? $_POST["users"] : "";

	$infos = split(",", $users);

	// spiClick registration [username],[company],[firstname],[lastname],[email],[website]
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

	// spiClick registration [username],[company],[firstname],[lastname],[email],[website]

	$db->insertPublisherPayment($username, $amount, $transaction_id);
	$db->payPublisher($username, $amount);
	
	header("Location: index.php");

	return $errors;
}

function ctrlAddUserPublic()
{
	global $db;

	$errors = array();

	$username = isset($_POST["username"]) ? trim($_POST["username"]) : "";
	$company_name = isset($_POST["company_name"]) ? trim($_POST["company_name"]) : "";
	$firstname = isset($_POST["firstname"]) ? trim($_POST["firstname"]) : "";
	$lastname = isset($_POST["lastname"]) ? trim($_POST["lastname"]) : "";
	$email = isset($_POST["email"]) ? trim($_POST["email"]) : "";
	$website = isset($_POST["website"]) ? trim($_POST["website"]) : "";
	$password = isset($_POST["password"]) ? trim($_POST["password"]) : "";
	$password_confirm = isset($_POST["password_confirm"]) ? trim($_POST["password_confirm"]) : "";
	$sent = isset($_POST["sent"]) ? intval($_POST["sent"]) : 0;

	// spiClick registration [username],[company],[firstname],[lastname],[email],[website]
	if ($sent == 0)
		return $errors;

	if (strlen($username) < 6)
	{
		$errors["Username"] = "Must be at least 6 characters.";
	}

	if ($company_name == "")
	{
		$errors["Company name"] = "A valid company name must be specified.";
	}


	if ($firstname == "")
	{
		$errors["Firstname"] = "A valid firstname must be specified.";
	}


	if ($lastname == "")
	{
		$errors["Company name"] = "A valid lastname must be specified.";
	}

	if ($website == "")
	{
		$errors["Website"] = "A valid website must be specified.";
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
		$db->insertUser($username, $company_name, $firstname, $lastname, $email, $website, $pw);
	
		if ($db->getLastId() <= 0)
		{
			$errors["Error"] = "An error occured while inserting user.";
		}
		else
		{
			sendRegistrationEmail($username, $firstname, $email, $pw);
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
