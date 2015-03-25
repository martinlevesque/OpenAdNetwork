<table>
<tr><th></th><th></th></tr>
<tr><td>Name:</td><td><input type="text" name="name" size="30" value="<?= $row["name"] ?>" /></td></tr>
<tr>

<td>Limit per day:</td>
<td>
<select name="max_per_day">
<option value="2.5" <?= ($row["max_per_day"] == 2.5 ? "selected" : "") ?>>$2.50</option>
<option value="5" <?= ($row["max_per_day"] == 5 ? "selected" : "") ?>>$5.00</option>
<option value="10" <?= ($row["max_per_day"] == 10 ? "selected" : "") ?>>$10.00</option>
<option value="20" <?= ($row["max_per_day"] == 20 ? "selected" : "") ?>>$20.00</option>
<option value="50" <?= ($row["max_per_day"] == 50 ? "selected" : "") ?>>$50.00</option>
<option value="100" <?= ($row["max_per_day"] == 100 ? "selected" : "") ?>>$100.00</option>
<option value="500" <?= ($row["max_per_day"] == 500 ? "selected" : "") ?>>$500.00</option>
<option value="1000" <?= ($row["max_per_day"] == 1000 ? "selected" : "") ?>>$1000.00</option>
<option value="10000" <?= ($row["max_per_day"] == 10000 ? "selected" : "") ?>>$10000.00</option>
</select>
</td>

</tr>
<tr>
<td>Category:</td>
<td><? include("select-category.php"); ?></td>
</tr>

<tr><td></td><td><input type="submit" value="Submit" /></td></tr>
</table>
