<?php
/******************************************************************************/
/*                                                                            */
/*                       __        ____                                       */
/*                 ___  / /  ___  / __/__  __ _____________ ___               */
/*                / _ \/ _ \/ _ \_\ \/ _ \/ // / __/ __/ -_|_-<               */
/*               / .__/_//_/ .__/___/\___/\_,_/_/  \__/\__/___/               */
/*              /_/       /_/                                                 */
/*                                                                            */
/*                                                                            */
/******************************************************************************/
/*                                                                            */
/* Titre          : Classe DATABASE                                           */
/*                                                                            */
/* URL            : http://www.phpsources.org/scripts142-PHP.htm              */
/* Auteur         : Eric Potvin                                               */
/* Date édition   : 27 Sept 2005                                              */
/* Website auteur : http://www.phpsources.org                                 */
/*                                                                            */
/******************************************************************************/

//version 0.1

class database {

  //Variable interne de la classe
  var $errorNum  = 0;
  var $errorMsg  = null;
  var $resource  = null;
  var $cursor    = null;
  var $number    = 0;

  //constructeur de la classe.
    function database(
                  $host='localhost',
                  $user = 'root',
                  $pass = 'password',
                  $db = 'spiclickadmin')
   {
    //pour valider que l'usager n'entre pas la base
    //de données systeme de MYSQL
    //afin de la pirater.
    if(strtolower($db) == 'mysql') {
      $db = '';
    }
    
    if(!($this->resource = mysql_connect($host, $user, $pass))) {
      //en cas d'échec du serveur
      $this->errorNum = mysql_errno();
      $this->errorMsg = mysql_error();
    }
    if (!mysql_select_db($db)) {
      //en cas d'échec de la bd
      $this->errorNum = mysql_errno();
      $this->errorMsg = mysql_error();
    }
  }

  //retoune le ID de l'erreur
  function getErrorNum() {
    return $this->errorNum;
  }
  //retoune le message de l'erreur
  function getErrorMsg() {
    return $this->errorMsg;
  }
  //s'assure que les champs entrés dans la base
  //de données sont valide en ajoutant au
  //besoins des ' - semblable à la fonction "addslashes"
  function getEscaped($text) {
    return mysql_escape_string($text);
  }



  //envoi une requete à la BD et retounr les résultats sous forme de tableau.
  function query($sql = '') {
    if(empty($sql)) {
      return array();
    }
    $this->errorNum = 0;
    $this->errorMsg = '';
    $array = array();

    //assigne le résultat de la requête
    $this->cursor = mysql_query($sql, $this->resource);

    if (!$this->cursor || is_bool($this->cursor)) {
      $this->errorNum = mysql_errno($this->resource);
      $this->errorMsg = mysql_error($this->resource);
      return array();
    }
    
    $this->number = mysql_num_rows($this->cursor);
    //affecteur le tableau avec les valeurs de retours.
    while($row = mysql_fetch_assoc($this->cursor)) {
      $array[] = $row;
    }
    mysql_free_result($this->cursor);
    return $array;
  }

  //ferme la connection 
  function close() {
    return mysql_close($this->resource);
  }
  //retourne le nombre de ligne(s)
  function getNumRows() {
    return $this->number;
  }
  //retourne le dernier ID de la dernière requête "insert" ajouté
  function getLastId()
  {
    return mysql_insert_id();
  }
  //retourne la version de mysql
  function getVersion()
  {
    return mysql_get_server_info();
  }

	function websitesWithoutMicroblogZones()
	{
		$rows = $this->query("select websites.id, websites.category_id FROM websites WHERE NOT EXISTS(SELECT * FROM website_zones z WHERE z.website_id = websites.id AND z.microblog);");

		return $rows;
	}

	function authValid($username, $password)
	{
		$rows = $this->query("SELECT * FROM users WHERE (username = '" . database::getEscaped($username) . "' OR email = '" . database::getEscaped($username) . "')  AND password = md5('" .
			database::getEscaped($password) . "') AND suspended = 0;");

		return count($rows) == 1;
	}

	function usernameExists($username)
	{
		$rows = $this->query("SELECT * FROM users WHERE username = '" . database::getEscaped($username) . "' OR email = '" . database::getEscaped($username) . "';;");

		return count($rows) == 1;
	}

