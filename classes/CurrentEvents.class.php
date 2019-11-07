<?php

require_once 'autoloader.php';

class CurrentEvents extends Page
{
  
  //------------------------- Attributes -------------------------
  private $db = null;
  public $content = '<br><br>';
  public $title = "Event Manager - Current Events";
  public $keywords = "event manager, current events";
  
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
  private function getAll()
  {
    $date = date('Y-m-d');
    $time = date('H:i:s');
    
    return $this->db->query_DB("SELECT E.ID, E.Name, E.Overview, E.Date, E.Start, E.End, E.Location, E.Address, O.Symbol AS Org
                                FROM Events E, Orgs O
                                WHERE E.Date = '$date'
                                AND E.Start <= '$time'
                                AND E.End >= '$time'
                                AND E.Approved = 1
                                AND E.Deleted = 0
                                AND E.Org_ID = O.ID
                                ORDER BY E.Date, E.Start
                              ");
  }

  /**
   * getFiltered - Returns all of the future events that match a filter.
   *
   * @param  string $filter
   *
   * @return array
   */
  private function getFiltered( $data )
  {
    $field = null;

    if( $data['filter'] === 'Org' )
    {
      $field = 'O.Symbol';
    }
    else
    {
      $field = 'E.' . $data['filter'];
    }

    return $this->db->query_DB("SELECT E.ID, E.Name, E.Overview, E.Date, E.Start, E.End, E.Location, E.Address, O.Symbol AS Org
                                FROM Events E, Orgs O
                                WHERE E.Date > '" . date('Y-m-d') . "'
                                AND " . $field . " LIKE '%" . $data['search'] . "%'
                                AND E.Approved = 1
                                AND E.Deleted = 0
                                AND E.Org_ID = O.ID
                                ORDER BY E.Date, E.Start
                              ");
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
    if( $filter['filter'] === null || $filter['filter'] === 'Show All' )
    {
      $data = $this->getAll();
    }
    else
    {
      $data = $this->getFiltered( $filter );
    }

    // Set the page header
    $this->content .= '
      <h1 id="page-title" tabindex="-1" role="heading" aria-level="1">Current Events</h1>
      <hr>
    ';

    $filter = new CurrentEventsFilter();

    $this->content .= $filter->Display();

    // Process the data into cards
    if( sizeof($data) > 0 )
    {
      foreach($data as $entry)
      {
        $block = new CurrentEventsBlock( $entry );
        $this->content .= $block->Display();
      }
    }
    else
    {
      $this->content .= '
      <div class="container">
        <h2>There are no events happening right now.</h2>

        <h3>You will be redirected to the announcements page in <span id="counter">5</span> seconds.
        <script type="text/javascript">
        function countdown()
        {
          var i = document.getElementById(\'counter\');
          if (parseInt(i.innerHTML)!=0)
          {
            i.innerHTML = parseInt(i.innerHTML)-1;
          }
        }
        setInterval(function()
        { 
          countdown();
        },1000);
        </script>
        </h3>
      </div>
      ';

      header( "refresh:5;url=index.php?display=Announcements" );
    }
    parent::Display();
  }

}

?>