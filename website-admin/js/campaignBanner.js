
function refreshCampaignTextBanner(divId, title, url, url_label, line1, line2)
{
	var content = "<span style='line-height: 18px;'><font size='4'><a href='" + url + "'>" + title + "</a></font><br />\n<a href='" + url + "'><font size='2'>" + url_label + "</font></a><br />\n<font size='3'>" + line1 + "\n<br />" + line2 + "\n</font></span>";

	var div = document.getElementById(divId);
	div.innerHTML = content;
	// width: 250px; height: 80px; border: 1px solid; padding: 4px 4px 4px 4px; margin: auto;
	div.style.width = '230px';
	div.style.height = '80px';
	div.style.border = '0px';
	div.style.padding = '0px 0px 0px 0px';
	div.style.margin = 'auto';
}
