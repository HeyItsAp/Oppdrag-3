<?php
session_start();
// Start the session (if not already started)
if (!isset($_SESSION["Admin"]) && $_SESSION["Admin"] != true) {
    header("refresh:0; url=index.php");
    echo '<script> alert("You need to be logged in to acsess this");</script>';
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['submitArr'])){
        // Get and validate data
            function validate($data) {
                $data = trim($data);
                $data = stripslashes($data);
                $data = htmlspecialchars($data);
                return $data;
            }
            $person_id = $_SESSION['id'];
            $arr_id = validate($_POST['arr_id']);
            
            if(empty($person_id) || empty($arr_id)) {
                header( "refresh:0; url=meldArrangermneter.php" );
                echo '<script> alert("Something is missing");</script>';
                die("");
            }

        // Alleredde meldt inn?
            try {
                require_once 'php_requires/db_connection.php';
                $query = "SELECT * FROM brukere_has_arrangement WHERE Brukere_IDbruker = :Brukere_IDbruker AND Arrangement_IDarrangement = :Arrangement_IDarrangement;";
                $stmt = $pdo -> prepare($query);
                $stmt -> bindParam(':Brukere_IDbruker', $person_id);
                $stmt -> bindParam(':Arrangement_IDarrangement', $arr_id);
                $stmt -> execute();
                $result = $stmt ->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                die("Failed " . $e->getMessage()); // die(); terminater scriptet og printer ut inni ()
            }


        if (!$result) {
            try {
                require_once 'php_requires/db_connection.php';
                $query = "INSERT INTO brukere_has_arrangement (Brukere_IDbruker, Arrangement_IDarrangement) values (:Brukere_IDbruker, :Arrangement_IDarrangement);";
                $stmt = $pdo -> prepare($query);
                $stmt -> bindParam(':Brukere_IDbruker', $person_id);
                $stmt -> bindParam(':Arrangement_IDarrangement', $arr_id);
                $stmt -> execute();

                // Closing the connection
                $pdo = null;
                $stmt = null;        
                header( "refresh:0; url=meldArrangermneter.php" );
                echo '<script> alert("Du har meldt deg inn!");</script>';
                die("");
                    
            } catch (PDOException $e) {
                die("Failed " . $e->getMessage()); // die(); terminater scriptet og printer ut inni ()
            }
        } else {
            header( "refresh:0; url=meldArrangermneter.php" );
            echo '<script> alert("Du er alleredde meldt inn");</script>';
            die("");
        }
    }
    if (isset($_POST['deleteArr'])){
        // Get and validate data
            function validate($data) {
                $data = trim($data);
                $data = stripslashes($data);
                $data = htmlspecialchars($data);
                return $data;
            }
            $person_id = $_SESSION['id'];
            $arr_id = validate($_POST['arr_id']);
            
            if(empty($person_id) || empty($arr_id)) {
                header( "refresh:0; url=meldArrangermneter.php" );
                echo '<script> alert("Something is missing");</script>';
                die("");
            }
        // Du er ikke meldt inn?
            try {
                require_once 'php_requires/db_connection.php';
                $query = "SELECT * FROM brukere_has_arrangement WHERE Brukere_IDbruker = :Brukere_IDbruker AND Arrangement_IDarrangement = :Arrangement_IDarrangement;";
                $stmt = $pdo -> prepare($query);
                $stmt -> bindParam(':Brukere_IDbruker', $person_id);
                $stmt -> bindParam(':Arrangement_IDarrangement', $arr_id);
                $stmt -> execute();
                $result = $stmt ->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                die("Failed " . $e->getMessage()); // die(); terminater scriptet og printer ut inni ()
            }
        // Try to delete
            if ($result){
                try {
                    require_once 'php_requires/db_connection.php';
                    $query = "DELETE FROM brukere_has_arrangement WHERE Brukere_IDbruker = :Brukere_IDbruker AND Arrangement_IDarrangement = :Arrangement_IDarrangement;";
                    $stmt = $pdo -> prepare($query);
                    $stmt -> bindParam(':Brukere_IDbruker', $person_id);
                    $stmt -> bindParam(':Arrangement_IDarrangement', $arr_id);
                    $stmt -> execute();

                    // Closing the connection
                    $pdo = null;
                    $stmt = null;        
                    header( "refresh:0; url=meldArrangermneter.php" );
                    echo '<script> alert("Nå meldt deg ut");</script>';
                    die("");
                        
                } catch (PDOException $e) {
                    die("Failed " . $e->getMessage()); // die(); terminater scriptet og printer ut inni ()
                }
            } else {
                header( "refresh:0; url=meldArrangermneter.php" );
                echo '<script> alert("Meld deg in først");</script>';
                die("");
            }
    }      
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fjell Bedriftsløsninger</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-dark">
    <section class="container my-5">
        <div class="row d-flex justify-content-center gap-5">
            <div class="card text-bg-dark" style="box-shadow: 0 0 2rem white;">
                    <div class="card-body d-flex justify-content-center">
                        <a href="oversiktSide.php">Tilbake <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
            <?php
                require_once 'php_requires/db_connection.php';
                $query = "SELECT * FROM arrangement";
                $stmt = $pdo -> prepare($query);
                $stmt -> execute();
                $arrangments_array = $stmt ->fetchAll(PDO::FETCH_ASSOC);

                if ($arrangments_array){
                    foreach ($arrangments_array as $row){
                        $id = $row['IDarrangement'];
                        $tittel = $row['Tittel'];
                        $Spill = $row['Spill'];
                        $beskrivelse = $row['beskrivelse'];
                        
                        $query = "SELECT * FROM brukere_has_arrangement WHERE Brukere_IDbruker = :Brukere_IDbruker AND Arrangement_IDarrangement = :Arrangement_IDarrangement;";
                        $stmt = $pdo -> prepare($query);
                        $stmt -> bindParam(':Brukere_IDbruker', $_SESSION['id']);
                        $stmt -> bindParam(':Arrangement_IDarrangement', $id);
                        $stmt -> execute();
                        $result = $stmt ->fetchAll(PDO::FETCH_ASSOC);?>

                        <div class="col-8 col-md-5 col-lg-3">
                            <div class="card text-bg-dark" style="box-shadow: 0 0 .75rem white;">
                                <div class="card-header"><h5 class="text-info"><?php echo $Spill;?></h5></div>
                                <div class="card-body">
                                    <h2><?php echo $tittel;?></h2>
                                    <p><?php echo $beskrivelse ?> </p>
                                    <?php if ($result){print '<p class="text-success mb-0"> Meldt inn</p>';} else {print '<p class="text-danger mb-0"> Ikke med </p>';} ?>
                                </div>
                                <div class="card-footer d-flex justify-content-between">
                                    <form method="POST">
                                        <input type="hidden" value="<?php echo $id?>" name="arr_id">
                                        <input type="submit" name="submitArr" value="Inn" class="btn btn-success">
                                        <input type="submit" name="deleteArr" value="Ut" class="btn btn-danger">
                                    </form>
                                </div>
                            </div>
                        </div>
                <?php }
                } else {?>
                    <div class="text-center">
                        <p class="display-5">Ingen arrangmenater akkurat nå...</p>
                    </div>
                <?php }
            ?>
  
        </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>
</html>