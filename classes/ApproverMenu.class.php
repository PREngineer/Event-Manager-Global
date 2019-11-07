<?php

require_once 'autoloader.php';

protectApprover();

class ApproverMenu extends Page
{
  
  //------------------------- Attributes -------------------------
  private $db      = null;
  private $events  = null;

  public $content  = '<br><br>';
  public $title    = "Event Manager - Pending Approvals";
  public $keywords = "event manager, pending approvals";
  
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
   * get_ApproverEvents - Retrieve all of the events pending approval for the user's Org.
   *
   * @return void
   */
  private function get_ApproverEvents()
  {
    $date = date('Y-m-d');

    return $this->db->query_DB("SELECT ID, Name, Date, Created, Creator_User_ID, Person_Code, Remote_Code, Approved, Estimated_Budget, Deleted
                                FROM Events
                                WHERE Date > '$date'
                                AND Deleted = '0'
                                AND Org_ID = '" . $_SESSION['Org_ID'] . "'
                                ORDER BY Date, Start
                              ");
  }

  private function handleInteractions( $data )
  {
    $id       = $data['id'];
    $approved = $data['approve'];

    return $this->db->query_DB("UPDATE Events
                                SET Approved = '$approved'
                                WHERE id = '$id'
                              ");
  }

  /**
   * Display - Displays the full page
   *
   * @param  mixed $filter
   *
   * @return void
   */
  public function Display( $data )
  {
    // Handle interactions
    $this->handleInteractions( $data );

    $this->events = $this->get_ApproverEvents();

    // Set the page header
    $this->content .= '
      <h1 id="page-title" tabindex="-1" role="heading" aria-level="1">Pending Approvals</h1>
      <hr>
    ';

    $this->content .= '
      <div class="panel panel-default">

        <!-- Default panel contents -->
        <div class="panel-heading">
          <p>Welcome ' . $_SESSION['userID'] . '!</p>
          <p>Here are all the future events that need to be evaluated in your organization.</p>
        </div>
        <div class="panel-body">
          <i class="glyphicon glyphicon-ok" title="Approve" style="color:green; padding-left:2em"></i> = Approve
          <i class="glyphicon glyphicon-remove" title="Disapprove" style="color:red; padding-left:2em"></i> = Disapprove
        </div>
      </div>

      <!-- Table -->
      <div class="table-responsive">
        <table class="table">

          <thead>
            <th>
              Options
            </th>

            <th>
              Name
            </th>

            <th>
              Date
            </th>

            <th>
              Created
            </th>

            <th>
              Creator
            </th>

            <th>
              Approved
            </th>

            <th>
              Estimated <i class="glyphicon glyphicon-usd" title="Estimated Budget" style="color:black"></i>
            </th>

          </thead>
          <tbody>
      ';

        foreach( $this->events as $event )
        {
          $this->content .= '
            <tr id="Entry' . $event['ID'] . '">
              <td>
          ';

          if( $event['Approved'] == 0 )
          {
            $this->content .= '
                <a href="index.php?display=ApproverMenu&approve=1&id=' . $event['ID'] . '" style="cursor:pointer;">
                  <i class="glyphicon glyphicon-ok" title="Approve" style="color: green"></i>
                </a>
            ';
          }
          else
          {
            $this->content .= '
                <a href="index.php?display=ApproverMenu&approve=0&id=' . $event['ID'] . '" style="cursor:pointer;">
                  <i class="glyphicon glyphicon-remove" title="Disapprove" style="color: red"></i>
                </a>
            ';
          }

          $this->content .= '
              </td>

              <td>
            ' . $event['Name']            . '
              </td>

              <td>
            ' . $event['Date']            . '
              </td>

              <td>
            ' . $event['Created']         . '
              </td>

              <td>
            ' . $event['Creator_User_ID'] . '
              </td>

              <td>
          ';

          if( $event['Approved'] == 1 )
          {
            $this->content .= '
                <i class="glyphicon glyphicon-ok-sign" title="Approved" style="color:green"></i>
            ';
          }
          else
          {
            $this->content .= '
                <i class="glyphicon glyphicon-remove-sign" title="Not Approved" style="color:red"></i>
            ';
          }

          $this->content .= '
              </td>

              <td>
            ' . $event['Estimated_Budget'] . '
              </td>

            </tr>';
        }

      $this->content .= '
          <tbody>
        </table>
      </div>
    ';

    parent::Display();
  }

}

?>