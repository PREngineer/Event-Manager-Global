<?php

require_once 'autoloader.php';

class POCPendingClosure extends Page
{

  //------------------------- Attributes -------------------------
  private $db = null;
  public $content = '<br><br>';
  public $title = "Event Manager - Events Pending Closure";
  public $keywords = "event manager, events pending closure";

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

  private function get_MyEventsPendingAction()
  {
    $date = date('Y-m-d');

    return $this->db->query_DB("SELECT `ID`, `Name`, `Date`, `Created`, `Creator_User_ID`, `Person_Code`, `Remote_Code`,
                                    `Approved`, `Estimated_Budget`, `Actual_Budget`, `Deleted`
                                FROM `Events`
                                WHERE `Creator_User_ID` = '" . $_SESSION['userID'] . "'
                                AND `Date` <= '$date'
                                AND `Approved` = '1'
                                AND `Deleted` = '0'
                                AND `Actual_Budget` IS NULL
                                ORDER BY `Date` DESC"
                              );
  }

  private function get_EventData( $id )
  {
    $date = date('Y-m-d');

    return $this->db->query_DB("SELECT `ID`, `Name`, `Date`, `Start`, `End`, `Location`, `Address`, `Created`, `Creator_User_ID`, `Person_Code`, `Remote_Code`,
                                  `Approved`, `Estimated_Budget`, `Actual_Budget`, `Deleted`
                                FROM `Events`
                                WHERE `ID` = '$id'"
                              )[0];
  }

  /****************
      Core Functionality Functions
  ****************/

  private function closeEvent( $data )
  {
    return $this->db->query_DB("UPDATE `Events`
                                    SET `Actual_Budget` = '" . $data['Actual_Budget'] . "'
                                    WHERE `ID` = " . $data['ID']
                                  );
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
      <h1 id="page-title" tabindex="-1" role="heading" aria-level="1">Events Pending Closure</h1>
      <hr>
    ';

    if( !isset( $_GET['id'] ) && !isset( $get['submitted'] ) )
    {
      $this->Display_List();
    }
    else if( isset( $_GET['id'] ) && !isset( $get['submitted'] ) )
    {
      $this->Display_Form( $get );
    }
    else
    {
      $this->Display_Processed( $get );
    }

    parent::Display();
  }