	function emailExists($email)
	{
		$rows = $this->query("SELECT * FROM users WHERE email = '" . database::getEscaped($email) . "';");

		return count($rows) == 1;
	}

	function hasPublisherWebsites($username)
	{
		
		$rows = $this->query("select * from websites, users WHERE users.id = websites.user_id and websites.active = 1 and users.email = '" . database::getEscaped($username) . "' limit 1;");

		return count($rows) == 1;
	}

	function todaysEarnings()
	{
		global $username;

		$rows = $this->query("SELECT IFNULL(SUM(nb_points), 0) as earnings FROM stats, website_zones z, websites w, users WHERE ".
			"stats.website_zone_id = z.id AND z.website_id = w.id AND users.id = w.user_id AND users.email = '$username' AND stats.created_on = CURDATE()");

		return $rows[0]["earnings"];
	}

	function todaysClicks()
	{
		global $username;

		$rows = $this->query("SELECT IFNULL(SUM(nb_clicks), 0) as clicks FROM stats, website_zones z, websites w, users WHERE ".
			"stats.website_zone_id = z.id AND z.website_id = w.id AND users.id = w.user_id AND users.email = '$username' AND stats.created_on = CURDATE()");

		return $rows[0]["clicks"];
	}

	function last30DaysEarnings()
	{
		global $username;

		$sql = "SELECT IFNULL(SUM(nb_points), 0) as earnings FROM stats, website_zones z, websites w, users WHERE ".
			"stats.website_zone_id = z.id AND z.website_id = w.id AND users.id = w.user_id AND users.email = '$username' AND stats.created_on >= DATE_SUB(curdate(), INTERVAL 30 DAY)";

		$rows = $this->query($sql);

		return $rows[0]["earnings"];
	}

	function last30DaysClicks()
	{
		global $username;

		$rows = $this->query("SELECT IFNULL(SUM(nb_clicks), 0) as clicks FROM stats, website_zones z, websites w, users WHERE ".
			"stats.website_zone_id = z.id AND z.website_id = w.id AND users.id = w.user_id AND users.email = '$username' AND stats.created_on >= DATE_SUB(curdate(), INTERVAL 30 DAY)");

		return $rows[0]["clicks"];
	}

	function websiteExists($name)
	{
		$n = database::getEscaped($name);

		$rows = $this->query("SELECT * FROM websites WHERE name = '$n'");

		return sizeof($rows) >= 1;
	}

	function getWebsites()
	{
		global $username;

		$rows = $this->query("SELECT websites.id, websites.name, websites.url, (SELECT COUNT(*) FROM website_zones z WHERE z.website_id = websites.id AND z.active = 1) as nb_zones_active, categories.name as category_name FROM websites, users, categories WHERE users.id = websites.user_id AND users.email = '$username' AND websites.category_id = categories.id AND websites.active = 1");

		return $rows;
	}

	function getCategories()
	{
		$rows = $this->query("SELECT * FROM categories WHERE parent_category_id IS NOT NULL");

		return $rows;
	}

	function getWebsitesLight()
	{
		global $username;

		$rows = $this->query("SELECT websites.id, websites.name FROM websites, users WHERE websites.user_id = users.id AND " .
			"users.email = '$username' AND websites.active = 1");

		return $rows;
	}

	function getBannerFormats()
	{
		$rows = $this->query("SELECT * FROM banner_formats");

		return $rows;
	}

	function userId()
	{
		global $username;

		$rows = $this->query("SELECT id FROM users WHERE email = '$username'");

		return $rows[0]["id"];
	}

	function insertWebsite($name, $description, $url, $category_id)
	{
		$userId = $this->userId();

		$this->query("INSERT INTO websites(user_id, category_id, name, description, url, active) VALUES(" .
			"$userId, $category_id, '" . database::getEscaped($name) . "', '" . database::getEscaped($description) . "', '" . database::getEscaped($url) . "', 1)");

		return mysql_insert_id();
	}

	function insertMicroblogZone($website_id, $name, $banner_format_id, $category_id)
	{
		$sql = "INSERT INTO website_zones(website_id, banner_format_id, category_id, name, active, microblog) VALUES(" .
			"$website_id, $banner_format_id, $category_id, '" . database::getEscaped($name) . "', 1, 1)";
	
		$this->query($sql);
	}

