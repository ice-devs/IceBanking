<?php
$DB_user = "root";
$DB_pass = "";
try
{
 $DB_con = new PDO("mysql:host=localhost;dbname=ebanking",$DB_user,$DB_pass);
 $DB_con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e)
{
 $e->getMessage();
}
