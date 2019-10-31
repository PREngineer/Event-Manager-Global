<?php

require_once 'autoloader.php';

class AnnouncementsBlock
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
    $this->Symbol   = $data['Symbol'];
  }

  public function Set($name, $value)
  {
    $this->$name = $value;
  }

  public function Display()
  {
    return '
            <div class="col-lg-11 container thumbnail">
              <table role="presentation" class="table">
              
                <tr>
                  <td style="width: 50%" class="text-muted text-left">
                    Org: ' . $this->Symbol . '
                  </td>
                  <td class="text-muted text-right">
                    Posted: ' . $this->Posted . '
                  </td>
                </tr>

                <tr>
                  <td colspan="2" class="text-center panel-heading bg-info">
                    <h3><b>' . $this->Title . '</b></h3>
                  </td>
                </tr>
                <tr>
                  <td colspan="2">' . $this->Contents . '</td>
                </tr>
              </table>
            </div>
           ';
  }

}

?>