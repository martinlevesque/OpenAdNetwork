<?php
/******************************************************************************/
/*                                                                            */
/*                       __        ____                                       */
/*                 ___  / /  ___  / __/__  __ _____________ ___               */
/*                / _ \/ _ \/ _ \_\ \/ _ \/ // / __/ __/ -_|_-<               */
/*               / .__/_//_/ .__/___/\___/\_,_/_/  \__/\__/___/               */
/*              /_/       /_/                                                 */
/*                                                                            */
/*                                                                            */
/******************************************************************************/
/*                                                                            */
/*                                                                            */
/*                                                                            */
/******************************************************************************/

//version 0.1

class database {

  //Variable interne de la classe
  var $errorNum  = 0;
  var $errorMsg  = null;
  var $resource  = null;
  var $cursor    = null;
  var $number    = 0;

  //constructeur de la classe.
    function database(
                  $host='localhost',
                  $user = 'root',
                  $pass = 'password',
                  $db = 'spiclickadmin')
   {
    //pour valider que l'usager n'entre pas la base
    //de données systeme de MYSQL
    //afin de la pirater.
    if(strtolower($db) == 'mysql') {
      $db = '';
    }
    
    if(!($this->resource = mysql_connect($host, $user, $pass))) {
      //en cas d'échec du serveur
      $this->errorNum = mysql_errno();
      $this->errorMsg = mysql_error();
    }
    if (!mysql_select_db($db)) {
      //en cas d'échec de la bd
      $this->errorNum = mysql_errno();
      $this->errorMsg = mysql_error();
    }
  }

  //retoune le ID de l'erreur
  function getErrorNum() {
    return $this->errorNum;
  }
  //retoune le message de l'erreur
  function getErrorMsg() {
    return $this->errorMsg;
  }
  //s'assure que les champs entrés dans la base
  //de données sont valide en ajoutant au
  //besoins des ' - semblable à la fonction "addslashes"
  function getEscaped($text) {
    return mysql_escape_string($text);
  }



  //envoi une requete à la BD et retounr les résultats sous forme de tableau.
  function query($sql = '') {
    if(empty($sql)) {
      return array();
    }
    $this->errorNum = 0;
    $this->errorMsg = '';
    $array = array();

    //assigne le résultat de la requête
    $this->cursor = mysql_query($sql, $this->resource);

    if (!$this->cursor || is_bool($this->cursor)) {
      $this->errorNum = mysql_errno($this->resource);
      $this->errorMsg = mysql_error($this->resource);
      return array();
    }
    
    $this->number = mysql_num_rows($this->cursor);
    //affecteur le tableau avec les valeurs de retours.
    while($row = mysql_fetch_assoc($this->cursor)) {
      $array[] = $row;
    }
    mysql_free_result($this->cursor);
    return $array;
  }

  //ferme la connection 
  function close() {
    return mysql_close($this->resource);
  }
  //retourne le nombre de ligne(s)
  function getNumRows() {
    return $this->number;
  }
  //retourne le dernier ID de la dernière requête "insert" ajouté
  function getLastId()
  {
    return mysql_insert_id();
  }
  //retourne la version de mysql
  function getVersion()
  {
    return mysql_get_server_info();
  }

	function category($sitename)
	{
		$s = $this->getEscaped($sitename);
		$row = $this->query("SELECT * FROM categories WHERE id = (SELECT category_id FROM websites WHERE name = '$s')");

		return $row[0]["name"];
	}

	function website($sitename)
	{
		$s = $this->getEscaped($sitename);
		$row = $this->query("SELECT * FROM websites WHERE name = '$s'");

		return $row[0];
	}

	function post($id)
	{
		$i = intval($id);
		$row = $this->query("SELECT * FROM microblog WHERE id = $i");

		if (count($row) == 0)
			return NULL;

		return $row[0];
	}

	function posts($website, $page)
	{
		$id = $website["id"];
		$prevPage = $page - 1;
		$from = $prevPage * 5;
		$row = $this->query("SELECT * FROM microblog WHERE website_id = $id ORDER BY id DESC LIMIT $from, 5");

		return $row;
	}

	function nbPosts($website)
	{
		$id = $website["id"];
		$row = $this->query("SELECT COUNT(*) as nb FROM microblog WHERE website_id = $id");

		return intval($row[0]["nb"]);
	}

	function categories()
	{
		return $this->query("SELECT * FROM categories WHERE parent_category_id IS NOT NULL");
	}

	function getBanner($websiteId, $width, $height)
	{
		$row = $this->query("SELECT website_zones.id FROM website_zones, banner_formats WHERE website_zones.banner_format_id = banner_formats.id AND banner_formats.width = $width AND banner_formats.height = $height AND website_zones.website_id = $websiteId AND website_zones.microblog = 1");

		return $row[0]["id"];
	}


	function websites($cat, $noPage)
	{

		$from = ($noPage - 1) * 30;
		return $this->query("SELECT websites.id, websites.name as name, websites.url as url, websites.description as description FROM websites, categories where websites.category_id = categories.id AND categories.name = '$cat' AND websites.active = 1 ORDER BY websites.id DESC LIMIT $from, 30");
	}

	function nbWebsites($cat)
	{
		$row = $this->query("SELECT COUNT(*) as cnt FROM websites, categories where websites.category_id = categories.id AND categories.name = '$cat' AND websites.active = 1");

		return intval($row[0]["cnt"]);
	}
}

?>