	function insertZone($website_id, $name, $banner_format_id, $category_id)
	{
		$sql = "INSERT INTO website_zones(website_id, banner_format_id, category_id, name, active) VALUES(" .
			"$website_id, $banner_format_id, $category_id, '" . database::getEscaped($name) . "', 1)";
	
		$this->query($sql);
	}

	function updateZone($id, $website_id, $name, $banner_format_id, $category_id)
	{
		$sql = "UPDATE website_zones SET " .
			"website_id = $website_id, banner_format_id = $banner_format_id, category_id = $category_id, name = '" . database::getEscaped($name) . "' WHERE id = $id";
	
		$this->query($sql);
	}

	function updateWebsite($id, $name, $description, $url, $category_id)
	{
		$this->query("UPDATE websites SET category_id = $category_id, name = '" . database::getEscaped($name) . "', description = '" . database::getEscaped($description) . "', url = '" . database::getEscaped($url) . "' WHERE id = $id");
	}

	function updateUser($firstname, $lastname, $company, $address, $city, $zipcode, $phone_number,
			$publisher_paypal, $country_id, $minimum_payout)
	{
		global $username;
		$sql = "UPDATE users SET firstname = '" . database::getEscaped($firstname) . "', lastname = '" . database::getEscaped($lastname) . "', company = '" . database::getEscaped($company) . "', address = '" . database::getEscaped($address) . "', city = '" . database::getEscaped($city) . "', " .
			"zipcode = '" . database::getEscaped($zipcode) . "', phone_number = '" . database::getEscaped($phone_number) . "', publisher_paypal = '" . database::getEscaped($publisher_paypal) . "', country_id = $country_id, minimum_payout = $minimum_payout WHERE users.email = '$username'";

		$this->query($sql);
	}

	function unsubscribeNewsletter($email)
	{
		global $username;
		$e = database::getEscaped($email);
		$sql = "UPDATE users SET newsletter=0 WHERE users.email = '$e'";

		$this->query($sql);

		return mysql_affected_rows();
	}

	function getUsersForNewsletter()
	{
		return $this->query("SELECT * from users WHERE newsletter = 1 ORDER BY id ASC");
	}

	function getCountries()
	{
		return $this->query("SELECT * from country_t;");
	}

	function deleteWebsite($id)
	{
		$this->query("UPDATE websites SET active = 0 WHERE id = $id");
	}

	function deleteZone($id)
	{
		$this->query("UPDATE website_zones SET active = 0 WHERE id = $id");
	}

	function getWebsite($id)
	{
		$rows = $this->query("SELECT * FROM websites WHERE id = $id");

		return $rows[0];
	}

	function getWebsiteIdOfMicroblog($id)
	{
		$rows = $this->query("SELECT microblog.website_id FROM microblog WHERE id = $id");

		return $rows[0]["website_id"];
	}

	function getUser()
	{
		global $username;

		$rows = $this->query("SELECT * FROM users WHERE (username = '$username' OR email = '$username')");

		return $rows[0];
	}

	function getUserU($uname)
	{
		$rows = $this->query("SELECT * FROM users WHERE username = '$uname' OR email = '$uname'");

		return $rows[0];
	}

	function getUserByEmail($email)
	{
		$e = database::getEscaped($email);

		$rows = $this->query("SELECT * FROM users WHERE email = '$e'");

		return $rows[0];
	}

	function getUserByUsername($u)
	{
		$rows = $this->query("SELECT * FROM users WHERE username = '$u' OR email = '$u'");

		return $rows[0];
	}

	function getZone($id)
	{
		$rows = $this->query("SELECT id, website_id, banner_format_id, category_id as category, name, active FROM website_zones WHERE id = $id");

		return $rows[0];
	}

	function getZones()
	{
		global $username;

		$sql = "SELECT websites.id as website_id, websites.name as website_name, z.id as zone_id, z.name as zone_name, banner_formats.name as banner_format_name, categories.name as category_name, z.microblog ".
			"FROM website_zones z, websites, users, banner_formats, categories ".
			"WHERE websites.id = z.website_id AND websites.user_id = users.id AND users.email = '$username' AND " .
			"	banner_formats.id = z.banner_format_id AND z.active = 1 AND categories.id = z.category_id " .
			"ORDER BY websites.id";

		$rows = $this->query($sql);

		return $rows;
	}

