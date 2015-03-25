<?

$campaigns = $db->getCampaignsLight();

?>

<select name="campaign_id">

<? 

if (isset($selectWithAll))
	echo "<option value='all' selected>All</option>";

for ($i = 0; $i < count($campaigns); ++$i) { 

?>
	<option value="<?= $campaigns[$i]["id"] ?>"><?= $campaigns[$i]["name"] ?></option>
<? } ?>
</select>
