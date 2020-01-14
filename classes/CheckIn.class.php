<?php

require_once 'autoloader.php';

class CheckIn extends Page
{

  //------------------------- Attributes -------------------------
  private $db = null;
  public $content = '<br><br>';
  public $title = "Event Manager - Event Checkin";
  public $keywords = "event manager, event checkin";

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

  /****************
      Helper Functions
  ****************/

  /**
   * get_EventData - Retrieve the Event's details.
   *
   * @param  mixed $id
   *
   * @return void
   */
  public function get_EventData( $id )
  {
    return $this->db->query_DB("SELECT *
                                FROM `Events`
                                WHERE `ID` = '$id'"
                              )[0];
  }

  public function get_MemberID( $eid )
  {
    return $this->db->query_DB("SELECT `ID`
                                FROM `Members`
                                WHERE `EID` = '$eid'"
                              )[0]['ID'];
  }

  /**
   * isRegistered - Checks whether the user has already registered for this event.
   *
   * @param  mixed $id
   *
   * @return void
   */
  private function isCheckedIn( $id, $mid )
  {
    return $this->db->query_DB("SELECT Count(`Event_ID`) as Count
                                FROM `Attendance`
                                WHERE `Event_ID`  = '" . $id  . "'
                                AND   `Member_ID` = '" . $mid . "'"
                              )[0]['Count'];
  }

  /****************
      Core Functionality Functions
  ****************/

  private function checkIn( $data )
  {
    // Has the user already checked in?
    $checkedIn = $this->isCheckedIn( $data['ID'], $data['MID'] );

    // No, check them in
    if( $checkedIn === '0' )
    {
      $success = $this->db->query_DB("INSERT INTO `Attendance`
                                      (`Event_ID`,`Member_ID`,`Type`)
                                      VALUES (
                                        '" . $data['ID']           . "',
                                        '" . $data['MID'] . "',
                                        '" . $data['Type']         . "')"
                                    );
      // Return True for success, Error if any
      return ( $success === True ) ? '0' : $success ;
    }
    // Yes, return checked in
    else if( $checkedIn === '1' )
    {
      return '1';
    }
  }

  /****************
      Page Display Functions
  ****************/

  /**
   * Display - Displays the full page
   *
   * @param  mixed $filter
   *
   * @return void
   */
  public function Display( $get )
  {
    // Set the page header
    $this->content .= '
      <h1 id="page-title" tabindex="-1" role="heading" aria-level="1">Event Check In</h1>
      <hr>
    ';

    if( !isset( $get['submitted'] ) )
    {
      $this->Display_Form( $get );
    }
    else
    {
      $this->Display_Processed( $get );
    }

    parent::Display();
  }

