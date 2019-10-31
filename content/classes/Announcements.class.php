<?php

require_once 'autoloader.php';

class Announcements extends Page
{
  
  //------------------------- Attributes -------------------------
  public $content = '<br>';
  public $title = "Event Manager - Announcements";
  public $keywords = "event manager, announcements";
  
  //------------------------- Operations -------------------------
  
  public function __construct()
  {
    parent::__construct();
  }

  public function Set($name, $value)
  {
    $this->$name = $value;
  }

  public function Display()
  {
    // Get the data
    $db = new Database();
    
    $data = $db->query_DB(
                          "SELECT A.Title, A.Content, A.Posted, A.Expires, O.Symbol
                           FROM Announcements A, Orgs O
                           WHERE A.Org_ID = O.ID
                           AND A.Expires >= '" . date('Y-m-d') . "'
                           ORDER BY A.Expires
                           ");

    // Set the page header
    $this->content .= '
      <h1 id="page-title" tabindex="-1" role="heading" aria-level="1">Announcements</h1>
      <hr>
    ';

    // Process the data into blocks
    if( sizeof($data) >= 0 )
    {
      foreach($data as $entry)
      {
        $block = new AnnouncementsBlock($entry);
        $this->content .= $block->Display();
      }
    }
    else
    {
      $this->content .= '
      <div class="container">
        <h2>There are no Announcements to show.</h2>
      </div>
      ';
    }

    parent::Display();
  }

}

?>