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
      <form class="form-inline" role="form" method="POST" id="filterForm">

        <div class="form-group">
          <label>Filter by Org:</label>
        </div>

        <div class="form-group">
          <select class="form-control" name="filter">
            <option>All</option>';

    foreach( $this->data as $one )
    {
      $content .= '
            <option>' . $one['Symbol'] . '</option>';
    }

    $content .= '                
          </select>
        </div>
      
        <button type="submit" class="btn btn-primary">
          <span class="glyphicon glyphicon-filter" aria-hidden="true"></span> Filter
        </button>

      </form><br>
    ';

    return $content;
  }

}

?>