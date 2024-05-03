<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get and validate data
    function validate($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    $navn = validate($_POST['navn']);
    $username = validate($_POST['username']);
    $passord = validate($_POST['passord']);
    $gamer_admin = 0;
    // echo $username . '<br>';
    // echo $passord . '<br>';

    if(empty($username) || empty($passord) || empty($username)) {
        header( "refresh:0; url=../index.php" );
        echo '<script> alert("Something is missing");</script>';
        die("");
    }
    require_once "db_connection.php";
    try {
        $query = "SELECT * FROM brukere WHERE brukernavn = :brukernavn";
        $stmt = $pdo -> prepare($query);
        $stmt -> bindParam(':brukernavn', $brukernavn);
        $stmt -> execute();
        $result = $stmt ->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Failed " . $e->getMessage()); // die(); terminater scriptet og printer ut inni ()
    }
    if(!$result){
        try {
            $query = "INSERT INTO brukere (passord, Admin, brukernavn, navn) values (:passord, :Admin, :brukernavn, :navn);";
            $stmt = $pdo -> prepare($query);
            $stmt -> bindParam(':passord', $passord);
            $stmt -> bindParam(':Admin', $gamer_admin);
            $stmt -> bindParam(':brukernavn', $username);
            $stmt -> bindParam(':navn', $navn);

            $stmt -> execute();

            // Closing the connection
            $pdo = null;
            $stmt = null;        
            header( "refresh:0; url=../index.php" );
            echo '<script> alert("Du har laget en bruker");</script>';
            die("");
                
        } catch (PDOException $e) {
            die("Failed " . $e->getMessage()); // die(); terminater scriptet og printer ut inni ()
        }
    } else {
        header( "refresh:0; url=../index.php" );
        echo '<script> alert("Brukernavn er allredde tatt");</script>';
        die("");
    }
        
} else {
    header("Location: ../index.php"); // Sender personen tilbake til index.php hvis det er ingen php
}