<?php

require_once 'autoloader.php';

class GlobalAdminMenu extends Page
{
  
  //------------------------- Attributes -------------------------
  private $db = null;

  public $content = '<br><br>';
  public $title = "Event Manager - Global Admin Menu";
  public $keywords = "event manager, global admin menu";
  
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
   * Display - Displays the full page
   *
   * @param  mixed $filter
   *
   * @return void
   */
  public function Display()
  {
    // Set the page header
    $this->content .= '
      <h1 id="page-title" tabindex="-1" role="heading" aria-level="1">Global Admin Menu</h1>
      <hr>
    ';

    $this->content .= '
    <div class="panel panel-default">
      <!-- Default panel contents -->
      <div class="panel-heading"><p>Welcome ' . $_SESSION['userID'] . '!</p></div>
      <div class="panel-body">
        <p>
          Being a Global Administrator gives you total control and power over all of the information
          that is received and managed by this platform.
        </p>
        <p>
          You are able to manage all organizations, all the members, and you have access to organizational and global reports.
        </p>
        <p>
          Please read about the different options available to you if this is your first time.
        </p>
      </div>
    </div>
  
    <!-- Table -->
    <table class="table">
  
      <thead>
        <tr>
        <th>Option</th>
        <th>Description</th>
        <tr>
      </thead>
  
      <tr>
        <td><a href="index.php?display=AnnouncementsMenu" style="cursor:pointer;">Announcements</a></td>
        <td>
          Use this option to create, remove, edit, and expire the announcements.
        </td>
      </tr>
  
      <tr>
        <td><a href="index.php?display=Attendance" style="cursor:pointer;">Attendance</a></td>
        <td>
          Use this option to add, remove, and modify the attendance.
        </td>
      </tr>
  
      <tr>
        <td><a href="index.php?display=Events" style="cursor:pointer;">Events</a></td>
        <td>
          Use this option to create, delete, recover, modify, approve, and disapprove events.
        </td>
      </tr>
  
      <tr>
        <td><a href="index.php?display=Members" style="cursor:pointer;">Members</a></td>
        <td>
          Use this option to add, remove, and edit the members of the Organizations.
        </td>
      </tr>
  
      <tr>
        <td><a href="index.php?display=Reports" style="cursor:pointer;">Reports</a></td>
        <td>
          Use this option to view live or historical reports about the organizations, membership, attendance, events, etc.
        </td>
      </tr>
  
      <tr>
        <td><a href="index.php?display=UserRoles" style="cursor:pointer;">User Roles</a></td>
        <td>
          Use this option to determine which members can have the following platform roles:<br>
          * Platform Administrator<br>
          * Organization Administrator<br>
          * Organization Point Of Contact<br>
          * Organization Event Approver
        </td>
      </tr>
    </table>
    ';

    parent::Display();
  }

}

?>