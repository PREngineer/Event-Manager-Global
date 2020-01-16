<?php

require_once 'autoloader.php';

class POCMyEvents extends Page
{

  //------------------------- Attributes -------------------------
  private $db = null;
  public $content = '<br><br>';
  public $title = "Event Manager - POC - My Events";
  public $keywords = "event manager, poc, my events";

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

  /****************
      Helper Functions
  ****************/

  private function get_MyEvents( $id )
  {
    $date = date('Y-m-d');

    return $this->db->query_DB("SELECT `ID`, `Name`, `Date`, `Created`, `Creator_User_ID`, `Person_Code`, `Remote_Code`,
                                    `Approved`, `Estimated_Budget`, `Actual_Budget`, `Deleted`
                                FROM `Events`
                                WHERE `Creator_User_ID` = '$id'
                                AND `Date` > '$date'
                                ORDER BY `Date` DESC,`Start`"
                              );
  }

  private function get_EventData( $id )
  {
    $date = date('Y-m-d');

    return $this->db->query_DB("SELECT `ID`, `Name`, `Date`, `Start`, `End`, `Location`, `Address`, `Created`, `Creator_User_ID`, `Person_Code`, `Remote_Code`,
                                  `Approved`, `Estimated_Budget`, `Actual_Budget`, `Deleted`
                                FROM `Events`
                                WHERE `ID` = '$id'"
                              )[0];
  }

  /****************
      Core Functionality Functions
  ****************/

  private function delete( $id )
  {
    return $this->db->query_DB("UPDATE `Events`
                                SET `Deleted` = '1'
                                WHERE `id` = $id"
                              );
  }

  private function recover( $id )
  {
    return $this->db->query_DB("UPDATE `Events`
                                SET `Deleted` = '0'
                                WHERE `id` = $id"
                              );
  }

  /****************
      Page Display Functions
  ****************/

  /**
   * Display - Displays the full page
   *
   * @param  mixed $filter
   *
   * @return void
   */
  public function Display( $get )
  {
    // Set the page header
    $this->content .= '
      <h1 id="page-title" tabindex="-1" role="heading" aria-level="1">My Events</h1>
      <hr>
    ';

    // Fresh load
    if( !isset( $get['del'] ) && !isset( $get['rec'] ) && !isset($get['ed']) || !isset($get['id']) )
    {
      $this->Display_List();
    }
    // Process deletion
    else if( $get['del'] === '1' && isset($get['id']) )
    {
      $success = $this->delete( $get['id'] );
      
      if( $success === True )
      {
        $this->content .= '
        <div class="container alert alert-success alert-dismissible" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">x</button>
          Your event has been deleted!
        </div>
        ';
      }
      else
      {
        $this->content .= '
        <div class="container alert alert-danger alert-dismissible" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">x</button>
          An error has occurred while deleting the event.<br>
          Error: ' . $success . '
          <br><br>
          Click <a href="">Here</a> to try again.
        </div>
        ';
      }
      
      $this->Display_List();
    }
    // Process recovery
    else if( $get['rec'] === '1' && isset($get['id']) )
    {
      $success = $this->recover( $get['id'] );

      if( $success === True )
      {
        $this->content .= '
        <div class="container alert alert-success alert-dismissible" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">x</button>
          Your event has been recovered!
        </div>
        ';
      }
      else
      {
        $this->content .= '
        <div class="container alert alert-danger alert-dismissible" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">x</button>
          An error has occurred while recovering the event.<br>
          Error: ' . $success . '
          <br><br>
          Click <a href="">Here</a> to try again.
        </div>
        ';
      }

      $this->Display_List();
    }
    // Edit
    else if( $get['ed'] === '0' && isset($get['id']) )
    {
      $this->Display_Form( $get['id'] );
    }
    // process edit
    else if( $get['ed'] === '1' && isset($get['id']) )
    {
      $success = $this->edit( $get['id'] );

      if( $success === True )
      {
        $this->content .= '
        <div class="container alert alert-success alert-dismissible" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">x</button>
          Your event has been updated!
        </div>
        ';
      }
      else
      {
        $this->content .= '
        <div class="container alert alert-danger alert-dismissible" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">x</button>
          An error has occurred while deleting the event.<br>
          Error: ' . $success . '
          <br><br>
          Refresh to try again.
        </div>
        ';
      }

      $this->Display_List();
    }

    parent::Display();
  }

  public function Display_Form( $id )
  {
    $this->content .= "
    Form goes here!
    ";
  }
  
