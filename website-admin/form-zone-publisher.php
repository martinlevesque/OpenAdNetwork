<table>
<tr><th></th><th></th></tr>
<tr><td>Website:</td><td><? include("select-website.php"); ?></td></tr>
<tr><td>Name:</td><td><input type="text" name="name" size="50" value="<?= $rowWebsite["name"] ?>" /></td></tr>
<tr><td>Banner format:</td><td><? include("select-banner-format.php"); ?></td></tr>
<tr>
<td>Category:</td>
<td><? include("select-category.php"); ?></td>
</tr>

<tr><td></td><td><input type="submit" value="Submit" /></td></tr>
</table>
