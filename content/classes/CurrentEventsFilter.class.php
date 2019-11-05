<?php

class CurrentEventsFilter
{
  
  //------------------------- Attributes -------------------------
  
  
  
  //------------------------- Operations -------------------------
  
  /**
   * __construct
   *
   * @param  mixed $data
   *
   * @return void
   */
  public function __construct()
  {
  
  }

  /**
   * Display - Returns the HTML of the filter widget
   *
   * @return string
   */
  public function Display()
  {
    $content .= '
      <form class="form-inline" role="form" method="POST" action="index.php?display=CurrentEvents" id="filterForm">

            <div class="form-group">
              <label>Filter by:</label>
            </div>

            <div class="form-group">
              <select class="form-control" name="filter">
                <option>Show All</option>
                <option>Org</option>
                <option>Name</option>
                <option>Overview</option>
                <option>Date</option>
                <option>Start</option>
                <option>Location</option>
                <option>Address</option>
              </select>
            </div>

            <div class="form-group">
              <label>Value:</label>
            </div>

            <div class="form-group">
              <input name="search" type="text" class="form-control" placeholder="Search for...">
            </div>

            <button type="submit" class="btn btn-primary">
              <span class="glyphicon glyphicon-filter" aria-hidden="true"></span> Filter
            </button>
        </form>
        <br>
    ';

    return $content;
  }

}

?>