<?php

require_once 'autoloader.php';

class AdminAnnouncementsBlock
{
  //------------------------- Attributes -------------------------
  
  private $ID       = null;
  private $Title    = null;
  private $Contents = null;
  private $Posted   = null;
  private $Expires  = null;
  
  //------------------------- Operations -------------------------
  
  public function __construct($data)
  {
    $this->ID       = $data['ID'];
    $this->Title    = $data['Title'];
    $this->Contents = $data['Content'];
    $this->Posted   = $data['Posted'];
    $this->Expires  = $data['Expires'];
    $this->Symbol   = $data['Symbol'];
  }

  /**
   * Set
   *
   * @param  mixed $name
   * @param  mixed $value
   *
   * @return void
   */
  public function Set($name, $value)
  {
    $this->$name = $value;
  }

  /**
   * Display - Returns the HTML of the Announcement Blocks
   *
   * @return string
   */
  public function Display()
  {
    return '
            <div id="' . $this->Symbol . '" class="col-lg-11 container thumbnail">
              <table role="presentation" class="table">
              
                <tr>
                  <td style="width: 33%" class="text-muted text-left">
                    Org: ' . $this->Symbol . '
                  </td>
                  <td style="width: 33%" class="text-muted text-center">
                  <a href="index.php?display=AdminEditAnnouncement&id=' . $this->ID . '" style="cursor:pointer;"><i class="glyphicon glyphicon-edit" title="Edit" style="color:orange; padding-left:2em"></i></a>
                  <a href="index.php?display=AdminAnnouncementsMenu&exp=1&id='   . $this->ID . '" style="cursor:pointer;"><i class="glyphicon glyphicon-time" title="Expire" style="color:red; padding-left:2em"></i></a>
                  <a href="index.php?display=AdminAnnouncementsMenu&del=1&id='   . $this->ID . '" style="cursor:pointer;"><i class="glyphicon glyphicon-trash" title="Delete" style="color:red; padding-left:2em"></i></a>
                  </td>
                  <td style="width: 33%" class="text-muted text-right">
                    Posted: ' . $this->Posted . ' | Expires: ' . $this->Expires . '
                  </td>
                </tr>

                <tr>
                  <td colspan="3" class="text-center panel-heading bg-info">
                    <h4><b>' . $this->Title . '</b></h4>
                  </td>
                </tr>
                <tr>
                  <td colspan="3">' . $this->Contents . '</td>
                </tr>
              </table>
            </div>
           ';
  }

}

?>