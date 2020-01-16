<?php

require_once 'autoloader.php';

class POCMenu extends Page
{
  
  //------------------------- Attributes -------------------------
  private $db = null;

  public $content = '<br><br>';
  public $title = "Event Manager - Point Of Contact Menu";
  public $keywords = "event manager, point of contact menu";
  
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
      <h1 id="page-title" tabindex="-1" role="heading" aria-level="1">Point Of Contact Menu</h1>
      <hr>
    ';

    $this->content .= '
    <div class="panel panel-default">
      <!-- Default panel contents -->
      <div class="panel-heading"><p>Welcome ' . $_SESSION['userID'] . '!</p></div>
      <div class="panel-body">
        <p>
          As a Point Of Contact, you have the options to create and manage events
          that have been created by you.
        </p>
        <p>
          Please read about the options that you have available.
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
        <td><a href="index.php?display=CreateEvent" style="cursor:pointer;">Create Event</a></td>
        <td>
          Use this option to create a New Event.
        </td>
      </tr>
      <tr>
        <td><a href="index.php?display=POCMyEvents" style="cursor:pointer;">My Events</a></td>
        <td>
          Use this option to view and manage your events.  You can perform the
          following actions on your existing events:<br>
          * Edit its details before the event occurs<br>
          * Delete an event before it occurs<br>
          * View all the events that you have created
        </td>
      </tr>
      <tr>
        <td><a href="index.php?display=POCCloseEvent" style="cursor:pointer;">Close Events</a></td>
        <td>
          Use this option to add the actual budget and close past events.
        </td>
      </tr>
    </table>

    ';

    parent::Display();
  }

}

?>