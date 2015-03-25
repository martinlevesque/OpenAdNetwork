<table>
<tr><th></th><th></th></tr>
<tr><td>Name:</td><td><input type="text" name="name" size="30" value="<?= $row["name"] ?>" /></td></tr>
<tr>

<td>Limit per day (iPoints):</td>
<td>
<input type="text" name="max_per_day" size="30" value="<?= $row["max_per_day"] ?>" /></td></tr>
</td>

</tr>
<tr>
<td>Category:</td>
<td><? include("select-category.php"); ?></td>
</tr>

<tr><td></td><td><input type="submit" value="Submit" /></td></tr>
</table>