  /**
   * Display - Displays the full page
   *
   * @param  mixed $filter
   *
   * @return void
   */
  public function Display_Form( $get )
  {
    // Make sure checkins are only allowed during the event.
    $data  = $this->get_EventData( $get['id'] );
    $start = ($data['Date'] . ' ' . $data['Start']);
    $end   = ($data['Date'] . ' ' . $data['End']);
    $now   = new DateTime("now");

    // Display only if the event is happening
    if( $end > $now->format('Y-m-d H:m:s') && $start < $now->format('Y-m-d H:m:s') )
    {
      // Set the page header
      $this->content .= '
        <p>
          You are checking in to the event <b>' . $data['Name'] . '<b/>
        </p>
        <hr>
        <form class="container" method="POST" id="checkinForm">
          <input name="submitted" type="hidden" value="1">
          <input name="personCode" type="hidden" value="' . $data['Person_Code'] . '">
          <input name="remoteCode" type="hidden" value="' . $data['Remote_Code'] . '">

          <p><strong>All fields marked with an asterisk ( <label class="text-danger">*</label> ) are required. </strong></p>

          <div class="form-group">
            <label class="form_label" for="enterpriseID"><label class="text-danger">*</label> Enterprise ID:</label>
            <div class="input-group">
              <span class="input-group-addon">
                <i class="glyphicon glyphicon-user"></i>
              </span>
              <input class="form-control" id="enterpriseID" type="text" name="enterpriseID" aria-required="true" placeholder="john.p.doe" aria-describedby="enterpriseIDHelp" required>
            </div>
            <small id="enterpriseIDHelp" class="sr-only form-text text-muted"></small>
          </div>

          <div class="form-group">
            <label class="form_label" for="code"> <label class="text-danger">*</label> Event Code: </label>
            <div class="input-group">
              <span class="input-group-addon">
                <span aria-hidden="true"><em class="glyphicon glyphicon-barcode"></em></span>
              </span>
              <input name="code" type="text" class="form-control" id="code" placeholder="abc123" aria-describedby="codeHelp" required>
            </div>
              <small id="codeHelp" class="sr-only form-text text-muted"></small>
          </div>

          <div>
            <button type="submit" class="btn btn-primary">Submit</button>
          </div>

        </form>

        <script type="text/javascript">
             $(document).ready(function()
             {
              $(\'#checkinForm\').bootstrapValidator({
                  // To use feedback icons, ensure that you use Bootstrap v3.1.0 or later
                  feedbackIcons:
                  {
                      valid: \'glyphicon glyphicon-ok\',
                      invalid: \'glyphicon glyphicon-remove\',
                      validating: \'glyphicon glyphicon-refresh\'
                  },
                  fields:
                  {
                      enterpriseID:
                      {
                          validators:
                          {
                              notEmpty:
                              {
                                  message: \'ERROR: Please enter your Enterprise ID.\'
                              }
                          }
                      },
                      code:
                      {
                          validators:
                          {
                              notEmpty:
                              {
                                  message: \'ERROR: Please enter a valid passcode.  This code is provided during the event.\'
                              }
                          }
                      },
                  }
                })

                  .on(\'success.form.bv\', function(e)
                  {
                      $(\'#success_message\').slideDown({ opacity: "show" }, "slow")
                          $(\'#loginPage\').data(\'bootstrapValidator\').resetForm();

                      // Prevent form submission
                      e.preventDefault();

                      // Get the form instance
                      var $form = $(e.target);

                      // Get the BootstrapValidator instance
                      var bv = $form.data(\'bootstrapValidator\');

                      // Use Ajax to submit form data
                      $.post($form.attr(\'display\'), $form.serialize(), function(result)
                      {
                          console.log(result);
                      }, \'json\');
                  });
          });
          </script>
      ';
    }
    else
    {
      $this->content .= 'No more RSVPs are allowed for the chosen event.<br><br>Either the event is full or it has already ended.';
    }
  }

  /**
   * Display_Processed - Creates the RSVP entry and notifies.
   *
   * @param  mixed $get
   *
   * @return void
   */
  public function Display_Processed( $posted )
  {
    $data = null;
    $type = null;
    $mID = $this->get_MemberID( $posted['enterpriseID'] );

    // Identify the type of Attendance
    if( $posted['remoteCode'] === $posted['code'] )
    {
      $type = '1';
    }
    else if( $posted['personCode'] === $posted['code'] )
    {
      $type = '0';
    }

    // -- Gather the data to pass to the e-mail function --
    $data['MID']  = $mID;
    $data['ID']   = $_GET['id'];
    $data['Type'] = $type;

    $success = $this->checkIn( $data );

    if( $success === '0' || $success === '1' )
    {
      // New Check In
      if( $success === '0' )
      {
        $this->content .= '
        <div class="text-success">
          <h2>
            Your Check In has been registered.
          </h2>

        </div>
        ';
      }
      // Already Checked In
      if( $success === '1' )
      {
        $this->content .= '
        <div class="text-warning">
          <h2>
            You have already checked in to this event.
          </h2>

          <br>

          <p class="text-warning">
            Nothing to do.
          </p>
        </div>
        ';
      }
    }
    // Errors occurred
    else
    {
      $this->content .= '
      <div class="text-danger">
        An error occurred while processing your Check In, please try again later.
        <br><br>
        Error: ' . $success . '
        <br><br>
        Click <a href="">Here</a> to try again.
      </div>';
    }
  }

}

?>
