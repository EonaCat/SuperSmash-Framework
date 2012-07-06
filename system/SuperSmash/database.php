<?php

/* PDO Database connection class
 *
 * @author:      SuperSmash
 * @copyright:   SuperSmash
 * @version:     1.0
 *
 */

 /*
  * How to use this class :
  *
  * Create a database connection: 
  *
  * $database = new database("databaseType","serverIP","databaseName","userName","password","(optional) port"); 
  * Example :  $database = new database ("mysql","localhost","school","root","myPassword","");
  *
  * Check if the database connection was successfull 
  * 
  * if (!$database) {
  *     die ("Database connection cannot be established");   
  * }
  *
  * Get the latest database error
  * 
  * echo $database->getError();
  *
  * Execute a query to the database:
  * 
  * $database->query("SELECT * FROM databaseName WHERE id = '1'";)
  * 
  * You can also get the inserted ID of the query you executed:
  * 
  * $insertedId = $database->insert("TABLE", "record1,record2,record3", "value1,value2,value3";)
  * Example: INSERT INTO USERS,firstname,lastname,VALUES('Amber','Heard') WHERE ADDRESS = 'Dark avenue 2';
  * Example: $insertedID = $database->insert("USERS","ADDRESS='Dark avenue 2','Amber',Heard'");
  *
  * Get the rowCount of an execute query
  *
  * echo $database->rowcount();
  *
  * Delete a row in the database
  *
  * $database->query("DELETE FROM USERS WHERE ID=1;"); 
  * 
  * Get the affected rows affected by the delete statement
  * 
  * $affectedRows = $database->delete("USERS", "ID=1");
  *
  * Update rows in the database
  *
  * Example:    $database->query("UPDATE USERS SET firstname='Joyce' WHERE ID=1;"); 
  * Example 2:  $affectedRows = $database->update("USERS", "FIRSTNAME='Joyce'", "ID=1"); 
  *
  * Get the ID that was inserted last in the database
  * 
  * $lastID = $database->getLatestId("Table","recordName"); 
  *
  * Example: $lastID = $database->getLatestId("USERS","Firstname");
  *
  * Create a Anti-SQL injection statement to the database
  *
  * $parameters = array(":id@0@INT", ":firstname@amber@STR"); 
  * $ID = $database->query_secure("INSERT INTO USERS (id,firstname) VALUES(:id,:firstname);", $parameters, false); 
  * (If the last false statement in the query above is set to true the class will return the resultset of the record)
  * (else if the last false statement in the query above is set to false you will get a true of false idenitifying if * the query was executed successfully to the database).
  *
  * The $ID variable will now hold the last inserted ID.
  * 
  * Show all the table that are in your database
  * 
  * $result = $database->ShowTables("databaseName");
  * Example: $result = $database->ShowTables("school"); 
  *
  * Output the result to the screen in a clean way:
  *
  * foreach($result as $row){ 
  * $i++;
  * echo "$row[$i]" . "<br />";
  *
  * Show all the databases this class holds (get all the databases you got permissions for)
  *
  * $result = $database->showDatabases();
  *
  * Output the result to the screen in a clean way:
  *
  * foreach($result as $row){ 
  * $i++;
  * echo "$row[$i]" . "<br />";
  *
  * close the database connection;
  *
  * $database->close();
 */

class Database
{

    // Create an array with all the database types this class can connect to
    private $database_types = array("databaselibrary",        // Database Library (used by PHP)
                                    "firebird",               // firebird
                                    "ibm",                    // IBM
                                    "informix",               // Informix
                                    "mssql",                  // Microsoft SQL (<= 2000)
                                    "mysql",                  // mysql
                                    "odbc",                   // Open database connectivity (Microsoft Access)
                                    "oracle",                 // Oracle
                                    "postgre",                // Postgre SQL
                                    "sqlite2",                // SQLite 2
                                    "sqlite3",                // SQLite 3
                                    "sql",                    // Microsoft SQL
                                    );

