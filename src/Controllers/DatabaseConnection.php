<?php 
namespace NTeste\Controllers;

use PDO;
use PDOException;



class DatabaseConnection{

   
    protected $_db_connect;
    protected $_sql;
    protected $_result;
    protected $_row;

    function DBConnection(){  
        
        $host = 'localhost';
        $dbname = 'nteste';
        $user = 'root';
        $pass = '';

        try{

            $DBH = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
            $DBH->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $DBH;

        }catch(PDOException $e){
            echo 'ERROR: ' . $e->getMessage();
        }
    }

    function sqlVerifyUsers($name){        

        $sql = "SELECT nome, nis FROM cadastros WHERE nome='$name';";
        $STH = $this->DBConnection()->prepare($sql);
        $STH->execute();
        $result = $STH->fetchAll(PDO::FETCH_ASSOC);
        return $result[0];

    }

    function sqlRegister($name, $nis){        
       
        $sql = "INSERT INTO cadastros SET nome='$name', nis='$nis';";
        $STH = $this->DBConnection()->prepare($sql);
        $STH->execute();       
        return true;


    }

   
   
}
