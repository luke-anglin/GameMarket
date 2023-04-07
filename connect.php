<?php
/** S23, PHP (on GCP, local XAMPP, or CS server) connect to MySQL (on CS server) **/
$username = 'al4ne'; 
$password = 'cs4750';
$host = 'mysql01.cs.virginia.edu';
$dbname = 'al4ne_d';
$dsn = "mysql:host=$host;dbname=$dbname";
$db = null; 
try 
{
  $db = new PDO($dsn, $username, $password);
  /** echo "<p>You are connected to the database: $dsn</p>"; **/
}
catch (PDOException $e)     // handle a PDO exception (errors thrown by the PDO library)
{
  $error_message = $e->getMessage();        
  echo "<p>An error occurred while connecting to the database: $error_message </p>";
}
catch (Exception $e)       // handle any type of exception
{
  $error_message = $e->getMessage();
  echo "<p>Error message: $error_message </p>";
}
?>