    private $server;        // This variable holds the host name of the server (serverName) used by the application
    private $database;      // This variable holds the databaseName of the database used by the application
    private $user;          // This variable holds the userName of the database used by the application
    private $password;      // This variable holds the password of the database used by the application
    private $port;          // This variable holds the port of the database used by the application
    private $database_type; // This variable holds the database type of the database used by the application
    private $root_mdb;      // This variable holds the root mdb of the database used by the application
    private $debug = false; // This variable holds the debugging state of the class
    
    private $sql;           // This variable holds all the sql connection parameters
    private $con;           // This variable holds the connection of the database used by the application
    private $err_msg = "";  // This variable holds the error message that was trown by the database (if exists)
    
    /**
    * Create the constructor
    * @param string $database_types (specify the database connection)
    * 
    * These are the database connection that are accepted by this class:
    * 
    * connection name   -   connection description
    * --------------------------------------------
    * databaselibrary       Database Library database (used by PHP)
    * firebird              firebird database
    * ibm                   IBM database
    * informix              Informix database
    * mssql                 Microsoft SQL Server  database (V. 2000 and lower)
    * mysql                 mysql database
    * odbc                  Open database connectivity (Microsoft Access)
    * oracle                Oracle database
    * postgre               postgre SQL database
    * sqlite2               SQLite 2 database
    * sqlite3               SQLite 3 database
    * sql                   Microsoft SQL
    * 
    * @param string $server     (The server where the database is located)
    * @param string $database   (The name of the database)
    * @param string $user       (The userName of the connection to the database)
    * @param string $password   (The password of the connection to the database)
    *
    */
    
    // Create the constructor and initialise the connection to the specified server
    public function __construct($database_type,$server,$database,$user,$password,$port)
    {
        $this->database_type = strtolower($database_type);
        $this->server = $server;
        $this->database = $database;
        $this->user = $user;
        $this->password = $password;
        $this->port = $port;
    }
    
    // initialise class and connects to the database
    public function open()
    {
        if(in_array($this->database_type, $this->database_types))
        {   
            try {
                switch ($this->database_type)
                {

                    // Database Library connection

                    case "databaselibrary":     // default port used by database => 10060
                            $this->con = new PDO("dblib:host=".$this->server.
                                                 ":".$this->port.";
                                                 dbname=".$this->database,$this->user,$this->password
                                                );
                            break;
                
                    // firebird connection

                    case "firebird":            // default port used by database => 3050
                            $this->con = new PDO("firebird:dbname=".$this->server.
                                                 ":".$this->database, $this->user, $this->password
                                                );
                            break;
                
                    // ibm connection

                    case "ibm":
                                $this->con = new PDO("ibm:DRIVER={IBM DB2 ODBC DRIVER};DATABASE=".$this->database.";  HOSTNAME=".$this->host.";
                                    PORT=".$this->port.";
                                    PROTOCOL=TCPIP;", 
                                    $this->user, $this->password
                                );
                            break;
                    
                    // informix connection
                    
                    case "informix":
                            $this->con = new PDO("informix:DSN=InformixDB", $this->user, $this->password);
                            break;
                    
                    // mssql connection
                    
                    case "mssql":
                            $this->con = new PDO("mssql:host=".$this->server.";
                                                 dbname=".$this->database, $this->user, $this->password
                                                );
                            break;

                    // mysql connection
                    
                    case "mysql":
                        if ($this->port != "") {
                            $this->con = new PDO("mysql:host=".$this->server.";
                                                  port=".$this->port.";
                                                  dbname=".$this->database, $this->user, $this->password
                                                );
                        }else{
                            $this->con = new PDO("mysql:host=".$this->server.";
                                                 dbname=".$this->database, $this->user, $this->password
                                                );
                        }
                        break;                   

                    // open database connectivity
                    
                    case "odbc":
                        $this->con = new PDO("odbc:Driver={Microsoft Access Driver (*.mdb)};
                                             Dbq=C:\accounts.mdb;Uid=".$this->user
                                            );
                        break;
                    
                    // oracle connection
                    
                    case "oracle":
                        $this->con = new PDO("OCI:dbname=".$this->database.";
                                             charset=UTF-8", $this->user, $this->password
                                            );
                        break;
                    
                    // postgre connection
                    
                    case "postgre":
                        
                        if($this->port!="")
                        {
                            $this->con = new PDO("pgsql:dbname=".$this->database.";
                                                 port=".$this->port.";
                                                 host=".$this->server, $this->user, $this->password
                                                );
                        }
                        else
                        {
                            $this->con = new PDO("pgsql:dbname=".$this->database.";
                                                 host=".$this->server, $this->user, $this->password
                                                );
                        }
                    break;

                    // sqlite2 connection
                    
                    case "sqlite2":
                        $this->con = new PDO("sqlite:".$this->server);
                        break;
                    
                    // sqlite3 connection
                    
                    case "sqlite3":
                        $this->con = new PDO("sqlite::memory");
                        break;
                    
                    // sql connection
                    
                    case "sql":
                        $this->con = new PDO("sqlsrv:server=".$this->server.";
                                             database=".$this->database, $this->user, $this->password
                                            );
            }
                
                    if ($this->debug)
                    {
                       $this->showDebugInformation();
                    } 
                        else 
                        {
                                    // Create the exception that will be thrown by the PDO if there is an error
                                    $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  
                        }
                return $this->con;
            }
            catch(PDOException $e)
            {
                $this->log ("Error: ". $e->getMessage());
                return false;
            }
        }
        else
        {
            $this->log("The parameters that are given are invalid. <br />
                        Possible reasons: <br /><br />
                        1. The parameters that are given are invalid for the class. <br />
                        2. The database connection is not supported by the class.<br /><br />
                        Please contact the administrator of the application. => info@SuperSmash.nl"
                    );
            return false;
        }
    }
    
