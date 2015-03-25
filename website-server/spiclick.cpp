#include <fcntl.h>
#include <string.h>
#include <stdlib.h>
#include <errno.h>
#include <stdio.h>
#include <netinet/in.h>
#include <resolv.h>
#include <sys/socket.h>
#include <arpa/inet.h>
#include <unistd.h>
#include <pthread.h>
#include <string>
#include <time.h>
#include <sstream>
#include <vector>
#include <iostream>
#include <mysql.h>
#include <sys/types.h>
#include <sys/sem.h>
#include <sys/ipc.h>
#include <sys/time.h>

#include "Semaphore.h"
#include "Util.h"
#include <utility>

using namespace std;

#define SYNDICATION_DOMAIN "syndication.infinetia.com"
#define CLICK_DOMAIN "click.infinetia.com"
#define IMAGES_DOMAIN "images.infinetia.com"
#define MAX_NB_PROCESSES 10

#define DEBUG 0

void* SocketHandler(void*);

struct ParamsToSocket
{
	int* csock;
	string ip;
};

MYSQL* createSQLCon()
{
	MYSQL* c = mysql_init(NULL);

	if (!mysql_real_connect(c, "127.0.0.1", "root", "password", "spiclickadmin", 0, NULL, 0))
	{ 
		return NULL;
	} 

	return c;
}

struct BannerFormat
{
	int id;
	int width;
	int height;
	string name;
};

vector<BannerFormat> bannerFormats;

int getBannerFormatIdOf(MYSQL* con, int websiteZoneId);

int main(int argv, char** argc){

	const int portInit = 1100;
	int host_port= portInit;

	struct sockaddr_in my_addr;

	int hsock;
	int * p_int ;
	int err;

	socklen_t addr_size = 0;
	int* csock;
	sockaddr_in sadr;
	pthread_t thread_id=0;

	hsock = socket(AF_INET, SOCK_STREAM, 0);
	if(hsock == -1){
		printf("Error initializing socket %d\n", errno);
		goto FINISH;
	}
	
	p_int = (int*)malloc(sizeof(int));
	*p_int = 1;
		
	if( (setsockopt(hsock, SOL_SOCKET, SO_REUSEADDR, (char*)p_int, sizeof(int)) == -1 )||
		(setsockopt(hsock, SOL_SOCKET, SO_KEEPALIVE, (char*)p_int, sizeof(int)) == -1 ) ){
		printf("Error setting options %d\n", errno);
		free(p_int);
		goto FINISH;
	}
	free(p_int);

	my_addr.sin_family = AF_INET ;
	
	
	memset(&(my_addr.sin_zero), 0, 8);
	my_addr.sin_addr.s_addr = INADDR_ANY ;
	my_addr.sin_port = htons(host_port);
	
	while ( bind( hsock, (sockaddr*)&my_addr, sizeof(my_addr)) == -1 )
	{
		++host_port;
		my_addr.sin_port = htons(host_port);
	
		if (host_port > portInit + MAX_NB_PROCESSES)
		{
			fprintf(stderr,"Error binding to socket, make sure nothing else is listening on this port %d\n",errno);
			goto FINISH;
		}
	}

	if(listen( hsock, 10) == -1 ){
		fprintf(stderr, "Error listening %d\n",errno);
		goto FINISH;
	}

	//Now lets do the server stuff

	addr_size = sizeof(sockaddr_in);
	
	while(true){
		printf("waiting for a connection\n");
		csock = (int*)malloc(sizeof(int));
		if((*csock = accept( hsock, (sockaddr*)&sadr, &addr_size))!= -1){
			printf("---------------------\nReceived connection from %s\n",inet_ntoa(sadr.sin_addr));

			ParamsToSocket* p = new ParamsToSocket();
			p->csock = csock;

			// X-Forwarded-For TODO
			p->ip = string(inet_ntoa(sadr.sin_addr));

			pthread_create(&thread_id,0,&SocketHandler, (void*)p );
			pthread_detach(thread_id);
		}
		else{
			fprintf(stderr, "Error accepting %d\n", errno);
		}
	}
	
FINISH:
;
}

struct Campaign
{
	int id;
	int campaignBannerId;
	double availableAdvertiserMoney;
	double maxPerDay;
	string pricingType;
	double CPM;
	double CPC;
	string created_on;
	string has_hover;
	string type;
	string text_title;
	string text_url_label;
	string text_line1;
	string text_line2;
};

struct ForClickInfos
{
	double AdvertiserCPC;
	double PublisherCPC;
	string pricingType;
};

struct Param
{
	string p;
	string v;
};

struct SqlToDo
{
	string type;
	string ip;
	Campaign campaign;
	int campaignBannerId; 
	int webZoneId;
};