	function getPublisherPayments()
	{
		global $username;

		return $this->query("SELECT * FROM publisher_payments, users WHERE publisher_payments.user_id = users.id AND users.email = '$username' ORDER BY created_on DESC");
	}

	function getStatsPublisher($dateFrom, $dateTo, $site)
	{
		global $username;

		$websiteCond = ($site == "all") ? "" : " AND websites.id = $site ";

		$sql = "SELECT stats.created_on, IFNULL(SUM(stats.nb_clicks),0) as nb_clicks, IFNULL(SUM(stats.nb_views),0) as nb_views, IFNULL(SUM(stats.nb_points),0) as publisher_earnings ".
			"FROM stats, website_zones, websites, users " .
			"WHERE stats.website_zone_id = website_zones.id AND website_zones.website_id = websites.id AND " .
			"	websites.user_id = users.id AND users.email = '$username' AND ".
			"	stats.created_on BETWEEN '$dateFrom' AND '$dateTo' $websiteCond " .
			"GROUP BY stats.created_on ORDER BY stats.created_on ASC LIMIT 90";

		$rows = $this->query($sql);

		return $rows;
	}

	function advertiserBalance()
	{
		$user = $this->getUser();

		return $user["points"];
	}

	function advertiserSpentToday()
	{
		global $username;

		$rows = $this->query("SELECT IFNULL(SUM(stats.nb_points_spent), 0) as costs ".
			"FROM stats, campaign_banners, campaigns, users ".
			"WHERE stats.campaign_banner_id = campaign_banners.id AND campaign_banners.campaign_id = campaigns.id AND " .
			"campaigns.user_id = users.id AND users.email = '$username' AND stats.created_on = CURDATE()");

		return $rows[0]["costs"];
	}

	function advertiserTodaysClicks()
	{
		global $username;

		$sql = "SELECT IFNULL(SUM(stats.nb_clicks), 0) as nb_clicks ".
			"FROM stats, campaign_banners, campaigns, users ".
			"WHERE stats.campaign_banner_id = campaign_banners.id AND campaign_banners.campaign_id = campaigns.id AND " .
			"campaigns.user_id = users.id AND users.email = '$username' AND stats.created_on = CURDATE()";

		$rows = $this->query($sql);

		return $rows[0]["nb_clicks"];
	}



	function advertiserSpentLast30Days()
	{
		global $username;

		$rows = $this->query("SELECT IFNULL(SUM(stats.nb_points_spent), 0) as costs ".
			"FROM stats, campaign_banners, campaigns, users ".
			"WHERE stats.campaign_banner_id = campaign_banners.id AND campaign_banners.campaign_id = campaigns.id AND " .
			"campaigns.user_id = users.id AND users.email = '$username' AND stats.created_on >= DATE_SUB(curdate(), INTERVAL 30 DAY)");

		return $rows[0]["costs"];
	}

	function advertiserClicks30Days()
	{
		global $username;

		$sql = "SELECT IFNULL(SUM(stats.nb_clicks), 0) as nb_clicks ".
			"FROM stats, campaign_banners, campaigns, users ".
			"WHERE stats.campaign_banner_id = campaign_banners.id AND campaign_banners.campaign_id = campaigns.id AND " .
			"campaigns.user_id = users.id AND users.email = '$username' AND stats.created_on >= DATE_SUB(curdate(), INTERVAL 30 DAY)";

		$rows = $this->query($sql);

		return $rows[0]["nb_clicks"];
	}

	function getCampaigns()
	{
		global $username;

		$sql = "SELECT campaigns.status, campaigns.status_comment, campaigns.id, campaigns.name, campaigns.max_per_day, categories.name as category_name, campaigns.paused, ".
			"(SELECT IFNULL(SUM(nb_clicks), 0) FROM stats, campaign_banners b WHERE stats.campaign_banner_id = b.id AND b.campaign_id = campaigns.id AND stats.created_on = CURDATE()) as nb_clicks, ".
			"(SELECT IFNULL(SUM(nb_points_spent), 0) FROM stats, campaign_banners b WHERE stats.campaign_banner_id = b.id AND b.campaign_id = campaigns.id AND stats.created_on = CURDATE()) as costs " .
			"FROM campaigns, categories, users " .
			"WHERE campaigns.category_id = categories.id AND campaigns.user_id = users.id AND users.email = '$username' AND campaigns.active = 1 ".
			"ORDER BY campaigns.id DESC";

		$rows = $this->query($sql);

		return $rows;
	}

