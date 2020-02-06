<?php

require_once 'autoloader.php';

class AdminAnnouncementsMenu extends Page
{
  
  //------------------------- Attributes -------------------------
  private $db = null;
  public $content = '<br><br>';
  public $title = "Event Manager - Admin - Announcements";
  public $keywords = "event manager, admin announcements";
  
  //------------------------- Operations -------------------------
  
  /**
   * __construct
   *
   * @return void
   */
  public function __construct()
  {
    $this->db = new Database();
    parent::__construct();
  }

  /**
   * getAll - Returns all of the active announcements.
   *
   * @return array
   */
  private function getAll()
  {
    return $this->db->query_DB("SELECT A.ID, A.Title, A.Content, A.Posted, A.Expires, O.Symbol
                                FROM Announcements A, Orgs O
                                WHERE A.Org_ID = O.ID
                                AND A.Expires >= '" . date('Y-m-d') . "'
                                ORDER BY A.Expires
                              ");
  }

  /**
   * getAllExpired - Returns all the expired announcements
   *
   * @return void
   */
  private function getAllExpired()
  {
    return $this->db->query_DB("SELECT A.ID, A.Title, A.Content, A.Posted, A.Expires, O.Symbol
                                FROM Announcements A, Orgs O
                                WHERE A.Org_ID = O.ID
                                AND A.Expires < '" . date('Y-m-d') . "'
                                ORDER BY A.Expires
                              ");
  }

  /**
   * getFiltered - Returns all of the active announcements that match a filter by Org.
   *
   * @param  string $filter
   *
   * @return array
   */
  private function getFiltered( $filter )
  {
    return $this->db->query_DB("SELECT A.Title, A.Content, A.Posted, A.Expires, O.Symbol
                                FROM Announcements A, Orgs O
                                WHERE A.Org_ID = O.ID
                                AND O.Symbol = '" . $filter . "'
                                AND A.Expires >= '" . date('Y-m-d') . "'
                                ORDER BY A.Expires
                              ");
  }

  /**
   * getFilters - Retrieves a list of unique Orgs to filter by.
   *
   * @return array
   */
  private function getFilters()
  {
    return $this->db->query_DB("SELECT DISTINCT O.Symbol
                                FROM Announcements A, Orgs O
                                WHERE A.Org_ID = O.ID
                                AND A.Expires >= '" . date('Y-m-d') . "'
                                ORDER BY O.Symbol
                              ");
  }

  /**
   * Set
   *
   * @param  string $name
   * @param  string $value
   *
   * @return void
   */
  public function Set( $name, $value )
  {
    $this->$name = $value;
  }

  /**
   * Display - Displays the full page
   *
   * @param  mixed $filter
   *
   * @return void
   */
  public function Display( $filter )
  {
    // Get the data
    if( $filter === null || $filter === 'All' )
    {
      $data = $this->getAll();
    }
    else
    {
      $data = $this->getFiltered( $filter );
    }

    $filt = $this->getFilters();

    // Set the page header
    $this->content .= '
      <h1 id="page-title" tabindex="-1" role="heading" aria-level="1">Announcements</h1>
      <hr>

      <div class="panel panel-default">

    <!-- Default panel contents -->
    <div class="panel-heading">Here are all the announcements that have been created.</div>
      <div class="panel-body">
        <a href="index.php?display=AdminCreateAnnouncement" style="cursor:pointer;"><i class="glyphicon glyphicon-plus" title="New Event"></i> New Announcement</a>
        <i class="glyphicon glyphicon-edit" title="Edit" style="color:orange; padding-left:2em"></i> = Edit
        <i class="glyphicon glyphicon-trash" title="Delete" style="color:red; padding-left:2em"></i> = Delete
        <i class="glyphicon glyphicon-time" title="Expire" style="color:red; padding-left:2em"></i> = Expire
      </div>
    </div>
    ';

    $filter = new AnnouncementsFilter( $filt );

    $this->content .= $filter->Display();

    // Process the data into blocks
    if( sizeof($data) >= 0 )
    {
      foreach($data as $entry)
      {
        $block = new AdminAnnouncementsBlock( $entry );
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