string valueOfParam(const vector<Param>& params, const string& variable)
{
	for (int i = 0; i < (int)params.size(); ++i)
	{
		if (params[i].p == variable)
			return params[i].v;
	}

	return "";
}

vector<Param> getParamsIn(const string& recv)
{
	vector<Param> result;

	int posBegin = recv.find("?");
	int posEnd = recv.find(" HTTP");

	if (posBegin == string::npos || posEnd == string::npos || posBegin >= posEnd)
	{
		return result;
	}

	string params = recv.substr(posBegin + 1, posEnd - (posBegin + 1));

	vector<string> r = Util::split(params, '&');

	for (int i = 0; i < (int) r.size(); ++i)
	{
		vector<string> r2 = Util::split(r[i], '=');

		if (r2.size() == 2)
		{
			Param p;
			p.p = r2[0];
			p.v = r2[1];
			result.push_back(p);
		}
	}

	return result;
}



MYSQL* acquireCon()
{
	MYSQL* con = createSQLCon();

	if ( ! con)
		return NULL;

	string stats = string(mysql_stat(con));

	if (stats.find("Uptime: ") == 0)
	{
		return con;
	}

	return NULL;
}

void LiberateCon(MYSQL* c)
{
	mysql_close(c);
}

string processSyndication(MYSQL* con, const vector<Campaign>& campaigns, int websiteZoneId)
{
	stringstream response;

	stringstream content;
	string type;

	type = campaigns[0].type;

	if (type == "image")
	{
		stringstream image;
		stringstream imageHover;
		string hoverConfs;
		Campaign c = campaigns[0];
		hoverConfs = "";
		
		image << "http://" << IMAGES_DOMAIN << "/" << c.created_on << "/" << c.campaignBannerId << ".jpg";

		if (c.has_hover == "1")
		{
			imageHover << "http://" << IMAGES_DOMAIN << "/" << c.created_on << "/" << c.campaignBannerId << "-hover.jpg";

			hoverConfs = "onMouseOver='this.src=\\\"" + imageHover.str() + "\\\"' onMouseOut='this.src=\\\"" + image.str() + "\\\"'";
		}

		content << "document.write(\"<a href='http://" << CLICK_DOMAIN << "/?cbi=" << c.campaignBannerId << "&wzi=" << websiteZoneId << "'>" 
			<< "<img " << hoverConfs << " src='" << image.str() << "' /></a>\");\n";
	}
	else
	if (type == "text")
	{
		int formatId = getBannerFormatIdOf(con, websiteZoneId);
	
		BannerFormat format;
		const int bannerWidth = 230;
		const int bannerHeight = 80;
		int posX = 0;
		int posY = 0;

		for (int i = 0; i < bannerFormats.size(); ++i)
		{
			if (bannerFormats[i].id == formatId)
			{
				format = bannerFormats[i];
				break;
			}
		}

		if (format.width > 0 && format.width <= 10000)
		{
			content << "document.write(\"<div style='text-align: left; position: relative; width: " << format.width << "px; height: " << format.height << "px;'>\");\n";
			int nbHorizontally = format.width / bannerWidth;
			int nbVertically = format.height / bannerHeight;

			if (nbVertically < 1)
				nbVertically = 1;

			int freeSpaceX = (format.width - (bannerWidth * nbHorizontally)) / (nbHorizontally + 1);
			int freeSpaceY = (format.height - (bannerHeight * nbVertically)) / (nbVertically + 1);
			int curC = 0;

			if (nbVertically == 1)
				freeSpaceY = 0;

			posX = freeSpaceX;
			posY = freeSpaceY;

			for (int v = 0; v < nbVertically; ++v)
			{
				for (int h = 0; h < nbHorizontally; ++h)
				{
					if (curC >= campaigns.size())
						break;

					stringstream url;
					url << "http://" << CLICK_DOMAIN << "/?cbi=" << campaigns[curC].campaignBannerId << "&wzi=" << websiteZoneId;
					int heightCurBox = (format.height >= 80) ? 80 : format.height;
					

					content << "document.write(\"<div style='position: absolute; top: " << posY << "px; left: " << posX << "px; width: 230px; height: " << heightCurBox << "px; border: 0px; padding: 0px 0px 0px 0px; margin: auto;'>";
					content << "<span style='line-height: 18px; width: 230px; height: 80px;'><font size='4'><a href='" << url.str() << "'>" << campaigns[curC].text_title << "</a></font><br />";
				

					// Exclude small mobile banner
					if (format.height> 50)
					{
						content << "<a href='" << url.str() << "'><font size='2'>" << campaigns[curC].text_url_label << "</font></a><br />";
					}

					content << "<font size='3'>" << campaigns[curC].text_line1 << "";
	
					if (format.height > 80)
						content << "<br />" << campaigns[curC].text_line2 << "";

					content << "</font></span>\"); document.write(\"</div>\");\n";

					++curC;
					posX += 230 + freeSpaceX;
				}

				// newline!
				posY += 80 + freeSpaceY;
				posX = freeSpaceX;
			}

			//content << "document.write(\"<div style='text-align: right; position: absolute; top: " << format.height - 6 << "px; left: 2px; width: " << format.width << "px; height: 10px;'><font size='1'>Ads by <a href='http://infinetia.com/'>infiNetia.com</a></font></div>\");\n";

			content << "document.write(\"</div>\");\n";
		}
	}

	// AJAX stuff:
	//content << "function 12load()\n";
	//content << "{\n";
/*
	content << "var xmlhttp;\n";
	content << "if (window.XMLHttpRequest)\n";
	content << "  {// code for IE7+, Firefox, Chrome, Opera, Safari\n";
	content << "  xmlhttp=new XMLHttpRequest();\n";
	content << "  }\n";
	content << "else\n";
	content << "  {// code for IE6, IE5\n";
	content << "  xmlhttp=new ActiveXObject(\"Microsoft.XMLHTTP\");\n";
	content << "  }\n";
	content << "xmlhttp.onreadystatechange=function()\n";
	content << "  {\n";
	content << "  if (xmlhttp.readyState==4 && xmlhttp.status==200)\n";
	content << "    {\n";
	content << "    //document.getElementById(\"myDiv\").innerHTML=xmlhttp.responseText;\n";
	content << "    }\n";
	content << "  }\n";
	content << "xmlhttp.open(\"GET\",\"/?wzi=" << websiteZoneId << "&check=1\",true);\n";
	content << "xmlhttp.send();\n";
	//content << "}\n";
	//content << "12load();\n";
	content << "document.write(\"<img src='http://syndication.infinetia.com/?wzi=" << websiteZoneId << "&check=1' width=1 height=1 />\");\n";
*/

	response << "HTTP/1.0 200 OK\r\n"
		"Content-Type: text/html\r\n"
		"Content-Length: " << content.str().size() << "\r\n"
		"\r\n" <<
		content.str();

	cout << "respond = " << response.str() << endl;

	return response.str();
}

