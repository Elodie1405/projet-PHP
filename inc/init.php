<?php

try{

    $bdd = new PDO("mysql:host=localhost;dbname=lokisalle", "root", "root", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING
    ]);
    
    }catch(\Exception $e){
        die('Erreur : ' . $e->getMessage());
    }
    
    session_start();

    $successMessage = '';
    $errorMessage = '';
    
    function debug($variable){
        echo '<pre>';
        print_r($variable);
        echo '</pre>';
    }
    

    



    ?>