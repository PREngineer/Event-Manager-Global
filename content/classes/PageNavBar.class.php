<?php

/**
 * NavBar
 */
class PageNavBar
{
  //------------------------- Attributes -------------------------
  
    
  //------------------------- Operations -------------------------
  
  /**
   * __construct
   *
   * @return void
   */
  public function __construct()
  {
    
  }
  
  /**
   * Display
   *
   * @return HTML Representation of the NavBar
   */
  public function Display()
  {
    $out .= '
    <div class="container">
      <nav class="navbar navbar-inverse navbar-fixed-top">
        <div class="container-fluid">
          <!-- Brand and toggle (hamburger) get grouped for better mobile display -->
          <div class="navbar-header">
            <!-- Hamburger -->
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#collapsibleNavbar" aria-expanded="false">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>

            <!-- Brand icon -->
            <a href="index.php" class="navbar-brand" style="cursor: pointer;">
              <img src="../images/logo.svg" width="30" height="30" alt="Logo">
            </a>
          </div>

          <!-- Collect the nav links, forms, and other content for toggling -->
          <div class="collapse navbar-collapse" id="collapsibleNavbar">
            <ul class="nav navbar-nav">
              <li id="announcementsLink">
                <a href="index.php?display=Announcements" style="cursor: pointer;">Announcements</a>
              </li>
              <li id="currentLink">
                <a href="index.php?display=Current" style="cursor: pointer;">Current Events</a>
              </li>
              <li id="futureLink">
                <a href="index.php?display=Future" style="cursor: pointer;">Future Events</a>
              </li>
              <li id="myRSVPLink">
                <a href="index.php?display=MyRSVP" style="cursor: pointer;">My RSVPs</a>
              </li>
              <li id="createMemberLink">
                <a href="index.php?display=CreateMember" style="cursor: pointer;">New Member</a>
              </li>
            </ul>

            <ul class="nav navbar-nav navbar-right">';

    if( !isset($_SESSION['userRole']) )
    {
    echo'
              <li id="loginLink">
                <a href="index.php?display=Login" style="cursor: pointer;">Login</a>
              </li>
    ';
    }

    if( $_SESSION['userRole'] == 1 )
    {
    echo'
              <li id="approverLink">
                <a href="index.php?display=Approver" style="cursor: pointer;">Approvers</a>
              </li>
              <li id="loginLink">
                <a href="index.php?display=Logout" style="cursor: pointer;">Logout</a>
              </li>
    ';
    }

    if( $_SESSION['userRole'] == 2 )
    {
    echo'
              <li id="pocLink">
                <a href="index.php?display=Poc" style="cursor: pointer;">POCs</a>
              </li>
              <li id="loginLink">
                <a href="index.php?display=Logout" style="cursor: pointer;">Logout</a>
              </li>
    ';
    }

    if( $_SESSION['userRole'] == 3 )
    {
    echo'
              <li id="adminLink" class="dropdown">
                <a href="index.php?display=Admin" style="cursor: pointer;">Administrators</a>
              </li>
              <li id="loginLink">
                <a href="index.php?display=Logout" style="cursor: pointer;">Logout</a>
              </li>
    ';
    }

    $out .= '
            </ul>
          </div>
          <!-- /.navbar-collapse -->
        </div>
        <!-- /.container-fluid -->
      </nav>
    </div>


    

    <!-- Handle NavBar Highlights -->
    <script>';

    if( $_GET['display'] == 'Announcements' )
    {
      $out .= '
      document.getElementById("announcementsLink").classList.add("active");
      ';
    }
    else
    {
      $out .= '
      document.getElementById("announcementsLink").classList.remove("active");
      ';
    }

    if( $_GET['display'] == 'Current' )
    {
      $out .= '
      document.getElementById("currentLink").classList.add("active");
      ';
    }
    else
    {
      $out .= '
      document.getElementById("currentLink").classList.remove("active");
      ';
    }

    if( $_GET['display'] == 'Future' )
    {
      $out .= '
      document.getElementById("futureLink").classList.add("active");
      ';
    }
    else
    {
      $out .= '
      document.getElementById("futureLink").classList.remove("active");
      ';
    }

    if( $_GET['display'] == 'CreateMember' )
    {
      $out .= '
      document.getElementById("createMemberLink").classList.add("active");
      ';
    }
    else
    {
      $out .= '
      document.getElementById("createMemberLink").classList.remove("active");
      ';
    }

    if( $_GET['display'] == 'Login' )
    {
      $out .= '
      document.getElementById("loginLink").classList.add("active");
      ';
    }
    else
    {
      $out .= '
      document.getElementById("loginLink").classList.remove("active");
      ';
    }

    if( $_GET['display'] == 'MyRSVP' )
    {
      $out .= '
      document.getElementById("myRSVPLink").classList.add("active");
      ';
    }
    else
    {
      $out .= '
      document.getElementById("myRSVPLink").classList.remove("active");
      ';
    }

    if( $_GET['display'] == 'Approver' && $_SESSION['userRole'] == 1 )
    {
      $out .= '
      document.getElementById("approverLink").classList.add("active");
      ';
    }
    if( $_GET['display'] != 'Approver' && $_SESSION['userRole'] == 1 )
    {
      $out .= '
      document.getElementById("approverLink").classList.remove("active");
      ';
    }

    if( $_GET['display'] == 'Poc' && $_SESSION['userRole'] == 2 )
    {
      $out .= '
      document.getElementById("pocLink").classList.add("active");
      ';
    }
    if( $_GET['display'] != 'Poc' && $_SESSION['userRole'] == 2 )
    {
      $out .= '
      document.getElementById("pocLink").classList.remove("active");
      ';
    }

    if( $_GET['display'] == 'Admin' && $_SESSION['userRole'] == 3 )
    {
      $out .= '
      document.getElementById("adminLink").classList.add("active");
      ';
    }
    if( $_GET['display'] != 'Admin' && $_SESSION['userRole'] == 3 )
    {
      $out .= '
      document.getElementById("adminLink").classList.remove("active");
      ';
    }

    $out .= '
    </script>';

    return $out;
  }

}

?>