string getEmptyImageResponse()
{
	return "HTTP/1.0 200 OK\r\n"
		"Content-Type: image/jpeg\r\n"
		"Content-Length: 0\r\n\r\n";
}

string getUrlOfCampaignBanner(MYSQL* con, int campaignBannerId)
{
	stringstream request;

	request << "SELECT url FROM campaign_banners WHERE id = " << campaignBannerId;
	
	if (mysql_query(con, request.str().c_str()) != 0)
	{
		return "";
	}

	MYSQL_ROW row;
	MYSQL_RES* res;
	string result = "";

	res = mysql_use_result(con);

	if ((row = mysql_fetch_row(res)) != NULL)
	{
		result = row[0];
	}

	mysql_free_result(res);

	return result;
}

string processClick(MYSQL* con, int campaignBannerId)
{
	string url = getUrlOfCampaignBanner(con, campaignBannerId);
	
	if (url == "")
		return "";

	stringstream response;

	response << "HTTP/1.0 302 Found\r\n"
		"Location: " << url << "\r\n\r\n";

	return response.str();
}

string extractIPIn(const string& recv)
{
	string key = "X-Forwarded-For: ";

	int pos = recv.find(key);

	if (pos == string::npos)
	{
		return "";
	}

	int posEnd = recv.find("\n", pos);

	return recv.substr(pos + key.size(), posEnd - (pos + key.size() + 1));
}