	function deleteCampaign($id)
	{
		$this->query("UPDATE campaigns SET active = 0 WHERE id = $id");
	}

	function deleteMicroblog($id)
	{
		$this->query("DELETE FROM microblog WHERE id = $id");
	}

	function activateCampaign($id, $paused)
	{
		$this->query("UPDATE campaigns SET paused = $paused WHERE id = $id");
	}

	function insertCampaign($name, $category_id, $max_per_day)
	{
		$user = $this->getUser();
		$user_id = $user["id"];
		$max_per_day = intval($max_per_day);

		if ($max_per_day <= 0)
			$max_per_day = 20;

		$rows = $this->query("INSERT INTO campaigns(name, user_id, category_id, pricing_type_id, max_per_day, active, created_at, updated_at, nb_views_unpaid, paused, status) VALUES('" . database::getEscaped($name) . "', $user_id, $category_id, (SELECT id FROM pricing_types WHERE name = 'CPC'), $max_per_day, 1, NOW(), NOW(), 0, 0, 'pending');");

		return mysql_insert_id();
	}

	function updateCampaign($id, $name, $category_id, $max_per_day)
	{
		$user = $this->getUser();
		$user_id = $user["id"];
		$max_per_day = intval($max_per_day);

		if ($max_per_day <= 0)
			$max_per_day = 20;

		$rows = $this->query("UPDATE campaigns SET status = 'pending', name = '" . database::getEscaped($name) . "', category_id = $category_id, max_per_day = $max_per_day ".
				"WHERE id = $id");
	}

	function getCampaign($id)
	{
		$rows = $this->query("SELECT * FROM campaigns WHERE id = $id");

		return $rows[0];

	}

	function getStatsAdvertiser($dateFrom, $dateTo, $campaign)
	{
		global $username;

		$campaignCond = ($campaign == "all") ? "" : " AND campaigns.id = $campaign ";

		$sql = "SELECT stats.created_on, campaigns.name as campaign_name, banner_formats.name as banner_name, campaign_banners.id as campaign_banner_id, IFNULL(SUM(stats.nb_clicks),0) as nb_clicks, IFNULL(SUM(stats.nb_views),0) as nb_views, IFNULL(SUM(stats.nb_points_spent),0) as costs ".
			"FROM stats, campaigns, campaign_banners, users, banner_formats ".
			"WHERE stats.campaign_banner_id = campaign_banners.id AND campaign_banners.campaign_id = campaigns.id AND ".
			"campaigns.user_id = users.id AND users.email = '$username' AND campaign_banners.banner_format_id = banner_formats.id AND ".
			"	stats.created_on BETWEEN '$dateFrom' AND '$dateTo' $campaignCond " .
			"GROUP BY stats.created_on, campaigns.id, campaign_banners.id ORDER BY stats.created_on DESC, campaigns.id, campaign_banners.id LIMIT 90";

		$rows = $this->query($sql);


		return $rows;
	}

	function getStatsAdminPublishers()
	{
		global $username;

		$sql = "SELECT users.email, users.created_at, IFNULL(SUM(stats.nb_clicks),0) as nb_clicks, IFNULL(SUM(stats.nb_views),0) as nb_views, IFNULL(SUM(stats.nb_points), 0) as nb_points ".
			"FROM stats, websites, website_zones, users ".
			"WHERE stats.created_on = CURDATE() AND stats.website_zone_id = website_zones.id AND website_zones.website_id = websites.id AND websites.user_id = users.id ".
			" " .
			"GROUP BY users.id ORDER BY IFNULL(SUM(stats.nb_views), 0) DESC LIMIT 90";

		$rows = $this->query($sql);


		return $rows;
	}

	function getCampaignsLight()
	{
		global $username;

		$rows = $this->query("SELECT campaigns.id, campaigns.name FROM campaigns, users WHERE campaigns.user_id = users.id AND " .
			"users.email = '$username' AND campaigns.active = 1");

		return $rows;
	}

