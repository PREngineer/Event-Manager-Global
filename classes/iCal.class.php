<?php

require_once 'autoloader.php';

class iCal
{
  
  //------------------------- Attributes -------------------------
  const DT_FORMAT = 'Ymd\THis\Z';

  private $db = null;
  
  private $properties = array();
  private $available_properties = array(
    'dtStart',
    'dtEnd',
    'summary',
    'description',
    'location',
    'url'
  );
  
  //------------------------- Operations -------------------------
  
  /**
   * __construct
   *
   * @return void
   */
  public function __construct( $data )
  {
    $this->db = new Database();
    $properties['dtStart']      = str_replace( '-','',$data['Date'] ) . ' ' . str_replace(':','',$data['Start']);
    $properties['dtEnd']        = str_replace( '-','',$data['Date'] ) . ' ' . str_replace(':','',$data['End']);
    $properties['summary']      = $data['Symbol']  . ' - '  . $data['Name'];
    $properties['description']  = $data['Name']    . "\\r\\n" . $data['Overview'];
    $properties['location']     = $data['Address'] . ' - (' . $data['Location'] . ')';
    
    $this->set( $properties );
  }
  
  /**
   * set - Sets the properties for the iCal item.
   *
   * @param  mixed $key
   * @param  mixed $val
   *
   * @return void
   */
  public function set($key, $val = false)
  {
    if( is_array( $key ) )
    {
      foreach( $key as $k => $v )
      {
        $this->set($k, $v);
      }
    }
    else
    {
      if( in_array($key, $this->available_properties) )
      {
        $this->properties[$key] = $this->sanitize_val($val, $key);
      }
    }
  }

  /**
   * to_string - Breaks the iCal into text.
   *
   * @return void
   */
  public function to_string()
  {
    $rows = $this->build_props();
    return implode("\r\n", $rows);
  }

  /**
   * build_props - Set the properties of the iCal file.
   *
   * @return void
   */
  private function build_props()
  {
    // Build ICS properties - add header
    $ics_props = array(
      'BEGIN:VCALENDAR',
      'VERSION:2.0',
      'PRODID:-//iCalendar//EN',
      'X-WR-CALNAME:Event',
      'CALSCALE:GREGORIAN',
      'BEGIN:VTIMEZONE',
      'TZID:America/New_York',
      'TZURL:http://tzurl.org/zoneinfo-outlook/America/New_York',
      'X-LIC-LOCATION:America/New_York',
      'BEGIN:DAYLIGHT',
      'TZOFFSETFROM:-0500',
      'TZOFFSETTO:-0400',
      'TZNAME:EDT',
      'DTSTART:19700308T020000',
      'RRULE:FREQ=YEARLY;BYMONTH=3;BYDAY=2SU',
      'END:DAYLIGHT',
      'BEGIN:STANDARD',
      'TZOFFSETFROM:-0400',
      'TZOFFSETTO:-0500',
      'TZNAME:EST',
      'DTSTART:19701101T020000',
      'RRULE:FREQ=YEARLY;BYMONTH=11;BYDAY=1SU',
      'END:STANDARD',
      'END:VTIMEZONE',
      'BEGIN:VEVENT'
    );

    // Build ICS properties - add header
    $props = array();

    // Set some default values
    $props['DTSTAMP'] = $this->format_timestamp('now');
    $props['UID'] = uniqid();

    // Add all the properties set on top
    foreach( $this->properties as $k => $v )
    {
      $props[strtoupper($k . ($k === 'url' ? ';VALUE=URI' : ''))] = $v;
    }

    // Append properties
    foreach( $props as $k => $v )
    {
      $ics_props[] = "$k:$v";
    }

    // Build ICS properties - add footer
    $ics_props[] = 'END:VEVENT';
    $ics_props[] = 'END:VCALENDAR';

    return $ics_props;
  }

  /**
   * sanitize_val - Checks that the times are properly formatted.
   *
   * @param  mixed $val
   * @param  mixed $key
   *
   * @return void
   */
  private function sanitize_val( $val, $key = false )
  {
    switch($key)
    {
      case 'dtEnd':
      case 'dtstamp':
      case 'dtStart':
        $val = $this->format_timestamp( $val );
        break;
      default:
        $val = $this->escape_string( $val );
    }

    return $val;
  }

  /**
   * format_timestamp - Formats the timestamp appropriately.
   *
   * @param  mixed $timestamp
   *
   * @return void
   */
  private function format_timestamp( $timestamp )
  {
    $dt = new DateTime(gmdate('c', strtotime($timestamp)));
    return $dt->format(self::DT_FORMAT);
    // Time has to be in Zulu TimeZone.
    //return str_replace('+00:00', 'Z', gmdate('c', strtotime($timestamp)));
  }

  /**
   * escape_string - Make sure to remove unallowed characters.
   *
   * @param  mixed $str
   *
   * @return void
   */
  private function escape_string( $str )
  {
    return preg_replace('/([\,;])/','\\\$1', $str);
  }
    
}

?>