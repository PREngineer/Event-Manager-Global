<?php

require_once 'autoloader.php';

class AdminMenu extends Page
{
  
  //------------------------- Attributes -------------------------
  private $db = null;

  public $content = '<br><br>';
  public $title = "Event Manager - Admin Menu";
  public $keywords = "event manager, admin menu";
  
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
      <h1 id="page-title" tabindex="-1" role="heading" aria-level="1">Admin Menu</h1>
      <hr>
    ';

    $this->content .= '
      <div class="panel panel-default">
        <!-- Default panel contents -->
        <div class="panel-heading"><p>Welcome ' . $_SESSION['userID'] . '!</p></div>
        <div class="panel-body">
          <p>
            Being an Administrator gives you plenty of control and power over the information
            that is received and managed by this web application for your organization.
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
            Use this option to manage the announcements:<br>
            * Create<br>
            * Remove<br>
            * Edit<br>
            * Expire
          </td>
        </tr>

        <tr>
          <td><a href="index.php?display=Attendance" style="cursor:pointer;">Attendance</a></td>
          <td>
            Use this option to manage attendance related information:<br>
            * Add (outside of the event date)<br>
            * Remove<br>
            * Modify
          </td>
        </tr>

        <tr>
          <td><a href="index.php?display=Events" style="cursor:pointer;">Events</a></td>
          <td>
            Use this option to manage the event information stored in the database:<br>
            * Create<br>
            * Delete / Recover<br>
            * Modify<br>
            * Approve / Disapprove
          </td>
        </tr>

        <tr>
          <td><a href="index.php?display=Members" style="cursor:pointer;">Members</a></td>
          <td>
            Use this option to manage the members of your Organization:<br>
            * Add<br>
            * Remove<br>
            * Edit
          </td>
        </tr>

        <tr>
          <td><a href="index.php?display=Reports" style="cursor:pointer;">Reports</a></td>
          <td>
            Use this option to view live or historical reports about the Organization\'s:<br>
            * Membership<br>
            * Attendance<br>
            * Events<br>
            * Others
          </td>
        </tr>

        <tr>
          <td><a href="index.php?display=UserRoles" style="cursor:pointer;">User Roles</a></td>
          <td>
            Use this option to determine which members of the Organization can have the following roles:<br>
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