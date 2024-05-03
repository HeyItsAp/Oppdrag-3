<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get and validate data
    function validate($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    $new_username = validate($_POST['new_username']);
    $new_pwd = validate($_POST['new_password']);
    $new_confpwd = validate($_POST['confirm_password']);
    $new_navn = validate($_POST['new_navn']);


    echo $new_username . '<br>';
    echo $new_pwd . '<br>';
    echo $new_confpwd . '<br>';
    echo $new_navn . '<br>';


    if(empty($new_username) || empty($new_pwd) || empty($new_confpwd) || empty($new_navn)) {
        header( "refresh:0; url=../main.php" );
        echo '<script> alert("Something is missing");</script>';
        die("");
    }
    if($new_pwd !== $new_confpwd) {
        header( "refresh:0; url=../main.php" );
        echo '<script> alert("Passwords dont match");</script>';
        die("");
    }

    try {
        require_once "db_connection.php"; 
        $query = "UPDATE brukere SET brukernavn = :brukernavn, passord = :passord, navn = :navn WHERE IDbruker = :IDbruker";
        $stmt = $pdo -> prepare($query);
        $stmt -> bindParam(':brukernavn', $new_username);
        $stmt -> bindParam(':passord', $new_confpwd);
        $stmt -> bindParam(':navn', $new_navn);
        $stmt -> bindParam(':IDbruker', $_SESSION['id']);
        $stmt -> execute();
            
            // New session variables
            $_SESSION['login'] = true;
            $_SESSION['brukernavn'] = $new_username;
            $_SESSION['passord'] = $new_confpwd;
            $_SESSION['Navn'] = $new_navn;



            $pdo = null;
            $stmt = null; 
            header( "refresh:0; url=../oversiktSide.php" );
            echo '<script> alert("Logged in, as '.$_SESSION['brukernavn']. '");</script>';

        
    } catch (PDOExecption $e) {
        die("Failed : " . $e->getMessage()); 
    }
} else {
    header("Location: ../index.php");
}
