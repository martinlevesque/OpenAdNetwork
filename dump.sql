
--
-- Table structure for table `advertiser_payments`
--

DROP TABLE IF EXISTS `advertiser_payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `advertiser_payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `amount` float DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `advertiser_payments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `advertiser_payments`
--

LOCK TABLES `advertiser_payments` WRITE;
/*!40000 ALTER TABLE `advertiser_payments` DISABLE KEYS */;
/*!40000 ALTER TABLE `advertiser_payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `banner_formats`
--

DROP TABLE IF EXISTS `banner_formats`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `banner_formats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `width` int(11) DEFAULT NULL,
  `height` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `banner_formats`
--

LOCK TABLES `banner_formats` WRITE;
/*!40000 ALTER TABLE `banner_formats` DISABLE KEYS */;
INSERT INTO `banner_formats` VALUES (1,'Leaderboard (728x90)',728,90),(2,'Normal (468x60)',468,60),(3,'Square (250x250)',250,250),(4,'Medium rectangle (300x250)',300,250),(5,'Vertical rectangle (260x340)',260,340),(7,'Big vertical banner (300x600)',300,600),(8,'Mobile leaderboard (320x50)',320,50),(9,'Large mobile banner (320x100)',320,100);
/*!40000 ALTER TABLE `banner_formats` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `campaign_banners`
--

DROP TABLE IF EXISTS `campaign_banners`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `campaign_banners` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `campaign_id` int(11) NOT NULL,
  `banner_format_id` int(11) NOT NULL,
  `url` varchar(500) DEFAULT NULL,
  `created_on` date DEFAULT NULL,
  `deleted` int(11) DEFAULT '0',
  `status_comment` varchar(255) DEFAULT '',
  `status` varchar(50) DEFAULT 'active',
  `has_hover` int(11) DEFAULT '0',
  `type` varchar(10) DEFAULT 'image',
  `text_title` varchar(100) DEFAULT '',
  `text_url_label` varchar(100) DEFAULT '',
  `text_line1` varchar(100) DEFAULT '',
  `text_line2` varchar(100) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `campaign_id` (`campaign_id`),
  KEY `banner_format_id` (`banner_format_id`),
  KEY `findBannerByStatus` (`status`),
  CONSTRAINT `campaign_banners_ibfk_1` FOREIGN KEY (`campaign_id`) REFERENCES `campaigns` (`id`),
  CONSTRAINT `campaign_banners_ibfk_2` FOREIGN KEY (`banner_format_id`) REFERENCES `banner_formats` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=591 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `campaign_banners`
--

LOCK TABLES `campaign_banners` WRITE;
/*!40000 ALTER TABLE `campaign_banners` DISABLE KEYS */;
/*!40000 ALTER TABLE `campaign_banners` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `campaigns`
--

DROP TABLE IF EXISTS `campaigns`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `campaigns` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `pricing_type_id` int(11) NOT NULL,
  `max_per_day` int(11) DEFAULT NULL,
  `active` int(11) DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `nb_views_unpaid` int(11) DEFAULT NULL,
  `paused` int(11) DEFAULT '0',
  `status` varchar(50) DEFAULT 'active',
  `status_comment` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `category_id` (`category_id`),
  KEY `pricing_type_id` (`pricing_type_id`),
  CONSTRAINT `campaigns_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `campaigns_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  CONSTRAINT `campaigns_ibfk_3` FOREIGN KEY (`pricing_type_id`) REFERENCES `pricing_types` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=351 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `campaigns`
--

LOCK TABLES `campaigns` WRITE;
/*!40000 ALTER TABLE `campaigns` DISABLE KEYS */;
/*!40000 ALTER TABLE `campaigns` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `CPMAdvertiser` float DEFAULT NULL,
  `CPCAdvertiser` float DEFAULT NULL,
  `CPCPublisher` float DEFAULT NULL,
  `parent_category_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `parent_category_id` (`parent_category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'General',0.01,0.02,0.01,NULL),(2,'Casino',0.01,0.02,0.01,NULL),(3,'Adult',0.01,0.02,0.01,NULL),(4,'General > Retail',0.01,0.02,0.01,1),(5,'General > Financial services',0.01,0.02,0.01,1),(6,'General > Automotive',0.01,0.02,0.01,1),(7,'General > Telecom',0.01,0.02,0.01,1),(8,'General > Consumer products',0.01,0.02,0.01,1),(9,'General > Travel',0.01,0.02,0.01,1),(10,'General > Computing products/services',0.01,0.02,0.01,1),(11,'General > Media',0.01,0.02,0.01,1),(12,'General > Entertainment',0.01,0.02,0.01,1),(13,'General > Healthcare and pharma',0.01,0.02,0.01,1),(14,'General > Other',0.01,0.02,0.01,1),(15,'Casino > All',0.01,0.02,0.01,2),(16,'Adult > All',0.01,0.02,0.01,3);
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `country_t`
--

DROP TABLE IF EXISTS `country_t`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `country_t` (
  `country_id` int(5) NOT NULL AUTO_INCREMENT,
  `iso2` char(2) DEFAULT NULL,
  `short_name` varchar(80) NOT NULL DEFAULT '',
  `long_name` varchar(80) NOT NULL DEFAULT '',
  `iso3` char(3) DEFAULT NULL,
  `numcode` varchar(6) DEFAULT NULL,
  `un_member` varchar(12) DEFAULT NULL,
  `calling_code` varchar(8) DEFAULT NULL,
  `cctld` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`country_id`)
) ENGINE=MyISAM AUTO_INCREMENT=251 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `country_t`
--

LOCK TABLES `country_t` WRITE;
/*!40000 ALTER TABLE `country_t` DISABLE KEYS */;
INSERT INTO `country_t` VALUES (1,'AF','Afghanistan','Islamic Republic of Afghanistan','AFG','004','yes','93','.af'),(2,'AX','Aland Islands','&Aring;land Islands','ALA','248','no','358','.ax'),(3,'AL','Albania','Republic of Albania','ALB','008','yes','355','.al'),(4,'DZ','Algeria','People\'s Democratic Republic of Algeria','DZA','012','yes','213','.dz'),(5,'AS','American Samoa','American Samoa','ASM','016','no','1+684','.as'),(6,'AD','Andorra','Principality of Andorra','AND','020','yes','376','.ad'),(7,'AO','Angola','Republic of Angola','AGO','024','yes','244','.ao'),(8,'AI','Anguilla','Anguilla','AIA','660','no','1+264','.ai'),(9,'AQ','Antarctica','Antarctica','ATA','010','no','672','.aq'),(10,'AG','Antigua and Barbuda','Antigua and Barbuda','ATG','028','yes','1+268','.ag'),(11,'AR','Argentina','Argentine Republic','ARG','032','yes','54','.ar'),(12,'AM','Armenia','Republic of Armenia','ARM','051','yes','374','.am'),(13,'AW','Aruba','Aruba','ABW','533','no','297','.aw'),(14,'AU','Australia','Commonwealth of Australia','AUS','036','yes','61','.au'),(15,'AT','Austria','Republic of Austria','AUT','040','yes','43','.at'),(16,'AZ','Azerbaijan','Republic of Azerbaijan','AZE','031','yes','994','.az'),(17,'BS','Bahamas','Commonwealth of The Bahamas','BHS','044','yes','1+242','.bs'),(18,'BH','Bahrain','Kingdom of Bahrain','BHR','048','yes','973','.bh'),(19,'BD','Bangladesh','People\'s Republic of Bangladesh','BGD','050','yes','880','.bd'),(20,'BB','Barbados','Barbados','BRB','052','yes','1+246','.bb'),(21,'BY','Belarus','Republic of Belarus','BLR','112','yes','375','.by'),(22,'BE','Belgium','Kingdom of Belgium','BEL','056','yes','32','.be'),(23,'BZ','Belize','Belize','BLZ','084','yes','501','.bz'),(24,'BJ','Benin','Republic of Benin','BEN','204','yes','229','.bj'),(25,'BM','Bermuda','Bermuda Islands','BMU','060','no','1+441','.bm'),(26,'BT','Bhutan','Kingdom of Bhutan','BTN','064','yes','975','.bt'),(27,'BO','Bolivia','Plurinational State of Bolivia','BOL','068','yes','591','.bo'),(28,'BQ','Bonaire, Sint Eustatius and Saba','Bonaire, Sint Eustatius and Saba','BES','535','no','599','.bq'),(29,'BA','Bosnia and Herzegovina','Bosnia and Herzegovina','BIH','070','yes','387','.ba'),(30,'BW','Botswana','Republic of Botswana','BWA','072','yes','267','.bw'),(31,'BV','Bouvet Island','Bouvet Island','BVT','074','no','NONE','.bv'),(32,'BR','Brazil','Federative Republic of Brazil','BRA','076','yes','55','.br'),(33,'IO','British Indian Ocean Territory','British Indian Ocean Territory','IOT','086','no','246','.io'),(34,'BN','Brunei','Brunei Darussalam','BRN','096','yes','673','.bn'),(35,'BG','Bulgaria','Republic of Bulgaria','BGR','100','yes','359','.bg'),(36,'BF','Burkina Faso','Burkina Faso','BFA','854','yes','226','.bf'),(37,'BI','Burundi','Republic of Burundi','BDI','108','yes','257','.bi'),(38,'KH','Cambodia','Kingdom of Cambodia','KHM','116','yes','855','.kh'),(39,'CM','Cameroon','Republic of Cameroon','CMR','120','yes','237','.cm'),(40,'CA','Canada','Canada','CAN','124','yes','1','.ca'),(41,'CV','Cape Verde','Republic of Cape Verde','CPV','132','yes','238','.cv'),(42,'KY','Cayman Islands','The Cayman Islands','CYM','136','no','1+345','.ky'),(43,'CF','Central African Republic','Central African Republic','CAF','140','yes','236','.cf'),(44,'TD','Chad','Republic of Chad','TCD','148','yes','235','.td'),(45,'CL','Chile','Republic of Chile','CHL','152','yes','56','.cl'),(46,'CN','China','People\'s Republic of China','CHN','156','yes','86','.cn'),(47,'CX','Christmas Island','Christmas Island','CXR','162','no','61','.cx'),(48,'CC','Cocos (Keeling) Islands','Cocos (Keeling) Islands','CCK','166','no','61','.cc'),(49,'CO','Colombia','Republic of Colombia','COL','170','yes','57','.co'),(50,'KM','Comoros','Union of the Comoros','COM','174','yes','269','.km'),(51,'CG','Congo','Republic of the Congo','COG','178','yes','242','.cg'),(52,'CK','Cook Islands','Cook Islands','COK','184','some','682','.ck'),(53,'CR','Costa Rica','Republic of Costa Rica','CRI','188','yes','506','.cr'),(54,'CI','Cote d\'ivoire (Ivory Coast)','Republic of C&ocirc;te D\'Ivoire (Ivory Coast)','CIV','384','yes','225','.ci'),(55,'HR','Croatia','Republic of Croatia','HRV','191','yes','385','.hr'),(56,'CU','Cuba','Republic of Cuba','CUB','192','yes','53','.cu'),(57,'CW','Curacao','Cura&ccedil;ao','CUW','531','no','599','.cw'),(58,'CY','Cyprus','Republic of Cyprus','CYP','196','yes','357','.cy'),(59,'CZ','Czech Republic','Czech Republic','CZE','203','yes','420','.cz'),(60,'CD','Democratic Republic of the Congo','Democratic Republic of the Congo','COD','180','yes','243','.cd'),(61,'DK','Denmark','Kingdom of Denmark','DNK','208','yes','45','.dk'),(62,'DJ','Djibouti','Republic of Djibouti','DJI','262','yes','253','.dj'),(63,'DM','Dominica','Commonwealth of Dominica','DMA','212','yes','1+767','.dm'),(64,'DO','Dominican Republic','Dominican Republic','DOM','214','yes','1+809, 8','.do'),(65,'EC','Ecuador','Republic of Ecuador','ECU','218','yes','593','.ec'),(66,'EG','Egypt','Arab Republic of Egypt','EGY','818','yes','20','.eg'),(67,'SV','El Salvador','Republic of El Salvador','SLV','222','yes','503','.sv'),(68,'GQ','Equatorial Guinea','Republic of Equatorial Guinea','GNQ','226','yes','240','.gq'),(69,'ER','Eritrea','State of Eritrea','ERI','232','yes','291','.er'),(70,'EE','Estonia','Republic of Estonia','EST','233','yes','372','.ee'),(71,'ET','Ethiopia','Federal Democratic Republic of Ethiopia','ETH','231','yes','251','.et'),(72,'FK','Falkland Islands (Malvinas)','The Falkland Islands (Malvinas)','FLK','238','no','500','.fk'),(73,'FO','Faroe Islands','The Faroe Islands','FRO','234','no','298','.fo'),(74,'FJ','Fiji','Republic of Fiji','FJI','242','yes','679','.fj'),(75,'FI','Finland','Republic of Finland','FIN','246','yes','358','.fi'),(76,'FR','France','French Republic','FRA','250','yes','33','.fr'),(77,'GF','French Guiana','French Guiana','GUF','254','no','594','.gf'),(78,'PF','French Polynesia','French Polynesia','PYF','258','no','689','.pf'),(79,'TF','French Southern Territories','French Southern Territories','ATF','260','no',NULL,'.tf'),(80,'GA','Gabon','Gabonese Republic','GAB','266','yes','241','.ga'),(81,'GM','Gambia','Republic of The Gambia','GMB','270','yes','220','.gm'),(82,'GE','Georgia','Georgia','GEO','268','yes','995','.ge'),(83,'DE','Germany','Federal Republic of Germany','DEU','276','yes','49','.de'),(84,'GH','Ghana','Republic of Ghana','GHA','288','yes','233','.gh'),(85,'GI','Gibraltar','Gibraltar','GIB','292','no','350','.gi'),(86,'GR','Greece','Hellenic Republic','GRC','300','yes','30','.gr'),(87,'GL','Greenland','Greenland','GRL','304','no','299','.gl'),(88,'GD','Grenada','Grenada','GRD','308','yes','1+473','.gd'),(89,'GP','Guadaloupe','Guadeloupe','GLP','312','no','590','.gp'),(90,'GU','Guam','Guam','GUM','316','no','1+671','.gu'),(91,'GT','Guatemala','Republic of Guatemala','GTM','320','yes','502','.gt'),(92,'GG','Guernsey','Guernsey','GGY','831','no','44','.gg'),(93,'GN','Guinea','Republic of Guinea','GIN','324','yes','224','.gn'),(94,'GW','Guinea-Bissau','Republic of Guinea-Bissau','GNB','624','yes','245','.gw'),(95,'GY','Guyana','Co-operative Republic of Guyana','GUY','328','yes','592','.gy'),(96,'HT','Haiti','Republic of Haiti','HTI','332','yes','509','.ht'),(97,'HM','Heard Island and McDonald Islands','Heard Island and McDonald Islands','HMD','334','no','NONE','.hm'),(98,'HN','Honduras','Republic of Honduras','HND','340','yes','504','.hn'),(99,'HK','Hong Kong','Hong Kong','HKG','344','no','852','.hk'),(100,'HU','Hungary','Hungary','HUN','348','yes','36','.hu'),(101,'IS','Iceland','Republic of Iceland','ISL','352','yes','354','.is'),(102,'IN','India','Republic of India','IND','356','yes','91','.in'),(103,'ID','Indonesia','Republic of Indonesia','IDN','360','yes','62','.id'),(104,'IR','Iran','Islamic Republic of Iran','IRN','364','yes','98','.ir'),(105,'IQ','Iraq','Republic of Iraq','IRQ','368','yes','964','.iq'),(106,'IE','Ireland','Ireland','IRL','372','yes','353','.ie'),(107,'IM','Isle of Man','Isle of Man','IMN','833','no','44','.im'),(108,'IL','Israel','State of Israel','ISR','376','yes','972','.il'),(109,'IT','Italy','Italian Republic','ITA','380','yes','39','.jm'),(110,'JM','Jamaica','Jamaica','JAM','388','yes','1+876','.jm'),(111,'JP','Japan','Japan','JPN','392','yes','81','.jp'),(112,'JE','Jersey','The Bailiwick of Jersey','JEY','832','no','44','.je'),(113,'JO','Jordan','Hashemite Kingdom of Jordan','JOR','400','yes','962','.jo'),(114,'KZ','Kazakhstan','Republic of Kazakhstan','KAZ','398','yes','7','.kz'),(115,'KE','Kenya','Republic of Kenya','KEN','404','yes','254','.ke'),(116,'KI','Kiribati','Republic of Kiribati','KIR','296','yes','686','.ki'),(117,'XK','Kosovo','Republic of Kosovo','---','---','some','381',''),(118,'KW','Kuwait','State of Kuwait','KWT','414','yes','965','.kw'),(119,'KG','Kyrgyzstan','Kyrgyz Republic','KGZ','417','yes','996','.kg'),(120,'LA','Laos','Lao People\'s Democratic Republic','LAO','418','yes','856','.la'),(121,'LV','Latvia','Republic of Latvia','LVA','428','yes','371','.lv'),(122,'LB','Lebanon','Republic of Lebanon','LBN','422','yes','961','.lb'),(123,'LS','Lesotho','Kingdom of Lesotho','LSO','426','yes','266','.ls'),(124,'LR','Liberia','Republic of Liberia','LBR','430','yes','231','.lr'),(125,'LY','Libya','Libya','LBY','434','yes','218','.ly'),(126,'LI','Liechtenstein','Principality of Liechtenstein','LIE','438','yes','423','.li'),(127,'LT','Lithuania','Republic of Lithuania','LTU','440','yes','370','.lt'),(128,'LU','Luxembourg','Grand Duchy of Luxembourg','LUX','442','yes','352','.lu'),(129,'MO','Macao','The Macao Special Administrative Region','MAC','446','no','853','.mo'),(130,'MK','Macedonia','The Former Yugoslav Republic of Macedonia','MKD','807','yes','389','.mk'),(131,'MG','Madagascar','Republic of Madagascar','MDG','450','yes','261','.mg'),(132,'MW','Malawi','Republic of Malawi','MWI','454','yes','265','.mw'),(133,'MY','Malaysia','Malaysia','MYS','458','yes','60','.my'),(134,'MV','Maldives','Republic of Maldives','MDV','462','yes','960','.mv'),(135,'ML','Mali','Republic of Mali','MLI','466','yes','223','.ml'),(136,'MT','Malta','Republic of Malta','MLT','470','yes','356','.mt'),(137,'MH','Marshall Islands','Republic of the Marshall Islands','MHL','584','yes','692','.mh'),(138,'MQ','Martinique','Martinique','MTQ','474','no','596','.mq'),(139,'MR','Mauritania','Islamic Republic of Mauritania','MRT','478','yes','222','.mr'),(140,'MU','Mauritius','Republic of Mauritius','MUS','480','yes','230','.mu'),(141,'YT','Mayotte','Mayotte','MYT','175','no','262','.yt'),(142,'MX','Mexico','United Mexican States','MEX','484','yes','52','.mx'),(143,'FM','Micronesia','Federated States of Micronesia','FSM','583','yes','691','.fm'),(144,'MD','Moldava','Republic of Moldova','MDA','498','yes','373','.md'),(145,'MC','Monaco','Principality of Monaco','MCO','492','yes','377','.mc'),(146,'MN','Mongolia','Mongolia','MNG','496','yes','976','.mn'),(147,'ME','Montenegro','Montenegro','MNE','499','yes','382','.me'),(148,'MS','Montserrat','Montserrat','MSR','500','no','1+664','.ms'),(149,'MA','Morocco','Kingdom of Morocco','MAR','504','yes','212','.ma'),(150,'MZ','Mozambique','Republic of Mozambique','MOZ','508','yes','258','.mz'),(151,'MM','Myanmar (Burma)','Republic of the Union of Myanmar','MMR','104','yes','95','.mm'),(152,'NA','Namibia','Republic of Namibia','NAM','516','yes','264','.na'),(153,'NR','Nauru','Republic of Nauru','NRU','520','yes','674','.nr'),(154,'NP','Nepal','Federal Democratic Republic of Nepal','NPL','524','yes','977','.np'),(155,'NL','Netherlands','Kingdom of the Netherlands','NLD','528','yes','31','.nl'),(156,'NC','New Caledonia','New Caledonia','NCL','540','no','687','.nc'),(157,'NZ','New Zealand','New Zealand','NZL','554','yes','64','.nz'),(158,'NI','Nicaragua','Republic of Nicaragua','NIC','558','yes','505','.ni'),(159,'NE','Niger','Republic of Niger','NER','562','yes','227','.ne'),(160,'NG','Nigeria','Federal Republic of Nigeria','NGA','566','yes','234','.ng'),(161,'NU','Niue','Niue','NIU','570','some','683','.nu'),(162,'NF','Norfolk Island','Norfolk Island','NFK','574','no','672','.nf'),(163,'KP','North Korea','Democratic People\'s Republic of Korea','PRK','408','yes','850','.kp'),(164,'MP','Northern Mariana Islands','Northern Mariana Islands','MNP','580','no','1+670','.mp'),(165,'NO','Norway','Kingdom of Norway','NOR','578','yes','47','.no'),(166,'OM','Oman','Sultanate of Oman','OMN','512','yes','968','.om'),(167,'PK','Pakistan','Islamic Republic of Pakistan','PAK','586','yes','92','.pk'),(168,'PW','Palau','Republic of Palau','PLW','585','yes','680','.pw'),(169,'PS','Palestine','State of Palestine (or Occupied Palestinian Territory)','PSE','275','some','970','.ps'),(170,'PA','Panama','Republic of Panama','PAN','591','yes','507','.pa'),(171,'PG','Papua New Guinea','Independent State of Papua New Guinea','PNG','598','yes','675','.pg'),(172,'PY','Paraguay','Republic of Paraguay','PRY','600','yes','595','.py'),(173,'PE','Peru','Republic of Peru','PER','604','yes','51','.pe'),(174,'PH','Phillipines','Republic of the Philippines','PHL','608','yes','63','.ph'),(175,'PN','Pitcairn','Pitcairn','PCN','612','no','NONE','.pn'),(176,'PL','Poland','Republic of Poland','POL','616','yes','48','.pl'),(177,'PT','Portugal','Portuguese Republic','PRT','620','yes','351','.pt'),(178,'PR','Puerto Rico','Commonwealth of Puerto Rico','PRI','630','no','1+939','.pr'),(179,'QA','Qatar','State of Qatar','QAT','634','yes','974','.qa'),(180,'RE','Reunion','R&eacute;union','REU','638','no','262','.re'),(181,'RO','Romania','Romania','ROU','642','yes','40','.ro'),(182,'RU','Russia','Russian Federation','RUS','643','yes','7','.ru'),(183,'RW','Rwanda','Republic of Rwanda','RWA','646','yes','250','.rw'),(184,'BL','Saint Barthelemy','Saint Barth&eacute;lemy','BLM','652','no','590','.bl'),(185,'SH','Saint Helena','Saint Helena, Ascension and Tristan da Cunha','SHN','654','no','290','.sh'),(186,'KN','Saint Kitts and Nevis','Federation of Saint Christopher and Nevis','KNA','659','yes','1+869','.kn'),(187,'LC','Saint Lucia','Saint Lucia','LCA','662','yes','1+758','.lc'),(188,'MF','Saint Martin','Saint Martin','MAF','663','no','590','.mf'),(189,'PM','Saint Pierre and Miquelon','Saint Pierre and Miquelon','SPM','666','no','508','.pm'),(190,'VC','Saint Vincent and the Grenadines','Saint Vincent and the Grenadines','VCT','670','yes','1+784','.vc'),(191,'WS','Samoa','Independent State of Samoa','WSM','882','yes','685','.ws'),(192,'SM','San Marino','Republic of San Marino','SMR','674','yes','378','.sm'),(193,'ST','Sao Tome and Principe','Democratic Republic of S&atilde;o Tom&eacute; and Pr&iacute;ncipe','STP','678','yes','239','.st'),(194,'SA','Saudi Arabia','Kingdom of Saudi Arabia','SAU','682','yes','966','.sa'),(195,'SN','Senegal','Republic of Senegal','SEN','686','yes','221','.sn'),(196,'RS','Serbia','Republic of Serbia','SRB','688','yes','381','.rs'),(197,'SC','Seychelles','Republic of Seychelles','SYC','690','yes','248','.sc'),(198,'SL','Sierra Leone','Republic of Sierra Leone','SLE','694','yes','232','.sl'),(199,'SG','Singapore','Republic of Singapore','SGP','702','yes','65','.sg'),(200,'SX','Sint Maarten','Sint Maarten','SXM','534','no','1+721','.sx'),(201,'SK','Slovakia','Slovak Republic','SVK','703','yes','421','.sk'),(202,'SI','Slovenia','Republic of Slovenia','SVN','705','yes','386','.si'),(203,'SB','Solomon Islands','Solomon Islands','SLB','090','yes','677','.sb'),(204,'SO','Somalia','Somali Republic','SOM','706','yes','252','.so'),(205,'ZA','South Africa','Republic of South Africa','ZAF','710','yes','27','.za'),(206,'GS','South Georgia and the South Sandwich Islands','South Georgia and the South Sandwich Islands','SGS','239','no','500','.gs'),(207,'KR','South Korea','Republic of Korea','KOR','410','yes','82','.kr'),(208,'SS','South Sudan','Republic of South Sudan','SSD','728','yes','211','.ss'),(209,'ES','Spain','Kingdom of Spain','ESP','724','yes','34','.es'),(210,'LK','Sri Lanka','Democratic Socialist Republic of Sri Lanka','LKA','144','yes','94','.lk'),(211,'SD','Sudan','Republic of the Sudan','SDN','729','yes','249','.sd'),(212,'SR','Suriname','Republic of Suriname','SUR','740','yes','597','.sr'),(213,'SJ','Svalbard and Jan Mayen','Svalbard and Jan Mayen','SJM','744','no','47','.sj'),(214,'SZ','Swaziland','Kingdom of Swaziland','SWZ','748','yes','268','.sz'),(215,'SE','Sweden','Kingdom of Sweden','SWE','752','yes','46','.se'),(216,'CH','Switzerland','Swiss Confederation','CHE','756','yes','41','.ch'),(217,'SY','Syria','Syrian Arab Republic','SYR','760','yes','963','.sy'),(218,'TW','Taiwan','Republic of China (Taiwan)','TWN','158','former','886','.tw'),(219,'TJ','Tajikistan','Republic of Tajikistan','TJK','762','yes','992','.tj'),(220,'TZ','Tanzania','United Republic of Tanzania','TZA','834','yes','255','.tz'),(221,'TH','Thailand','Kingdom of Thailand','THA','764','yes','66','.th'),(222,'TL','Timor-Leste (East Timor)','Democratic Republic of Timor-Leste','TLS','626','yes','670','.tl'),(223,'TG','Togo','Togolese Republic','TGO','768','yes','228','.tg'),(224,'TK','Tokelau','Tokelau','TKL','772','no','690','.tk'),(225,'TO','Tonga','Kingdom of Tonga','TON','776','yes','676','.to'),(226,'TT','Trinidad and Tobago','Republic of Trinidad and Tobago','TTO','780','yes','1+868','.tt'),(227,'TN','Tunisia','Republic of Tunisia','TUN','788','yes','216','.tn'),(228,'TR','Turkey','Republic of Turkey','TUR','792','yes','90','.tr'),(229,'TM','Turkmenistan','Turkmenistan','TKM','795','yes','993','.tm'),(230,'TC','Turks and Caicos Islands','Turks and Caicos Islands','TCA','796','no','1+649','.tc'),(231,'TV','Tuvalu','Tuvalu','TUV','798','yes','688','.tv'),(232,'UG','Uganda','Republic of Uganda','UGA','800','yes','256','.ug'),(233,'UA','Ukraine','Ukraine','UKR','804','yes','380','.ua'),(234,'AE','United Arab Emirates','United Arab Emirates','ARE','784','yes','971','.ae'),(235,'GB','United Kingdom','United Kingdom of Great Britain and Nothern Ireland','GBR','826','yes','44','.uk'),(236,'US','United States','United States of America','USA','840','yes','1','.us'),(237,'UM','United States Minor Outlying Islands','United States Minor Outlying Islands','UMI','581','no','NONE','NONE'),(238,'UY','Uruguay','Eastern Republic of Uruguay','URY','858','yes','598','.uy'),(239,'UZ','Uzbekistan','Republic of Uzbekistan','UZB','860','yes','998','.uz'),(240,'VU','Vanuatu','Republic of Vanuatu','VUT','548','yes','678','.vu'),(241,'VA','Vatican City','State of the Vatican City','VAT','336','no','39','.va'),(242,'VE','Venezuela','Bolivarian Republic of Venezuela','VEN','862','yes','58','.ve'),(243,'VN','Vietnam','Socialist Republic of Vietnam','VNM','704','yes','84','.vn'),(244,'VG','Virgin Islands, British','British Virgin Islands','VGB','092','no','1+284','.vg'),(245,'VI','Virgin Islands, US','Virgin Islands of the United States','VIR','850','no','1+340','.vi'),(246,'WF','Wallis and Futuna','Wallis and Futuna','WLF','876','no','681','.wf'),(247,'EH','Western Sahara','Western Sahara','ESH','732','no','212','.eh'),(248,'YE','Yemen','Republic of Yemen','YEM','887','yes','967','.ye'),(249,'ZM','Zambia','Republic of Zambia','ZMB','894','yes','260','.zm'),(250,'ZW','Zimbabwe','Republic of Zimbabwe','ZWE','716','yes','263','.zw');
/*!40000 ALTER TABLE `country_t` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `impression_durations`
--

DROP TABLE IF EXISTS `impression_durations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `impression_durations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `duration_in_ms` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=647 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `impression_durations`
--

LOCK TABLES `impression_durations` WRITE;
/*!40000 ALTER TABLE `impression_durations` DISABLE KEYS */;
/*!40000 ALTER TABLE `impression_durations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ip_clicks`
--

DROP TABLE IF EXISTS `ip_clicks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ip_clicks` (
  `campaign_banner_id` int(11) NOT NULL,
  `website_zone_id` int(11) NOT NULL,
  `created_on` date NOT NULL DEFAULT '0000-00-00',
  `ip` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`website_zone_id`,`created_on`,`ip`),
  KEY `website_zone_id` (`website_zone_id`),
  KEY `ip_clicks_ibfk_1` (`campaign_banner_id`),
  CONSTRAINT `ip_clicks_ibfk_1` FOREIGN KEY (`campaign_banner_id`) REFERENCES `campaign_banners` (`id`),
  CONSTRAINT `ip_clicks_ibfk_2` FOREIGN KEY (`website_zone_id`) REFERENCES `website_zones` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ip_clicks`
--

LOCK TABLES `ip_clicks` WRITE;
/*!40000 ALTER TABLE `ip_clicks` DISABLE KEYS */;
/*!40000 ALTER TABLE `ip_clicks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ip_views`
--

DROP TABLE IF EXISTS `ip_views`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ip_views` (
  `campaign_banner_id` int(11) NOT NULL,
  `website_zone_id` int(11) NOT NULL,
  `created_on` date NOT NULL DEFAULT '0000-00-00',
  `ip` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`campaign_banner_id`,`website_zone_id`,`created_on`,`ip`),
  KEY `website_zone_id` (`website_zone_id`),
  CONSTRAINT `ip_views_ibfk_1` FOREIGN KEY (`campaign_banner_id`) REFERENCES `campaign_banners` (`id`),
  CONSTRAINT `ip_views_ibfk_2` FOREIGN KEY (`website_zone_id`) REFERENCES `website_zones` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ip_views`
--

LOCK TABLES `ip_views` WRITE;
/*!40000 ALTER TABLE `ip_views` DISABLE KEYS */;
/*!40000 ALTER TABLE `ip_views` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ip_views_old`
--

DROP TABLE IF EXISTS `ip_views_old`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ip_views_old` (
  `campaign_banner_id` int(11) NOT NULL,
  `website_zone_id` int(11) NOT NULL,
  `created_on` date NOT NULL DEFAULT '0000-00-00',
  `ip` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`campaign_banner_id`,`website_zone_id`,`created_on`,`ip`),
  KEY `website_zone_id` (`website_zone_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ip_views_old`
--

LOCK TABLES `ip_views_old` WRITE;
/*!40000 ALTER TABLE `ip_views_old` DISABLE KEYS */;
/*!40000 ALTER TABLE `ip_views_old` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `microblog`
--

DROP TABLE IF EXISTS `microblog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `microblog` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `website_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `title` varchar(500) DEFAULT NULL,
  `content` mediumtext,
  PRIMARY KEY (`id`),
  KEY `website_id` (`website_id`)
) ENGINE=InnoDB AUTO_INCREMENT=137 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `microblog`
--

LOCK TABLES `microblog` WRITE;
/*!40000 ALTER TABLE `microblog` DISABLE KEYS */;
/*!40000 ALTER TABLE `microblog` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pricing_types`
--

DROP TABLE IF EXISTS `pricing_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pricing_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pricing_types`
--

LOCK TABLES `pricing_types` WRITE;
/*!40000 ALTER TABLE `pricing_types` DISABLE KEYS */;
INSERT INTO `pricing_types` VALUES (1,'CPM'),(2,'CPC');
/*!40000 ALTER TABLE `pricing_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `publisher_payments`
--

DROP TABLE IF EXISTS `publisher_payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `publisher_payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `amount` float DEFAULT '0',
  `created_on` date DEFAULT NULL,
  `transaction_id` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `publisher_payments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `publisher_payments`
--

LOCK TABLES `publisher_payments` WRITE;
/*!40000 ALTER TABLE `publisher_payments` DISABLE KEYS */;
/*!40000 ALTER TABLE `publisher_payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stats`
--

DROP TABLE IF EXISTS `stats`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stats` (
  `campaign_banner_id` int(11) NOT NULL,
  `website_zone_id` int(11) NOT NULL,
  `created_on` date NOT NULL DEFAULT '0000-00-00',
  `nb_clicks` int(11) DEFAULT '0',
  `nb_views` int(11) DEFAULT '0',
  `advertiser_view_costs` float DEFAULT NULL,
  `advertiser_click_costs` float DEFAULT NULL,
  `publisher_earnings` double(20,1) DEFAULT NULL,
  `nb_points` double(20,1) DEFAULT NULL,
  `nb_points_spent` double(20,1) DEFAULT NULL,
  PRIMARY KEY (`campaign_banner_id`,`website_zone_id`,`created_on`),
  KEY `website_zone_id` (`website_zone_id`),
  CONSTRAINT `stats_ibfk_1` FOREIGN KEY (`campaign_banner_id`) REFERENCES `campaign_banners` (`id`),
  CONSTRAINT `stats_ibfk_2` FOREIGN KEY (`website_zone_id`) REFERENCES `website_zones` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stats`
--

LOCK TABLES `stats` WRITE;
/*!40000 ALTER TABLE `stats` DISABLE KEYS */;
/*!40000 ALTER TABLE `stats` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `statstest`
--

DROP TABLE IF EXISTS `statstest`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `statstest` (
  `campaign_banner_id` int(11) NOT NULL,
  `website_zone_id` int(11) NOT NULL,
  `created_on` date NOT NULL DEFAULT '0000-00-00',
  `nb_clicks` int(11) DEFAULT '0',
  `nb_views` int(11) DEFAULT '0',
  `advertiser_view_costs` float DEFAULT NULL,
  `advertiser_click_costs` float DEFAULT NULL,
  `publisher_earnings` float DEFAULT '0',
  PRIMARY KEY (`campaign_banner_id`,`website_zone_id`,`created_on`),
  KEY `website_zone_id` (`website_zone_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `statstest`
--

LOCK TABLES `statstest` WRITE;
/*!40000 ALTER TABLE `statstest` DISABLE KEYS */;
/*!40000 ALTER TABLE `statstest` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `company` varchar(255) DEFAULT NULL,
  `firstname` varchar(255) DEFAULT NULL,
  `lastname` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `available_advertiser_money` float DEFAULT NULL,
  `unpaid_earnings` float DEFAULT NULL,
  `phone_number` varchar(255) DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  `address` varchar(500) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `zipcode` varchar(255) DEFAULT NULL,
  `publisher_paypal` varchar(255) DEFAULT NULL,
  `minimum_payout` int(11) DEFAULT NULL,
  `suspended` int(11) DEFAULT '0',
  `points` double(20,1) DEFAULT NULL,
  `newsletter` int(11) DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=7700 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `website_zones`
--

DROP TABLE IF EXISTS `website_zones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `website_zones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `website_id` int(11) NOT NULL,
  `banner_format_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `active` tinyint(4) DEFAULT '1',
  `microblog` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `website_id` (`website_id`),
  KEY `banner_format_id` (`banner_format_id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `website_zones_ibfk_1` FOREIGN KEY (`website_id`) REFERENCES `websites` (`id`),
  CONSTRAINT `website_zones_ibfk_2` FOREIGN KEY (`banner_format_id`) REFERENCES `banner_formats` (`id`),
  CONSTRAINT `website_zones_ibfk_3` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2541 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `website_zones`
--

LOCK TABLES `website_zones` WRITE;
/*!40000 ALTER TABLE `website_zones` DISABLE KEYS */;
/*!40000 ALTER TABLE `website_zones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `websites`
--

DROP TABLE IF EXISTS `websites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `websites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` varchar(500) DEFAULT NULL,
  `url` varchar(500) DEFAULT NULL,
  `active` tinyint(4) DEFAULT '1',
  `screenshot_generated` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `user_id` (`user_id`),
  KEY `category_id` (`category_id`),
  KEY `screenshot_generated` (`screenshot_generated`),
  CONSTRAINT `websites_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `websites_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3077 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `websites`
--

LOCK TABLES `websites` WRITE;
/*!40000 ALTER TABLE `websites` DISABLE KEYS */;
/*!40000 ALTER TABLE `websites` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-03-25 14:58:07
