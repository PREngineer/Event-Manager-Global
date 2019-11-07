<?php

require_once 'autoloader.php';

class CreateMember extends Page
{
  
  //------------------------- Attributes -------------------------
  private $db = null;
  public $content = '<br><br>';
  public $title = "Event Manager - Create New Member";
  public $keywords = "event manager, future events";
  
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
   * createMember - Creates a new member entry.
   *
   * @param  mixed $data
   *
   * @return bool|string Success|Error Message
   */
  private function createMember( $data )
  {
    // Check that the member doesn't exist already
    $exists = ( 
               $this->db->query_DB("SELECT COUNT(`EID`) AS Count
                                    FROM `Members`
                                    WHERE `EID` = '" . $data['enterpriseID'] . "'
                                  ")
              )[0]['Count'];

    // Create the member entry
    if( $exists == 0 )
    {
      return $this->db->query_DB("INSERT INTO `Members`
                                    (`EID`, `FName`, `Initials`,`LName`,`Level`,`Segment`,`Email`,`Newsletter`,`Volunteer`,`Active`,`Lead`,`Role`)
                                  VALUES ('" . $data['enterpriseID']  . "',
                                          '" . $data['firstName']     . "',
                                          '" . $data['initials']      . "',
                                          '" . $data['lastName']      . "',
                                          '" . $data['level']         . "',
                                          '" . $data['segment']       . "',
                                          '" . $data['email']         . "',
                                          '" . $data['newsletter']    . "',
                                          '" . $data['volunteer']     . "',
                                          '0',
                                          '0',
                                          '0')
                                    ");
    }
    else
    {
      return 'This member already exists in the system.';
    }
  }
  
  /**
   * getCareerLevels - Returns all the Career Levels
   *
   * @return mixed
   */
  private function getCareerLevels()
  {
    return $this->db->query_DB("SELECT *
                                FROM `Career Levels`
                              ");
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
   * getSegments - Returns all of the Company Segments
   *
   * @return void
   */
  private function getSegments()
  {
    return $this->db->query_DB("SELECT *
                                FROM `Company Segments`
                              ");
  }

  /**
   * handlePOST - Takes control of the action once the form is posted.
   *
   * @param  mixed $posted
   *
   * @return void
   */
  private function handlePOST( $posted )
  {
    $success = $this->createMember( $posted );
    
    if( $success === True )
    {
      $success = $this->linkToOrg( $posted );
    
      if( $success === True )
      {
        $this->content .= '
        <div class="container alert alert-success alert-dismissible" role="alert"">
          <button type = "button" class="close" data-dismiss = "alert">x</button>
            Success!
            <hr>
            Your membership has been registered!
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
   * linkToOrg - Creates an entry in the Membership table.
   *
   * @param  mixed $data
   *
   * @return void
   */
  private function linkToOrg( $data )
  {
    $Org_ID    = (
                 $this->db->query_DB("SELECT ID
                                      FROM Orgs
                                      WHERE Symbol = '" . $data['org'] . "'
                                    ")
                  )[0]['ID'];
    
    $Member_ID = (
                 $this->db->query_DB("SELECT ID
                                      FROM Members
                                      WHERE EID = '" . $data['enterpriseID'] . "'
                                    ")
                  )[0]['ID'];
                  
    return $this->db->query_DB("INSERT INTO `Membership`
                                       (`Org_ID`, `Member_ID`)
                                       VALUES ('" . $Org_ID . "','" . $Member_ID . "')
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
    // Handle data and give feedback
    if( isset( $posted['enterpriseID'] ) )
    {
      $this->handlePOST( $posted );
    }

    $segments = $this->getSegments();
    $careerLs = $this->getCareerLevels();
    $orgs     = $this->getOrgs();
    
    // Set the page header
    $this->content .= '
      <h1 id="page-title" tabindex="-1" role="heading" aria-level="1">New Member</h1>
      <hr>
    ';

    // Display the form
    $this->content .= '
    <!-- Form STARTS here -->
      <form class="container" method="POST" id="createMemberForm">
        
        <p><strong> Note: All fields marked with an asterisk ( <label class="text-danger">*</label> ) are required. </strong></p>

        <div class="form-group">
          <label for="org"><label class="text-danger">*</label> Organization</label>
          <div class="input-group">
            <span class="input-group-addon">
              <i class="glyphicon glyphicon-th-list"></i>
            </span>
            <select name="org" class="form-control" id="org" required>
              <option></option>';
  
    foreach( $orgs as $org )
    {
    $this->content .= '
            <option>'. $org['Symbol'] . '</option>';
    }

    $this->content .= '
            </select>
          </div>
        </div>

        <div class="form-group">
          <label for="enterpriseID"> <label class="text-danger">*</label> Enterprise ID</label>
          <div class="input-group">
            <span class="input-group-addon">
              <i class="glyphicon glyphicon-lock"></i>
            </span>
            <input name="enterpriseID" type="text" class="form-control" id="enterpriseID" placeholder="john.p.doe" aria-describedby="enterpriseIDHelp" required>
          </div>
          <small id="enterpriseIDHelp" class="form-text text-muted">Use your enterprise ID only, don\'t include "@company.com"</small>
        </div>

        <div class="form-group">
          <label for="firstName"> <label class="text-danger">*</label> First Name</label>
          <div class="input-group">
            <span class="input-group-addon">
              <i class="glyphicon glyphicon-user"></i>
            </span>
            <input name="firstName" type="text" class="form-control" id="firstName" placeholder="John" aria-describedby="firstNameHelp" required>
          </div>
        </div>

        <div class="form-group">
          <label for="initials"> Middle Name</label>
          <div class="input-group">
            <span class="input-group-addon">
              <i class="glyphicon glyphicon-user"></i>
            </span>
            <input name="initials" type="text" class="form-control" id="initials" placeholder="Paul or P" aria-describedby="initialsHelp">
          </div>
        </div>

        <div class="form-group">
          <label for="lastName"> <label class="text-danger">*</label> Last Name(s)</label>
          <div class="input-group">
            <span class="input-group-addon">
              <i class="glyphicon glyphicon-user"></i>
            </span>
            <input name="lastName" type="text" class="form-control" id="lastName" placeholder="Doe" aria-describedby="lastNameHelp" required>
          </div>
        </div>

        <div class="form-group">
          <label for="email"> <label class="text-danger">*</label> E-mail</label>
          <div class="input-group">
            <span class="input-group-addon">
              <i class="glyphicon glyphicon-envelope"></i>
            </span>
            <input name="email" type="email" class="form-control" id="email" placeholder="john.p.doe@company.com"
            aria-describedby="emailHelp" required>
          </div>
        </div>

        <div class="form-group">
          <label for="segment" style="margin-top:10px"><label class="text-danger">*</label> Company Segment</label>
          <div class="input-group form-control">';
    
    foreach( $segments as $one )
    {
      $this->content .= '
            <input name="segment" type="radio" id="segment" value="' . $one['Segment'] . '" required>&nbsp;&nbsp;' . $one['Segment'] . '<br>';
    }

    $this->content .= '
          </div>
        </div>

        <div class="form-group">
          <label for="level"><label class="text-danger">*</label> Career Level</label>
          <div class="input-group">
            <span class="input-group-addon">
              <i class="glyphicon glyphicon-th-list"></i>
            </span>
          <select name="level" class="form-control" id="level" required>
            <option></option>';
    
    foreach( $careerLs as $CL )
    {
      $this->content .= '
              <option>'. $CL['Level'] . '</option>';
    }
  
      $this->content .= '
          </select>
          </div>
        </div>

      <div class="form-group">
        <div class="input-group">
          <span class="input-group pull-left">
            <input name="newsletter" id="newsletter" type="hidden" value="0">
            <input name="newsletter" id="newsletter" type="checkbox" value="1">
            I want to be included in the Org\'s Weekly Newsletter.
          </span>
        </div>
      </div>

      <div class="form-group">
        <div class="input-group">
          <span class="input-group pull-left">
            <input name="volunteer" id="volunteer" type="hidden" value="0">
            <input name="volunteer" id="volunteer" type="checkbox" value="1">
            I want to be part of the Org\'s Volunteer Pool.<sup>*</sup>
          </span>
        </div>
      </div>

      <!-- ******************* Submit, Clear & Cancel Button ******************* -->
      <div class="form-group">
        <div class="input-group">
            <input class="btn btn-primary" type="submit" value="Submit">&nbsp;
            <button class="btn btn-default" type="reset">Clear</button>
        </div>
      </div>
      </form>

      <div class="form-group">
        <div class="input-group">
        <span><br><sup>*</sup>The volunteer pool is a list with all the members that would
          like to further help and contribute to the committees on any task related to
          the events, projects and/or voluntary service.<br></span>
        </div>
      </div>
      <!-- Form ENDS here -->

      <script type="text/javascript">

        $(document).ready(function()
        {
          $(\'#createMemberForm\').bootstrapValidator(
            {
              // To use feedback icons, ensure that you use Bootstrap v3.1.0 or later
              feedbackIcons:
              {
                valid: \'glyphicon glyphicon-ok\',
                invalid: \'glyphicon glyphicon-remove\',
                validating: \'glyphicon glyphicon-refresh\'
              },
              fields:
              {
                org:
                {
                    validators:
                    {
                        notEmpty:
                        {
                            message: \'ERROR: Please select the organization that you wish to join.\'
                        }
                    }
                },
                enterpriseID:
                {
                    validators:
                    {
                        notEmpty:
                        {
                            message: \'ERROR: Please enter your enterprise ID.\'
                        }
                    }
                },

                firstName:
                {
                    validators:
                    {
                        notEmpty:
                        {
                            message: \'ERROR: Please enter your first name.\'
                        }
                    }
                },

                lastName:
                {
                    validators:
                    {
                        notEmpty:
                        {
                            message: \'ERROR: Please enter your last name.\'
                        }
                    }
                },

                email:
                {
                    validators:
                    {
                        notEmpty:
                        {
                            message: \'ERROR: Please enter your e-mail.\'
                      }
                    }
                },

                segment:
                {
                    validators:
                    {
                        notEmpty:
                        {
                            message: \'ERROR: Please select your company segment.\'
                        }
                    }
                },

                level:
                {
                    validators:
                    {
                        notEmpty:
                        {
                            message: \'ERROR: Please select your career level.\'
                        }
                    }
                },
              }
            })

              .on(\'success.form.bv\', function(e)
              {
                  $(\'#success_message\').slideDown({ opacity: "show" }, "slow")
                      $(\'#createMemberForm\').data(\'bootstrapValidator\').resetForm();

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

    parent::Display();
  }

}

?>