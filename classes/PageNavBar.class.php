<?php

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
   * Display - Returns the HTML of the NavBar
   *
   * @return string NavBar
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
            <a href="index.php" class="navbar-brand">
              <img src="../images/TLogo.png" width="30" height="30" alt="Logo">
            </a>
          </div>

          <!-- Collect the nav links, forms, and other content for toggling -->
          <div class="collapse navbar-collapse" id="collapsibleNavbar">
            <ul class="nav navbar-nav">
              <li id="announcementsLink">
                <a href="index.php?display=Announcements">
                <span class="glyphicon glyphicon-exclamation-sign"></span> Announcements
                </a>
              </li>
              <li id="currentLink">
                <a href="index.php?display=CurrentEvents">
                <span class="glyphicon glyphicon-copy"></span> Current Events
                </a>
              </li>
              <li id="futureLink">
                <a href="index.php?display=FutureEvents">
                <span class="glyphicon glyphicon-hourglass"></span> Future Events
                </a>
              </li>
              <li id="myRSVPsLink">
                <a href="index.php?display=MyRSVPs">
                <span class="glyphicon glyphicon-pushpin"></span> My RSVPs
                </a>
              </li>
              <li id="createMemberLink">
                <a href="index.php?display=CreateMember">
                <span class="glyphicon glyphicon-thumbs-up"></span> New Member
                </a>
              </li>
            </ul>

            <ul class="nav navbar-nav navbar-right">';

    if( $_SESSION['userRole'] == 1 )
    {
      $out .= '
              <li id="approverLink">
                <a href="index.php?display=ApproverMenu">
                <span class="glyphicon glyphicon-user" style="color: blue"></span> Approvers
                </a>
              </li>
      ';
    }
    else if( $_SESSION['userRole'] == 2 )
    {
      $out .= '
              <li id="pocLink">
                <a href="index.php?display=POCMenu">
                <span class="glyphicon glyphicon-user" style="color: green"></span> POCs
                </a>
              </li>
      ';
    }
    else if( $_SESSION['userRole'] == 3 )
    {
      $out .= '
              <li id="adminLink" class="dropdown">
                <a href="index.php?display=AdminMenu">
                <span class="glyphicon glyphicon-user" style="color: orange"></span> Administrators
                </a>
              </li>
      ';
    }
    else if( $_SESSION['userRole'] == 4 )
    {
      $out .= '
              <li id="globalAdminLink" class="dropdown">
                <a href="index.php?display=GlobalAdminMenu">
                <span class="glyphicon glyphicon-user" style="color: red"></span> Global Admins
                </a>
              </li>
      ';
    }
    
    if( !isset($_SESSION['userRole']) )
    {
      $out .= '
              <li id="loginLink">
                <a href="index.php?display=Login" style="cursor: pointer;">
                <span class="glyphicon glyphicon-log-in"></span> Login
                </a>
              </li>
      ';
    }
    else
    {
      $out .= '
              <li id="logoutLink">
                <a href="index.php?display=Logout" style="cursor: pointer;">
                <span class="glyphicon glyphicon-log-out"></span> Logout</a>
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

    if( $_GET['display'] === 'Announcements' )
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

    if( $_GET['display'] === 'CurrentEvents' || !isset( $_GET['display'] ) )
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

    if( $_GET['display'] === 'FutureEvents' )
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

    if( $_GET['display'] === 'CreateMember' )
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

    if( $_GET['display'] === 'Login' )
    {
      $out .= '
      document.getElementById("loginLink").classList.add("active");
      ';
    }

    if( $_GET['display'] === 'MyRSVPs' )
    {
      $out .= '
      document.getElementById("myRSVPsLink").classList.add("active");
      ';
    }
    else
    {
      $out .= '
      document.getElementById("myRSVPsLink").classList.remove("active");
      ';
    }

    if( $_GET['display'] === 'ApproverMenu' && $_SESSION['userRole'] === '1' )
    {
      $out .= '
      document.getElementById("approverLink").classList.add("active");
      ';
    }
    else if( $_GET['display'] !== 'ApproverMenu' && $_SESSION['userRole'] === '1' )
    {
      $out .= '
      document.getElementById("approverLink").classList.remove("active");
      ';
    }

    if( $_GET['display'] === 'POCMenu' && $_SESSION['userRole'] === '2' )
    {
      $out .= '
      document.getElementById("pocLink").classList.add("active");
      ';
    }
    else if( $_GET['display'] !== 'POCMenu' && $_SESSION['userRole'] === '2' )
    {
      $out .= '
      document.getElementById("pocLink").classList.remove("active");
      ';
    }

    if( $_GET['display'] === 'AdminMenu' && $_SESSION['userRole'] === '3' )
    {
      $out .= '
      document.getElementById("adminLink").classList.add("active");
      ';
    }
    else if( $_GET['display'] !== 'AdminMenu' && $_SESSION['userRole'] === '3' )
    {
      $out .= '
      document.getElementById("adminLink").classList.remove("active");
      ';
    }

    if( $_GET['display'] === 'GlobalAdminMenu' && $_SESSION['userRole'] === '4' )
    {
      $out .= '
      document.getElementById("globalAdminLink").classList.add("active");
      ';
    }
    else if( $_GET['display'] !== 'GlobalAdminMenu' && $_SESSION['userRole'] === '4' )
    {
      $out .= '
      document.getElementById("globalAdminLink").classList.remove("active");
      ';
    }

    $out .= '
    </script>
    
    
    ';

    return $out;
  }

}

?>