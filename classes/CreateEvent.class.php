<?php

require_once 'autoloader.php';

protectPOC();

class CreateEvent extends Page
{
  
  //------------------------- Attributes -------------------------
  private $db      = null;

  public $content  = '<br><br>';
  public $title    = "Event Manager - Create Event";
  public $keywords = "event manager, create event";
  
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
   * createEvent - Creates a new event entry.
   *
   * @param  mixed $data
   *
   * @return bool|string Success|Error Message
   */
  private function createEvent( $data )
  {
    $code1 = substr( MD5( date('Y-m-d H:i:s') ), 0, 6 );
    $code2 = substr( MD5( date('Y-m-d H-i-s') ), 0, 6 );

    return $this->db->query_DB("INSERT INTO `Events`
                                  (`Org_ID`, `Name`, `Overview`, `Date`, `Start`, `End`, `Location`, `Address`, `Estimated_Budget`, `Committee_ID`, `Target`,
                                    `Type`, `Objective`, `Creator_User_ID`, `Person_Code`, `Remote_Code`)
                                VALUES (
                                  '" . $data['org']              . "', 
                                  '" . $data['eventName']        . "', 
                                  '" . $data['overview']         . "', 
                                  '" . $data['eventDate']        . "', 
                                  '" . $data['start']            . ":00', 
                                  '" . $data['end']              . ":00', 
                                  '" . $data['location']         . "', 
                                  '" . $data['address']          . "', 
                                  '" . $data['estimatedBudget']  . "', 
                                  '" . $data['sponsorCommittee'] . "', 
                                  '" . $data['eventTarget']      . "', 
                                  '" . $data['eventType']        . "', 
                                  '" . $data['eventObjective']   . "', 
                                  '" . $data['creator']          . "', 
                                  '" . $code1                    . "', 
                                  '" . $code2                    . "' )"
                              );
  }

  /**
   * get_Committees - Retrieve all of the commitees.
   *
   * @return void
   */
  private function get_Committees()
  {
    return $this->db->query_DB("SELECT * FROM `Sponsor Committees`");
  }

  /**
   * get_EventObjectives - Retrieves all of the Event Objectives in the system.
   *
   * @return mixed
   */
  private function get_EventObjectives()
  {
    return $this->db->query_DB("SELECT * FROM `Event Objectives`");
  }
  
  /**
   * get_EventTargets - Retrieves all of the Event Targets in the system.
   *
   * @return mixed
   */
  private function get_EventTargets()
  {
    return $this->db->query_DB("SELECT * FROM `Event Targets`");
  }
  
  /**
   * get_EventTypes - Retrieves all of the Event Types in the system.
   *
   * @return mixed
   */
  private function get_EventTypes()
  {
    return $this->db->query_DB("SELECT * FROM `Event Types`");
  }