	function insertMicroblog($websiteId, $title, $content)
	{
		$t = database::getEscaped($title);
		$c = database::getEscaped($content);
		$this->query("INSERT INTO microblog(website_id, title, content) VALUES($websiteId, '$title', '$content');");
	}

	function microblogs($page)
	{
		global $username;

		$from = ($page - 1) * 10;
		$rows = $this->query("SELECT microblog.id, websites.name, microblog.created_at, microblog.title, microblog.content FROM users, websites, microblog WHERE users.email = '$username' AND users.id = websites.user_id AND websites.id = microblog.website_id ORDER BY microblog.id desc LIMIT $from, 10");

		return $rows;
	}

	function nbMicroblogs()
	{
		global $username;

		$row = $this->query("SELECT COUNT(*) as nb FROM users, websites, microblog WHERE users.email = '$username' AND users.id = websites.user_id AND websites.id = microblog.website_id ORDER BY microblog.id desc");

		return intval($row[0]["nb"]);
	}

	function insertBanner($campaignId, $bannerFormatId, $url, $has_hover)
	{
		$this->query("INSERT INTO campaign_banners(campaign_id, banner_format_id, url, created_on, status, has_hover) VALUES($campaignId, $bannerFormatId, '" . database::getEscaped($url) . "', CURDATE(), 'pending', $has_hover);");
	}

	// $db->insertTextBanner($campaign_id, $_POST["title"], $_POST["url"], $_POST["url_label"], $_POST["line1"], $_POST["line2"])
	function insertTextBanner($campaignId, $title, $url, $url_label, $line1, $line2)
	{
		$url = database::getEscaped($url);
		$title = database::getEscaped($title);
		$url_label = database::getEscaped($url_label);
		$line1 = database::getEscaped($line1);
		$line2 = database::getEscaped($line2);

		$sql = "INSERT INTO campaign_banners(campaign_id, banner_format_id, url, created_on, status, has_hover, type, text_title, text_url_label, text_line1, text_line2) VALUES($campaignId, 2, '$url', CURDATE(), 'pending', 0, 'text', '$title', '$url_label', '$line1', '$line2');";

		$this->query($sql);
	}

	// registration [username],[company],[firstname],[lastname],[email],[website]
	function insertUser($username, $company, $firstname, $lastname, $email, $website, $pw)
	{
		$sql = "INSERT INTO users(username, password, company, firstname, lastname, email, website, created_at, updated_at, available_advertiser_money, unpaid_earnings, phone_number, country_id, address, city, zipcode, publisher_paypal, minimum_payout, points) ".
			"VALUES('$username', md5('$pw'), '$company', '$firstname', '$lastname', '$email', '$website', NOW(), NOW(), 0, 0, '', 1, '', '', '', '', 20, 0)";
		$this->query($sql);
	}

	function updatePw($username, $pw)
	{
		$sql = "UPDATE users SET password = md5('$pw') WHERE email = '$username'";
		$this->query($sql);
	}

	function deleteBanner($id)
	{
		$this->query("DELETE FROM campaign_banners WHERE id = $id;");
	}

	function getBannerFormatSize($width, $height)
	{
		$rows = $this->query("SELECT * FROM banner_formats WHERE width = $width AND height = $height;");

		return $rows[0];
	}

	function getBannerFormat($id)
	{
		$rows = $this->query("SELECT * FROM banner_formats WHERE id = $id;");

		return $rows[0];
	}

	function getBanners($campaignId)
	{
		return $this->query("SELECT * FROM campaign_banners WHERE campaign_id = $campaignId AND deleted = 0;");
	}

	function deleteBannerWeb($id)
	{
		$this->query("UPDATE campaign_banners SET deleted = 1 where id = $id;");
	}

	function getPaymentsDuePublisher()
	{
		global $username;

		$sql = "SELECT created_at, username, email, unpaid_earnings, publisher_paypal, minimum_payout ".
			"FROM users WHERE unpaid_earnings > 0 ORDER BY (minimum_payout - unpaid_earnings) ASC LIMIT 90";

		$rows = $this->query($sql);


		return $rows;
	}

	function getTodaysPosts()
	{
		$sql = "select websites.name, IFNULL(count(*), 0) as cnt from microblog, websites WHERE microblog.website_id = websites.id AND date(created_at) = CURDATE() GROUP BY websites.name";

		$rows = $this->query($sql);


		return $rows;
	}

