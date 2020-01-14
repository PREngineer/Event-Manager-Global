<?php

require_once 'autoloader.php';

class Mailer
{
  
  //------------------------- Attributes -------------------------
  
  private $SMTPHOSTS          = null;
  private $SMTPAUTHENTICATION = null;
  private $SMTPUSER           = null;
  private $SMTPPASS           = null;
  private $SMTPENC            = null;
  private $SMTPPORT           = null;
  private $SMTPFROMEMAIL      = null;
  private $SMTPFROMNAME       = null;
  private $MYDOMAIN           = null;
  
  //------------------------- Operations -------------------------
  
  /**
   * __construct
   *
   * @return void
   */
  public function __construct()
  {
    if( file_exists('mailSettings.php') )
    {
      require_once 'mailSettings.php';
      
      $this->SMTPHOSTS          = $SMTPHOSTS;
      $this->SMTPAUTHENTICATION = $SMTPAUTHENTICATION;
      $this->SMTPUSER           = $SMTPUSER;
      $this->SMTPPASS           = $SMTPPASS;
      $this->SMTPENC            = $SMTPENC;
      $this->SMTPPORT           = $SMTPPORT;
      $this->SMTPFROMEMAIL      = $SMTPFROMEMAIL;
      $this->SMTPFROMNAME       = $SMTPFROMNAME;
      $this->MYDOMAIN           = $MYDOMAIN;
    }
    else
    {
      // Save settings to file
      $file = 'mailSettings.php';

      $content = '
      <?php
        // Mail related settings
        // sub.domain.tld or IP
        $SMTPHOSTS          = "smtp.domain.tld";
        // True or False
        $SMTPAUTHENTICATION = True;
        // E-mail address or Username
        $SMTPUSER           = "email@address.com";
        // The Password
        $SMTPPASS           = "some password";
        // 1 or 0 / True or False
        $SMTPENC            = 1;
        // 25, 587, or other
        $SMTPPORT           = "25";
        // E-mail address to show up as sender
        $SMTPFROMEMAIL      = "Event.Manager@mycompany.com";
        // Name of the sender
        $SMTPFROMNAME       = "Event Manager";
        // domain.tld
        $MYDOMAIN           = "mydomain.tld";
      ?>';

      file_put_contents($file, $content);
    }
  }

  /**
   * cancelRSVP - Sends the e-mail about cancelling an RSVP
   *
   * @param  mixed $data
   *
   * @return void
   */
  public function cancelRSVP( $data )
  {
    $subject = "Event Manager - RSVP Cancellation Request";

    $msg = '
    <img src="http://' . $_SERVER['HTTP_HOST'] . '/images/event.png" width="200" height="200" alt="Event Manager Banner"/>

    <br><br>
    Hello ' . $data[4] . ',

    <br><br>
    We have received a request to cancel a reservation on ' . date("m/d/Y") . ' through the event management platform.

    <br><br>
    Please confirm that you want to cancel this reservation by clicking the link provided.

    <br><br>

    <hr>

    <br>

    <table>
      <tr>
        <td colspan="2">
        Reservation Information
        </td>
      </tr>
      <tr>
        <td colspan="2">
          ---------------------------------------------------------
        </td>
      </tr>
      <tr>
        <td width="50%">
          Your EID:
        </td>
        <td width="50%">' .
          $data[4] . '
        </td>
      </tr>
      <tr>
        <td>
          Event Name:
        </td>
        <td>' .
          $data[1] . '
        </td>
      </tr>
      <tr>
        <td>
          Event Date:
        </td>
        <td>' .
          $data[2] . '
        </td>
      </tr>
      <tr>
        <td>
          Event Location:
        </td>
        <td>' .
          $data[3] . '
        </td>
      </tr>
      <tr>
        <td>
          Reason for cancellation:
        </td>
        <td>' .
          $data[6] . '
        </td>
      </tr>
      <tr>
        <td>
          Confirm:
        </td>
        <td>
          <a href="http://' . $_SERVER['HTTP_HOST'] . '/index.php?display=CancelRSVP&id=' . $data[0] .
                  '&rsvpid=' . $data[5] . '&code=' . SHA1($data[4]) . '&eid=' . $data[4] . '">CONFIRM CANCELLATION</a>
        </td>
      </tr>
    </table>
    <br><br>

    Thank you,
    <br>
    Event Manager';

    return $this->sendEmail( array( "subject"=>$subject, "msg"=>$msg, "email"=>$data[7], "name"=>$data[4] ) );
  }

  /**
   * notifyRSVPCancelled - Sends the e-mail confirming the RSVP cancellation
   *
   * @param  mixed $data
   *
   * @return void
   */
  public function notifyRSVPCancelled( $data )
  {
    $subject = "Event Manager - RSVP Cancellation Processed";

    $msg = '
    <img src="http://' . $_SERVER['HTTP_HOST'] . '/images/event.png" width="200" height="200" alt="Event Manager Banner"/>

    <br><br>
    Hello ' . $data[4] . ',

    <br><br>
    Your RSVP cancellation request has been processed on ' . date("m/d/Y") . '.

    <br><br>
    Please keep this e-mail for your records.

    <br><br>

    <hr>

    <br>

    <table>
      <tr>
        <td colspan="2">
        Cancelled RSVP
        </td>
      </tr>
      <tr>
        <td colspan="2">
          ---------------------------------------------------------
        </td>
      </tr>
      <tr>
        <td width="50%">
          Your EID:
        </td>
        <td width="50%">' .
          $data[4] . '
        </td>
      </tr>
      <tr>
        <td>
          Event Name:
        </td>
        <td>' .
          $data[1] . '
        </td>
      </tr>
      <tr>
        <td>
          Event Date:
        </td>
        <td>' .
          $data[2] . '
        </td>
      </tr>
      <tr>
        <td>
          Event Location:
        </td>
        <td>' .
          $data[3] . '
        </td>
      </tr>
      <tr>
        <td>
          Reason for cancellation:
        </td>
        <td>' .
          $data[6] . ' - ' . $data[7] . '
        </td>
      </tr>
    </table>
    <br><br>

    Thank you,
    <br>
    Event Manager';

    return $this->sendEmail( array( "subject"=>$subject, "msg"=>$msg, "email"=>$data[8], "name"=>$data[4] ) );
  }