  public function Display_List( )
  {
    $events = $this->get_MyEventsPendingAction();
    
    $this->content .= '
    <ol class="breadcrumb">
      <li>
        <a href="index.php?display=POCMenu" style="cursor:pointer;">
          <i class="glyphicon glyphicon-arrow-left"></i> POC Menu
        </a>
      </li>
    </ol>

    <div class="panel panel-default">

      <!-- Default panel contents -->
      <div class="panel-heading">' . $_SESSION['userID'] . ', here are all the open past events that do not have their Actual Budget and need closing.</div>
      <div class="panel-body">
        <i class="glyphicon glyphicon-flag" title="Close Event" style="color:orange; padding-left:2em"></i> = Provide Actual Budget and Close
      </div>
    </div>

    <div class="table-responsive">
    <!-- Table -->
    <table class="table">

      <thead>
        <th>
          Options
        </th>

        <th>
          Name
        </th>

        <th>
          Date
        </th>

        <th>
          Created
        </th>

        <th>
          Creator
        </th>

        <th>
          <i class="glyphicon glyphicon-user" title="In Person Code" style="color:black"></i> Code
        </th>

        <th>
          <i class="glyphicon glyphicon-headphones" title="Remote Code" style="color:black"></i> Code
        </th>

        <th>
          Approved
        </th>

        <th>
          <i class="glyphicon glyphicon-flag" title="Estimated Budget" style="color:blue">
            <i class="glyphicon glyphicon-usd" title="Estimated Budget" style="color:black"></i>
          </i>
        </th>

        <th>
          <i class="glyphicon glyphicon-flag" title="Actual Budget" style="color:green">
            <i class="glyphicon glyphicon-usd" title="Actual Budget" style="color:black"></i>
          </i>
        </th>

        <th>
          Deleted
        </th>

      </thead>';

      foreach ($events as $key => $value)
      {
        $this->content .= '
        <tr>

          <td>
            <a href="index.php?display=POCCloseEvent&id=' . $value[0] . '" style="cursor:pointer;">
              <i class="glyphicon glyphicon-flag" title="Edit" style="color: orange"></i>
            </a>
          </td>

          <td>
          ' . $value[1] . '
          </td>

          <td>
          ' . $value[2] . '
          </td>

          <td>
          ' . $value[3] . '
          </td>

          <td>
          ' . $value[4] . '
          </td>

          <td>
          ' . $value[5] . '
          </td>

          <td>
          ' . $value[6] . '
          </td>

          <td>
        ';

        if( $value[7] == 1 )
        {
          $this->content .= '
              <i class="glyphicon glyphicon-ok-sign" title="Yes" style="color:green"></i>
          ';
        }
        else
        {
          $this->content .= '
              <i class="glyphicon glyphicon-remove-sign" title="No" style="color:red"></i>
          ';
        }

        $this->content .= '
          </td>

          <td>
          ' . $value[8] . '
          </td>

          <td>
        ';
        if( $value[9] == "" )
        {
          $this->content .= '
              <i class="glyphicon glyphicon-remove-sign" title="No" style="color:red"></i>
        ';
        }
        else
        {
          $this->content .=  $value[9];
        }

        $this->content .= '
          </td>

          <td>
        ';

        if( $value[10] == 1 )
        {
          $this->content .= '
              <i class="glyphicon glyphicon-ok-sign" title="Yes" style="color:green"></i>
          ';
        }
        else
        {
          $this->content .= '
              <i class="glyphicon glyphicon-remove-sign" title="No" style="color:red"></i>
          ';
        }

        $this->content .= '
          </td>

        </tr>';
      }
    
      $this->content .= '
    </table>
    </div>
    ';
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

    // Display only if the event is happening
    if( $data['Actual_Budget'] === NULL )
    {
      // Set the page header
      $this->content .= '
        <div class="panel panel-default">
          <div class="panel-heading">
            You are closing the following event:
          </div>
          <table class="table">
            <tr>
              <td><b>Event Name</b></td>
              <td>' . $data['Name'] . '</td>
            </tr>
            <tr>
              <td><b>Event Start</b></td>
              <td>' . date( 'Y-m-d h:i:sA', strtotime($data['Date'] . ' ' . $data['Start']) ) . '</td>
            </tr>
            <tr>
              <td><b>Event End</b></td>
              <td>' . date( 'Y-m-d h:i:sA', strtotime($data['Date'] . ' ' . $data['End']) ) . '</td>
            </tr>
            <tr>
              <td><b>Location</b></td>
              <td>' . $data['Location'] . '</td>
            </tr>
            <tr>
              <td><b>Address</b></td>
              <td>' . $data['Address'] . '</td>
            </tr>
            <tr>
              <td><b>Estimated Budget</b></td>
              <td>$' . $data['Estimated_Budget'] . '</td>
            </tr>
          </table>
        </div>

        <form class="container" method="POST" id="closeEventForm">
          <input name="submitted" type="hidden" value="1">

          <p><strong>All fields marked with an asterisk ( <label class="text-danger">*</label> ) are required. </strong></p>

          <div class="form-group">
            <label for="actualBudget"><label class="text-danger">*</label> Actual Budget:</label>
            <div class="input-group">
              <span class="input-group-addon">
                <i class="glyphicon glyphicon-usd"></i>
              </span>
              <input name="actualBudget" type="text" class="form-control" id="actualBudget"
              placeholder="1234.56" aria-describedby="actualBudgetHelp" aria-required="true">
            </div>
            <small id="actualBudgetHelp" class="form-text text-muted">Do not include commas.</small>
          </div>

          <div>
            <input class="btn btn-primary" type="submit" value="Submit">
            <input class="btn btn-primary" type="reset"  value="Clear">  
          </div>

        </form>

        <script type="text/javascript">
          $(document).ready(function()
          {
            $(\'#closeEventForm\').bootstrapValidator(
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
                    actualBudget:
                    {
                        validators:
                        {
                            notEmpty:
                            {
                                message: \'ERROR: Please enter the Actual Budget.\'
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
    else
    {
      $this->content .= 'The specified event is already closed.';
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
    
    // -- Gather the data to pass to the e-mail function --
    $data['ID']            = $_GET['id'];
    $data['Actual_Budget'] = $posted['actualBudget'];
    
    $success = $this->closeEvent( $data );

    // If successful
    if( $success === True )
    {
      $this->content .= '
      <div class="text-success">
        <h2>
          The event has been updated.
        </h2>
      </div>
      ';
    }
    // Errors occurred
    else
    {
      $this->content .= '
      <div class="text-danger">
        An error occurred while updating the event, please try again later.
        <br><br>
        Error: ' . $success . '
        <br><br>
        Click <a href="">Here</a> to try again.
      </div>';
    }
  }

}

?>
