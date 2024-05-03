<?php
session_start();
if (!isset($_SESSION["login"]) && $_SESSION["login"] != true && $_SESSION["Admin"] == 1){
    header( "refresh:0; url=login.php" );
    echo '<script> alert("Du er ikke tillat for å være her");</script>';
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get and validate data
    function validate($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    $Tittel = validate($_POST['Tittel']);
    $Spill = validate($_POST['Spill']);
    $beskrivelse = validate($_POST['beskrivelse']);


    if(empty($Tittel) || empty($Spill) || empty($beskrivelse)) {
        header( "refresh:0; url=problem.php" );
        echo '<script> alert("Something is missing");</script>';
        die("");
    }
    
    try {
        require_once 'php_requires/db_connection.php';
        $query = "INSERT INTO arrangement (Tittel, Spill, beskrivelse) values (:Tittel, :Spill, :beskrivelse);";
        $stmt = $pdo -> prepare($query);
        $stmt -> bindParam(':Tittel', $Tittel);
        $stmt -> bindParam(':Spill', $Spill);
        $stmt -> bindParam(':beskrivelse', $beskrivelse);
        $stmt -> execute();

        // Closing the connection
        $pdo = null;
        $stmt = null;        
        header( "refresh:0; url=oversiktSide.php" );
        echo '<script> alert("Arrangment sendt!");</script>';
        die("");
            
        } catch (PDOException $e) {
            die("Failed " . $e->getMessage()); // die(); terminater scriptet og printer ut inni ()
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
        <div class="row d-flex justify-content-center">
            <div class="col-10 col-lg-8 col-xl-7">
                <div class="card text-bg-dark" style="box-shadow: 0 0 2rem white;">
                    <div class="card-body">
                        <form method="POST">
                        <div class="mb-3">
                            <h2> Skriv inn dealjer om Arrangmentet ditt: </h2>
                        </div>
                        <div class="mb-3">
                            <label for="Tittel" class="form-label">Tittel / navn</label>
                            <input type="text" class="form-control" name="Tittel" placeholder="Eksempel: Kuben LAN Party">
                        </div>
                        <div class="mb-3">
                            <label for="Spill" class="form-label">Spill </label>
                            <input type="text" class="form-control" name="Spill" placeholder="Eksempel: Valorant">
                        </div>
                        <div class="mb-3">
                            <label for="beskrivelse" class="form-label">Andre detaljer: </label>
                            <textarea class="form-control" name="beskrivelse" rows="3" placeholder="18-24. Pizza til Middag. Ta med deodoarant"></textarea>
                        </div>
                        <div class="mb-3 d-flex justify-content-between">
                            <input type="submit" class="btn btn-primary" name="submitArr" value="Lag Arrangment">
                            <a href="oversiktSide.php" class="btn btn-link">Gå tilbake her</a>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>
</html>