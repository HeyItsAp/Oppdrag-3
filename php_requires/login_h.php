<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get and validate data
    function validate($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    $brukernavn = validate($_POST['brukernavn']);
    $passord = validate($_POST['passord']);
    // echo $username . '<br>';
    // echo $pwd . '<br>';
    if(empty($brukernavn) || empty($passord)) {
        header( "refresh:0; url=../index.php" );
        echo '<script> alert("Something is missing");</script>';
        die("");
    }

    try {
        require_once "db_connection.php"; 
        $query = "SELECT * FROM brukere WHERE brukernavn = :brukernavn";
        $stmt = $pdo -> prepare($query);
        $stmt -> bindParam(':brukernavn', $brukernavn);
        $stmt -> execute();
        $result = $stmt ->fetch(PDO::FETCH_ASSOC);
        if ($result && $passord == $result['passord']) {
            // Found Login
            $_SESSION['login'] = true;
            $_SESSION['brukernavn'] = $brukernavn;
            $_SESSION['passord'] = $passord;
            $_SESSION['id'] = $result['IDbruker'];
            $_SESSION['Admin'] = $result['Admin'];
            $_SESSION['Navn'] = $result['navn'];



            $pdo = null;
            $stmt = null; 
            header( "refresh:0; url=../oversiktSide.php" );
            echo '<script> alert("Logg inn vellykket, som: '.$_SESSION['brukernavn'] . '"); </script>';


        } else {
            // Cant find Login
            $pdo = null;
            $stmt = null;           
            header( "refresh:0; url=../index.php" );
            echo '<script> alert("Logg inn feil");</script>';
            die("");

        }
    } catch (PDOExecption $e) {
        die("Failed : " . $e->getMessage()); 
    }
} else {
    header("Location: ../index.php");
}
