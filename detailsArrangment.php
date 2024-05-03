<?php
session_start();
function fancyDump($array){
    echo '<pre>';
    var_dump($array);
    echo '</pre>';
}
function validate($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if (!isset($_SESSION["login"]) && $_SESSION["login"] != true) {
    header("refresh:0; url=index.php");
    echo '<script> alert("You need to be logged in to acsess this");</script>';
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        require_once 'php_requires/db_connection.php';
        $query = "SELECT * FROM brukere INNER JOIN brukere_has_arrangement ON brukere.IDbruker = brukere_has_arrangement.Brukere_IDbruker INNER JOIN arrangement ON brukere_has_arrangement.Arrangement_IDarrangement = arrangement.IDarrangement WHERE IDarrangement = :IDarrangement;";
        $stmt = $pdo->prepare($query);
        $stmt -> bindParam(':IDarrangement', $_POST['arr_id']);
        $stmt->execute();
        $arr_info = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOExecption $e) {
        die("Failed : " . $e->getMessage()); 
    }
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
<?php 
    if ($arr_info){
        $tittel = $arr_info[0]['Tittel'];
        $Spill = $arr_info[0]['Spill'];
        $beskrivelse = $arr_info[0]['beskrivelse'];
    ?>
        <!-- Info --> 
        <section class="container my-5">
            <div class="row w-100 d-flex justify-content-center">     
                <div class="col-12">
                    <div class="card text-bg-dark" style="box-shadow: 0 0 2rem white;">
                        <div class="card-header"><h2 class="text-info"> Detaljer </h2></div>
                            <div class="card-body">
                                <h2><?php echo $tittel;?></h2>
                                <h5 class="text-warning"><?php echo $Spill;?></h5>
                                <p><?php echo $beskrivelse ?> </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Medlemmer -->
        <section class="container my-5">
            <div class="row w-100 d-flex justify-content-center">     
                <div class="col-12">
                    <div class="card text-bg-dark" style="box-shadow: 0 0 2rem white;">
                    <div class="card-header"><h2 class="text-info">Medlemmer</h2></div>
                            <div class="card-body">
                                <ul class="list-group">
                                    <li class="list-group-item list-group-item-secondary fw-semibold">Navn: </li>
                                    <?php
                                        foreach ($arr_info as $row){
                                            print '<li class="list-group-item">' . $row["navn"] . ' </li>';
                                        }
                                    
                                    ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Turnering -->
        <section class="container my-5">  
            <div class="row d-flex justify-content-center gap-5">
                <?php
                    require_once 'php_requires/db_connection.php';
                    $query = "SELECT navn, turnerings_plass FROM brukere INNER JOIN brukere_has_arrangement ON brukere.IDbruker = brukere_has_arrangement.Brukere_IDbruker WHERE Arrangement_IDarrangement = :Arrangement_IDarrangement ORDER BY turnerings_plass ASC;";
                    $stmt = $pdo->prepare($query);
                    $stmt -> bindParam(':Arrangement_IDarrangement', $_POST['arr_id']);
                    $stmt->execute();
                    $turn_info = $stmt->fetchAll(PDO::FETCH_ASSOC);
                ?>
                <div class="col-12">
                    <div class="card text-bg-dark" style="box-shadow: 0 0 2rem white;">
                    <div class="card-header"><h2 class="text-info">Turnering resultater</h2></div>
                    <div class="card-body">
                        <?php
                            if ($turn_info){
                                foreach ($turn_info as $person){
                                    if ($person["turnerings_plass"] != 0){
                                        if ($person["turnerings_plass"] == 1){
                                            print '<p class="display-3 text-warning"> 1. plass: ' . $person["navn"] . '</p>';
                                        } else {
                                            print '<h5 class="fs-4 ">' . $person["turnerings_plass"] . '. plass: ' . $person["navn"] . '</h5>';

                                        }
                                    } else {
                                        print '<p class="display-5"> Ingen turnering har blitt redigert </p>';
                                        break;

                                    }
                                }
                            }
                        ?>
                    </div>
                </div>
            </div>
            <?php if ($_SESSION['Admin'] == 1){?>
                <div class="row d-flex justify-content-center gap-5">
                    <div class="col-12">
                        <div class="card text-bg-dark" style="box-shadow: 0 0 2rem white;">
                        <div class="card-header"><h2 class="text-info">Turnering</h2></div>
                        <div class="card-body">
                            <form method="POST" action="php_requires/turn_h.php">
                                <h2> Turnerings plasser: </h2>
                                <input type="hidden" name="turnering_id" value="<?php echo $_POST['arr_id']?>">
                                <?php
                                    $antall_medlemmer = count($arr_info);
                                    $t = 1;
                                    foreach ($arr_info as $key => $row){
                                        print '<div class="mb-3">';
                                            print '<input type="hidden" name="deltakerId[' . $t . ']" value="' . $row["IDbruker"] . '">';
                                            print '<label class="form-label" for="turnering">' . $row['navn'] . '</label>';
                                            print '<select class="form-select" name="turnering[' . $t . ']">';
                                                for ($i = 1; $i <= $antall_medlemmer; $i++) {
                                                    print '<option value="' . $i . '">' . $i . '. plass </option>';
                                                }

                                            print '</select>';
                                        print '</div>';
                                        $t = $t + 1;
                                    }
                                ?>
                                <input type="submit" name="submitTurnering" value="Send resultater" class="btn btn-primary">
                            </form>
                        </div>
                    </div>
                </div>
            <?php } ?> 
        </section>
<?php 
    } else {
        print '<div class="text-center text-danger my-5"><h3> Ingen meldemmer har meldt sin inn </h3></div>';
    }
?>


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