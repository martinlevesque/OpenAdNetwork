<?

$websites = $db->getWebsitesLight();

?>

<select name="website_id">

<? 

if (isset($selectWithAll))
	echo "<option value='all' selected>All</option>";

for ($i = 0; $i < count($websites); ++$i) { 

?>
	<option value="<?= $websites[$i]["id"] ?>" <?= $rowWebsite["website_id"] == $websites[$i]["id"] ? "selected" : "" ?>><?= $websites[$i]["name"] ?></option>
<? } ?>
</select>
