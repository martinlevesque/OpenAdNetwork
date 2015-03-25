<?

$pendingBanners = $db->pendingBanners();
$pendingCampaigns = $db->pendingCampaigns();

?>

<ul id="menu-top" class="menu">

<? if ($isLogged) { ?>

<? if ($username == "admin" || $username == "spi.blog.com@gmail.com") { ?>
	<li id="" class="menu-item menu-item-type-custom menu-item-object-custom"><a href="dashboard-admin.php">Admin</a>
	</li>
<? } ?>

          <li id="" class="menu-item menu-item-type-custom menu-item-object-custom"><a href="dashboard-publisher.php">Publish (Earn points)</a>

		</li>

		<li id="" class="menu-item menu-item-type-custom menu-item-object-custom"><a href="dashboard-advertiser.php">Advertise (Spend points)</a></li>
		<li id="" class="menu-item menu-item-type-custom menu-item-object-custom"><a href="add-microblog-publisher.php">Blog (SEO)</a></li>

		<li id="" class="menu-item menu-item-type-custom menu-item-object-custom"><a href="account-configurations.php">Account configurations</a></li>
		<li id="" class="menu-item menu-item-type-custom menu-item-object-custom"><a href="support.php">Technical support</a></li>
		<li id="" class="menu-item menu-item-type-custom menu-item-object-custom"><a href="logout.php">Logout</a></li>
<? } ?>

        </ul>

    </div><!-- end of #header -->
        
	    <div id="wrapper" class="clearfix">
    
        <div id="content-full" class="grid col-940">
        

		        
		<div class="breadcrumb-list">

			<? if (in_array($_SERVER['PHP_SELF'], array("/dashboard-publisher.php", "/stats-publisher.php", "/websites-publisher.php", "", "/zones-publisher.php", "/payments-publisher.php")) || strstr($_SERVER['PHP_SELF'], "-publisher.php") !== FALSE) { ?>
				<a href="dashboard-publisher.php">Dashboard</a> |
				<a href="stats-publisher.php">Stats</a> |
				<a href="websites-publisher.php">Websites</a> |
				<? if ($db->hasPublisherWebsites($username)) { ?>
					<a href="zones-publisher.php">Zones</a> |
				<? } ?>
				<a href="add-microblog-publisher.php">Blog (SEO)</a>
			<? } ?>

			<? if (in_array($_SERVER['PHP_SELF'], array("/admin-send-newsletter.php", "/dashboard-admin.php", "/add-user.php", "/stats-admin-publishers.php", "/admin-publisher-payments-due.php", "/make-payment.php", "/admin-pending-banners.php", "/admin-pending-campaigns.php"))) { ?>
				<a href="admin-send-newsletter.php">Send newsletter</a> |
				<a href="add-user.php">Add user</a> |
				<a href="stats-admin-publishers.php">Stats publishers</a> |
				<a href="admin-publisher-payments-due.php">Payments due</a> |
				<a href="make-payment.php">Make a payment (Publisher)</a> |
				<a href="admin-pending-banners.php">Pending banners (<?= sizeof($pendingBanners) ?>)</a> |
				<a href="admin-pending-campaigns.php">Pending campaigns (<?= sizeof($pendingCampaigns) ?>)</a>
			<? } ?>

			<? if (in_array($_SERVER['PHP_SELF'], array("/dashboard-advertiser.php", "/stats-advertiser.php", "/campaigns-advertiser.php", "/payments-advertiser.php")) || strstr($_SERVER['PHP_SELF'], "-advertiser.php") !== FALSE) { ?>
				<a href="dashboard-advertiser.php">Dashboard</a> |
				<a href="recommend-advertiser.php">Get 5000 free iPoints</a> |
				<a href="stats-advertiser.php">Stats</a> |
				<a href="campaigns-advertiser.php">Campaigns</a>
			<? } ?>

		</div>

            <div id="post-11" class="post-11 page type-page status-publish hentry">
                <h1>
			<? if ($account != "") { ?>
				<?= $account ?> - 
			<? } ?>

			<?= $title ?>
		</h1>

<div class="post-entry">
