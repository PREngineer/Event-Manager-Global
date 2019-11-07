<?php

require_once 'autoloader.php';

class MyRSVPs extends Page
{
  
  //------------------------- Attributes -------------------------
  private $db = null;
  public $content = '<br><br>';
  public $title = "Event Manager - My RSVPs";
  public $keywords = "event manager, My RSVPs";
  
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
   * getAll - Returns all of the approved future events.
   *
   * @return array
   */
  private function getAll( $id )
  {
    $date = date('Y-m-d');
    
    return $this->db->query_DB("SELECT E.ID, E.Name, E.Overview, E.Date, E.Start, E.End, E.Location, E.Address, O.Symbol AS Org, R.Enterprise_ID AS EID, R.ID AS RSVPID
                                FROM Events E
                                INNER JOIN Orgs O
                                ON E.Org_ID = O.ID
                                INNER JOIN RSVP R
                                ON E.ID = R.Event_ID
                                WHERE R.Enterprise_ID = '$id'
                                AND E.Date > '$date'
                                AND E.Approved = '1'
                                AND E.Deleted = '0'
                                AND R.Cancel = '0'
                              ");
  }

  /**
   * Display - Displays the full page
   *
   * @param  mixed $filter
   *
   * @return void
   */
  public function Display( $posted )
  {
    // Set the page header
    $this->content .= '
      <h1 id="page-title" tabindex="-1" role="heading" aria-level="1">My RSVPs</h1>
      <hr>
    ';

    $this->content .= '
    <!-- Form STARTS here -->

    <form class="form-inline" role="form" method="POST" action="index.php?display=MyRSVPs" id="myRSVPForm">
      
      <div class="form-group">
        <label>Enterprise ID:</label>
      </div>

      <div class="form-group">
        <input name="search" type="text" class="form-control" placeholder="user.name">
      </div>

      <button type="submit" class="btn btn-primary">
        <span class="glyphicon glyphicon-search" aria-hidden="true"></span> Find
      </button>  
    
    </form>
    <br>
    <!-- Form ENDS here -->    
    ';

    $data = null;
    // Get the data
    if( $posted['search'] !== null )
    {
      $data = $this->getAll( $posted['search'] );
    }

    // Process the data into cards
    if( sizeof($data) >= 0 )
    {
      foreach($data as $entry)
      {
        $block = new MyRSVPsBlock( $entry );
        $this->content .= $block->Display();
      }
    }
    else
    {
      $this->content .= '
      <div class="container">
        <h2>There are no RSVPs to show.</h2>
      </div>
      ';
    }

    parent::Display();
  }

}

?>