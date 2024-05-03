<?php
session_start();
function fancyDump($array){
    echo '<pre>';
    var_dump($array);
    echo '</pre>';
}

if (!isset($_SESSION["login"]) && $_SESSION["login"] != true) {
    header("refresh:0; url=index.php");
    echo '<script> alert("You need to be logged in to acsess this");</script>';
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fjell Bedriftsløsninger</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body class="bg-dark">
    <?php
    if ($_SESSION['Admin'] != 1){
        try {
            require_once 'php_requires/db_connection.php';
            $query = "SELECT * FROM brukere_has_arrangement INNER JOIN arrangement ON brukere_has_arrangement.Arrangement_IDarrangement = arrangement.IDarrangement WHERE Brukere_IDbruker = :Brukere_IDbruker;";
            $stmt = $pdo->prepare($query);
            $stmt -> bindParam(':Brukere_IDbruker', $_SESSION['id']);
            $stmt->execute();
            $arrangement_array = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOExecption $e) {
            die("Failed : " . $e->getMessage()); 
        }
    } else {
        try {
            require_once 'php_requires/db_connection.php';
            $query = "SELECT * FROM arrangement";
            $stmt = $pdo->prepare($query);
            $stmt->execute();
            $arrangement_array = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOExecption $e) {
            die("Failed : " . $e->getMessage()); 
        }
    }
    ?>
    <section class="container my-5">
        <div class="row w-100 d-flex justify-content-center">     
            <div class="col-5 col-lg-2">
                <div class="card text-bg-dark" style="box-shadow: 0 0 2rem white;">
                    <div class="card-body d-flex justify-content-center">
                        <a href="oversiktSide.php">Tilbake <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="container my-5">  
        <div class="row d-flex justify-content-center gap-5">
            <?php
            if (!$arrangement_array) {
                print '<div class="text-center"><p>Ingen problemer</p></div>';
            } else {
                foreach ($arrangement_array as $row) {
                    $id = $row['IDarrangement'];
                    $tittel = $row['Tittel'];
                    $Spill = $row['Spill'];
                    $beskrivelse = $row['beskrivelse'];?>

                    <div class="col-8 col-md-5 col-lg-3">
                        <div class="card text-bg-dark" style="box-shadow: 0 0 .75rem white;">
                            <div class="card-header"><h5 class="text-info"><?php echo $Spill;?></h5></div>
                            <div class="card-body">
                                <h2><?php echo $tittel;?></h2>
                                <p><?php echo $beskrivelse ?> </p>
                            </div>
                            <div class="card-footer d-flex justify-content-between">
                                <form action="detailsArrangment.php" method="post">
                                    <input type="hidden" name="arr_id" value="<?php echo $id?>"/>
                                    <input type="submit" name="seeArr" value="See medlemmer / turnering" class="btn btn-primary"/>
                                </form>
                            </div>
                        </div>
                    </div>
            <?php }
            }
            ?>
        </div>
    </section>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
        integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
        crossorigin="anonymous"></script>
</body>

</html>