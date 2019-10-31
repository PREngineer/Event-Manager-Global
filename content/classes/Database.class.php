<?php

class Database
{
  
  //------------------------- Attributes -------------------------
  
  private $type = null;
  private $name = null;
  private $user = null;
  private $pass = null;
  private $host = null;
  private $port = null;
  private $conn = null;
  
  //------------------------- Operations -------------------------
  
  /**
   * __construct
   *
   * @param  mixed $data
   *
   * @return void
   */
  public function __construct()
  {
    if( file_exists('settings.php') )
    {
      require_once 'settings.php';
      
      $this->type = $DBTYPE;
      $this->name = $DBNAME;
      $this->user = $DBUSER;
      $this->pass = $DBPASS;
      $this->host = $DBHOST;
      $this->port = $DBPORT;
    }
  }

  /**
   * connect - Establishes a connection to the database.
   *
   * @return PDO|String Connection or Error
   */
  private function connect()
  {
    // Set up only if not already connected
    if($this->conn === null)
    {
      $dsn = '';
      
      if($this->type == 'mysql')
      {
        $dsn = 'mysql:host=' . $this->host . ';port=' . $this->port . ';dbname=' . $this->name . ';charset=utf8';
      }
      elseif($this->type == 'sqlsrv')
      {
        $dsn = 'sqlsrv:Server=' . $this->host . ',' . $this->port . ';Database=testdb';
      }
      
      try
      {
        $this->conn = new PDO($dsn, $this->user, $this->pass);
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);;
      }
      catch(PDOException $error)
      {
        return $error->getMessage();
      }
    }
    return True;
  }
  
  /**
   * queryDB - executes a query against the database.
   *
   * @param  mixed $query
   *
   * @return result data or result
   */
  public function query_DB($query)
  {
    $stmt = null;

    try
    {
      $this->connect();
      
      $stmt = $this->conn->prepare($query);
      $stmt->execute();
    }
    catch(PDOException $error)
    {
      return $error->getMessage();
    }

    $data = $stmt->fetchAll();

    $stmt = null;

    return $data;
  }

}

?>