vector<Campaign> findImageCampaignToPrint(MYSQL* con, int websiteZoneId)
{
	vector<Campaign> result;

	stringstream request;

	request << "SELECT campaigns.id, campaign_banners.id, advertisers.available_advertiser_money, campaigns.max_per_day, pricing_types.name, campaign_banners.created_on, campaign_banners.has_hover, campaign_banners.type FROM websites, website_zones z, campaigns, campaign_banners, pricing_types, users advertisers, categories cCat, categories zCat ";
	request << "WHERE z.website_id = websites.id AND z.id = " << websiteZoneId << " AND campaign_banners.campaign_id = campaigns.id AND ";
	request << " z.banner_format_id = campaign_banners.banner_format_id AND  ";
	request << " cCat.id = campaigns.category_id AND zCat.id = z.category_id AND cCat.parent_category_id = zCat.parent_category_id AND pricing_types.id = campaigns.pricing_type_id AND ";
	request << " advertisers.suspended = 0 AND advertisers.id = campaigns.user_id AND campaigns.user_id <> websites.user_id AND ";
	request << " campaigns.active = 1 AND campaigns.paused = 0 AND campaigns.status = 'active' AND campaign_banners.deleted = 0 AND campaign_banners.status = 'active' AND campaign_banners.type = 'image' AND advertisers.points - 1 > 0 AND ";
	request << " IFNULL((SELECT SUM(nb_points_spent) FROM stats, campaign_banners cbstats WHERE cbstats.id = stats.campaign_banner_id AND campaigns.id = cbstats.campaign_id AND stats.created_on = CURDATE()), 0) < campaigns.max_per_day";
	request << " ORDER BY z.category_id = campaigns.category_id DESC, rand() ";
	request << " LIMIT 1";

	if (mysql_query(con, request.str().c_str()) != 0)
	{
		return result;
	}

	MYSQL_ROW row;
	MYSQL_RES* res;

	res = mysql_use_result(con);

	if ((row = mysql_fetch_row(res)) != NULL)
	{
		result.push_back(Campaign());
		result[0].id = atoi(row[0]);
		result[0].campaignBannerId = atoi(row[1]);
		result[0].availableAdvertiserMoney = atof(row[2]);
		result[0].maxPerDay = atof(row[3]);
		result[0].pricingType = string(row[4]);
		result[0].created_on = string(row[5]);
		result[0].has_hover = string(row[6]);
		result[0].type = string(row[7]);
	}

	mysql_free_result(res);

	return result;
}

int getBannerFormatIdOf(MYSQL* con, int websiteZoneId)
{
	stringstream request;

	request << "SELECT banner_format_id from website_zones WHERE id = " << websiteZoneId;

	if (mysql_query(con, request.str().c_str()) != 0)
	{
		return -1;
	}

	MYSQL_ROW row;
	MYSQL_RES* res;

	res = mysql_use_result(con);

	int result = 0;

	if ((row = mysql_fetch_row(res)) != NULL)
	{
		result = atoi(row[0]);
	}

	mysql_free_result(res);

	return result;

}

int requiredTextAds(int formatId)
{
	for (int i = 0; i < bannerFormats.size(); ++i)
	{
		if (bannerFormats[i].id == formatId)
		{
			if (bannerFormats[i].name.find("728x90") != string::npos)
			{
				return 3;
			}
			else
			if (bannerFormats[i].name.find("468x60") != string::npos)
			{
				return 2;
			}
			else
			if (bannerFormats[i].name.find("250x250") != string::npos)
			{
				return 3;
			}
			else
			if (bannerFormats[i].name.find("300x250") != string::npos)
			{
				return 3;
			}
			else
			if (bannerFormats[i].name.find("260x340") != string::npos)
			{
				return 4;
			}
			else
			if (bannerFormats[i].name.find("300x600") != string::npos)
			{
				return 7;
			}
		}
	}

	return 10;
}

vector<Campaign> findTextCampaignToPrint(MYSQL* con, int websiteZoneId)
{
	vector<Campaign> result;

	stringstream request;

	int formatId = getBannerFormatIdOf(con, websiteZoneId);
	int nbAdsRequired = requiredTextAds(formatId);
	
	request << "SELECT campaigns.id, campaign_banners.id, advertisers.available_advertiser_money, campaigns.max_per_day, pricing_types.name, campaign_banners.created_on, campaign_banners.has_hover, campaign_banners.type, campaign_banners.text_title, campaign_banners.text_url_label, campaign_banners.text_line1, campaign_banners.text_line2 FROM websites, website_zones z, campaigns, campaign_banners, pricing_types, users advertisers, categories zCat, categories cCat ";
	request << "WHERE websites.id = z.website_id AND z.id = " << websiteZoneId << " AND campaign_banners.campaign_id = campaigns.id AND ";
	request << " ";
	request << " zCat.id = z.category_id AND cCat.id = campaigns.category_id AND cCat.parent_category_id = zCat.parent_category_id AND pricing_types.id = campaigns.pricing_type_id AND ";
	request << " advertisers.suspended = 0 AND advertisers.id = campaigns.user_id AND campaigns.user_id <> websites.user_id AND ";
	request << " campaigns.active = 1 AND campaigns.paused = 0 AND campaigns.status = 'active' AND campaign_banners.deleted = 0 AND campaign_banners.status = 'active' AND campaign_banners.type = 'text' AND advertisers.points - 1 > 0 AND ";
	request << " IFNULL((SELECT SUM(nb_points_spent) FROM stats, campaign_banners cbstats WHERE cbstats.id = stats.campaign_banner_id AND campaigns.id = cbstats.campaign_id AND stats.created_on = CURDATE()), 0) < campaigns.max_per_day";
	request << " ORDER BY z.category_id = campaigns.category_id DESC, rand() ";
	request << " LIMIT " << nbAdsRequired;

	if (mysql_query(con, request.str().c_str()) != 0)
	{
		return result;
	}

	MYSQL_ROW row;
	MYSQL_RES* res;

	res = mysql_use_result(con);

	while ((row = mysql_fetch_row(res)) != NULL)
	{

		Campaign c;
		c.id = atoi(row[0]);
		c.campaignBannerId = atoi(row[1]);
		c.availableAdvertiserMoney = atof(row[2]);
		c.maxPerDay = atof(row[3]);
		c.pricingType = string(row[4]);
		c.created_on = string(row[5]);
		c.has_hover = string(row[6]);
		c.type = string(row[7]);
		c.text_title = string(row[8]);
		c.text_url_label = string(row[9]);
		c.text_line1 = string(row[10]);
		c.text_line2 = string(row[11]);

		result.push_back(c);
	}

	mysql_free_result(res);

	return result;
}