            private function log($message)
            {
                $this->err_msg = "SuperSmash Database Connection class:<br />
                                  =====================================<br /><br />
                                  $message";
            }

            private function showDebugInformation(){
        
                // Create the exception that will be thrown by the PDO if there is an error
                $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // The following extra attributes will only be shown in debugging mode

                    // Create the exception that will be thrown by the PDO if there is a warning
                    $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
                
                    // Create the exception that will be thrown by the PDO if there is an error  (silent mode)
                    $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
                                
                // Log all database connection information (for debugging)     
                echo "SuperSmash Database debug information:<br />
                                  ============================<br /><br />";
                    echo "Status: " . $this->con->getAttribute(PDO::ATTR_CONNECTION_STATUS) . "<br />";
                    echo "Drivername: " . $this->con->getAttribute(PDO::ATTR_DRIVER_NAME) . "<br />";
                    echo "Serverversion: " . $this->con->getAttribute(PDO::ATTR_SERVER_VERSION) . "<br />";
                    echo "Clientversion: " . $this->con->getAttribute(PDO::ATTR_CLIENT_VERSION) . "<br />";
                    echo "Serverinfo: " . $this->con->getAttribute(PDO::ATTR_SERVER_INFO) . "<br />";
                    die();

            }

    // Print all the available drivers to the screen (for instant debugging off supported drivers)
    public function drivers()
    {
        print_r(PDO::getAvailableDrivers()); 
    }

    //Execute the query to the database
    public function query($sql_statement)
    {
        $this->err_msg = "";
        if($this->con!=null)
        {
            try 
            {
                $this->sql=$sql_statement;
                return $this->con->query($this->sql);
            } 
            catch(PDOException $e) 
            {
                $this->log("Error: ". $e->getMessage());
                return false;
            }
        }
        else
        {
            $this->log("Error: Connection to database lost.");
            return false;
        }
    }
    
