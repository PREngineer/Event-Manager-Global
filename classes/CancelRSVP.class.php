<?php

require_once 'autoloader.php';

class CancelRSVP extends Page
{
  
  //------------------------- Attributes -------------------------
  private $db = null;
  public $content = '<br><br>';
  public $title = "Event Manager - Cancel My RSVP";
  public $keywords = "event manager, Cancel My RSVP";
  
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

  public function get_EventData( $id )
  {

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
    if( !empty($_GET) )
    {
      $eventData = $this->get_EventData( $_GET['id'] );

      // -- Gather the data to pass to the e-mail function --
      // Event ID, Event Name, Event Date, Location, Enterprise ID, RSVP ID
      // SMTP Hosts, SMTP Authentication, SMTP User, SMTP Password, SMTP Encryption, SMTP Port
      $data[0]   = $eventData[0];
      $data[1]   = $eventData[1];
      $data[2]   = $eventData[2];
      $data[3]   = $eventData[7];
      $data[4]   = $_GET['eid'];
      $data[5]   = $_GET['rsvpid'];
      $data[6]   = "You will be prompted for the reason later.";
      $data[7]   = $SMTPHOSTS;
      $data[8]   = $SMTPAUTHENTICATION;
      $data[9]   = $SMTPUSER;
      $data[10]  = $SMTPPASS;
      $data[11]  = $SMTPENC;
      $data[12]  = $SMTPPORT;
      $data[13]  = $SMTPFROMEMAIL;
      $data[14]  = $SMTPFROMNAME;
      $data[15]  = $MYDOMAIN;

      //print_r($data);
    }

    $success = cancelRSVPMail($data);

    // Set the page header
    $this->content .= '
      <h1 id="page-title" tabindex="-1" role="heading" aria-level="1">Cancel My RSVP</h1>
      <hr>
    ';

    parent::Display();
  }

}

?>