  /**
   * notifyRSVPRegistered - Send an e-mail confirmation of the RSVP with iCalendar.
   *
   * @param  mixed $data
   *
   * @return void
   */
  public function notifyRSVPRegistered( $data )
  {
    $subject = "Event Manager - RSVP Registered";

    $msg = '
    <img src="http://' . $_SERVER['HTTP_HOST'] . '/images/event.png" width="200" height="200" alt="Event Manager Banner"/>

    <br><br>
    Hello ' . $data['Eid'] . ',

    <br><br>
    We have received your RSVP on ' . date("m/d/Y") . ' through the event management platform.

    <br><br>

    <hr>

    <br>

    <table>
      <tr>
        <td colspan="2">
        Reservation Information
        </td>
      </tr>
      <tr>
        <td colspan="2">
          ---------------------------------------------------------
        </td>
      </tr>
      <tr>
        <td width="50%">
          Your EID:
        </td>
        <td width="50%">' .
          $data['Eid'] . '
        </td>
      </tr>
      <tr>
        <td>
          Event Name:
        </td>
        <td>' .
          $data['Name'] . '
        </td>
      </tr>
      <tr>
        <td>
          Event Date:
        </td>
        <td>' .
          $data['Date'] . '
        </td>
      </tr>
      <tr>
        <td>
          Event Location:
        </td>
        <td>' .
          $data['Location'] . '
        </td>
      </tr>
    </table>

    <br><br>

    An iCal has been attached for your convenience, please use it to add a reminder to your calendar.

    <br><br>

    Thank you,
    <br>
    Event Manager<br><br>';

    return $this->sendEmail( array( "subject"=>$subject, "msg"=>$msg, "email"=>$data['Email'], "name"=>$data['Eid'], "iCal"=>$data['iCal'] ) );
  }

  /**
   * sendEmail - Triggers the e-mail to be sent
   *
   * @param  mixed $data
   *
   * @return void
   */
  private function sendEmail( $data )
  {
    require_once 'lib/PHPMailer/class.phpmailer.php';
    require_once 'lib/PHPMailer/class.smtp.php';
    
    // Used to view the e-mail that gets generated when coding changes.
    //echo $msg;

    $mail = new PHPMailer;

    // Set mailer to use SMTP
    $mail->isSMTP();
    // Specify main and backup SMTP servers
    $mail->Host = $this->SMTPHOSTS;
    // Enable SMTP authentication
    $mail->SMTPAuth = $this->SMTPAUTHENTICATION;
    // SMTP username
    $mail->Username = $this->SMTPUSER;
    // SMTP password
    $mail->Password = $this->SMTPPASS;
    // Enable TLS encryption
    if( $this->SMTPENC == 1 )
    {
      $mail->SMTPSecure = 'tls';
    }
    // Enable SSL encryption
    if( $this->SMTPENC == 2 )
    {
      $mail->SMTPSecure = 'ssl';
    }
    // TCP port to connect to
    $mail->Port = $this->SMTPPORT;

    // Set Sender
    $mail->setFrom( $this->SMTPFROMEMAIL, $this->SMTPFROMNAME );
    // Add a recipient
    $mail->addAddress( $data['email'], $data['name'] );

    /*
    if($SMTPREPLYTO !== "")
    {
      $mail->addReplyTo($SMTPREPLYTOEMAIL, $SMTPREPLYTO);
    }

    if(SMTPCC !== "")
    {
      $mail->addCC(SMTPCC);
    }
    if(SMTPBCC !== "")
    {
      $mail->addBCC(SMTPBCC);
    }
    */

    // Set email format to HTML
    $mail->isHTML(true);

    $mail->Subject = $data['subject'];
    $mail->Body    = $data['msg'];
    $mail->AltBody = $data['msg'];

    // Add an iCal string attachment
    if( isset( $data['iCal'] ) )
    {
      $mail->addStringAttachment( $data['iCal'], 'Event.ics', 'base64', 'text/calendar' );
    }
    
    // Add attachments
    //$mail->addAttachment("/var/tmp/file.tar.gz");
    // Optional name
    //$mail->addAttachment(""/tmp/image.jpg", "new.jpg");

    // Add the banner on top
    //$mail->AddEmbeddedImage("../images/logo.png", "banner");

    // Use if having troubles with the e-mail.
    //$mail->SMTPDebug = 2;

    if( !$mail->send() )
    {
      //echo 'Message could not be sent.';
      Return $mail->ErrorInfo;
    }
    else
    {
      //echo 'Message has been sent';
      Return True;
    }
  }
  

}

?>