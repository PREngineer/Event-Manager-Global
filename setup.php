<?php

if( file_exists('settings.php') )
{
  header("Location: index.php");
}

?>

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
          Please provide the following details to set up the database for the Event Manager platform.
        </p>

        <div class="alert alert-warning alert-dismissible" role="alert">
          <button type = "button" class="close" data-dismiss = "alert">x</button>
          <b>Important!</b><br><br>
          You must provide a database <b>administrator</b> account.<br><br>
          It will need to <b>create a database and tables</b> and it will set itself as the account to be used
          to work on this Database in the future.
        </div>

        <br>

        <div class="form-group">
          <label for="username">
            Event Manager's Instance Name:
          </label>
          <div class="input-group">
            <span class="input-group-addon">
              <i class="glyphicon glyphicon-book"></i>
            </span>
            <input name="dbname" type="text" class="form-control" placeholder="MYORG" value="MYORG" aria-describedby="dbnameHelp" required>
          </div>
      	   <small id="dbnameHelp" class="form-text text-muted">This is will be the database's name.</small>
        </div>

        <div class="form-group">
          <label for="dbtype" style="margin-top:10px"><label class="text-danger">*</label> Database Type</label>
          <div class="input-group form-control" aria-describedby="dbtypeHelp">
            <input name="dbtype" type="radio" id="dbtype" value="mysql" checked required>&nbsp;&nbsp;MySQL, MariaDB
            <br>
            <input name="dbtype" type="radio" id="dbtype" value="sqlsrv" required>&nbsp;&nbsp;Microsoft SQL Server
          </div>
          <small id="dbtypeHelp" class="form-text text-muted">Only the types shown are supported.</small>
        </div>

        <div class="form-group">
          <label for="username">
            DB Username:
          </label>
          <div class="input-group">
            <span class="input-group-addon">
              <i class="glyphicon glyphicon-user"></i>
            </span>
            <input name="username" type="text" class="form-control" placeholder="root" value="root" aria-describedby="usernameHelp" required>
          </div>
      	   <small id="usernameHelp" class="form-text text-muted">Must be an administrator username.</small>
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
      	   <small id="passwordHelp" class="form-text text-muted">The administrator password.</small>
        </div>

        <div class="form-group">
          <label for="ip">
            DB IP/URL:
          </label>
          <div class="input-group">
            <span class="input-group-addon">
              <i class="glyphicon glyphicon-globe"></i>
            </span>
            <input name="ip" type="text" class="form-control" placeholder="localhost or sub.domain.com or 10.0.0.25" value="127.0.0.1" aria-describedby="ipHelp" required>
          </div>
      	   <small id="ipHelp" class="form-text text-muted">e.g. localhost, 10.38.4.56, mysqldb.database.windows.net, db.domain.tld</small>
        </div>

        <div class="form-group">
          <label for="port">
            DB Port:
          </label>
          <div class="input-group">
            <span class="input-group-addon">
              <i class="glyphicon glyphicon-asterisk"></i>
            </span>
            <input name="port" type="text" class="form-control" placeholder="3306" value="3306" aria-describedby="portHelp" required>
          </div>
      	   <small id="portHelp" class="form-text text-muted">Use the port configured for your database here instead of in the URL.</small>
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

      require 'classes/DatabaseSetup.php';

      // If the POST has information,
      // Check if the information provided was correct.
      if( !empty($_POST) )
      {
        $dbsetup = new DatabaseSetup($_POST);
        $check = $dbsetup->test();

        // If the information is incorrect
        if($check !== True)
        {
          echo '<br><br>
                <div class="alert alert-danger alert-dismissible" role="alert">
                <button type = "button" class="close" data-dismiss = "alert">x</button>
                  Could not connect to the MySQL Server.
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
          $result = $dbsetup->setup_DB();

          if($result === True)
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
                    ' . count($result) . ' Error(s) occurred while creating the database: ' . $_POST['dbname'] . '!<br><br>' .
                    $result .
                  '</div>';
          }

          // Create Announcements Table
          $result = $dbsetup->setup_AnnouncementsTable();

          if($result === True)
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
                    ' . count($result) . ' Error(s) occurred while creating table "Announcements"!<br><br>' .
                    $result .
                  '</div>';
          }

          // Create Attendance Table
          $result = $dbsetup->setup_AttendanceTable();

          if($result === True)
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
                    ' . count($result) . ' Error(s) occurred while creating table "Attendance"!<br><br>' .
                    $result .
                  '</div>';
          }

          // Create Career Levels Table
          $result = $dbsetup->setup_CareerLevelsTable();

          if($result === True)
          {
            echo '<div class="alert alert-success alert-dismissible" role="alert">
            <button type = "button" class="close" data-dismiss = "alert">x</button>
                    --> Successfully created table "Career Levels".
                  </div>';
          }
          else
          {
            echo '<div class="alert alert-danger alert-dismissible" role="alert">
            <button type = "button" class="close" data-dismiss = "alert">x</button>
                    ' . count($result) . ' Error(s) occurred while creating table "Career Levels"!<br><br>' .
                    $result .
                  '</div>';
          }

          // Create Company Segments Table
          $result = $dbsetup->setup_CompanySegmentsTable();

          if($result === True)
          {
            echo '<div class="alert alert-success alert-dismissible" role="alert">
            <button type = "button" class="close" data-dismiss = "alert">x</button>
                    --> Successfully created table "Company Segments".
                  </div>';
          }
          else
          {
            echo '<div class="alert alert-danger alert-dismissible" role="alert">
            <button type = "button" class="close" data-dismiss = "alert">x</button>
                    ' . count($result) . ' Error(s) occurred while creating table "Company Segments"!<br><br>' .
                    $result .
                  '</div>';
          }

          // Create Event Change Log Table
          $result = $dbsetup->setup_EventChangeLogTable();

          if($result === True)
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
                    ' . count($result) . ' Error(s) occurred while creating table "Event Change Log"!<br><br>' .
                    $result .
                  '</div>';
          }

          // Create Event Objectives Table
          $result = $dbsetup->setup_EventObjectivesTable();

          if($result === True)
          {
            echo '<div class="alert alert-success alert-dismissible" role="alert">
            <button type = "button" class="close" data-dismiss = "alert">x</button>
                    --> Successfully created table "Event Objectives".
                  </div>';
          }
          else
          {
            echo '<div class="alert alert-danger alert-dismissible" role="alert">
            <button type = "button" class="close" data-dismiss = "alert">x</button>
                    ' . count($result) . ' Error(s) occurred while creating table "Event Objectives"!<br><br>' .
                    $result .
                  '</div>';
          }

          // Create Events Table
          $result = $dbsetup->setup_EventsTable();

          if($result === True)
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
                    ' . count($result) . ' Error(s) occurred while creating table "Events"!<br><br>' .
                    $result .
                  '</div>';
          }

          // Create Event Targets Table
          $result = $dbsetup->setup_EventTargetsTable();

          if($result === True)
          {
            echo '<div class="alert alert-success alert-dismissible" role="alert">
            <button type = "button" class="close" data-dismiss = "alert">x</button>
                    --> Successfully created table "Event Targets".
                  </div>';
          }
          else
          {
            echo '<div class="alert alert-danger alert-dismissible" role="alert">
            <button type = "button" class="close" data-dismiss = "alert">x</button>
                    ' . count($result) . ' Error(s) occurred while creating table "Event Targets"!<br><br>' .
                    $result .
                  '</div>';
          }

          // Create Event Types Table
          $result = $dbsetup->setup_EventTypesTable();

          if($result === True)
          {
            echo '<div class="alert alert-success alert-dismissible" role="alert">
            <button type = "button" class="close" data-dismiss = "alert">x</button>
                    --> Successfully created table "Event Types".
                  </div>';
          }
          else
          {
            echo '<div class="alert alert-danger alert-dismissible" role="alert">
            <button type = "button" class="close" data-dismiss = "alert">x</button>
                    ' . count($result) . ' Error(s) occurred while creating table "Event Types"!<br><br>' .
                    $result .
                  '</div>';
          }

          // Create Leads Table
          $result = $dbsetup->setup_LeadsTable();

          if($result === True)
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
                    ' . count($result) . ' Error(s) occurred while creating table "Leads"!<br><br>' .
                    $result .
                  '</div>';
          }

          // Create Membership Table
          $result = $dbsetup->setup_MembershipTable();

          if($result === True)
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
                    ' . count($result) . ' Error(s) occurred while creating table "Membership"!<br><br>' .
                    $result .
                  '</div>';
          }

          // Create Members Table
          $result = $dbsetup->setup_MembersTable();

          if($result === True)
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
                    ' . count($result) . ' Error(s) occurred while creating table "Members"!<br><br>' .
                    $result .
                  '</div>';
          }

          // Create Orgs Table
          $result = $dbsetup->setup_OrgsTable();

          if($result === True)
          {
            echo '<div class="alert alert-success alert-dismissible" role="alert">
            <button type = "button" class="close" data-dismiss = "alert">x</button>
                    --> Successfully created table "Orgs".
                  </div>';
          }
          else
          {
            echo '<div class="alert alert-danger alert-dismissible" role="alert">
            <button type = "button" class="close" data-dismiss = "alert">x</button>
                    ' . count($result) . ' Error(s) occurred while creating table "Orgs"!<br><br>' .
                    $result .
                  '</div>';
          }

          // Create RSVP Table
          $result = $dbsetup->setup_RSVPTable();

          if($result === True)
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
                    ' . count($result) . ' Error(s) occurred while creating table "RSVP"!<br><br>' .
                    $result .
                  '</div>';
          }

          // Create Sponsor Committees Table
          $result = $dbsetup->setup_SponsorCommitteesTable();

          if($result === True)
          {
            echo '<div class="alert alert-success alert-dismissible" role="alert">
            <button type = "button" class="close" data-dismiss = "alert">x</button>
                    --> Successfully created table "Sponsor Committees".
                  </div>';
          }
          else
          {
            echo '<div class="alert alert-danger alert-dismissible" role="alert">
            <button type = "button" class="close" data-dismiss = "alert">x</button>
                    ' . count($result) . ' Error(s) occurred while creating table "Sponsor Committees"!<br><br>' .
                    $result .
                  '</div>';
          }

          // Create Users Table
          $result = $dbsetup->setup_UsersTable();

          if($result === True)
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
                    ' . count($result) . ' Error(s) occurred while creating table "Users"!<br><br>' .
                    $result .
                  '</div>';
          }

          // Create admin user
          $result = $dbsetup->create_adminUser();

          if($result === True)
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
                    ' . count($result) . ' Error(s) occurred while creating admin user!<br><br>' .
                    $result .
                  '</div>';
          }
        }

        echo '
        <div class="form-group">
          <p>
            If you don\'t see any errors or everything already exists,
          </p>
          <a class="btn btn-primary" href="index.php">Click Here</a>
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
