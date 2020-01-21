<?php

require_once 'autoloader.php';

class RSVP extends Page
{
  
  //------------------------- Attributes -------------------------
  private $db = null;
  private $mailer  = null;
  public $content = '<br><br>';
  public $title = "Event Manager - Event RSVP";
  public $keywords = "event manager, event rsvp";
  
  //------------------------- Operations -------------------------
  
  /**
   * __construct
   *
   * @return void
   */
  public function __construct()
  {
    $this->db = new Database();
    $this->mailer = new Mailer();
    parent::__construct();
  }

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

  /**
   * get_OrgSymbol - Returns the Org Symbol
   *
   * @param  mixed $id
   *
   * @return void
   */
  public function get_OrgSymbol( $id )
  {
    return $this->db->query_DB("SELECT Symbol
                                FROM `Orgs`
                                WHERE `ID` = '" . $id . "'"
                              )[0]['Symbol'];
  }
  
  /**
   * isRegistered - Checks whether the user has already registered for this event.
   *
   * @param  mixed $id
   *
   * @return void
   */
  private function isRegistered( $id, $eid )
  {
    return $this->db->query_DB("SELECT Cancel
                                FROM `RSVP`
                                WHERE `Event_ID`      = '" . $id . "'
                                AND   `Enterprise_ID` = '" . $eid . "'"
                              )[0]['Cancel'];
  }
  
  /**
   * registerRSVP - Adds the RSVP entry to the database.
   *
   * @param  mixed $data
   *
   * @return void
   */
  private function registerRSVP( $data )
  {
    $cancelled = $this->isRegistered( $data['ID'], $data['Eid'] );
  
    if( $cancelled === null )
    {
      $success = $this->db->query_DB("INSERT INTO `RSVP`
                                      (`Event_ID`, `Enterprise_ID`, `Cancel`)
                                      VALUES ('" . $data['ID'] . "', '" . $data['Eid'] . "', '0')"
                                    );
      return ( $success === True ) ? '0' : $success ;
    }
    else if( $cancelled === '1' )
    {
      $success = $this->db->query_DB("UPDATE `RSVP`
                                      SET `Cancel`        = '0',
                                          `Cancel_Reason` = NULL,
                                          `Cancel_Timestamp` = NULL
                                      WHERE `Event_ID`    = '" . $data['ID']  . "'
                                      AND `Enterprise_ID` = '" . $data['Eid'] . "'"
                                    );
      return ( $success === True ) ? '1' : $success ;
    }
    else
    {
      return '2';
    }
  }

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
      <h1 id="page-title" tabindex="-1" role="heading" aria-level="1">Event RSVP</h1>
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
    $data = $this->get_EventData( $get['id'] );
    $edate = ($data['Date'] . ' ' . $data['Start']);
    $now = new DateTime("now");

    // Display only if the event is in the future
    if( $edate > $now->format('Y-m-d H:m:s') )
    {
      // Set the page header
      $this->content .= '
        <!-- Form STARTS here -->

        <form class="container" method="POST" id="rsvpForm">
        <input type="hidden" name="submitted" value="1">

        <p><strong> Note: All fields marked with an asterisk ( <label class="text-danger">*</label> ) are required.</strong></p>

          <div class="form-group">
            <label for="eid"> <label class="text-danger">*</label> Enterprise ID:</label>
            <div class="input-group">
              <span class="input-group-addon">
                <i class="glyphicon glyphicon-user"></i>
              </span>
              <input name="eid" type="text" class="form-control" id="eid"
              placeholder="john.p.doe" aria-describedby="enterpriseIDHelp" aria-required="true" value="' . $_SESSION['userID'] . '">
            </div>
          <small id="enterpriseIDHelp" class="form-text text-muted">Use your enterprise ID only, don\'t include "@company.com"</small>
          </div>

          <div class="form-group">
            <label for="email"> <label class="text-danger">*</label> Enterprise E-mail:</label>
            <div class="input-group">
              <span class="input-group-addon">
                <i class="glyphicon glyphicon-user"></i>
              </span>
              <input name="email" type="text" class="form-control" id="email"
              placeholder="john.p.doe" aria-describedby="enterpriseIDHelp" aria-required="true" value="' . $_SESSION['userID'] . '">
            </div>
          </div>

          <hr>

          <input class="btn btn-primary" type="submit" value="Submit">
          <input class="btn btn-primary" type="reset"  value="Clear">

        </form>
        <!-- Form ENDS here -->

        <!--**************  Inline Error Messages **************-->
        <script>
          $(document).ready(function()
          {
            $(\'#rsvpForm\').bootstrapValidator(
            {
                container: \'#messages\',
                // To use feedback icons, ensure that you use Bootstrap v3.1.0 or later
                feedbackIcons:
                {
                    valid: \'glyphicon glyphicon-ok\',
                    invalid: \'glyphicon glyphicon-remove\',
                    validating: \'glyphicon glyphicon-refresh\'
                },
                fields:
                {
                    eid:
                    {
                        validators:
                        {
                            notEmpty:
                            {
                                message: \'ERROR: Please enter your Enterprise ID.\'
                            }
                        }
                    },
                    email:
                    {
                        validators:
                        {
                            notEmpty:
                            {
                                message: \'ERROR: Please enter your Enterprise E-mail.\'
                            }
                        }
                    }
                }
            })

            // POST if everything is OK
            .on(\'success.form.bv\', function(e)
            {
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
        <!-- End Scripts for Inline Error Messages -->
      ';
    }
    else
    {
      $this->content .= 'No more RSVPs are allowed for the chosen event.';
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

    $eventData = $this->get_EventData( $_GET['id'] );

    $org  = $this->get_OrgSymbol( $eventData['Org_ID'] );

    // -- Gather the data to pass to the e-mail function --
    // Event ID, Event Name, Event Date, Location, Enterprise ID, RSVP ID
    $data           = $eventData;
    $data['Eid']    = $posted['eid'];
    $data['Email']  = $posted['email'];
    $data['Symbol'] = $org;
    
    $success = $this->registerRSVP( $data );

    if( $success === '0' || $success === '1' || $success === '2' )
    {
      // New RSVP
      if( $success === '0' )
      {
        $iCal = new iCal( $data );
        $data['iCal'] = $iCal->to_string();
        
        $this->mailer->notifyRSVPRegistered( $data );

        $this->content .= '
        <div class="text-success">
          <h2>
            Your RSVP has been registered.
          </h2>

          <br>

          <p class="text-success">
            A confirmation e-mail has been sent to \'' . $data['Email'] . '\'.<br>
            Please keep it for your records.
          </p>
          
        </div>
        ';
      }
      // Renewed Cancelled RSVP
      if( $success === '1' )
      {
        $iCal = new iCal( $data );
        $data['iCal'] = $iCal->to_string();
        
        $this->mailer->notifyRSVPRegistered( $data );

        $this->content .= '
        <div class="text-success">
          <h2>
            Your previously cancelled RSVP has been re-registered.
          </h2>

          <br>

          <p class="text-success">
            A confirmation e-mail has been sent to \'' . $data['Email'] . '\'.<br>
            Please keep it for your records.
          </p>
        </div>
        ';
      }
      // Already RSVPed
      if( $success === '2' )
      {
        $this->content .= '
        <div class="text-warning">
          <h2>
            You already have an RSVP for this event.
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
        An error occurred while processing your RSVP registration, please try again later.
        <br><br>
        Error: ' . $success . '
        <br><br>
        Click <a href="">Here</a> to try again.
      </div>';
    }
  }

}

?>