    //Execute queries with Anti SQL injection
    public function query_secure($sql_statement, $params, $fetch_rows=false)
    {
        $this->err_msg = "";
        if($this->con!=null)
        {
            $obj = $this->con->prepare($sql_statement);
            for($i=0;$i<count($params);$i++)
            {
                $params_split = explode("@",$params[$i]);
                if($params_split[2]=="INT")
                    $obj->bindParam($params_split[0], $params_split[1], PDO::PARAM_INT);
                else
                    $obj->bindParam($params_split[0], $params_split[1], PDO::PARAM_STR);
            }
            try 
            {
                $obj->execute();
            }
            catch(PDOException $e)
            {
                $this->log("Error: ". $e->getMessage());
                return false;
            }
            if($fetch_rows)
                return $obj->fetchAll();
            if(is_numeric($this->con->lastInsertId()))
                return $this->con->lastInsertId();
            return true;
        }
        else
        {
            $this->log("Error: Connection to database lost.");
            return false;
        }
    }
    
    // Get the first row of a query in the database
    public function query_first($sql_statement)
    {
        $this->err_msg = "";
        if($this->con!=null)
        {
            try 
            {
                $sttmnt = $this->con->prepare($sql_statement);
                $sttmnt->execute();
                return $sttmnt->fetch();
            } 
            catch(PDOException $e) 
            {
                $this->log("Error: ". $e->getMessage());
                return false;
            }
        }
        else
        {
            $this->log("Error: Connection to database lost.");
            return false;
        }
    }
    
    // Get the first tableCell from a query in the database
    public function query_single($sql_statement)
    {
        $this->err_msg = "";
        if($this->con!=null)
        {
            try 
            {
                $sttmnt = $this->con->prepare($sql_statement);
                $sttmnt->execute();
                return $sttmnt->fetchColumn();
            } 
            catch(PDOException $e)
            {
                $this->log("Error: ". $e->getMessage());
                return false;
            }
        }
        else
        {
            $this->log("Error: Connection to database lost.");
            return false;
        }
    }
    
    // Return the rowcount of a query in the database
    public function rowcount()
    {
        $this->err_msg = "";
        if($this->con!=null)
        {
            try 
            {
                $stmnt_tmp = $this->stmntCount($this->sql);
                if($stmnt_tmp!=false && $stmnt_tmp!="")
                {
                    return $this->query_single($stmnt_tmp);
                }
                else
                {
                    $this->log("Error: A few data required.");
                    return -1;
                }
            } 
            catch(PDOException $e)
            {
                $this->log("Error: ". $e->getMessage());
                return -1;
            }
        }
        else
        {
            $this->log("Error: Connection to database lost.");
            return false;
        }
    }
    
    // Return all the colum names in the database (as an array)
    public function columns($table)
    {
        $this->err_msg = "";
        $this->sql="Select * From $table";
        if($this->con!=null)
        {
            try 
            {
                $q = $this->con->query($this->sql);
                $column = array();
                foreach($q->fetch(PDO::FETCH_ASSOC) as $key=>$val)
                {
                     $column[] = $key;
                }
                return $column;
            } 
            catch(PDOException $e)
            {
                $this->log("Error: ". $e->getMessage());
                return false;
            }
        }
        else
        {
            $this->log("Error: Connection to database lost.");
            return false;
        }
    }
    
    // Insert the query and get the new ID from the database
    public function insert($table, $data){
        $this->err_msg = "";
        if($this->con!=null)
        {
            try 
            {
                $texto = "Insert Into $table (";
                $texto_extra = ") Values (";
                $texto_close = ")";
                $data_column = explode(",", $data);
                for($x=0;$x<count($data_column);$x++)
                {
                    $data_content = explode("=", $data_column[$x]); //0=Field, 1=Value
                    if($x==0)
                      { 
                        $texto.= $data_content[0]; 
                      }
                    else
                    { 
                      $texto.= "," . $data_content[0]; }
                    if($x==0)
                      { 
                        $texto_extra.= $data_content[1]; 
                      }
                      else
                        {
                         $texto_extra.= "," . $data_content[1]; 
                        }                  
                }
                $this->con->exec("$texto $texto_extra $texto_close");
                return $this->con->lastInsertId();
            } 
            catch(PDOException $e) 
            {
                $this->log("Error: ". $e->getMessage());
                return false;
            }
        }
        else
        {
            $this->log("Error: Connection to database lost.");
            return false;
        }
    }
    