vector<BannerFormat> getBannerFormats(MYSQL* con)
{
	vector<BannerFormat> result;

	stringstream request;

	request << "SELECT id, width, height, name from banner_formats";

	if (mysql_query(con, request.str().c_str()) != 0)
	{
		return result;
	}

	MYSQL_ROW row;
	MYSQL_RES* res;

	res = mysql_use_result(con);

	while ((row = mysql_fetch_row(res)) != NULL)
	{
		BannerFormat f;
		f.id = atoi(row[0]);
		f.width = atoi(row[1]);
		f.height= atoi(row[2]);
		f.name = string(row[3]);

		result.push_back(f);
	}

	mysql_free_result(res);

	return result;
}

vector<Campaign> findCampaignToPrint(MYSQL* con, int websiteZoneId)
{
	vector<Campaign> c;
	static int cntSelect = 0;

	cntSelect = ! cntSelect;

	// TODO nb text ads per zone

	if (cntSelect == 0)
	{
		c = findImageCampaignToPrint(con, websiteZoneId);
	
		if (c.size() > 0)
			return c;

		return findTextCampaignToPrint(con, websiteZoneId);
	}
	else
	{
		c = findTextCampaignToPrint(con, websiteZoneId);

		if (c.size() > 0)
			return c;

		return findImageCampaignToPrint(con, websiteZoneId);
	}

	return c;
}

int nbUnpaidViewsFor(MYSQL* con, int campaignId)
{
	stringstream s;
	s << "SELECT nb_views_unpaid FROM campaigns WHERE id = " << campaignId << "";

	if (mysql_query(con, s.str().c_str()) == 0)
	{
		MYSQL_ROW row;
		MYSQL_RES* res;

		res = mysql_use_result(con);

		if (res && (row = mysql_fetch_row(res)) != NULL)
		{
			
			int nb = atoi(row[0]);
			mysql_free_result(res);
			return nb;
		}

		if (res)
			mysql_free_result(res);		
	}

	return -1;
}

void addView(MYSQL* con, const string& ip, const string& pricingType, double CPM, int campaignId, int campaignBannerId, int websiteZoneId)
{
	stringstream insertIpViews;

	insertIpViews << "INSERT INTO ip_views(campaign_banner_id, website_zone_id, created_on, ip) VALUES(" << campaignBannerId << ", " << 
		websiteZoneId << ", CURDATE(), '" << ip << "');";

	if (mysql_query(con, insertIpViews.str().c_str()) == 0)
	{
		// OK, we added a row!! Thus, we can add it also in stats.
		stringstream insertStats;
		insertStats << "INSERT INTO stats(campaign_banner_id, website_zone_id, created_on, nb_clicks, nb_views, advertiser_view_costs, advertiser_click_costs, publisher_earnings, nb_points, nb_points_spent) " <<
			" VALUES(" << campaignBannerId << ", " << websiteZoneId << ", CURDATE(), 0, 1, 0, 0, 0, 0.5, 0);";

		if (mysql_query(con, insertStats.str().c_str()) == 0)
		{
			
		}
		else
		{
			// It already exists, we need to update it !!!
			stringstream updateStats;
			updateStats << "UPDATE stats SET nb_views = nb_views + 1, nb_points = nb_points + 0.5 WHERE campaign_banner_id = " << campaignBannerId << 
				" AND website_zone_id = " << websiteZoneId << " AND created_on = CURDATE()";

			mysql_query(con, updateStats.str().c_str());
		}

		// Finally, add some points for the publisher
		stringstream updateEarnings;
		updateEarnings << "UPDATE users SET points = points + 0.5  WHERE users.id = (SELECT w.user_id FROM websites w, website_zones wz WHERE w.id = wz.website_id AND wz.id = " << websiteZoneId << ");  ";
		mysql_query(con, updateEarnings.str().c_str());
		fprintf(stderr, "%s\n", mysql_error(con));

		// Update available advertiser money
		stringstream updateAvailableAdMoney;
		updateAvailableAdMoney << "UPDATE users SET points = points - 1 WHERE users.id = (SELECT campaigns.user_id FROM campaigns WHERE campaigns.id = (SELECT campaign_banners.campaign_id FROM campaign_banners WHERE campaign_banners.id = " << campaignBannerId << "));  ";
		mysql_query(con, updateAvailableAdMoney.str().c_str());
		fprintf(stderr, "%s\n", mysql_error(con));
		stringstream updateAvailableAdMoney2;
		updateAvailableAdMoney2 << "UPDATE users SET points = 0 WHERE points < 0 and users.id = (SELECT campaigns.user_id FROM campaigns WHERE campaigns.id = (SELECT campaign_banners.campaign_id FROM campaign_banners WHERE campaign_banners.id = " << campaignBannerId << "));  ";
		mysql_query(con, updateAvailableAdMoney2.str().c_str());
		fprintf(stderr, "%s\n", mysql_error(con));


		// Update advertiser click costs in stats:
		stringstream updateAdvertiserCosts;
		
		updateAdvertiserCosts << "UPDATE stats SET nb_points_spent = nb_points_spent + " << 1
			<< " WHERE campaign_banner_id = " << campaignBannerId << " AND website_zone_id = " << websiteZoneId 
			<< " AND created_on = CURDATE();  ";
		mysql_query(con, updateAdvertiserCosts.str().c_str());
		fprintf(stderr, "%s\n", mysql_error(con));
	}
}

