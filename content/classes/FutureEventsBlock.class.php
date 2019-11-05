<?php

require_once 'autoloader.php';

class FutureEventsBlock
{
  //------------------------- Attributes -------------------------
  
  private $ID       = null;
  private $Name     = null;
  private $Overview = null;
  private $Date     = null;
  private $Start    = null;
  private $End      = null;
  private $Location = null;
  private $Address  = null;
  private $Org      = null;
  
  //------------------------- Operations -------------------------
  
  public function __construct($data)
  {
    $this->ID = $data['ID'];

    if( strlen( $data['Name'] ) > 50 )
    {
      $this->Name = substr( $data['Name'], 0, 46) . '...';
    }
    else
    {
      $this->Name = $data['Name'];
    }

    if( strlen( $data['Overview'] ) > 200 )
    {
      $this->Overview = substr( $data['Overview'], 0, 196) . '...';
    }
    else
    {
      $this->Overview = $data['Overview'];
    }

    $this->Date  = $data['Date'];
    $this->Start = $data['Start'];
    $this->End   = $data['End'];

    if( strlen( $data['Location'] ) > 56 )
    {
      $this->Location = substr( $data['Location'], 0, 52) . '...';
    }
    else
    {
      $this->Location = $data['Location'];
    }

    $this->Address = $data['Address'];
    $this->Org     = $data['Org'];
  }

  /**
   * Display - Returns the HTML of the Announcement Blocks
   *
   * @return string
   */
  public function Display()
  {
    $url = 'http://' . $_SERVER['HTTP_HOST'] . preg_split("/content/", $_SERVER['PHP_SELF'])[0] . 'content/index.php?display=FutureEventDetails%26id=' . $this->ID;

    return '
    <div class="col-sm-3">
      <div class="thumbnail" style="height: 640px;">

        <div class="caption">

          <div class="text-center">
            <img src="../images/event.png" alt="Event image" width="150" height="150">
            <br>
            <label>' . $this->Org . '</label>
          </div>

          <table role="presentation" class="table">

            <tr>
              <td style="height: 60px;" class="text-center" colspan="2">
                <strong>
                  <a href="index.php?display=FutureEventDetails&id=' . $this->ID . '">' . $this->Name . '</a>
                </strong>
              </td>
            </tr>

            <tr>
              <td style="height: 150px;" colspan="2">' . $this->Overview . '</td>
            </tr>

            <tr>
              <td>Date:</td>
              <td>' . $this->Date . '</td>
            </tr>

            <tr>
              <td>Starts:</td>
              <td>' . $this->Start . '</td>
            </tr>

            <tr>
              <td>Ends:</td>
              <td>' . $this->End . '</td>
            </tr>

            <tr>
              <td style="height: 80px;" >Location:</td>
              <td>
                ' . $this->Location . ' <a href="https://www.google.com/maps/dir/?api=1&destination=' . str_replace(' ','+',str_replace(',','%2C',$this->Address)) . '">
                <span class="glyphicon glyphicon-pushpin" aria-hidden="true"></span></a>
              </td>
            </tr>

            <tr>
              <td class="text-center">
                <a href="index.php?display=RSVP&id=' . $this->ID . '" class="btn btn-primary" role="button">RSVP</a>
              </td>
              <td class="text-center">
                <a href="mailto:?subject=Thought%20you%20would%20like%20to%20know&body=Check%20out%20this%20event.%0A%0AIt is named: ' . str_replace('=','',str_replace('&','and',str_replace('?','',$this->Name))) .
                '%0A%0ALocation: ' . $this->Location . '%0A%0ADate: ' . $this->Date . '%0A%0AFrom: ' . $this->Start . '%20-%20' . $this->End .
                '%0A%0ARSVP here: ' . $url . '" class="btn btn-success" role="button">Share</a>
              </td>
            </tr>

          </table>

        </div>
      </div>
    </div>
    ';
  }

}

?>