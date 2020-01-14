<?php

require_once 'autoloader.php';

class CurrentEventDetails extends Page
{
  
  //------------------------- Attributes -------------------------
  private $db = null;
  
  public $content  = '<br><br>';
  public $title    = "Event Manager - Current Event Details";
  public $keywords = "event manager, current events";


  //------------------------- Operations -------------------------
  
  /**
   * __construct
   *
   * @return void
   */
  public function __construct( $id )
  {
    $this->db        = new Database();
    
    $this->ID        = $id;
    
    parent::__construct();
  }

  /**
   * getAll - Returns the Current event's data.
   *
   * @return array
   */
  private function getData()
  {
    return $this->db->query_DB("SELECT E.ID, E.Name, E.Overview, E.Date, E.Start, E.End, E.Location, E.Address, O.Symbol AS Org
                                FROM Events E, Orgs O
                                WHERE E.ID = '" . $this->ID . "'
                                AND E.Org_ID = O.ID
                              ");
  }

  /**
   * Display - Displays the full page
   *
   * @param  mixed $filter
   *
   * @return void
   */
  public function Display()
  {
    $data = $this->getData()[0];

    $url = 'http://' . $_SERVER['HTTP_HOST'] . '/index.php?display=CurrentEventDetails%26id=' . $this->ID;

    $shareurl = 'mailto:?subject=Thought%20you%20would%20like%20to%20know&body=Check%20out%20this%20event.%0A%0AIt is named: ' . 
                str_replace('=','',str_replace('&','and',str_replace('?','',$data['Name']))) . '%0A%0ALocation: ' . 
                $data['Location'] . '%0A%0ADate: ' . $data['Date'] . '%0A%0AFrom: ' . $data['Start'] . '%20-%20' . $data['End'] . '%0A%0ARSVP here: ' . $url;

    // Set the page header
    $this->content .= '
      <h1 id="page-title" tabindex="-1" role="heading" aria-level="1">Current Event Details</h1>
      <hr>
    ';

    $start     = strtotime( $data['Date'] . ' ' . $data['Start'] );
    $end       = strtotime( $data['Date'] . ' ' . $data['End'] );
    $now       = strtotime( Date('Y-m-d H:m:s') );
    
    // Process the data into the card, only if event is currently happening
    if( ( sizeof($data) > 0 ) && 
        ( $start < $now ) && 
        ( $end > $now ) )
    {
      $this->content .= '
      <div class="col-lg-12">
        <div class="thumbnail">

          <div class="caption">

            <div class="text-center">
              <img src="images/event.png" alt="Event image" width="150" height="150">
              <br>
              <label>' . $data['Org'] . '</label>
            </div>

            <table role="presentation" class="table">

              <tr>
                <td class="text-center" colspan="2">
                  <strong>' . $data['Name'] . '</strong>
                </td>
              </tr>

              <tr>
                <td colspan="2">' . $data['Overview'] . '</td>
              </tr>

              <tr>
                <td style="width: 50%">Date:</td>
                <td>' . $data['Date'] . '</td>
              </tr>

              <tr>
                <td>Starts:</td>
                <td>' . $data['Start'] . '</td>
              </tr>

              <tr>
                <td>Ends:</td>
                <td>' . $data['End'] . '</td>
              </tr>

              <tr>
                <td style="height: 80px;" >Location:</td>
                <td>
                  ' . $data['Location'] . ' 
                </td>
              </tr>

              <tr>
                <td>Address:</td>
                <td>
                  ' . $data['Address'] .'
                </td>
              </tr>

              <tr>
                <td>
                  <a href="https://maps.apple.com/?address=' . str_replace(' ','+',str_replace(',','%2C',data['Address'])) . '">
                    <img src="images/amaps.png">
                  </a>
                </td>
                <td>
                  <a href="https://www.google.com/maps/dir/?api=1&destination=' . str_replace(' ','+',str_replace(',','%2C',$data['Address'])) . '">
                    <img src="images/gmaps.png">
                  </a>
                </td>
              </tr>

              <tr>
                <td class="text-center" colspan="2">
                  <a href="index.php?display=Checkin&id=' . $this->ID . '" class="btn btn-primary" role="button">Check In</a>
                </td>
              </tr>

            </table>

          </div>
        </div>
      </div>
      ';
    }
    else
    {
      $this->content .= '
      <div class="container">
        <h2>The event ID provided is not valid.</h2>
      </div>
      ';
    }
    
    parent::Display();
  }

}

?>