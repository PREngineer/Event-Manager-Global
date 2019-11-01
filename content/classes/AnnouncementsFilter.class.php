<?php

class AnnouncementsFilter
{
  
  //------------------------- Attributes -------------------------
  private $data = null;
  
  
  //------------------------- Operations -------------------------
  
  /**
   * __construct
   *
   * @param  mixed $data
   *
   * @return void
   */
  public function __construct( $data )
  {
    $this->data = $data;
  }

  /**
   * Display - Returns the HTML of the filter widget
   *
   * @return string
   */
  public function Display()
  {
    $content .= '
      <form class="container" method="POST" action="index.php?display=Announcements" id="filterForm">

            <div class="row">
              <div class="col-lg-4">
                <div class="input-group">
                  <label class="input-group-addon">Filter by Org:</label>
                  <select class="form-control" name="filter">
                    <option>All</option>';

    foreach( $this->data as $one )
    {
      $content .= '
                    <option>' . $one['Symbol'] . '</option>';
    }

    $content .= '                
                  </select>
                  <span class="input-group-btn">
                    <button class="btn btn-primary" type="submit">
                      <span class="glyphicon glyphicon-filter" aria-hidden="true"></span> Filter
                    </button>
                  </span>
                </div>
              </div>
            </div>

      </form><br>
    ';

    return $content;
  }

}

?>