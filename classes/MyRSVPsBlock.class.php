<?php

require_once 'autoloader.php';

class MyRSVPsBlock
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
  private $EID      = null;
  private $RSVPID   = null;
  
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
    $this->EID     = $data['EID'];
    $this->RSVPID  = $data['RSVPID'];
  }

  /**
   * Display - Returns the HTML of the Announcement Blocks
   *
   * @return string
   */
  public function Display()
  {
    $url = 'http://' . $_SERVER['HTTP_HOST'] . '/index.php?display=RSVP%26id=' . $this->ID;

    return '
    <div class="col-sm-3">
      <div class="thumbnail" style="height: 710px;">

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
                ' . $this->Location . ' 
              </td>
            </tr>

            <tr>
              <td>
                <a href="https://maps.apple.com/?address=' . str_replace(' ','+',str_replace(',','%2C',$this->Address)) . '">
                  <img src="images/amaps.png">
                </a>
              </td>
              <td>
                <a href="https://www.google.com/maps/dir/?api=1&destination=' . str_replace(' ','+',str_replace(',','%2C',$this->Address)) . '">
                  <img src="images/gmaps.png">
                </a>
              </td>
            </tr>

            <tr>
              <td colspan="2" class="text-center">
                <a href="index.php?display=CancelRSVP&id=' . $this->ID . '&eid=' . $this->EID . '&rsvpid=' . $this->RSVPID . '" class="btn btn-danger" role="button">Cancel my RSVP</a>
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