/*
struct ForClickInfos
{
	double AdvertiserCPC;
	double PublisherCPC;
	string pricingType;
};
*/

ForClickInfos getInfosForClick(MYSQL* con, int campaignBannerId, int websiteZoneId)
{
	ForClickInfos result;

	stringstream s;
	s << "SELECT categories.CPCAdvertiser, categories.CPCPublisher, pricing_types.name " <<
		"FROM campaign_banners, campaigns, categories, pricing_types " <<
		"WHERE campaign_banners.id = " << campaignBannerId << " AND campaigns.id = campaign_banners.campaign_id " <<
		"	AND campaigns.pricing_type_id = pricing_types.id AND campaigns.category_id = categories.id ";

	if (mysql_query(con, s.str().c_str()) == 0)
	{
		MYSQL_ROW row;
		MYSQL_RES* res;

		res = mysql_use_result(con);

		if (res && (row = mysql_fetch_row(res)) != NULL)
		{
			
			result.AdvertiserCPC = atof(row[0]);
			result.PublisherCPC = atof(row[1]);
			result.pricingType = string(row[2]);

			mysql_free_result(res);	
			return result;
		}

		if (res)
			mysql_free_result(res);		
	}

	return result;
}

bool isAccountSuspended(MYSQL*con, int websiteZoneId)
{
	stringstream s;
	s << "SELECT users.suspended FROM website_zones, websites, users WHERE website_zones.id = " << websiteZoneId << " AND website_zones.website_id = websites.id AND websites.user_id = users.id ";

	if (mysql_query(con, s.str().c_str()) == 0)
	{
		MYSQL_ROW row;
		MYSQL_RES* res;

		res = mysql_use_result(con);

		if (res && (row = mysql_fetch_row(res)) != NULL)
		{
			int suspended = atoi(row[0]);
			
			mysql_free_result(res);	
			return suspended == 1;
		}

		if (res)
			mysql_free_result(res);		
	}

	return false;
}

bool clickerHasViewedIn(MYSQL*con, const string& ip, int campaignBannerId, int websiteZoneId, const string& table)
{
	stringstream s;
	s << "SELECT COUNT(*) as cnt FROM " << table << " WHERE campaign_banner_id = " << campaignBannerId << " AND website_zone_id = " << websiteZoneId << " AND ip LIKE '%" << ip << "%'";

	if (mysql_query(con, s.str().c_str()) == 0)
	{
		MYSQL_ROW row;
		MYSQL_RES* res;

		res = mysql_use_result(con);

		if (res && (row = mysql_fetch_row(res)) != NULL)
		{
			int cnt = atoi(row[0]);
			
			mysql_free_result(res);	
			return cnt > 0;
		}

		if (res)
			mysql_free_result(res);		
	}

	return false;
}

bool clickerHasViewed(MYSQL*con, const string& ip, int campaignBannerId, int websiteZoneId)
{
	return clickerHasViewedIn(con, ip, campaignBannerId, websiteZoneId, "ip_views") ||
		clickerHasViewedIn(con, ip, campaignBannerId, websiteZoneId, "ip_views_old");
}

