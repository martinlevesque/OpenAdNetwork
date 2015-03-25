<?

$account = "Advertiser";
$title = "Banners";

require_once("util.php");
require_once("controller.php");
require_once("db.php");

$db = new database();

$errors = ctrlAddBanner();

include("head.php");
include("menu.php");

printErrors($errors);

if ($errors == array() && isset($_GET["type"]))
{
	printSuccess();
	unset($_POST);
}

$id = intval($_GET["id"]);
$rowWebsite = array("banner_format_id" => "0");

$banners = $db->getBanners($id);

?>

<div align="center">
<br />

<div style="width: 500px;">
	<table>
		<tr>
			<td>Banner type: </td>
			<td>
				<select onChange="bannerTypeChanged();" id="selectBannerType">
				    <option value="text">Text</option>
				    <option value="image">Image</option>
				</select>
			</td>
		</tr>
	</table> 
</div>

<div id="formImageBanner">
	<form method="POST" action="banners-advertiser.php?id=<?= $id ?>&type=image" enctype="multipart/form-data">	
	<table>
		<tr>
			<td>Banner image (*.jpg, Max size: 200 Kb):</td><td><input type="file" name="banner_file" /></td>
		</tr>
		<tr>
			<td>Hover image (Optional, *.jpg, Max size: 200 Kb):</td><td><input type="file" name="hover_file" /></td>
		</tr>
		<tr>
			<td>URL: </td><td><input type="text" size="40" name="url" value="<?= (isset($_POST["url"])) ? $_POST["url"] : "http://" ?>" /></td>
		</tr>
		<tr>
			<td>Format: </td><td><? include("select-banner-format.php"); ?></td>
		</tr>
		<tr>
			<td></td><td><input type="submit" value="Add" /><input type="hidden" name="MAX_FILE_SIZE" value="500000" /></td>
		</tr>
	</table>
	</form> 
</div>

<div id="formTextBanner" style="width: 500px;">
	<form method="POST" action="banners-advertiser.php?id=<?= $id ?>&type=text" enctype="multipart/form-data">	
	<table>
		<tr>
			<td>Title: </td><td><input type="text" onKeyUp="refreshPreview();" id="text_title" size="30" name="title" value="<?= (isset($_POST["title"])) ? $_POST["title"] : "" ?>" /></td>
		</tr>
		<tr>
			<td>URL: </td><td><input type="text" onKeyUp="refreshPreview();" id="text_url" size="40" name="url" value="<?= (isset($_POST["url"])) ? $_POST["url"] : "http://" ?>" /></td>
		</tr>
		<tr>
			<td>URL label: </td><td><input type="text" onKeyUp="refreshPreview();" id="text_url_label" size="30" name="url_label" value="<?= (isset($_POST["url_label"])) ? $_POST["url_label"] : "www." ?>" /></td>
		</tr>
		<tr>
			<td>Line 1: </td><td><input type="text" onKeyUp="refreshPreview();" id="text_line1" size="30" name="line1" value="<?= (isset($_POST["line1"])) ? $_POST["line1"] : "" ?>" /></td>
		</tr>
		<tr>
			<td>Line 2: </td><td><input type="text" onKeyUp="refreshPreview();" id="text_line2" size="30" name="line2" value="<?= (isset($_POST["line2"])) ? $_POST["line2"] : "" ?>" /></td>
		</tr>
		<tr>
			<td>Preview</td>
			<td>
				<div id="preview_banner"></div>
			</td>
		</tr>
		<tr>
			<td></td><td><input type="submit" value="Add" /></td>
		</tr>
	</table>
	</form> 
</div>
<script type='text/javascript'>
function fixInput(inputId, fontSize)
{
	var ruler_ = document.createElement('div'); // create the DIV element

	ruler_.setAttribute('style',  // set the style attribute on the element
		'position:absolute;' +      // allows us to correctly measure the div
		'visibility:hidden;' +        // we don't want your text cluttering the page
		"font-size: " + fontSize + ";");

	document.body.appendChild(ruler_);  // add the ruler to the body of the document

	var content = document.getElementById(inputId).value;
	var width = 0;

	do
	{
		ruler_.innerHTML = content; // set the inner html of the element to be the text.

		width = ruler_.offsetWidth;  // the width of the div with the styling and the text

		if (width >= 225)
		{
			content = content.substring(0, content.length - 1)
		}
	}
	while (width >= 225);

	document.body.removeChild(ruler_); // remove the child from the body

	document.getElementById(inputId).value = content;
}

function refreshPreview()
{
	fixInput("text_title", "large");
	fixInput("text_url_label", "small");
	fixInput("text_line1", "medium");
	fixInput("text_line2", "medium");

	refreshCampaignTextBanner("preview_banner", document.getElementById("text_title").value, document.getElementById("text_url").value, document.getElementById("text_url_label").value, document.getElementById("text_line1").value, document.getElementById("text_line2").value);
}


refreshPreview();
</script>

<script type="text/javascript">
// Default div



document.getElementById("selectBannerType").value = "<?= ((isset($_GET["type"]) && $_GET["type"] == "text") || ! isset($_GET["type"])) ? "text" : "image" ?>";

function bannerTypeChanged()
{
	var value = document.getElementById("selectBannerType").value;

	if (value == "text")
	{
		document.getElementById("formImageBanner").style.display = 'none';
		document.getElementById("formTextBanner").style.display = 'block';
	}
	else
	{
		document.getElementById("formImageBanner").style.display = 'block';
		document.getElementById("formTextBanner").style.display = 'none';
	}
}

bannerTypeChanged();
</script>

<table>
<tr>
<th>ID</th><th>Banner</th><th>Status</th><th>Actions</th>
</tr>

<?

for ($i = 0; $i < sizeof($banners); ++$i)
{

	echo "<tr><td>" . $banners[$i]["id"] . "</td><td >";

	if ($banners[$i]["type"] == "image")
	{
		$image = "http://images.infinetia.com/" . $banners[$i]["created_on"] . "/" . $banners[$i]["id"] . ".jpg";
		$imageHover = intval($banners[$i]["has_hover"]) == 1 ? "http://images.infinetia.com/" . $banners[$i]["created_on"] . "/" . $banners[$i]["id"] . "-hover.jpg" : "";

		$hoverConfs = "onMouseOut='this.src=\"$image\"' onMouseOver='this.src=\"$imageHover\"'";

		if (intval($banners[$i]["has_hover"]) == 0)
		{
			$hoverConfs = "";
		}

		echo "<img $hoverConfs src='$image' />";
	}
	else	
	if ($banners[$i]["type"] == "text")
	{
		$title = $banners[$i]["text_title"];
		$url = $banners[$i]["url"];
		$url_label = $banners[$i]["text_url_label"];
		$line1 = $banners[$i]["text_line1"];
		$line2 = $banners[$i]["text_line2"];

		echo "<div id=\"banner_" . $banners[$i]["id"] . "\"></div>";
		echo "<script type='text/javascript'>refreshCampaignTextBanner(\"banner_" . $banners[$i]["id"] . "\", \"$title\", \"$url\", \"$url_label\", \"$line1\", \"$line2\");</script>";
	}

	echo "</td><td><b>" . $banners[$i]["status"] . "</b>" . (($banners[$i]["status_comment"] != "") ? ", comment: " . $banners[$i]["status_comment"] : "") . "</td><td><a onclick='return confirm(\"Are you sure ?\");' href='delete-banner.php?id=" . $banners[$i]["id"] . "&campaign_id=$id'>Delete</a></td></tr>";
}

?>

</table>

</div>

<?

include("foot.php");

$db->close();

?>
