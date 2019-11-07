<?php
// echo '<br><br><br>Loaded Init';

// Initialize the session
session_start();

// If the session's user_id variable doesn't exist, create it NULL
if(array_key_exists('userID', $_SESSION) === false)
{
	$_SESSION['userID'] = null;
}

// If the session's user_id variable doesn't exist, create it NULL
if(array_key_exists('userRole', $_SESSION) === false)
{
	$_SESSION['userRole'] = null;
}

// Redirect to setup if settings was not found
if( !file_exists('settings.php') && ($_GET['init'] != 'sub') )
{
  echo '<script type="text/javascript">
      window.location.href = "../setup.php"
    </script>
  ';
}

/*
  Function that checks if the user SESSION is active
  - Used all over the place
  @Return - Boolean (T or F) if logged in
*/
function logged_in()
{
  return ( isset( $_SESSION['userID'] ) ) ? true : false;
}

/*
  Function that checks if the user Global Admin session is active
  - Used all over the place
  @Return - Boolean (T or F) if logged in
*/
function protectGlobalAdmin()
{
  if( $_SESSION['userRole'] != '4' )
  {
    // destroy the session 
    unset($_SESSION);
    session_destroy();
  
    echo '<script type="text/javascript">
        window.location.href = "index.php?display=Login"
      </script>';
  }
}

/*
  Function that checks if the user Admin session is active
  - Used all over the place
  @Return - Boolean (T or F) if logged in
*/
function protectAdmin()
{
  if( $_SESSION['userRole'] != '3' )
  {
    // destroy the session 
    unset($_SESSION);
    session_destroy(); 
  
    echo '<script type="text/javascript">
        window.location.href = "index.php?display=Login"
      </script>';
  }
}

/*
  Function that checks if the user Approver session is active
  - Used all over the place
  @Return - Boolean (T or F) if logged in
*/
function protectApprover()
{
  if( $_SESSION['userRole'] != '1' && $_SESSION['userRole'] != '3' && $_SESSION['userRole'] != '4')
  {
    // destroy the session 
    unset($_SESSION);
    session_destroy();
  
    echo '<script type="text/javascript">
        window.location.href = "index.php?display=Login"
      </script>';
  }
}

/*
  Function that checks if the user POC session is active
  - Used all over the place
  @Return - Boolean (T or F) if logged in
*/
function protectPoc()
{
  if( $_SESSION['userRole'] != '2' && $_SESSION['userRole'] != '3' && $_SESSION['userRole'] != '4')
  {
    // destroy the session 
    unset($_SESSION);
    session_destroy();
  
    echo '<script type="text/javascript">
        window.location.href = "index.php?display=Login"
      </script>';
  }
}

// Redirect if settings was not found but inside the attendance admin area
// if( !file_exists('../../functions/settings.php') && ($_GET['init'] == 'sub') )
// {
//   echo '<script type="text/javascript">
//       window.location.href = "../../setup.php"
//     </script>
//   ';
// }

// Find the string in between two other
function getSubstring($start, $end, $content)
{
    $r = explode($start, $content);
    if (isset($r[1]))
    {
        $r = explode($end, $r[1]);
        return $r[0];
    }
    return '';
}

?>