void addClick(MYSQL* con, const string& ip, int campaignBannerId, int websiteZoneId)
{
	// block frauders.. did the user VIEWED the banner before clicking on it ?
	if ( ! clickerHasViewed(con, ip, campaignBannerId, websiteZoneId))
	{
		return;
	}

	stringstream insertIpClicks;

	insertIpClicks << "INSERT INTO ip_clicks(campaign_banner_id, website_zone_id, created_on, ip) VALUES(" << campaignBannerId << ", " << 
		websiteZoneId << ", CURDATE(), '" << ip << "');";

	if (mysql_query(con, insertIpClicks.str().c_str()) == 0)
	{
		// OK, we added a row!! Thus, we can add it also in stats.
		stringstream insertStats;
		insertStats << "INSERT INTO stats(campaign_banner_id, website_zone_id, created_on, nb_clicks, nb_views, advertiser_view_costs, advertiser_click_costs, publisher_earnings, nb_points, nb_points_spent) " <<
			" VALUES(" << campaignBannerId << ", " << websiteZoneId << ", CURDATE(), 1, 0, 0, 0, 0, 500, 0);";

		if (mysql_query(con, insertStats.str().c_str()) == 0)
		{
			
		}
		else
		{
			// It already exists, we need to update it !!!
			stringstream updateStats;
			updateStats << "UPDATE stats SET nb_clicks = nb_clicks + 1, nb_points = nb_points + 500 WHERE campaign_banner_id = " << campaignBannerId << 
				" AND website_zone_id = " << websiteZoneId << " AND created_on = CURDATE()";

			mysql_query(con, updateStats.str().c_str());
		}

		// PublisherCPC, AdvertiserCPC, pricingType
		ForClickInfos infos = getInfosForClick(con, campaignBannerId, websiteZoneId);

		// Ok, stats inserted. Then, we need to update in campaigns
		if (infos.pricingType == "CPC")
		{
			// Advertiser needs to pay for 1 click.
			
			// Update available advertiser money
			stringstream updateAvailableAdMoney;
			updateAvailableAdMoney << "UPDATE users SET points = points - 1000 WHERE users.id = (SELECT campaigns.user_id FROM campaigns WHERE campaigns.id = (SELECT campaign_banners.campaign_id FROM campaign_banners WHERE campaign_banners.id = " << campaignBannerId << "));  ";
			mysql_query(con, updateAvailableAdMoney.str().c_str());
			fprintf(stderr, "%s\n", mysql_error(con));
			stringstream updateAvailableAdMoney2;
			updateAvailableAdMoney2 << "UPDATE users SET points = 0 WHERE points < 0 and users.id = (SELECT campaigns.user_id FROM campaigns WHERE campaigns.id = (SELECT campaign_banners.campaign_id FROM campaign_banners WHERE campaign_banners.id = " << campaignBannerId << "));  ";
			mysql_query(con, updateAvailableAdMoney2.str().c_str());
			fprintf(stderr, "%s\n", mysql_error(con));


			// Update advertiser click costs in stats:
			stringstream updateAdvertiserCosts;
			
			updateAdvertiserCosts << "UPDATE stats SET nb_points_spent = nb_points_spent + " << 1000 
				<< " WHERE campaign_banner_id = " << campaignBannerId << " AND website_zone_id = " << websiteZoneId 
				<< " AND created_on = CURDATE();  ";
			mysql_query(con, updateAdvertiserCosts.str().c_str());
			fprintf(stderr, "%s\n", mysql_error(con));

			// Update publisher click earnings in stats:
/*
			stringstream updatePubStatsEarnings;
			updatePubStatsEarnings << "UPDATE stats SET publisher_earnings = publisher_earnings + " << infos.PublisherCPC 
				<< " WHERE campaign_banner_id = " << campaignBannerId << " AND website_zone_id = " << websiteZoneId 
				<< " AND created_on = CURDATE();  ";
			cout << updatePubStatsEarnings.str() << endl;
			mysql_query(con, updatePubStatsEarnings.str().c_str());
			fprintf(stderr, "%s\n", mysql_error(con));
*/

			// Finally, add some points for the publisher
			stringstream updateEarnings;
			updateEarnings << "UPDATE users SET points = points + 500  WHERE users.id = (SELECT w.user_id FROM websites w, website_zones wz WHERE w.id = wz.website_id AND wz.id = " << websiteZoneId << ");  ";
			mysql_query(con, updateEarnings.str().c_str());
			fprintf(stderr, "%s\n", mysql_error(con));
		}
	}
}

