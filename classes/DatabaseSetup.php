<?php

class DatabaseSetup
{

  //------------------------- Attributes -------------------------

  private $type = null;
  private $name = null;
  private $user = null;
  private $pass = null;
  private $host = null;
  private $port = null;
  private $conn = null;

  //------------------------- Operations -------------------------

  /**
   * __construct
   *
   * @param  array $data
   *
   * @return void
   */
  public function __construct($data)
  {
    $this->type = $data['dbtype'];
    $this->name = $data['dbname'];
    $this->user = $data['username'];
    $this->pass = $data['password'];
    $this->host = $data['ip'];
    $this->port = $data['port'];
  }

  /**
   * test - Checks whether the database information provided is correct.
   *
   * @return bool
   */
  public function test()
  {
    $success = $this->first_connect();

    if( $success === True )
    {
      // Save settings to file
      $file = 'settings.php';

      $content = '
      <?php
        // Database related settings
        $DBHOST = "' . $this->host . '";
        $DBPORT = "' . $this->port . '";
        $DBNAME = "' . $this->name . '";
        $DBUSER = "' . $this->user . '";
        $DBPASS = "' . $this->pass . '";
        $DBTYPE = "' . $this->type . '";

        // Login related settings
        $LOGINTYPE = "App";
        $LOGINURL  = "localhost";
      ?>';

      file_put_contents($file, $content);

      return True;
    }
    return $success;
  }

  /**
   * first_connect - Establishes a connection to the database.
   *
   * @return bool|string Success|Error
   */
  private function first_connect()
  {
    $dsn = '';

    if($this->type === 'mysql')
    {
      $dsn = 'mysql:host=' . $this->host . ';port=' . $this->port . ';charset=utf8';
    }
    elseif($this->type === 'sqlsrv')
    {
      $dsn = 'sqlsrv:Server=' . $this->host . ',' . $this->port . ';';
    }

    try
    {
      $this->conn =  new PDO($dsn, $this->user, $this->pass);
      $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);;
    }
    catch(PDOException $error)
    {
      return $error->getMessage();
    }

