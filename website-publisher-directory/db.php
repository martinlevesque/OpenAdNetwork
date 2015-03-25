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
/* Titre          : Classe DATABASE                                           */
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

	function categories()
	{
		return $this->query("SELECT * FROM categories WHERE parent_category_id IS NOT NULL");
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

	function website($id)
	{
		$row = $this->query("SELECT * FROM websites where id = $id");

		return $row[0];
	}
}

?>
