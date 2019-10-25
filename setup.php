<!DOCTYPE html>
<html lang="en">

  <!-- ******************* Head Section ******************* -->
  <head>
    <!-- Application Name -->
    <title>Event Manager - Database Setup</title>

    <!-- Encoding and Mobile First -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap core CSS -->
    <link href="theme/css/bootstrap-theme.min.css" rel="stylesheet">
    <link href="theme/css/bootstrap.min.css" rel="stylesheet">
    <link href="theme/css/bootstrap-datepicker3.standalone.min.css" rel="stylesheet">
    <link href="theme/css/bootstrap-datepicker3.min.css" rel="stylesheet">

    <!-- Importing jQuery and other dependencies -->
    <script src="theme/js/jquery-3.2.1.min.js"></script>
    <script src="theme/js/bootstrap-datepicker.min.js"></script>
    <script src="theme/js/BootstrapValidator.min.js"></script>

    <!-- Bootstrap JavaScript -->
    <script src="theme/js/bootstrap.js"></script>
  </head>

  <body>

    <!-- ******************* START FORM ******************* -->
    <div class="container" id="Content" name="Content">

      <h1>Event Manager - Database Setup</h1>

      <form class="container" method="POST" id="setupDBForm">
        <hr>

        <p>
          Please provide the following details to set up the database for the Event Manager Platform.
        </p>

        <p>
          Important!<br>
          The user provided should be an administrator.<br>
          It will create a database and tables and it will set itself to work only on this Database
          in the future.
        </p>

        <div class="form-group">
          <label for="username">
            ERG's Name:
          </label>
          <div class="input-group">
            <span class="input-group-addon">
              <i class="glyphicon glyphicon-book"></i>
            </span>
            <input name="dbname" type="text" class="form-control" placeholder="DCHAERG" aria-describedby="dbnameHelp" required>
          </div>
      	   <small id="dbnameHelp" class="form-text text-muted">This is the ERG's identifier (name).  [Location][Identifier][ERG]  Ex: DCHAERG, DCAAERG</small>
        </div>

        <div class="form-group">
          <label for="username">
            DB Username:
          </label>
          <div class="input-group">
            <span class="input-group-addon">
              <i class="glyphicon glyphicon-user"></i>
            </span>
            <input name="username" type="text" class="form-control" placeholder="root" aria-describedby="usernameHelp" required>
          </div>
      	   <small id="usernameHelp" class="form-text text-muted">This is the database administrator username.</small>
        </div>

        <div class="form-group">
          <label for="password">
            DB Password:
          </label>
          <div class="input-group">
            <span class="input-group-addon">
              <i class="glyphicon glyphicon-lock"></i>
            </span>
            <input name="password" type="password" class="form-control" placeholder="password" aria-describedby="passwordHelp" required>
          </div>
      	   <small id="passwordHelp" class="form-text text-muted">This is the database administrator password.</small>
        </div>

        <div class="form-group">
          <label for="ip">
            DB IP/URL:
          </label>
          <div class="input-group">
            <span class="input-group-addon">
              <i class="glyphicon glyphicon-globe"></i>
            </span>
            <input name="ip" type="text" class="form-control" placeholder="localhost or sub.domain.com or 10.0.0.25" aria-describedby="ipHelp" required>
          </div>
      	   <small id="ipHelp" class="form-text text-muted">This is the database ip or url.</small>
        </div>

        <div class="form-group">
          <label for="port">
            DB Port:
          </label>
          <div class="input-group">
            <span class="input-group-addon">
              <i class="glyphicon glyphicon-asterisk"></i>
            </span>
            <input name="port" type="text" class="form-control" placeholder="3306" aria-describedby="portHelp" required>
          </div>
      	   <small id="portHelp" class="form-text text-muted">This is the database port.</small>
        </div>

        <div class="form-group">
          <input class="btn btn-primary" type="submit" value="Setup Database">
        </div>

        <hr>

      </form>

      <!-- Close the alerts after 15 seconds -->
      <script>
      window.setTimeout(function()
      {
          $(".alert").fadeTo(500, 0).slideUp(500, function()
          {
              $(this).remove();
          });
      }, 15000);
      </script>

      <?php

      include 'functions/DB.php';

      // If the POST has information,
      // Check if the information provided was correct.
      if( !empty($_POST) )
      {
        // Gather the information provided
        $dbname = $_POST['dbname'];
        $user   = $_POST['username'];
        $pass   = $_POST['password'];
        $host   = $_POST['ip'];
        $port   = $_POST['port'];

        $check = test_MySQL( $dbname, $user, $pass, $host, $port );

        // If the information is incorrect
        if($check !== True)
        {
          echo '<br><br>
                <div class="alert alert-danger alert-dismissible" role="alert">
                <button type = "button" class="close" data-dismiss = "alert">x</button>
                  [!] Could not connect to the MySQL Server.
                  <br><br>';
                  echo 'Error(s): ' . $check;
          echo '<br><br>
                  Please check that the information provided is correct.
                </div>';
        }
        // If the information is correct
        else
        {
          echo '<div class="alert alert-success alert-dismissible" role="alert">
          <button type = "button" class="close" data-dismiss = "alert">x</button>
                  --> Successfully connected to MySQL server!
                </div>';

          // Create Database
          $result = setup_DB( $dbname, $user, $pass, $host, $port );

          if($result == 1)
          {
            echo '<div class="alert alert-success alert-dismissible" role="alert">
            <button type = "button" class="close" data-dismiss = "alert">x</button>
                    --> Successfully created the database "' . $_POST['dbname'] . '".
                  </div>';
          }
          else
          {
            echo '<div class="alert alert-danger alert-dismissible" role="alert">
            <button type = "button" class="close" data-dismiss = "alert">x</button>
                    [!] ' . count($result['Errors']) . ' Error(s) occurred while creating the database: ' . $_POST['dbname'] . '!<br><br>' .
                    $result .
                  '</div>';
          }

          // Create Attendance Table
          $result = setup_AttendanceTable();

          if($result['Result'])
          {
            echo '<div class="alert alert-success alert-dismissible" role="alert">
            <button type = "button" class="close" data-dismiss = "alert">x</button>
                    --> Successfully created table "Attendance".
                  </div>';
          }
          else
          {
            echo '<div class="alert alert-danger alert-dismissible" role="alert">
            <button type = "button" class="close" data-dismiss = "alert">x</button>
                    [!] ' . count($result['Errors']) . ' Error(s) occurred while creating table!<br><br>' .
                    $result['Errors'] .
                  '</div>';
          }

          // Create Announcements Table
          $result = setup_AnnouncementsTable();

          if($result['Result'])
          {
            echo '<div class="alert alert-success alert-dismissible" role="alert">
            <button type = "button" class="close" data-dismiss = "alert">x</button>
                    --> Successfully created table "Announcements".
                  </div>';
          }
          else
          {
            echo '<div class="alert alert-danger alert-dismissible" role="alert">
            <button type = "button" class="close" data-dismiss = "alert">x</button>
                    [!] ' . count($result['Errors']) . ' Error(s) occurred while creating table!<br><br>' .
                    $result['Errors'] .
                  '</div>';
          }

          // Create Dimensional Event Objective Table
          $result = setup_DIMEventObjectiveTable();

          if($result['Result'])
          {
            echo '<div class="alert alert-success alert-dismissible" role="alert">
            <button type = "button" class="close" data-dismiss = "alert">x</button>
                    --> Successfully created table "DIM Event Objective".
                  </div>';
          }
          else
          {
            echo '<div class="alert alert-danger alert-dismissible" role="alert">
            <button type = "button" class="close" data-dismiss = "alert">x</button>
                    [!] ' . count($result['Errors']) . ' Error(s) occurred while creating table!<br><br>' .
                    $result['Errors'] .
                  '</div>';
          }

          // Create Dimensional Event Target Table
          $result = setup_DIMEventTargetTable();

          if($result['Result'])
          {
            echo '<div class="alert alert-success alert-dismissible" role="alert">
            <button type = "button" class="close" data-dismiss = "alert">x</button>
                    --> Successfully created table "DIM Event Target".
                  </div>';
          }
          else
          {
            echo '<div class="alert alert-danger alert-dismissible" role="alert">
            <button type = "button" class="close" data-dismiss = "alert">x</button>
                    [!] ' . count($result['Errors']) . ' Error(s) occurred while creating table!<br><br>' .
                    $result['Errors'] .
                  '</div>';
          }
          
          // Create Dimensional Event Type Table
          $result = setup_DIMEventTypeTable();

          if($result['Result'])
          {
            echo '<div class="alert alert-success alert-dismissible" role="alert">
            <button type = "button" class="close" data-dismiss = "alert">x</button>
                    --> Successfully created table "DIM Event Type".
                  </div>';
          }
          else
          {
            echo '<div class="alert alert-danger alert-dismissible" role="alert">
            <button type = "button" class="close" data-dismiss = "alert">x</button>
                    [!] ' . count($result['Errors']) . ' Error(s) occurred while creating table!<br><br>' .
                    $result['Errors'] .
                  '</div>';
          }

          // Create Dimensional Committee Table
          $result = setup_DIMERGTable();

          if($result['Result'])
          {
            echo '<div class="alert alert-success alert-dismissible" role="alert">
            <button type = "button" class="close" data-dismiss = "alert">x</button>
                    --> Successfully created table "DIM ERG".
                  </div>';
          }
          else
          {
            echo '<div class="alert alert-danger alert-dismissible" role="alert">
            <button type = "button" class="close" data-dismiss = "alert">x</button>
                    [!] ' . count($result['Errors']) . ' Error(s) occurred while creating table!<br><br>' .
                    $result['Errors'] .
                  '</div>';
          }

          // Create Events Table
          $result = setup_EventsTable();

          if($result['Result'])
          {
            echo '<div class="alert alert-success alert-dismissible" role="alert">
            <button type = "button" class="close" data-dismiss = "alert">x</button>
                    --> Successfully created table "Events".
                  </div>';
          }
          else
          {
            echo '<div class="alert alert-danger alert-dismissible" role="alert">
            <button type = "button" class="close" data-dismiss = "alert">x</button>
                    [!] ' . count($result['Errors']) . ' Error(s) occurred while creating the Events table!<br><br>' .
                    $result['Errors'] .
                  '</div>';
          }

          // Create Event Change Log Table
          $result = setup_EventChangeLogTable();

          if($result['Result'])
          {
            echo '<div class="alert alert-success alert-dismissible" role="alert">
            <button type = "button" class="close" data-dismiss = "alert">x</button>
                    --> Successfully created table "Event Change Log".
                  </div>';
          }
          else
          {
            echo '<div class="alert alert-danger alert-dismissible" role="alert">
            <button type = "button" class="close" data-dismiss = "alert">x</button>
                    [!] ' . count($result['Errors']) . ' Error(s) occurred while creating table!<br><br>' .
                    $result['Errors'] .
                  '</div>';
          }

          // Create Leads Table
          $result = setup_LeadsTable();

          if($result['Result'])
          {
            echo '<div class="alert alert-success alert-dismissible" role="alert">
            <button type = "button" class="close" data-dismiss = "alert">x</button>
                    --> Successfully created table "Leads".
                  </div>';
          }
          else
          {
            echo '<div class="alert alert-danger alert-dismissible" role="alert">
            <button type = "button" class="close" data-dismiss = "alert">x</button>
                    [!] ' . count($result['Errors']) . ' Error(s) occurred while creating table!<br><br>' .
                    $result['Errors'] .
                  '</div>';
          }

          // Create Members Table
          $result = setup_MembersTable();

          if($result['Result'])
          {
            echo '<div class="alert alert-success alert-dismissible" role="alert">
            <button type = "button" class="close" data-dismiss = "alert">x</button>
                    --> Successfully created table "Members".
                  </div>';
          }
          else
          {
            echo '<div class="alert alert-danger alert-dismissible" role="alert">
            <button type = "button" class="close" data-dismiss = "alert">x</button>
                    [!] ' . count($result['Errors']) . ' Error(s) occurred while creating table!<br><br>' .
                    $result['Errors'] .
                  '</div>';
          }

          // Create Membership Table
          $result = setup_MembershipTable();

          if($result['Result'])
          {
            echo '<div class="alert alert-success alert-dismissible" role="alert">
            <button type = "button" class="close" data-dismiss = "alert">x</button>
                    --> Successfully created table "Membership".
                  </div>';
          }
          else
          {
            echo '<div class="alert alert-danger alert-dismissible" role="alert">
            <button type = "button" class="close" data-dismiss = "alert">x</button>
                    [!] ' . count($result['Errors']) . ' Error(s) occurred while creating table!<br><br>' .
                    $result['Errors'] .
                  '</div>';
          }

          // Create RSVP Table
          $result = setup_RSVPTable();

          if($result['Result'])
          {
            echo '<div class="alert alert-success alert-dismissible" role="alert">
            <button type = "button" class="close" data-dismiss = "alert">x</button>
                    --> Successfully created table "RSVP".
                  </div>';
          }
          else
          {
            echo '<div class="alert alert-danger alert-dismissible" role="alert">
            <button type = "button" class="close" data-dismiss = "alert">x</button>
                    [!] ' . count($result['Errors']) . ' Error(s) occurred while creating table!<br><br>' .
                    $result['Errors'] .
                  '</div>';
          }

          // Create Dimensional Committee Table
          $result = setup_DIMSponsorCommitteeTable();

          if($result['Result'])
          {
            echo '<div class="alert alert-success alert-dismissible" role="alert">
            <button type = "button" class="close" data-dismiss = "alert">x</button>
                    --> Successfully created table "DIM Sponsor Committee".
                  </div>';
          }
          else
          {
            echo '<div class="alert alert-danger alert-dismissible" role="alert">
            <button type = "button" class="close" data-dismiss = "alert">x</button>
                    [!] ' . count($result['Errors']) . ' Error(s) occurred while creating table!<br><br>' .
                    $result['Errors'] .
                  '</div>';
          }

          // Create Users Table
          $result = setup_UsersTable();

          if($result['Result'])
          {
            echo '<div class="alert alert-success alert-dismissible" role="alert">
            <button type = "button" class="close" data-dismiss = "alert">x</button>
                    --> Successfully created table "Users".
                  </div>';
          }
          else
          {
            echo '<div class="alert alert-danger alert-dismissible" role="alert">
            <button type = "button" class="close" data-dismiss = "alert">x</button>
                    [!] ' . count($result['Errors']) . ' Error(s) occurred while creating table!<br><br>' .
                    $result['Errors'] .
                  '</div>';
          }

          // Create admin user
          $result = create_adminUser();

          if($result)
          {
            echo '<div class="alert alert-success alert-dismissible" role="alert">
            <button type = "button" class="close" data-dismiss = "alert">x</button>
                    --> Successfully created admin user.
                  </div>';
          }
          else
          {
            echo '<div class="alert alert-danger alert-dismissible" role="alert">
            <button type = "button" class="close" data-dismiss = "alert">x</button>
                    [!] ' . count($result['Errors']) . ' Error(s) occurred while creating admin user!<br><br>' .
                    $result['Errors'] .
                  '</div>';
          }
        }

        echo '
        <div class="form-group">
          <p>
            If you don\'t see any errors or everything already exists,
          </p>
          <a class="btn btn-primary" href="index.html">Click Here</a>
          <br><br><br>
          <p>
            You admin user and password combination is:<br>
            Username: administrator<br>
            Password: password<br>
            <b>Remember to change it immediately.</b>
          </p>
        </div>
        ';
      }

      ?>

    </div>


    <!-- ******************* END FORM ******************* -->

  </body>