    // Update the tables in the database
    public function update($table, $data, $condition="")
    {
        $this->err_msg = "";
        if($this->con!=null)
        {
            try 
            {
                return (trim($condition)!="") ? $this->con->exec("update $table set $data where $condition") : $this->con->exec("update $table set $data");
            }
             catch(PDOException $e) 
             {
                $this->err_msg = "Error: ". $e->getMessage();
                return false;
            }
        }
        else
        {
            $this->log("Error: Connection to database lost.");
            return false;
        }
    }
    
    // Delete a record from the database
    public function delete($table, $condition="")
    {
        $this->err_msg = "";
        if($this->con!=null)
        {
            try
            {
                return (trim($condition)!="") ? $this->con->exec("delete from $table where $condition") : $this->con->exec("delete from $table");
            } 
            catch(PDOException $e)
            {
                $this->log("Error: ". $e->getMessage());
                return false;
            }
        }
        else
        {
            $this->log("Error: Connection to database lost.");
            return false;
        }
    }
    
    //Execute Store Procedures
    public function execute($sp_query)
    {
        $this->err_msg = "";
        if($this->con!=null)
        {
            try 
            {
                $this->con->exec("$sp_query");
                return true;
            } 
            catch(PDOException $e)
            {
                $this->log("Error: ". $e->getMessage());
                return false;
            }
        }
        else
        {
            $this->log("Error: Connection to database lost.");
            return false;
        }
    }
    
    // Get the latest ID from the specified table in the database
    public function getLatestId($db_table, $table_field)
    {
        $this->err_msg = "";
        $sql_statement = "";
        $dbtype = $this->database_type;
        
        if($dbtype=="sql" || $dbtype=="mssql" || $dbtype=="ibm" || $dbtype=="databaselibrary" || $dbtype=="odbc")
        {
            $sql_statement = "select top 1 $table_field from $db_table order by $table_field desc";
        }
        if($dbtype=="oracle")
        {
            $sql_statement = "select $table_field from $db_table where ROWNUM<=1 order by $table_field desc";
        }
        if($dbtype=="informix" || $dbtype=="firebird")
        {
            $sql_statement = "select first 1 $table_field from $db_table order by $table_field desc";
        }
        if($dbtype=="mysql" || $dbtype=="sqlite2" || $dbtype=="sqlite3")
        {
            $sql_statement = "select $table_field from $db_table order by $table_field desc limit 1";
        }
        if($dbtype=="postgre")
        {
            $sql_statement = "select $table_field from $db_table order by $table_field desc limit 1 offset 0";
        }
        
        if($this->con!=null)
        {
            try 
            {
                return $this->query_single($sql_statement);
            } 
            catch(PDOException $e) 
            {
                $this->log("Error: ". $e->getMessage());
                return false;
            }
        }
        else
        {
            $this->log("Error: Connection to database lost.");
            return false;
        }
    }
    