	function getAdminRegistrations()
	{
		$sql = "select date(created_at) as date, COUNT(*) as cnt from users WHERE date(created_at) BETWEEN DATE_SUB(curdate(), INTERVAL 30 DAY) AND curdate() GROUP BY date(created_at)";

		$rows = $this->query($sql);


		return $rows;
	}

	function getAdminActivePublishers()
	{
		$sql = "select stats.created_on as date, count(distinct w.user_id) as cnt from users, stats, website_zones z, websites w WHERE stats.website_zone_id = z.id AND z.website_id = w.id AND stats.created_on BETWEEN DATE_SUB(curdate(), INTERVAL 30 DAY) AND CURDATE() AND users.id = w.user_id AND stats.nb_views > 0 GROUP BY stats.created_on ORDER BY stats.created_on ASC";

		$rows = $this->query($sql);


		return $rows;
	}

	function getAdminPublisherViews()
	{
		$sql = "select created_on as date, IFNULL(sum(nb_views), 0) as cnt from stats WHERE created_on BETWEEN DATE_SUB(curdate(), INTERVAL 30 DAY) AND CURDATE() GROUP BY created_on ORDER BY created_on ASC";

		$rows = $this->query($sql);


		return $rows;
	}

	function getAdminMicroblogStats()
	{
		$sql = "select date(created_at) as date, IFNULL(count(*), 0) as cnt from microblog WHERE date(created_at) BETWEEN DATE_SUB(curdate(), INTERVAL 30 DAY) AND CURDATE() GROUP BY date(created_at) ORDER BY date(created_at) ASC";

		$rows = $this->query($sql);


		return $rows;
	}

	function getAdminNbPublishers()
	{
		$sql = "SELECT COUNT(*) AS cnt FROM users WHERE EXISTS(SELECT * FROM websites w where w.user_id = users.id)";

		$rows = $this->query($sql);


		return $rows[0]["cnt"];
	}

	function insertPublisherPayment($username, $amount, $transaction_id)
	{
		$user = $this->getUserByUsername($username);
		$user_id = $user["id"];
		$this->query("INSERT INTO publisher_payments(user_id, amount, transaction_id, created_on) VALUES($user_id, $amount, '$transaction_id', CURDATE())");
	}

	function payPublisher($username, $amount)
	{
		$this->query("UPDATE users SET unpaid_earnings = unpaid_earnings - $amount WHERE email = '$username'");
	}

	function pendingBanners()
	{
		return $this->query("SELECT campaign_banners.url, campaign_banners.created_on, campaign_banners.id, categories.name as category_name, campaign_banners.has_hover, campaign_banners.type, campaign_banners.text_title, campaign_banners.text_url_label, campaign_banners.text_line1, campaign_banners.text_line2 FROM campaigns, categories, campaign_banners WHERE campaigns.id = campaign_banners.campaign_id AND campaigns.category_id = categories.id AND campaign_banners.status = 'pending' and campaign_banners.deleted = 0");
	}

	function pendingCampaigns()
	{
		return $this->query("SELECT campaigns.id, categories.name as category_name FROM campaigns, categories WHERE campaigns.category_id = categories.id AND status = 'pending' AND EXISTS(SELECT * FROM campaign_banners b WHERE b.campaign_id = campaigns.id)");
	}

	function getBannersOf($id)
	{
		return $this->query("SELECT * FROM campaign_banners WHERE campaign_id = $id");
	}

	function updatePendingBanner($id, $status, $status_comment)
	{
		$this->query("UPDATE campaign_banners SET status = '$status', status_comment = '$status_comment' WHERE id = $id");
	}

	function updatePendingCampaign($id, $status, $status_comment)
	{
		$this->query("UPDATE campaigns SET status = '$status', status_comment = '$status_comment' WHERE id = $id");
	}

	function getIpsPublisher($username)
	{
		$sql = "SELECT ip_views.ip, z.id as zone_id, websites.url FROM ip_views, website_zones z, websites, users WHERE ip_views.website_zone_id = z.id AND z.website_id = websites.id AND users.id = websites.user_id AND users.email = '$username' ORDER BY zone_id, ip_views.ip";

		return $this->query($sql);
	}

