<p>General information</p>

<table>
<tr><th></th><th></th></tr>
<tr><td>Username</td><td><b><?= $row["username"] ?></b></td></tr>
<tr><td>Email</td><td><b><?= $row["email"] ?></b></td></tr>
<tr><td>Firstname</td><td><input type="text" name="firstname" value="<?= $row["firstname"] ?>" /></td></tr>
<tr><td>Lastname</td><td><input type="text" name="lastname" value="<?= $row["lastname"] ?>" /></td></tr>
<tr><td>Company</td><td><input type="text" name="company" value="<?= $row["company"] ?>" /></td></tr>
</table>

<p>Address</p>

<table>
<tr><th></th><th></th></tr>
<tr><td>Country</td><td><? include("select-country.php") ?></td></tr>
<tr><td>Address</td><td><input type="text" name="address" value="<?= $row["address"] ?>" /></td></tr>
<tr><td>City</td><td><input type="text" name="city" value="<?= $row["city"] ?>" /></td></tr>
<tr><td>Zip code</td><td><input type="text" name="zipcode" value="<?= $row["zipcode"] ?>" /></td></tr>
<tr><td>Phone number</td><td><input type="text" name="phone_number" value="<?= $row["phone_number"] ?>" /></td></tr>
</table>

<p>Publisher information</p>

<table>
<tr><th></th><th></th></tr>
<tr><td>Paypal (email)</td><td><input type="text" name="publisher_paypal" value="<?= $row["publisher_paypal"] ?>" /></td></tr>
<tr><td>Minimum payout</td>
	<td>
		<select name="minimum_payout">
			<option value="5" <?= $row["minimum_payout"] == 5 ? "selected" : "" ?>>$5</option>
			<option value="10" <?= $row["minimum_payout"] == 10 ? "selected" : "" ?>>$10</option>
			<option value="20" <?= $row["minimum_payout"] == 20 ? "selected" : "" ?>>$20</option>
			<option value="50" <?= $row["minimum_payout"] == 50 ? "selected" : "" ?>>$50</option>
			<option value="100" <?= $row["minimum_payout"] == 100 ? "selected" : "" ?>>$100</option>
			<option value="500" <?= $row["minimum_payout"] == 500 ? "selected" : "" ?>>$500</option>
			<option value="1000" <?= $row["minimum_payout"] == 1000 ? "selected" : "" ?>>$1000</option>
			<option value="5000" <?= $row["minimum_payout"] == 5000 ? "selected" : "" ?>>$5000</option>
			<option value="10000" <?= $row["minimum_payout"] == 10000 ? "selected" : "" ?>>$10000</option>
		</select>
	</td>
</tr>
</table>

<input type="submit" value="Save" />