  /**
   * getOrgs - Retrieves all of the Orgs in the system.
   *
   * @return mixed
   */
  private function getOrgs()
  {
    return $this->db->query_DB("SELECT O.Symbol, O.Name
                                FROM Orgs O
                                WHERE SYMBOL <> 'GLOBAL'
                                ORDER BY O.Symbol ASC
                              ");
  }

  /**
   * handlePOST - Does the actual event creation.
   *
   * @param  mixed $data
   *
   * @return void
   */
  private function handlePOST( $data )
  {
    $success = $this->createEvent( $data );
    
    if( $success === True )
    {
      $this->content .= '
      <div class="container alert alert-success alert-dismissible" role="alert"">
        <button type = "button" class="close" data-dismiss = "alert">x</button>
          Success!
          <hr>
          Your event has been registered!
      </div>
      ';
    }
    else
    {
      $this->content .= '
      <div class="container alert alert-danger alert-dismissible" role="alert"">
        <button type="button" class="close" data-dismiss="alert">x</button>
        Failure!
        <hr>
        ' . $success . '
      </div>';
    }
  }

  /**
   * Display - Displays the full page
   *
   * @param  mixed $filter
   *
   * @return void
   */
  public function Display( $data )
  {
    // Handle interactions
    if( isset( $data['eventName'] ) )
    {
      $this->handlePOST( $data );
    }

    $committees = $this->get_Committees();
    $targets    = $this->get_EventTargets();
    $types      = $this->get_EventTypes();
    $objectives = $this->get_EventObjectives();
    $orgs       = $this->getOrgs();
    
    // Set the page header
    $this->content .= '
    <h1 id="page-title" tabindex="-1" role="heading" aria-level="1">Create Event</h1>
    <hr>';

    if( $_SESSION['userRole'] == 2 )
    {
    $this->content .= '
          <ol class="breadcrumb">
            <li>
              <a href="index.php?display=POCMenu" style="cursor:pointer;">
                <i class="glyphicon glyphicon-arrow-left"></i> POC Menu
              </a>
            </li>
            <li>
              <a href="index.php?display=MyEvents" style="cursor:pointer;">
                My Events
              </a>
            </li>
          </ol>';
    }

    if( $_SESSION['userRole'] == 3 )
    {
    $this->content .= '
          <ol class="breadcrumb">
            <li>
              <a href="index.php?display=Admin" style="cursor:pointer;">
                <i class="glyphicon glyphicon-arrow-left"></i> Admin
              </a>
            </li>
            <li>
              <a href="index.php?display=Events" style="cursor:pointer;">
                All Events
              </a>
            </li>
          </ol>';
    }

    $this->content .= '
    <!-- Form STARTS here -->

    <form class="container" method="POST" id="createEventForm">';

    $this->content .= '
      <p><strong> Note: All fields marked with an asterisk ( <label class="text-danger">*</label> ) are required.</strong></p>

      <div class="form-group">
        <label for="creator"> <label class="text-danger">*</label> Event Owner\'s Enterprise ID:</label>
        <div class="input-group">
          <span class="input-group-addon">
            <i class="glyphicon glyphicon-user"></i>
          </span>
          <input name="creator" type="text" class="form-control" id="creator"
          placeholder="john.p.doe" aria-describedby="enterpriseIDHelp" aria-required="true" value="' . $_SESSION['userID'] . '">
        </div>
      <small id="enterpriseIDHelp" class="form-text text-muted">Use your enterprise ID only, don\'t include "@company.com"</small>
      </div>';

      // Global admins get to pick which Org the new event is for
      if( $_SESSION['userRole'] == 4 )
      {
          $this->content .= '
      <div class="form-group">
        <label for="org"><label class="text-danger">*</label> Organization</label>
        <div class="input-group">
          <span class="input-group-addon">
            <i class="glyphicon glyphicon-th-list"></i>
          </span>
          <select name="org" class="form-control" id="org" required>
            <option></option>
            ';
      
        foreach( $orgs as $org )
        {
        $this->content .= '<option value="'. $org['Symbol'] . '">'. $org['Symbol'] . '</option>
            ';
        }

        $this->content .= '
          </select>
        </div>
      </div>';
      }
      else
      {
        $this->content .= '
        <input type="hidden" name="org" value="' . $_SESSION['Org_ID'] . '">
        ';
      }

        $this->content .= '
      <div class="form-group">
        <label for="eventName"> <label class="text-danger">*</label> Event Name:</label>
        <div class="input-group">
          <span class="input-group-addon">
            <i class="glyphicon glyphicon-pencil"></i>
          </span>
          <input name="eventName" type="text" class="form-control" id="eventName" placeholder="e.g. Professional Development" aria-required="true">
        </div>
      </div>

      <div class="form-group">
        <label for="overview"> <label class="text-danger">*</label> Event Overview:</label>
        <div class="input-group">
          <span class="input-group-addon">
            <i class="glyphicon glyphicon-pencil"></i>
          </span>
          <textarea name="overview" rows="5" type="text" class="form-control" id="overview" placeholder="e.g. This is a brief description of the event." aria-required="true"></textarea>
        </div>
      </div>

      <div class="form-group">
        <label for="eventDate"> <label class="text-danger">*</label> Event Date:</label>
        <div class="input-group">
          <span class="input-group-addon">
            <i class="glyphicon glyphicon-calendar"></i>
          </span>
          <input name="eventDate" class="form-control" type="text" id="eventDate" placeholder="YYYY-MM-DD" aria-required="true">
        </div>
      </div>

      <div class="form-group">
        <label for="start"> <label class="text-danger">*</label> Event Start Time:</label>
        <div class="input-group">
          <span class="input-group-addon">
            <i class="glyphicon glyphicon-time"></i>
          </span>
          <input name="start" class="form-control" type="text" id="start" placeholder="12:00" aria-required="true">
        </div>
      </div>

      <div class="form-group">
        <label for="end"> <label class="text-danger">*</label> Event End Time:</label>
        <div class="input-group">
          <span class="input-group-addon">
            <i class="glyphicon glyphicon-time"></i>
          </span>
          <input name="end" class="form-control" type="text" id="end" placeholder="17:00" aria-required="true">
        </div>
      </div>

      <div class="form-group">
        <label for="location"> <label class="text-danger">*</label> Event Location:</label>
        <div class="input-group">
          <span class="input-group-addon">
            <i class="glyphicon glyphicon-search"></i>
          </span>
          <input name="location" type="text" class="form-control" id="location" placeholder="Arlington Office" aria-required="true">
        </div>
      </div>

      <div class="form-group">
        <label for="address"> <label class="text-danger">*</label> Event Address:</label>
        <div class="input-group">
          <span class="input-group-addon">
            <i class="glyphicon glyphicon-pushpin"></i>
          </span>
          <input name="address" type="text" class="form-control" id="address" placeholder="123 First St., City, State, Country" aria-required="true">
        </div>
      </div>

      <div class="form-group">
        <label for="estimatedBudget"> <label class="text-danger">*</label> Estimated Budget:</label>
        <div class="input-group">
          <span class="input-group-addon">
            <i class="glyphicon glyphicon-usd"></i>
          </span>
          <input name="estimatedBudget" type="text" class="form-control" id="estimatedBudget" placeholder="1234.56" aria-describedby="estimatedBudgetHelp" aria-required="true">
        </div>
        <small id="estimatedBudgetHelp" class="form-text text-muted">Do not include commas.</small>
      </div>

      <div class="form-group">
        <label for="sponsorCommittee"> <label class="text-danger">*</label> Sponsor Committee:</label>
        <div class="input-group">
          <span class="input-group-addon">
            <i class="glyphicon glyphicon-th-list"></i>
          </span>
          <select name="sponsorCommittee" class="form-control" id="sponsorCommittee" aria-required="true">
            <option></option>
          ';

        foreach ($committees as $entry => $value)
        {
          $this->content .= ' <option>' . $value['Name'] . '</option>
          ';
        }

        $this->content .= '
          </select>
        </div>
      </div>

      <div class="form-group">
        <label for="eventTarget"> <label class="text-danger">*</label> Target:</label>
        <div class="input-group">
          <span class="input-group-addon">
            <i class="glyphicon glyphicon-th-list"></i>
          </span>
          <select onchange="changeTypes(this.value)" name="eventTarget" class="form-control" id="eventTarget" aria-required="true">
            <option></option>
        ';

        foreach ($targets as $entry => $value)
        {
          $this->content .= '<option>' . $value['Name'] . '</option>
          ';
        }

        $this->content .= '
          </select>
        </div>
      </div>

      <div class="form-group">
        <label for="eventType"> <label class="text-danger">*</label> Event Type:</label>
        <div class="input-group">
          <span class="input-group-addon">
            <i class="glyphicon glyphicon-th-list"></i>
          </span>
          <select onchange="changeObjectives(this.value)" name="eventType" class="form-control" id="eventType" aria-required="true">
            <option></option>
        ';
        
        foreach ($types as $entry => $value)
        {
          $this->content .= '<option>' . $value['Name'] . '</option>
          ';
        }

        $this->content .= '
          </select>
        </div>
      </div>

      <div class="form-group">
        <label for="eventObjective"> <label class="text-danger">*</label> Event Objective:</label>
        <div class="input-group">
          <span class="input-group-addon">
            <i class="glyphicon glyphicon-th-list"></i>
          </span>
          <select name="eventObjective" class="form-control" id="eventObjective" aria-required="true">
            <option></option>
          ';
          
          foreach ($objectives as $entry => $value)
          {
            $this->content .= '<option>' . $value['Name'] . '</option>
            ';
          }
      
          $this->content .= '
            </select>
        </div>
      </div>

      <hr>

      <input class="btn btn-primary" type="submit" value="Submit">
      <input class="btn btn-primary" type="reset"  value="Clear">

    </form>
    <!-- Form ENDS here -->
    ';

      
    $this->content .= '
    
    <!-------------- ALL Form related JavaScript -------------->

    <script type="text/javascript">

      //************** Date Picker Formatting **************//
      $(\'#eventDate\').datepicker(
      {
        format: "yyyy-mm-dd",
        startDate: \'+6d\',
        toggleActive: true,
        weekStart: 1,
        maxViewMode: 3,
        autoclose: true,
        daysOfWeekHighlighted: "1,2,3,4,5",
        todayHighlight: true
        }).on(\'changeDate\', function (e)
        {
          $(this).focus();
      });

      //************** Start Time Picker **************//
      $(function()
      {
        $(\'#start\').timepicker({ \'timeFormat\': \'H:i\' });
      });
      
      //************** End Time Picker **************//
      $(function()
      {
        $(\'#end\').timepicker({ \'timeFormat\': \'H:i\' });
      });
      
      //************** Passing event data to javascript for dropdown handling **************//

      // JavaScript array of Event Objectives
      var eventObjectives = [';

    // JavaScript array of Event Objectives
    for( $i=0; $i < sizeof( $objectives ); $i++ )
    {
      $this->content .= '
          {ID:"' . $objectives[$i]['ID'] . '", Type:"' . $objectives[$i]['Type'] . '", Name:"' . $objectives[$i]['Name'] . '"}';

      if( $i < sizeof($objectives)-1 )
      {
        $this->content .= ',
        ';
      }
    }
    
    $this->content .= '
        ];
    ';

    // JavaScript array of Event Types
    $this->content .= '
        // JavaScript array of Event Types
        var eventTypes = [';

    for( $i=0; $i < sizeof( $types ); $i++ )
    {
      $this->content .= '
          {ID:"' . $types[$i]['ID'] . '", Target_ID:"' . $types[$i]['Target_ID'] . '", Name:"' . $types[$i]['Name'] . '"}';

      if( $i < sizeof($types)-1 )
      {
        $this->content .= ',
        ';
      }
    }
    
    $this->content .= '
        ];
    ';
    
    // Creating a JavaScript array of Event Targets
    $this->content .= '
        // JavaScript array of Event Targets
        var eventTargets = [';

    for( $i=0; $i < sizeof( $targets ); $i++ )
    {
      $this->content .= '
          {ID:"' . $targets[$i]['ID'] . '", Name:"' . $targets[$i]['Name'] . '"}';

      if( $i < sizeof($targets)-1 )
      {
        $this->content .= ',
        ';
      }
    }
      
    $this->content .= '
      ];
  
      //************** Change Types based on selection of Target **************//
      // Mark the Dropdown to update
      var obj = document.getElementById("eventType");
      var target;

      function changeTypes(value)
      {
        // Remove all previous options
        while(obj.firstChild)
        {
          obj.removeChild(obj.firstChild);
        }
        if(obj.selectedIndex == 0)
        {
          return;
        }

        // Firstly, put the empty option on top
        var o = document.createElement("option");
        o.value = \'\';
        o.text = \'\';
        obj.appendChild(o);

        // Secondly, find the ID of the selected Event Target
        for(var i = 0; i < eventTargets.length; i++)
        {
          // Grab the id of the Event Target
          if(eventTargets[i].Name == value)
          {
            // To use in the next function
            target = eventTargets[i].ID;
            
            // Look for all Types that belong to that Event Target
            for(var j = 0; j < eventTypes.length; j++)
            {
              // If it belongs to that one, create an option
              if(eventTypes[j].Target_ID == eventTargets[i].ID)
              {
                o = document.createElement("option");
                o.value = eventTypes[j].Name;
                o.text = eventTypes[j].Name;
                obj.appendChild(o);
              }
            }
          }
        }
      }

      //************** Change Objectives based on selection of Type **************//
      // Mark the Dropdown to update
      var obj2 = document.getElementById("eventObjective");

      function changeObjectives(value)
      {
        // Remove all previous options
        while(obj2.firstChild)
        {
          obj2.removeChild(obj2.firstChild);
        }
        if(obj2.selectedIndex == 0)
        {
          return;
        }

        // First put the empty option on top
        var o = document.createElement("option");
        o.value = \'\';
        o.text = \'\';
        obj2.appendChild(o);

        // Check the Event Types
        for(var i = 0; i < eventTypes.length; i++)
        {
          // Grab the right Event Type
          if(eventTypes[i].Name == value && eventTypes[i].Target_ID == target)
          {
            // Look for all Objectives that belong to that Event Type
            for(var j = 0; j < eventObjectives.length; j++)
            {
              // If it belongs to that one, create an option
              if(eventObjectives[j].Type == eventTypes[i].ID)
              {
                o = document.createElement("option");
                o.value = eventObjectives[j].Name;
                o.text = eventObjectives[j].Name;
                obj2.appendChild(o);
              }
            }
          }
        }
      }

      //**************  Inline Error Messages ************** //
      $(document).ready(function()
      {
        $(\'#createEventForm\').bootstrapValidator(
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
                creator:
                {
                    validators:
                    {
                        notEmpty:
                        {
                            message: \'ERROR: Please enter your Enterprise ID.\'
                        }
                    }
                },';

      // If Global Admin
      if( $_SESSION['userRole'] == 4 )
      {
        $this->content .= '
                org:
                {
                    validators:
                    {
                        notEmpty:
                        {
                            message: \'ERROR: Please enter the org.\'
                        }
                    }
                },
        ';
      }
      
      $this->content .= '
                eventName:
                {
                    validators:
                    {
                        notEmpty:
                        {
                            message: \'ERROR: Please enter the Event Name.\'
                        }
                    }
                },
                overview:
                {
                    validators:
                    {
                        notEmpty:
                        {
                            message: \'ERROR: Please enter the Event Overview.\'
                        }
                    }
                },
                eventDate:
                {
                    // The hidden input will not be ignored
                    excluded: false,
                    validators:
                    {
                        notEmpty:
                        {
                            message: \'ERROR: Please enter the Event Date.\'
                        },
                        date:
                        {
                            format: \'yyyy-mm-dd\',
                            message: \'ERROR: The date format is not a valid. It should be YYY-mm-dd.\'
                        }
                    }
                },
                start:
                {
                    validators:
                    {
                        notEmpty:
                        {
                            message: \'ERROR: Please enter the Event Start Time.\'
                        }
                    }
                },
                end:
                {
                    validators:
                    {
                        notEmpty:
                        {
                            message: \'ERROR: Please enter the Event End Time.\'
                        }
                    }
                },
                location:
                {
                    validators:
                    {
                        notEmpty:
                        {
                            message: \'ERROR: Please select the Event Location.\'
                        }
                    }
                },
                address:
                {
                    validators:
                    {
                        notEmpty:
                        {
                            message: \'ERROR: Please select the Event Address.\'
                        }
                    }
                },
                estimatedBudget:
                {
                    validators:
                    {
                        notEmpty:
                        {
                            message: \'ERROR: Please enter the Estimated Budget.\'
                        }
                    }
                },
                sponsorCommittee:
                {
                    validators:
                    {
                        notEmpty:
                        {
                            message: \'ERROR: Please select the Sponsor Committee.\'
                        }
                    }
                },
                eventTarget:
                {
                    validators:
                    {
                        notEmpty:
                        {
                            message: \'ERROR: Please select the Event Target.\'
                        }
                    }
                },
                eventType:
                {
                    validators:
                    {
                        notEmpty:
                        {
                            message: \'ERROR: Please select the Event Type.\'
                        }
                    }
                },
                eventObjective:
                {
                    validators:
                    {
                        notEmpty:
                        {
                            message: \'ERROR: Please select the Event Objective.\'
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

    parent::Display();
  }

}

?>