</html>

<!-- Begin Scripts for Inline Error Messages -->
<script type="text/javascript">

   $(document).ready(function()
   {
    $('#setupDBForm').bootstrapValidator(
    {
        container: '#messages',
        // To use feedback icons, ensure that you use Bootstrap v3.1.0 or later
        feedbackIcons:
        {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields:
        {
			      dbname:
            {
                validators:
                {
                    notEmpty:
                    {
                        message: 'ERROR: Please enter the ERG\'s identifier.'
                    }
                }
            },
            username:
            {
                validators:
                {
                    notEmpty:
                    {
                        message: 'ERROR: Please enter the database server username.'
                    }
                }
            },
            password:
            {
                // The hidden input will not be ignored
                excluded: false,
                validators:
                {
                    notEmpty:
                    {
                        message: 'ERROR: Please enter the database server password.'
                    }
                }
            },
			      ip:
            {
                validators:
                {
                    notEmpty:
                    {
                        message: 'ERROR: Please enter database server ip.'
                    }
                }
            },
            port:
            {
                validators:
                {
                    notEmpty:
                    {
                        message: 'ERROR: Please enter the databaser server port.'
                    }
                }
            }
        }
    })

    // POST if everything is OK
    .on('success.form.bv', function(e)
    {
          // Prevent form submission
          e.preventDefault();

          // Get the form instance
          var $form = $(e.target);

          // Get the BootstrapValidator instance
          var bv = $form.data('bootstrapValidator');

          // Use Ajax to submit form data
          $.post($form.attr('display'), $form.serialize(), function(result)
          {
              console.log(result);
          }, 'json');
    });
});

</script>
