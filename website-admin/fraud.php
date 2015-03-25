<?

function hasSuspiciousIps($ips)
{
	$total = 0;
	$sum = 0;

	$keysSelected = array_rand($ips, 100);

	for ($i = 0; $i < sizeof($keysSelected); ++$i)
	{
		$k = $keysSelected[$i];
		$e = trim($ips[$k]["ip"]);

		$elemsE = split("\.", $e);

		if (sizeof($elemsE) < 4)
		{
			continue;
		}

		$firstOrder = $elemsE[0] . ".";
		$secondOrder= $elemsE[0] . "." . $elemsE[1] . ".";
		$thirdOrder= $elemsE[0] . "." . $elemsE[1] . "." . $elemsE[2] . ".";

		for ($j = 0; $j < sizeof($ips); ++$j)
		{
			$total += 1.0;
			$res1 = strpos($ips[$j]["ip"], $firstOrder);
			$res2 = strpos($ips[$j]["ip"], $secondOrder);
			$res3 = strpos($ips[$j]["ip"], $thirdOrder);

			if ($res3 !== FALSE && $res3 == 0)
			{
				$sum += 8.0;
			}
			else
			if ($res2 !== FALSE && $res2 == 0)
			{
				$sum += 4.0;
			}
			else
			if ($res1 !== FALSE && $res1 == 0)
			{
				$sum += 2.0;
			}
			else
			{
				$sum += 1.0;
			}
		}
	}

	if ($total <= 0)
		return 1.0;

	return round(floatval($sum) / floatval($total), 10);
}

?>
