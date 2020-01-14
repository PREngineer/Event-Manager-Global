<?php

require_once 'autoloader.php';

class CancelRSVP extends Page
{
  
  //------------------------- Attributes -------------------------
  private $db      = null;
  private $mailer  = null;
  public $content  = '<br><br>';
  public $title    = "Event Manager - Cancel My RSVP";
  public $keywords = "event manager, cancel my rsvp";
  
  //------------------------- Operations -------------------------
  
  /**
   * __construct
   *
   * @return void
   */
  public function __construct()
  {
    $this->db     = new Database();
    $this->mailer = new Mailer();
    parent::__construct();
  }

  /**
   * cancelRSVP - Execute a query to mark the RSVP as cancelled.
   *
   * @param  mixed $data
   *
   * @return void
   */
  public function cancelRSVP( $data )
  {
    $ts = date("Y-m-d H:m:s");

    return $this->db->query_DB("UPDATE `RSVP`
                                SET `Cancel` = '1',
                                    `Cancel_Reason` = '" . $data[6] . " - " . $data[7] . "',
                                    `Cancel_Timestamp` = '$ts'
                                WHERE `ID` = '" . $data[5] . "'"
                              );
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
   * get_UserEmail - Retrieve the user's e-mail
   *
   * @param  mixed $eid
   *
   * @return void
   */
  public function get_UserEmail( $eid )
  {
    return $this->db->query_DB("SELECT Email
                                FROM `Members`
                                WHERE `EID` = '$eid'"
                              )[0]['Email'];
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
      <h1 id="page-title" tabindex="-1" role="heading" aria-level="1">Cancel My RSVP</h1>
      <hr>
    ';

    if( empty( $get['code'] ) && !isset( $get['submitted'] ) )
    {
      $this->Display_Received( $get );
    }
    else if( !empty( $get['code'] ) && !isset( $get['submitted'] ) )
    {
      $this->Display_Form( $get );
    }
    else if( $get['submitted'] === "1" )
    {
      $this->Display_Processed( $get );
    }

    parent::Display();
  }

  /**
   * Display_Form - Shows the form to ask for input
   *
   * @param  mixed $get
   *
   * @return void
   */
  public function Display_Form( $get )
  {
    $this->content .= '
        <h2>
        Please provide the following information.
        </h2>

        <br>

        <!-- Form STARTS here -->

        <form class="container" method="POST" id="cancelRSVPForm">

          <p><strong> Note: All fields marked with an asterisk ( <label class="text-danger">*</label> ) are required.</strong></p>
          <input name="submitted" type="hidden" value="1">

          <div class="form-group">
            <label for="reason"> <label class="text-danger">*</label> I want to cancel my RSVP for the following reason:</label>
            <div class="input-group">
              <span class="input-group-addon">
                <i class="glyphicon glyphicon-question-sign"></i>
              </span>
              <select onchange="showDetails(this.value)" name="reason" class="form-control" id="reason" aria-required="true">
                <option></option>
                <option>No longer interested</option>
                <option>Scheduling conflict</option>
                <option>I didn\'t sign up for this event</option>
                <option>Other</option>
              </select>
            </div>
          </div>

          <div class="form-group" id="details">
            <label for="details"> <label class="text-danger">*</label> Details:</label>
            <div class="input-group">
              <span class="input-group-addon">
                <i class="glyphicon glyphicon-pencil"></i>
              </span>
              <textarea name="details" rows="5" type="text" class="form-control" id="details" placeholder="Please provide details." aria-required="true"></textarea>
            </div>
          </div>

          <hr>

          <input class="btn btn-primary" type="submit" value="Submit">
          <input class="btn btn-primary" type="reset"  value="Clear">
    
        </form>
        <!-- Form ENDS here -->

        <script>
          var textArea = document.querySelector("#details");
          textArea.style.display = "none";

          function showDetails( selected )
          {
            var textArea = document.querySelector("#details");
            if( selected == "Other" || selected == "No longer interested" )
            {
              textArea.style.display = "block";
            }
            else
            {
              textArea.style.display = "none";
            }
          }

          //**************  Inline Error Messages ************** //
          $(document).ready(function()
          {
            $(\'#cancelRSVPForm\').bootstrapValidator(
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
                  reason:
                  {
                      validators:
                      {
                          notEmpty:
                          {
                              message: \'ERROR: Please select the reason.\'
                          }
                      }
                  },
                  details:
                  {
                      validators:
                      {
                          notEmpty:
                          {
                              message: \'ERROR: Please provide details.\'
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
    ';
  }
  
  /**
   * Display_Processed - Cancels the RSVP in the system.
   *
   * @param  mixed $get
   *
   * @return void
   */
  public function Display_Processed( $posted )
  {
    $data = null;

    $eventData = $this->get_EventData( $_GET['id'] );

    // -- Gather the data to pass to the e-mail function --
    // Event ID, Event Name, Event Date, Location, Enterprise ID, RSVP ID
    $data[0]   = $eventData['ID'];
    $data[1]   = $eventData['Name'];
    $data[2]   = $eventData['Date'];
    $data[3]   = $eventData['Location'];
    $data[4]   = $_GET['eid'];
    $data[5]   = $_GET['rsvpid'];
    $data[6]   = $posted['reason'];
    $data[7]   = $posted['details'];
    $data[8]   = $this->get_UserEmail( $_GET['eid'] );

    $success = $this->cancelRSVP( $data );

    if( $success === True )
    {
      $this->mailer->notifyRSVPCancelled( $data );

      $this->content .= '
        <h2>
        Your cancellation request has been processed.
        </h2>

        <br>

        <p class="text-success">
          An confirmation e-mail has been sent to \'' . $data[8] . '\'.<br>
          Please keep it for your records.
        </p>
      ';
    }
    else
    {
      $this->content .= '<div class="alert alert-danger alert-dismissible" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span></button>
      Error: ' . $success . '</div>';
    }
  }
  
  /**
   * Display_Received - Shows a message after sending confirmation e-mail
   *
   * @param  mixed $get
   *
   * @return void
   */
  public function Display_Received( $get )
  {
    $data = null;

    $eventData = $this->get_EventData( $get['id'] );

    // -- Gather the data to pass to the e-mail function --
    // Event ID, Event Name, Event Date, Location, Enterprise ID, RSVP ID
    $data[0]   = $eventData['ID'];
    $data[1]   = $eventData['Name'];
    $data[2]   = $eventData['Date'];
    $data[3]   = $eventData['Location'];
    $data[4]   = $get['eid'];
    $data[5]   = $get['rsvpid'];
    $data[6]   = "You will be prompted for the reason later.";
    $data[7]   = $this->get_UserEmail( $get['eid'] ); 

    $success = $this->mailer->cancelRSVP( $data );

    if( $success === True )
    {
      $this->content .= '
        <h2>
        Your cancellation request has been received.
        </h2>

        <br>

        <p class="text-danger">
          An e-mail has been sent to \'' . $data[7] . '\' for you to confirm this cancellation.<br>
          Please follow the instructions to complete the request.
        </p>
        
        <br>

        <table style="width:50%">

        <tr>
          <td style="width:50%">
            <strong>Event Name:</strong>
          </td>
          <td>'
            . $data[1] . '
          </td>
        </tr>

        <tr>
          <td>
            <strong>Event Date:</strong>
          </td>
          <td>'
            . $data[2] . '
          </td>
        </tr>

        <tr>
          <td>
            <strong>Event Location:</strong>
          </td>
          <td>'
            . $data[3] . '
          </td>
        </tr>

        <tr>
          <td>
            <strong>Event Reservation:</strong>
          </td>
          <td>'
            . $data[4] . '
          </td>
        </tr>

        </table>
      ';
    }
    else
    {
      $this->content .= '<div class="alert alert-danger alert-dismissible" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span></button>
      Mailer Error: ' . $success . '</div>';
    }
  }

}

?>