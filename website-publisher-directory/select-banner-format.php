<?

$bannerFormats = $db->getBannerFormats();

?>

<select name="banner_format_id">
<? for ($i = 0; $i < count($bannerFormats); ++$i) { ?>
	<option value="<?= $bannerFormats[$i]["id"] ?>" <?= $rowWebsite["banner_format_id"] == $bannerFormats[$i]["id"] ? "selected" : "" ?>><?= $bannerFormats[$i]["name"] ?></option>
<? } ?>
</select>
