<?

$account = "Publisher";
$title = "Blog post";

require_once("util.php");
require_once("controller.php");
require_once("db.php");


$db = new database();

$websites = $db->getWebsitesLight();

$errors = ctrlAddMicroblog();

$rowWebsite = array("category" => "1", "name" => "", "description" => "", "url" => "http://", "website_id" => ((isset($_POST["website_id"])) ? $_POST["website_id"] : ""));

$page = 1;

if (isset($_GET["page"]))
{
	$page = intval($_GET["page"]);
}

$posts = $db->microblogs($page);
$nbPosts = $db->nbMicroblogs();

include("head.php");
include("menu.php");

printErrors($errors);

if (isset($_POST["website_id"]) && count($errors) == 0)
{
	printSuccess();
	unset($_POST);
}

?>

<p>Post on your blogs to promote your websites and automatically earn iPoint! infiNetia Ads will be automatically shown on your blogs for maximum traffic generation.</p>

<? if (sizeof($websites) > 0) { ?>

	<script type="text/javascript" src="http://js.nicedit.com/nicEdit-latest.js"></script> <script type="text/javascript">
	//<![CDATA[
		bkLib.onDomLoaded(function() { new nicEditor({fullPanel : true}).panelInstance('content')  });
	  //]]>
	  </script>

	<div align="center">

	<form method="POST" action="add-microblog-publisher.php">

	<table>
	<tr><th></th><th></th></tr>
	<tr>
	<td>Website:</td>
	<td><? include("select-website.php"); ?></td>
	</tr>
	<tr><td>Title:</td><td><input type="text" name="title" size="30" value="<?= (isset($_POST["title"])) ? $_POST["title"] : "" ?>" /></td></tr>
	<tr><td>Post content:</td><td>
	<textarea name="content" id="content" style="width: 100%; height: 300px;">
	<?= isset($_POST["content"]) ? $_POST["content"] : "" ?>
	</textarea>
	</td></tr>

	<tr><td></td><td><input type="submit" value="Submit" /></td></tr>
	</table>

	</form>

	</div>

	<h1>Posts</h1>

	<? for ($i = 0; $i < sizeof($posts); ++$i) { ?>
	<h3><?= $posts[$i]["title"] ?></h3>
	<p style="margin-top: -10px;">
	<a href="delete-microblog-publisher.php?id=<?= $posts[$i]["id"] ?>" onclick="return confirm('Are you sure?')">[X] Delete</a><br /><br />
	Copy-paste the following link on the Internet to promote your post:<br />
	<textarea rows="1" cols="70" onclick="this.focus(); this.select();">
	http://<?= $posts[$i]["name"] ?>.infinetia.com/p.php?post=<?= slug($posts[$i]["title"]) ?>&amp;id=<?= $posts[$i]["id"] ?>
	</textarea>
	</p>
	<div>
	<?= $posts[$i]["content"] ?><br /><br />
	Added on <?= $posts[$i]["created_at"] ?>.
	<hr />
	</div>

	<? } ?>

	<div align="center">

	<? if ($page > 1) { ?>
		<a href="?page=<?= $page - 1 ?>">Previous postings</a>
	<? } ?>

	<? if ($page * 10 < $nbPosts) { ?>
		<a href="?page=<?= $page + 1 ?>">More postings >></a>
	<? } ?>
	</div>
<? } else { ?>

<p>No website has been added yet. <a href="add-website-publisher.php">Add a website &gt;&gt;</a>

<? } ?>

<?

include("foot.php");

$db->close();

?>
