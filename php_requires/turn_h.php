<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get and validate data
    function validate($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    function fancyDump($array){
        echo '<pre>';
        var_dump($array);
        echo '</pre>';
    }
    $turnering = $_POST['turnering'];
    $deltakerId = $_POST['deltakerId'];
    $turnering_id = validate($_POST['turnering_id']);

    fancyDump($turnering);
    fancyDump($deltakerId);

    if(empty($turnering)) {
        header( "refresh:0; url=../seeArrangment.php" );
        echo '<script> alert("Something is missing");</script>';
        die("");
    }
    // Combine arrays
    $turnering_bracket = array_combine($deltakerId, $turnering);
    fancyDump($turnering_bracket);
    $filter_like = array_unique($turnering_bracket); // For å sjekke om det er ingen duplicate


    if (count($filter_like) !== count($turnering_bracket)){ 
        header( "refresh:0; url=../seeArrangment.php" );
        echo '<script> alert("Det finns dublikate av en plass");</script>';
        die("");
    }

    require_once 'db_connection.php';
    foreach ($turnering_bracket as $id => $plass){
        $query = "UPDATE brukere_has_arrangement SET turnerings_plass = :turnerings_plass WHERE Brukere_IDbruker = :Brukere_IDbruker AND Arrangement_IDarrangement = :Arrangement_IDarrangement;";
        $stmt = $pdo -> prepare($query);
        $stmt -> bindParam(':turnerings_plass', $plass);
        $stmt -> bindParam(':Brukere_IDbruker', $id);
        $stmt -> bindParam(':Arrangement_IDarrangement', $turnering_id);
        $stmt -> execute();

    }
    $pdo = null;
    $stmt = null;        
    header( "refresh:0; url=../oversiktSide.php" );
    echo '<script> alert("Turnering er oppdatert");</script>';
    die("");
} else {
    header("Location: ../index.php"); // Sender personen tilbake til index.php hvis det er ingen php
}