  public function Display_List( )
  {
    $events = $this->get_MyEvents( 'poc');//$_SESSION['userID'] );
    
    $this->content .= '
    <ol class="breadcrumb">
      <li>
        <a href="index.php?display=POCMenu" style="cursor:pointer;">
          <i class="glyphicon glyphicon-arrow-left"></i> POC Menu
        </a>
      </li>
    </ol>

    <div class="panel panel-default">
      <!-- Default panel contents -->
      <div class="panel-heading">Here are all the events that you have created.</div>
      <div class="panel-body">
        <a href="index.php?display=CreateEvent" style="cursor:pointer;"><i class="glyphicon glyphicon-plus" title="New Event"></i> New Event</a>
        <i class="glyphicon glyphicon-edit" title="Edit" style="color:orange; padding-left:2em"></i> = Edit
        <i class="glyphicon glyphicon-trash" title="Delete" style="color:red; padding-left:2em"></i> = Delete
        <i class="glyphicon glyphicon-magnet" title="Recover" style="color:green; padding-left:2em"></i> = Recover
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
          <i class="glyphicon glyphicon-user" title="In Person Code" style="color:black"></i> Code
        </th>

        <th>
          <i class="glyphicon glyphicon-headphones" title="Remote Code" style="color:black"></i> Code
        </th>

        <th>
          Approved
        </th>

        <th>
          <i class="glyphicon glyphicon-flag" title="Estimated Budget" style="color:blue"><i class="glyphicon glyphicon-usd" title="Estimated Budget" style="color:black"></i></i>
        </th>

        <th>
          <i class="glyphicon glyphicon-ok" title="Actual Budget" style="color:green"><i class="glyphicon glyphicon-usd" title="Actual Budget" style="color:black"></i></i>
        </th>

        <th>
          Deleted
        </th>

      </thead>

      <tbody>
      ';

    if( sizeof( $events ) === 0 )
    {
      $this->content .= '
      </tbody>
      </table>
      </div>

      <div class="container">
        <h2>There are no future events created by you.</h2>
      </div>
      ';
    }
    else
    {
      foreach ($events as $key => $value)
      {
        $this->content .= '
        <tr>

          <td>
            <a href="index.php?display=POCMyEvents&ed=0&id=' . $value[0] . '" style="cursor:pointer;"><i class="glyphicon glyphicon-edit" title="Edit" style="color: orange"></i></a>
        ';
      if( $value[10] == 0 )
      {
        $this->content .= '
            <a href="index.php?display=POCMyEvents&del=1&id=' . $value[0] . '" style="cursor:pointer;"><i class="glyphicon glyphicon-trash" title="Delete" style="color: red"></i></a>
        ';
      }
      else
      {
        $this->content .= '
            <a href="index.php?display=POCMyEvents&rec=1&id=' . $value[0] . '" style="cursor:pointer;"><i class="glyphicon glyphicon-magnet" title="Recover" style="color: green"></i></a>
        ';
      }
      
      $this->content .= '
          </td>

          <td>
          ' . $value[1] . '
          </td>

          <td>
          ' . $value[2] . '
          </td>

          <td>
          ' . date( 'Y-m-d h:i:sA', strtotime($value[3]) ) . '
          </td>

          <td>
          ' . $value[4] . '
          </td>

          <td>
          ' . $value[5] . '
          </td>

          <td>
          ' . $value[6] . '
          </td>

          <td>
        ';

        if( $value[7] == 1 )
        {
          $this->content .= '
              <i class="glyphicon glyphicon-ok-sign" title="Yes" style="color:green"></i>
          ';
        }
        else
        {
          $this->content .= '
              <i class="glyphicon glyphicon-remove-sign" title="No" style="color:red"></i>
          ';
        }

        $this->content .= '
          </td>

          <td>
          ' . $value[8] . '
          </td>

          <td>
        ';

        if( $value[9] == "" )
        {
          $this->content .= '
              <i class="glyphicon glyphicon-remove-sign" title="No" style="color:red"></i>
        ';
        }
        else
        {
          $this->content .= $value[9];
        }

        $this->content .= '
          </td>

          <td>
        ';
          if( $value[10] == 1 )
          {
            $this->content .= '
                <i class="glyphicon glyphicon-ok-sign" title="Yes" style="color:green"></i>
            ';
          }
          else
          {
            $this->content .= '
                <i class="glyphicon glyphicon-remove-sign" title="No" style="color:red"></i>
            ';
          }

        $this->content .= '
          </td>

        </tr>';
      }

      $this->content .= '
      </tbody>
    </table>
    </div>';
    }
  }

}

?>
