<?

$categories = $db->getCategories();

?>

<select name="category">
<? for ($i = 0; $i < count($categories); ++$i) { ?>
	<option value="<?= $categories[$i]["id"] ?>" <?= $rowWebsite["category"] == $categories[$i]["id"] ? "selected" : "" ?>><?= $categories[$i]["name"] ?></option>
<? } ?>
</select>
