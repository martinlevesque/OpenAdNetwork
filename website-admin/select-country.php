<?

$countries = $db->getCountries();

?>

<select name="country_id">
<? for ($i = 0; $i < count($countries); ++$i) { ?>
	<option value="<?= $countries[$i]["country_id"] ?>" <?= $row["country_id"] == $countries[$i]["country_id"] ? "selected" : "" ?>><?= $countries[$i]["short_name"] ?></option>
<? } ?>
</select>