    return True;
  }

  /**
   * connect - Establishes a connection to the database.
   *
   * @return bool|string Success|Error
   */
  private function connect()
  {
    // Set up only if not already connected
    if($this->conn === null)
    {
      $dsn = '';

      if($this->type === 'mysql')
      {
        $dsn = 'mysql:host=' . $this->host . ';port=' . $this->port . ';dbname=' . $this->name . ';charset=utf8';
      }
      elseif($this->type === 'sqlsrv')
      {
        $dsn = 'sqlsrv:Server=' . $this->host . ',' . $this->port . ';Database=testdb';
      }

      try
      {
        $this->conn = new PDO($dsn, $this->user, $this->pass);
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);;
      }
      catch(PDOException $error)
      {
        return $error->getMessage();
      }
    }
    return True;
  }

  /**
   * queryDB - Executes a query against the database.
   *
   * @param  string $query
   *
   * @return bool|string Success|Error
   */
  private function query_DB($query)
  {
    try
    {
      $this->connect();

      $stmt = $this->conn->prepare($query);
      $stmt->execute();

      $stmt = null;
    }
    catch(PDOException $error)
    {
      return $error->getMessage();
    }

    return True;
  }

  /**
   * create_adminUser - Creates the default Admin User.
   *
   * @return bool|string Success|Error
   */
  public function create_adminUser()
  {
    $pass = hash( 'sha256', SHA1( MD5("password") ) );
    $apik = hash( 'sha256', SHA1( MD5( "administrator" . Date("Ymd") ) ) );
    return $this->query_DB("INSERT INTO `Users`
                            (`Username`, `Password`, `Role`, `API_Key`)
                            VALUES ('administrator','" . $pass . "','4','" . $apik . "')"
                          );
  }

  /**
   * setup_AnnouncementsTable - Creates the Announcements Table.
   *
   * @return bool|string Success|Error
   */
  public function setup_AnnouncementsTable()
  {
    return $this->query_DB("CREATE TABLE `Announcements` (
                            `ID` bigint(20) NOT NULL AUTO_INCREMENT,
                            `Org_ID` bigint(20) NOT NULL,
                            `Title` text NOT NULL,
                            `Content` text NOT NULL,
                            `Posted` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                            `Expires` date NOT NULL,
                            PRIMARY KEY (`ID`)
                            )
                            ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT
                            CHARSET=utf8"
                          );
  }

  /**
   * setup_AttendanceTable - Creates the Attendance Table.
   *
   * @return bool|string Success|Error
   */
  public function setup_AttendanceTable()
  {
    return $this->query_DB("CREATE TABLE `Attendance` (
                            `ID` bigint(20) NOT NULL AUTO_INCREMENT,
                            `Event_ID` bigint(20) NOT NULL,
                            `Member_ID` text NOT NULL,
                            `Type` tinyint(1) NOT NULL,
                            `Timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                            PRIMARY KEY (`ID`) )
                            ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT
                            CHARSET=utf8"
                          );
  }

  /**
   * setup_DB - Creates the database.
   *
   * @return bool|string Success|Error
   */
  public function setup_DB()
  {
    // Connect
    $this->first_connect();

    // Create the database
    $query = "CREATE DATABASE `" . $this->name . "`
              DEFAULT CHARACTER SET = 'utf8'
              DEFAULT COLLATE = 'utf8_general_ci'";

    try
    {
      $stmt = $this->conn->prepare($query);
      $result = $stmt->execute();

      $stmt = null;
      $this->conn = null;
    }
    catch(PDOException $error)
    {
      return $error->getMessage();
    }
    return True;
  }

  /**
   * setup_CareerLevelsTable - Creates the Career Levels Table.
   *
   * @return void
   */
  public function setup_CareerLevelsTable()
  {
    $create = $this->query_DB("CREATE TABLE `Career Levels` (
                                `ID` bigint(20) NOT NULL AUTO_INCREMENT,
                                `Level` text NOT NULL,
                                `Name` text NOT NULL,
                              PRIMARY KEY (`ID`)
                              ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT
                              CHARSET=utf8"
     );

    if( $create )
    {
      return $this->query_DB("INSERT INTO `Career Levels` (`Level`, `Name`)
                              VALUES  ('14', 'Intern'),
                                      ('13', 'Associate'),
                                      ('12', 'Associate'),
                                      ('11', 'Analyst'),
                                      ('10', 'Sr. Analyst'),
                                      ('9', 'Consultant'),
                                      ('8', 'Associate Manager'),
                                      ('7', 'Manager'),
                                      ('6', 'Sr. Manager'),
                                      ('L', 'Leadership')"
          );
    }
    else
    {
      return $create;
    }
  }

  /**
   * setup_CompanySegmentsTable - Creates the Company Segments Table.
   *
   * @return void
   */
  public function setup_CompanySegmentsTable()
  {
    $create = $this->query_DB("CREATE TABLE `Company Segments` (
                                `ID` bigint(20) NOT NULL AUTO_INCREMENT,
                                `Segment` text NOT NULL,
                              PRIMARY KEY (`ID`)
                              ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT
                              CHARSET=utf8"
     );

    if( $create )
    {
      return $this->query_DB("INSERT INTO `Company Segments` (`Segment`)
                              VALUES  ('Federal'),
                                      ('Commercial')"
          );
    }
    else
    {
      return $create;
    }
  }

  /**
   * setup_EventChangeLogTable - Creates the Event Change Log Table.
   *
   * @return bool|string Success|Error
   */
  public function setup_EventChangeLogTable()
  {
    return $this->query_DB("CREATE TABLE `Event Change Log` (
                              `ID` bigint(20) NOT NULL AUTO_INCREMENT,
                              `Event_ID` bigint(20) NOT NULL,
                              `Changed_By_ID` text NOT NULL,
                              `Type` text NOT NULL,
                              `Reason` text NOT NULL,
                              `Timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                            PRIMARY KEY (`ID`)
                            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT
                            CHARSET=utf8"
                          );
  }

  /**
   * setup_EventObjectivesTable - Creates the Event Objectives Table.
   *
   * @return bool|string Success|Error
   */
  public function setup_EventObjectivesTable()
  {
    $create = $this->query_DB("CREATE TABLE `Event Objectives` (
                                `ID` BIGINT NOT NULL,
                                `Target` BIGINT NOT NULL,
                                `Type` BIGINT NOT NULL,
                                `Name` TEXT NOT NULL)
                                ENGINE  = InnoDB
                                CHARSET = utf8
                                COLLATE utf8_general_ci"
                             );

    if( $create )
    {
      return $this->query_DB("INSERT INTO `Event Objectives` (`ID`, `Target`, `Type`, `Name`)
                              VALUES  ('1',  '1', '1', 'Networking'),
                                      ('2',  '1', '2', 'Mentorship'),
                                      ('3',  '1', '2', 'Skills to Succeed'),
                                      ('4',  '1', '2', 'Leading in the New'),
                                      ('5',  '1', '2', 'Community of Practice'),
                                      ('6',  '1', '3', 'Networking'),
                                      ('7',  '1', '3', 'Networking (Happy Hour)'),
                                      ('8',  '1', '4', 'Corporate Citizenship'),
                                      ('9',  '2', '5', 'Philantropy'),
                                      ('10', '2', '5', 'Fundraiser'),
                                      ('11', '2', '6', 'I&D Recruiting'),
                                      ('12', '2', '7', 'Sponsorship'),
                                      ('13', '3', '8', 'Prospective Clients'),
                                      ('14', '3', '8', 'Client Connection'),
                                      ('15', '3', '9', 'Sponsorship'),
                                      ('16', '3', '9', 'Networking'),
                                      ('17', '3', '9', 'Networking (Happy Hour)'),
                                      ('18', '3', '10', 'Relationship I&D'),
                                      ('19', '3', '10', 'Cross Org')"
                            );
    }
    else
    {
      return $create;
    }
  }

  /**
   * setup_EventsTable - Creates the Events Table.
   *
   * @return bool|string Success|Error
   */
  public function setup_EventsTable()
  {
    return $this->query_DB("CREATE TABLE `Events` (
                            `ID` bigint(20) NOT NULL AUTO_INCREMENT,
                            `Org_ID` bigint(20) NOT NULL,
                            `Name` text NOT NULL,
                            `Overview` text NOT NULL,
                            `Date` date NOT NULL,
                            `Start` time NOT NULL,
                            `End` time NOT NULL,
                            `Estimated_Budget` double NOT NULL,
                            `Actual_Budget` double DEFAULT NULL,
                            `Location` text NOT NULL,
                            `Address` text NOT NULL,
                            `Committee_ID` text NOT NULL,
                            `Target` text NOT NULL,
                            `Type` text NOT NULL,
                            `Objective` text NOT NULL,
                            `Created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                            `Creator_User_ID` text NOT NULL,
                            `Person_Code` text NOT NULL,
                            `Remote_Code` text NOT NULL,
                            `Approved` tinyint(1) NOT NULL DEFAULT '0',
                            `Deleted` tinyint(1) NOT NULL DEFAULT '0',
                            PRIMARY KEY (`ID`)
                            )
                            ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT
                            CHARSET=utf8"
                          );
  }

  /**
   * setup_EventTargetsTable - Creates the Event Targets Table.
   *
   * @return bool|string Success|Error
   */
  public function setup_EventTargetsTable()
  {
    $create = $this->query_DB("CREATE TABLE `Event Targets` (
                                `ID` BIGINT NOT NULL,
                                `Name` TEXT NOT NULL)
                                ENGINE  = InnoDB
                                CHARSET = utf8
                                COLLATE utf8_general_ci"
                             );

    if( $create )
    {
      return $this->query_DB("INSERT INTO `Event Targets` (`ID`, `Name`)
                              VALUES  ('1', 'People'),
                                      ('2', 'Community'),
                                      ('3', 'Market')"
                            );
    }
    else
    {
      return $create;
    }
  }

  /**
   * setup_EventTypesTable - Creates the Event Types Table.
   *
   * @return bool|string Success|Error
   */
  public function setup_EventTypesTable()
  {
    $create = $this->query_DB("CREATE TABLE `Event Types` (
                                `ID` BIGINT NOT NULL,
                                `Target_ID` BIGINT NOT NULL,
                                `Name` TEXT NOT NULL,
                                PRIMARY KEY (`ID`))
                                ENGINE  = InnoDB
                                CHARSET = utf8
                                COLLATE utf8_general_ci"
                             );

    if( $create )
    {
      return $this->query_DB("INSERT INTO `Event Types` (`ID`, `Target_ID`, `Name`)
                              VALUES  ('1',  '1', 'Attract'),
                                      ('2',  '1', 'Development'),
                                      ('3',  '1', 'Retain'),
                                      ('4',  '1', 'Vigilance & Visibility'),
                                      ('5',  '2', 'Service'),
                                      ('6',  '2', 'Attract'),
                                      ('7',  '2', 'Vigilance & Visibility'),
                                      ('8',  '3', 'Attract'),
                                      ('9',  '3', 'Develop'),
                                      ('10', '3', 'Vigilance & Visibility')"
                            );
    }
    else
    {
      return $create;
    }
  }

  /**
   * setup_LeadsTable - Creates the Leads Table.
   *
   * @return bool|string Success|Error
   */
  public function setup_LeadsTable()
  {
    return $this->query_DB("CREATE TABLE `Leads` (
                              `ID` BIGINT NOT NULL,
                              `Org_ID` BIGINT NOT NULL,
                              `Committee_ID` TEXT NOT NULL,
                              `Member_ID` BIGINT NOT NULL,
                              PRIMARY KEY (`ID`))
                              ENGINE  = InnoDB
                              CHARSET = utf8
                              COLLATE utf8_general_ci"
                          );
  }

  /**
   * setup_MembershipTable - Creates the Membership Table.
   *
   * @return bool|string Success|Error
   */
  public function setup_MembershipTable()
  {
    return $this->query_DB("CREATE TABLE `Membership` (
                              `ID` bigint(20) NOT NULL AUTO_INCREMENT,
                              `Org_ID` bigint(20) NOT NULL,
                              `Member_ID` bigint(20) NOT NULL,
                              PRIMARY KEY (`ID`)
                            )
                            ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT
                            CHARSET=utf8"
                          );
  }

  /**
   * setup_MembersTable - Creates the Members Table.
   *
   * @return bool|string Success|Error
   */
  public function setup_MembersTable()
  {
    return $this->query_DB("CREATE TABLE `Members` (
                              `ID` bigint(20) NOT NULL AUTO_INCREMENT,
                              `EID` text NOT NULL,
                              `FName` text NOT NULL,
                              `Initials` text,
                              `LName` text NOT NULL,
                              `Level` int(11) NOT NULL,
                              `Title` text,
                              `Segment` text NOT NULL,
                              `Email` text NOT NULL,
                              `Newsletter` tinyint(1) NOT NULL DEFAULT '0',
                              `Volunteer` tinyint(1) NOT NULL DEFAULT '0',
                              `Active` tinyint(1) NOT NULL DEFAULT '0',
                              `Lead` tinyint(1) NOT NULL DEFAULT '0',
                              `Role` int(11) NOT NULL DEFAULT '0',
                              PRIMARY KEY (`ID`)
                            )
                            ENGINE=InnoDB AUTO_INCREMENT=104 DEFAULT
                            CHARSET=utf8"
                          );
  }

  /**
   * setup_OrgsTable - Creates the Orgs Table.
   *
   * @return bool|string Success|Error
   */
  public function setup_OrgsTable()
  {
    $create = $this->query_DB("CREATE TABLE `Orgs` (
                              `ID` bigint(20) NOT NULL AUTO_INCREMENT,
                              `Symbol` VARCHAR(255) NOT NULL,
                              `Name` VARCHAR(255) NOT NULL,
                              `Location` VARCHAR(255) NOT NULL,
                              `Region` VARCHAR(255) NOT NULL,
                              `Country` VARCHAR(255) NOT NULL,
                              PRIMARY KEY (`ID`))
                              ENGINE  = InnoDB
                              CHARSET = utf8
                              COLLATE utf8_general_ci"
                             );

    if( $create )
    {
      return $this->query_DB("INSERT INTO `Orgs` (`ID`, `Symbol`, `Name`, `Location`, `Region`, `Country`)
                              VALUES  ('0', 'GLOBAL',  'All Orgs', 'All Locations', 'All Regions', 'All Countries')"
                            );
    }
    else
    {
      return $create;
    }
  }

  /**
   * setup_RSVPTable - Creates the RSVP Table.
   *
   * @return bool|string Success|Error
   */
  public function setup_RSVPTable()
  {
    return $this->query_DB("CREATE TABLE `RSVP` (
                              `ID` BIGINT NOT NULL AUTO_INCREMENT,
                              `Event_ID` BIGINT NOT NULL,
                              `Enterprise_ID` TEXT NOT NULL,
                              `Cancel` BOOLEAN NOT NULL,
                              `Cancel_Reason` TEXT,
                              `Cancel_Timestamp` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
                              `Register_Timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                              PRIMARY KEY (`ID`) )
                              ENGINE  = InnoDB
                              CHARSET = utf8
                              COLLATE utf8_general_ci"
                          );
  }

  /**
   * setup_SponsorCommitteesTable - Creates the Sponsor Committees Table
   *
   * @return bool|string Success|Error
   */
  public function setup_SponsorCommitteesTable()
  {
    $create = $this->query_DB("CREATE TABLE `Sponsor Committees` (
                              `ID` VARCHAR(255) NOT NULL,
                              `Name` TEXT NOT NULL,
                              PRIMARY KEY (`ID`))
                              ENGINE  = InnoDB
                              CHARSET = utf8
                              COLLATE utf8_general_ci"
                             );

    if( $create )
    {
      return $this->query_DB("INSERT INTO `Sponsor Committees` (`ID`, `Name`)
                              VALUES  ('1', 'Recruiting'),
                                      ('2', 'Local Marketing'),
                                      ('3', 'Membership Engagement'),
                                      ('4', 'Professional Development'),
                                      ('5', 'Community Outreach'),
                                      ('6', 'Communication'),
                                      ('7', 'Advisory Board'),
                                      ('8', 'Lead Sponsor'),
                                      ('9', 'Co-Leads'),
                                      ('10','Support - Metrics & Compliance')"
                            );
    }
    else
    {
      return $create;
    }
  }

  /**
   * setup_UsersTable - Creates the Users Table.
   *
   * @return bool|string Success|Error
   */
  public function setup_UsersTable()
  {
    return $this->query_DB("CREATE TABLE `Users` (
                            `Username` VARCHAR(200) NOT NULL,
                            `Member_ID` BIGINT DEFAULT NULL,
                            `Password` TEXT NOT NULL,
                            `Role` BIGINT NOT NULL,
                            `API_Key` TEXT NOT NULL,
                            PRIMARY KEY (`Username`))
                            ENGINE  = InnoDB
                            CHARSET = utf8
                            COLLATE utf8_general_ci"
                          );
  }

}

?>
