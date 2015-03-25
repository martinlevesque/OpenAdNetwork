<table>
<tr><th></th><th></th></tr>
<tr><td>Name:</td>
<td>
<? if (isset($isEditWebsite) && $isEditWebsite == 1) { ?>
	<?= $rowWebsite["name"] ?>
<? } else { ?>
	<input type="text" name="name" size="30" value="<?= $rowWebsite["name"] ?>" /> <br />* The website's name must be unique with letters and numbers only. It is used to generate automatically a blog: &lt;name&gt;.infinetia.com.
<? } ?>
</td></tr>
<tr><td>Description:</td><td><input type="text" name="description" size="50" value="<?= $rowWebsite["description"] ?>" /></td></tr>
<tr><td>URL:</td><td><input type="text" name="url" size="50" value="<?= $rowWebsite["url"] ?>" /></td></tr>
<tr>
<td>Category:</td>
<td><? include("select-category.php"); ?></td>
</tr>

<tr><td></td><td><input type="submit" value="Submit" /></td></tr>
</table>