pair<string, vector<SqlToDo> > processRequest(const string& recv)
{
	string result = "";	

	vector<Param> params = getParamsIn(recv);
	vector<SqlToDo> sqlsToDo;

	// int campaignBannerId = atoi(valueOfParam(params, "cbi").c_str());
	int webZoneId = atoi(valueOfParam(params, "wzi").c_str());
	string browserCheck = valueOfParam(params, "check");
	int campaignBannerId = atoi(valueOfParam(params, "cbi").c_str());

	string ip = extractIPIn(recv);

	if (ip == "")
	{
		goto endProcessRequest;
	}

	
	if (recv.find(string("Host: ") + string(SYNDICATION_DOMAIN)) != string::npos && browserCheck == "1")
	{
		cout << "OK browser check !!\n";
		result = getEmptyImageResponse();
	}
	else
	if (recv.find(string("Host: ") + string(SYNDICATION_DOMAIN)) != string::npos)
	{

		MYSQL* con = acquireCon();

		if (con == NULL)
		{
			goto endProcessRequest;
		}

		if (bannerFormats.size() == 0)
		{
			bannerFormats = getBannerFormats(con);
		}

		if (isAccountSuspended(con, webZoneId))
		{
			LiberateCon(con);
			goto endProcessRequest;
		}

		vector<Campaign> campaigns = findCampaignToPrint(con, webZoneId);
/*
{
        string type;
        string ip;
        Campaign campaign;
        int campaignBannerId; 
        int webZoneId;
*/

		if (campaigns.size() > 0)
		{
			for (int i = 0; i < campaigns.size(); ++i)
			{
				// Update add view!
				//addView(con, ip, campaigns[i].pricingType, campaigns[i].CPM, campaigns[i].id, campaigns[i].campaignBannerId, webZoneId);
				SqlToDo t;
				t.type = "view";
				t.ip = ip;
				t.campaign = campaigns[i];
				t.webZoneId = webZoneId;
				sqlsToDo.push_back(t);
			}

			result = processSyndication(con, campaigns, webZoneId);
		}

		LiberateCon(con);
	}
	else
	if (recv.find(string("Host: ") + string(CLICK_DOMAIN)) != string::npos)
	{
		SqlToDo t;
		MYSQL* con = acquireCon();

		if (con == NULL)
		{
			goto endProcessRequest;
		}

		if (isAccountSuspended(con, webZoneId))
		{
			LiberateCon(con);
			goto endProcessRequest;
		}

		//addClick(con, ip, campaignBannerId, webZoneId);
		t.type = "click";
		t.ip = ip;
		t.campaignBannerId = campaignBannerId;
		t.webZoneId = webZoneId;
		sqlsToDo.push_back(t);

		result = processClick(con, campaignBannerId);

		LiberateCon(con);
	}

	endProcessRequest:

	if (result == "")
	{

		result = "HTTP/1.0 200 OK\r\nContent-Type: text/html\r\nContent-Length: 0\r\n\r\n";
	}

	return make_pair(result, sqlsToDo);
}

void* SocketHandler(void* lp){

	ParamsToSocket* params = (ParamsToSocket*)lp;

	int *csock = params->csock;

	delete params;

	char buffer[2024];
	int buffer_len = 2024;
	int bytecount;
	pair<string, vector<SqlToDo> > result;
	string response = "";
	struct timeval start, end;
	long mtime, seconds, useconds;  
	MYSQL* con2;
	stringstream sqlInsertConnDuration;

	memset(buffer, 0, buffer_len);
	if((bytecount = recv(*csock, buffer, buffer_len, 0))== -1){
		fprintf(stderr, "Error receiving data %d\n", errno);
		goto FINISHSocket;
	}

	gettimeofday(&start, NULL);

	printf("L = %d, Request: %s\n", bytecount, string(buffer).c_str());
	
	result = processRequest(string(buffer));
	response = result.first;

	if((bytecount = send(*csock, response.c_str(), (int)response.size(), 0))== -1){
		fprintf(stderr, "Error sending data %d\n", errno);
		goto FINISHSocket;
	}
	
	gettimeofday(&end, NULL);

	seconds  = end.tv_sec  - start.tv_sec;
	useconds = end.tv_usec - start.tv_usec;

	mtime = ((seconds) * 1000000 + useconds) + 0.5;
	//elapsed = (double)(endProc - beginProc) / CLOCKS_PER_SEC;
	


FINISHSocket:
	close(*csock);
	free(csock);
	//free(params);

	con2 = acquireCon();
	sqlInsertConnDuration << "INSERT INTO impression_durations(time, duration_in_ms) VALUES(NOW(), " << mtime/1000 << ")";
	mysql_query(con2, sqlInsertConnDuration.str().c_str());

	for (int i = 0; i < (int)result.second.size(); ++i)
	{
		if (result.second[i].type == "view")
		{
			addView(con2, result.second[i].ip, result.second[i].campaign.pricingType, result.second[i].campaign.CPM, result.second[i].campaign.id, result.second[i].campaign.campaignBannerId, result.second[i].webZoneId);
		}
		else
		if (result.second[i].type == "click")
		{
			addClick(con2, result.second[i].ip, result.second[i].campaignBannerId, result.second[i].webZoneId);
		}
	}

	LiberateCon(con2);

    return 0;
}