	function getUsersbak()
	{
		$sql = "SELECT * from usersbak order by id ASC";

		return $this->query($sql);
	}

	function getUsersCreatedYesterday()
	{
		$sql = "select * from users where date(created_at) = date(DATE_SUB(NOW(), INTERVAL 1 DAY))";

		return $this->query($sql);
	}

	function getActiveUsersLastWeek()
	{
		$sql = "select * from users where (exists(SELECT * FROM stats, website_zones, websites WHERE stats.website_zone_id = website_zones.id AND website_zones.website_id = websites.id AND websites.user_id = users.id AND stats.nb_points > 0 AND stats.created_on >= DATE_SUB(curdate(), INTERVAL 7 DAY)) OR EXISTS(SELECT * FROM stats, campaign_banners, campaigns WHERE stats.campaign_banner_id = campaign_banners.id AND campaign_banners.campaign_id = campaigns.id AND campaigns.user_id = users.id AND stats.nb_points_spent > 0 AND stats.created_on >= DATE_SUB(curdate(), INTERVAL 7 DAY)) OR users.points > 0) AND users.newsletter = 1 ORDER BY users.id ASC";

		return $this->query($sql);
	}

	function getLastWeekPublisher($user_id)
	{
		$sql = "select IFNULL(SUM(stats.nb_views), 0) AS nb_views, IFNULL(SUM(stats.nb_clicks), 0) as nb_clicks FROM stats, website_zones, websites WHERE stats.website_zone_id = website_zones.id AND website_zones.website_id = websites.id AND websites.user_id = $user_id AND stats.created_on >= DATE_SUB(curdate(), INTERVAL 7 DAY)";

		$rows = $this->query($sql);

		return $rows[0];
	}

	function getLastWeekAdvertiser($user_id)
	{
		$sql = "SELECT IFNULL(SUM(stats.nb_views), 0) AS nb_views, IFNULL(SUM(stats.nb_clicks), 0) as nb_clicks FROM stats, campaign_banners, campaigns WHERE stats.campaign_banner_id = campaign_banners.id AND campaign_banners.campaign_id = campaigns.id AND campaigns.user_id = $user_id AND stats.created_on >= DATE_SUB(curdate(), INTERVAL 7 DAY)";

		$rows = $this->query($sql);

		return $rows[0];
	}

	function getWebsiteWithoutSS()
	{
		$sql = "select id from websites WHERE screenshot_generated = 0 LIMIT 1";

		$rows = $this->query($sql);

		if (sizeof($rows) == 0)
		{
			return -1;
		}

		return $rows[0]["id"];
	}

	function markWebsiteSSComputed($id)
	{
		$sql = "update websites set screenshot_generated = 1 WHERE id = $id";

		$this->query($sql);
	}

	function hasCreatedMicroblogs()
	{
		global $username;

		$sql = "select COUNT(*) as cnt FROM users, websites, microblog WHERE users.email = '$username' AND users.id = websites.user_id AND websites.id = microblog.website_id";

		$rows = $this->query($sql);

		return intval($rows[0]["cnt"]) > 0;
	}

	function getIpsViewsUser($u)
	{
		$sql = "select distinct ip_views.ip from ip_views, website_zones, websites, users WHERE users.email = '$u' AND websites.user_id = users.id AND website_zones.website_id = websites.id AND website_zones.id = ip_views.website_zone_id";

		$rows = $this->query($sql);

		return $rows;
	}

	function meanImpression()
	{
		$sql = "select IFNULL(sum(duration_in_ms), 0) as durations, COUNT(*) as cnt from impression_durations";

		$rows = $this->query($sql);

		$cnt = intval($rows[0]["cnt"]);
		$dur = intval($rows[0]["durations"]);

		if ($cnt == 0)
			return 0;

		return $dur / $cnt;
	}

	function banPublisher($username)
	{
		// Ban publisher!
		$this->query("UPDATE users SET unpaid_earnings = 0, suspended = 1 WHERE email = '$username'");
		$this->query("UPDATE stats SET publisher_earnings = 0 WHERE website_zone_id IN (SELECT website_zones.id FROM website_zones, websites, users WHERE website_zones.website_id = websites.id AND websites.user_id = users.id AND users.email = '$username')");
	}
}

?>