    //Get all the tables from a specified database
    public function ShowTables($database)
    {
        $this->err_msg = "";
        $complete = "";     
        $sql_statement = "";        
        $dbtype = $this->database_type;
        
        if($dbtype=="sql" || $dbtype=="mssql" || $dbtype=="ibm" || $dbtype=="databaselibrary" || $dbtype=="odbc" || $dbtype=="sqlite2" || $dbtype=="sqlite3")
        {
            $sql_statement = "select name from sysobjects where xtype='U'";
        }
        if($dbtype=="oracle")
        {
            //If the query statement fail, try with uncomment the next line:
            //$sql_statement = "SELECT table_name FROM tabs";
            $sql_statement = "SELECT table_name FROM cat";
        }
        if($dbtype=="informix" || $dbtype=="firebird")
        {
            $sql_statement = "SELECT RDB$RELATION_NAME FROM RDB$RELATIONS WHERE RDB$SYSTEM_FLAG = 0 AND RDB$VIEW_BLR IS NULL ORDER BY RDB$RELATION_NAME";
        }
        if($dbtype=="mysql")
        {
            if($database!="")
              { 
                $complete = " from $database"; 
              }
            $sql_statement = "show tables $complete";
        }
        if($dbtype=="postgre")
        {
            $sql_statement = "select relname as name from pg_stat_user_tables order by relname";
        }
        
        if($this->con!=null)
        {
            try 
            {
                $this->sql=$sql_statement;
                return $this->con->query($this->sql);           
            } 
            catch(PDOException $e) 
            {
                $this->log("Error: ". $e->getMessage());
                return false;
            }
        }
        else
        {
            $this->log("Error: Connection to database lost.");
            return false;
        }
    }
    
    //Get all databases that exists on the server (and where you got permission to use them)
    public function showDatabases()
    {
        $this->err_msg = "";
        $sql_statement = "";        
        $dbtype = $this->database_type;
        
        if($dbtype=="sql" || $dbtype=="mssql" || $dbtype=="ibm" || $dbtype=="databaselibrary" || $dbtype=="odbc" || $dbtype=="sqlite2" || $dbtype=="sqlite3")
        {
            $sql_statement = "SELECT name FROM sys.Databases";
        }
        if($dbtype=="oracle")
        {
            //If the query statement fail, try with uncomment the next line:
            //$sql_statement = "select * from user_tablespaces";
            $sql_statement = "select * from v$database";
        }
        if($dbtype=="informix" || $dbtype=="firebird")
        {
            $sql_statement = "";
        }
        if($dbtype=="mysql")
        {
            $sql_statement = "SHOW DATABASES";
        }
        if($dbtype=="postgre")
        {
            $sql_statement = "select datname as name from pg_database";
        }
        
        if($this->con!=null)
        {
            try 
            {
                $this->sql=$sql_statement;
                return $this->con->query($this->sql);           
            }
             catch(PDOException $e) 
             {
                $this->log("Error: ". $e->getMessage());
                return false;
            }
        }
        else
        {
            $this->log("Error: Connection to database lost.");
            return false;
        }   
    }
    
    //Get the latest error ocurred in the connection
    public function getError()
    {
        return trim($this->err_msg)!="" ? "<span style='color:#FF0000;background:#FFEDED;font-weight:bold;border:2px solid #FF0000;padding:2px 4px 2px 4px;'>".$this->err_msg."</span><br />" : "";
    }
    
    //Disconnect from database
    public function close()
    {
        $this->err_msg = "";
        if($this->con)
        {
            $this->con = null;
            return true;
        }
        else
        {
            $this->log("Error: Connection to database lost.");
            return false;
        }
    }
    
    //Build the query neccesary for the count(*) in rowcount method
    private function stmntCount($query_stmnt)
    {
        if(trim($query_stmnt)!="")
        {
            $query_stmnt = trim($query_stmnt);
            $query_split = explode(" ",$query_stmnt);
            $query_flag = false;
            $query_final = "";      

            for($x=0;$x<count($query_split);$x++)
            {
                //Checking "SELECT"
                if($x==0 && strtoupper(trim($query_split[$x]))=="SELECT")
                    $query_final = "SELECT count(*) ";
                if($x==0 && strtoupper(trim($query_split[$x]))!="SELECT")
                    return false;

                //Checking "FROM"
                if(strtoupper(trim($query_split[$x]))=="FROM")
                {
                    $query_final .= "FROM ";
                    $query_flag = true;
                    continue;
                }

                //Building the query
                if(trim($query_split[$x])!="" && $query_flag)
                    $query_final .= " " . trim($query_split[$x]) . " ";
            }
            return trim($query_final);
        }
        return false;
    }
}
?>