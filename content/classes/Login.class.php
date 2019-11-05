<?php

require_once 'autoloader.php';

class Login extends Page
{
  
  //------------------------- Attributes -------------------------
  private $db = null;

  private $loginType = null;
  private $loginURL  = null;

  public $content = '<br><br>';
  public $title = "Event Manager - Login";
  public $keywords = "event manager, login";
  
  //------------------------- Operations -------------------------
  
  /**
   * __construct
   *
   * @return void
   */
  public function __construct()
  {
    $this->db = new Database();

    if( file_exists('settings.php') )
    {
      require 'settings.php';
      $this->loginType = $LOGINTYPE;
      $this->loginURL  = $LOGINURL;
    }

    parent::__construct();
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
    // If using Active Directory
    if( $this->loginType === 'AD' )
    {
      $success = $this->loginAD( $posted );

      if( isset( $success[0]['Role'] ) )
      {
        $this->setupCookie( $success[0] );
      }
    }
    // If using Azure Active Directory
    else if( $this->loginType === 'AzureAD' )
    {
      $success = $this->loginAZD( $posted );
    }
    // If using in App authentication
    else
    {
      $success = $this->login( $posted );
    }    
    
    if( $success === True )
    {
      $this->content .= '
      <div class="container alert alert-success alert-dismissible" role="alert"">
        <button type = "button" class="close" data-dismiss = "alert">x</button>
          Success!
      </div>
      ';
      
      if( $_SESSION['userRole'] == '1' )
      {
        echo '
        <script>
          window.location = "index.php?display=Approver";
        </script>
        ';
      }
      if( $_SESSION['userRole'] == '2' )
      {
        echo '
        <script>
          window.location = "index.php?display=Poc";
        </script>
        ';
      }
      if( $_SESSION['userRole'] == '3' )
      {
        echo '
        <script>
          window.location = "index.php?display=Admin";
        </script>
        ';
      }
      if( $_SESSION['userRole'] == '4' )
      {
        echo '
        <script>
          window.location = "index.php?display=GlobalAdmin";
        </script>
        ';
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
   * login - Handles the login validation when using in App authentication.
   *
   * @param  mixed $posted
   *
   * @return void
   */
  private function login( $posted )
  {
    // Encrypt password with MD5->SHA1->SHA256
    $password = hash( 'sha256', SHA1( MD5( $posted['password'] ) ) );

    // Check if the username & password combination exists
    $check = $this->db->query_DB("SELECT COUNT(`Username`) as Count
                                  FROM `Users`
                                  WHERE `Username` = '" . $posted['username'] . "'
                                  AND `Password`   = '" . $password           . "'");
    
    if( $check[0]['Count'] == 1 )
    {
      $data = $this->db->query_DB("SELECT `Username`, `Role`
                                   FROM `Users`
                                   WHERE `Username` = '" . $posted['username'] . "'
                                   AND `Password`   = '" . $password           . "'");

      return $this->setupCookie( $data[0] );
    }
    else
    {
      return 'The combination provided is incorrect.';
    }
  }

  private function setupCookie( $data )
  {
    // Initialize the session
    session_start();

    $_SESSION['userID']   = $data['Username'];
    $_SESSION['userRole'] = $data['Role'];

    // Extend cookie life time
    // A month in seconds = 30 days * 24 hours * 60 mins * 60 secs
    $cookieLifetime = 30 * 24 * 60 * 60;
    setcookie("Event-Manager-Global", session_id(), time() + $cookieLifetime);

    return True;
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
    if( isset( $posted['username'] ) )
    {
      $this->handlePOST( $posted );
    }

    // Set the page header
    $this->content .= '
      <h1 id="page-title" tabindex="-1" role="heading" aria-level="1">Login</h1>
      <hr>
    ';

    $this->content .= '
    <!-- Form STARTS here -->
    <form class="container" method="POST" id="loginPage">

      <p><strong>All fields marked with an asterisk ( <label class="text-danger">*</label> ) are required. </strong></p>

      <div class="form-group">
        <label for="username"> <label class="text-danger">*</label> Enterprise ID</label>
        <div class="input-group">
          <span class="input-group-addon">
            <i class="glyphicon glyphicon-user"></i>
          </span>
          <input name="username" type="text" class="form-control" id="username" placeholder="john.p.doe" aria-describedby="usernameHelp" required>
        </div>
        <small id="usernameHelp" class="sr-only form-text text-muted">Use your enterprise ID only, don\'t include "@company.com"</small>
      </div>

      <div class="form-group">
        <label for="Password"> <label class="text-danger">*</label> Password</label>
        <div class="input-group">
          <span class="input-group-addon">
            <i class="glyphicon glyphicon-lock"></i>
          </span>
          <input name="password" type="password" class="form-control" id="password" placeholder="password" required>
        </div>
      </div>

      <!--Login Button-->
      <div>
        <input class="btn btn-primary" type="submit" value="Submit">
      </div>

    </form>

    <!-- ******************* END FORM ******************* -->

    <script type="text/javascript">
      $(document).ready(function()
      {
        $(\'#loginPage\').bootstrapValidator({
            // To use feedback icons, ensure that you use Bootstrap v3.1.0 or later
            feedbackIcons:
            {
                valid: \'glyphicon glyphicon-ok\',
                invalid: \'glyphicon glyphicon-remove\',
                validating: \'glyphicon glyphicon-refresh\'
            },
            fields:
            {
                username:
                {
                    validators:
                    {
                        notEmpty:
                        {
                            message: \'ERROR: Please enter your Enterprise ID.\'
                        }
                    }
                },
                password:
                {
                    validators:
                    {
                        notEmpty:
                        {
                            message: \'ERROR: Please enter your password.\'
                        }
                    }
                },
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

    parent::Display();
  }

}

?>