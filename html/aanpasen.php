<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../stylesheet/style.css">
</head>
<body>
        <header>
        <?php include './header2.php' ?>
    </header>
    <?php
    session_start();

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            if(isset($_POST["uitloggen"])){
                $_POST["verwijderen"] = null;
                $_POST["update"] = null;
                $_SESSION['ingelogd'] = null;
                $_SESSION['email'] = null;
                update();
                // als je op uitloggen klikt en die variable bestaat  fwordt deze code uit gevoerd om je uit te loggen 
            }
            if(isset($_POST["verwijderen"])){
                $_POST["update"] = null;
                $_POST["verwijderen"] = null;
                $_SESSION['ingelogd'] = null;
                verwijderen();
                // en deze om je account te verwijderen
                
            }
            exit;
        }
        
        // dit is een funtctie om je account te verwijderen
        function verwijderen(){
            $host = "localhost";
            $user = "root";
            $pass = "root";
            $database = "newshub";
              // hier verbind ik de server
        // try catch voor veilig heid van de data
            try {
                $conn = new mysqli($host, $user, $pass, $database);
                 // hij zet de variables bij elkaar om op te kunnen sturen

                if ($conn->connect_error) {
                    throw new Exception("Verbindingsfout: " . $conn->connect_error);
                    // als er een error is dan voert hij dee code uit en geeft hij aan wat de error is
                }

                $query = "DELETE FROM `klant` WHERE klant_email = '".$_SESSION['email']."'";
                // dit is de delte query 
                $statement = $conn->prepare($query);
                  // preparen van de query zodat het veliger is

                $statement->execute();
                 // hij voer de stament uit
                if (!$statement) {
                    throw new Exception("Fout bij prepare: " . $conn->error);
                }

                // $statement->bind_result($email, $wachtwoord);
                // $statement->fetch();

                if (!$statement->execute()) {
                    throw new Exception("Fout bij uitvoeren: " . $statement->error);
                      // als er een error is dan voert hij dee code uit en geeft hij aan wat de error is
                }
                $_SESSION['email'] = null;
                header("Location: ../index.php");
                // hij veranderd de emain op null en stuurt je terug naar header
                

            }catch (Exception $e) {
                echo "Update mislukt: " . $e->getMessage();
                 // als iets niet werkt breekt hij de rest af en echood hij de error code
                exit;
            }finally {
                if (isset($statement)) $statement->close();
                if (isset($conn)) $conn->close();
                  // sluit alles af voor de veiligheid
            }
        }
        function update(){
            $_SESSION['ingelogd'] = null;
            header("Location: ../index.php");
            // dit is de function voor uitloggen. hij logt je uit ne stuurt je terug naar de home pagina
        }
    ?>
    <div id="aanpassen">
            <form id="formUitloggen" class="form33" action="" method="post">
                <input class='uitloggen1' type='submit' name="uitloggen" value='uitloggen'>
                <!-- dit is de knop om uit te loggen en dan stuurt hij het op via een post -->
            </form>
        <form id="formVeranderen" class="form33" action="wachtwoord.php" method="post">
            <input class='uitloggen1' type='submit' name="veranderen" value='wachtwoord veranderen'>
            <!-- dit is de knop om uit te loggen en dan stuurt hij het op via een post -->
        </form>
        <form id="formVerwijderen" action="aanpasen.php" method="post">
            <input id="verwijderen"  type='submit' name="verwijderen" value='account verwijderen'>
            <!-- deze is om je account te verwijderen ook va een post  -->
        </form>
        <form class="form222" action="maken.php">
            <a href='../index.php'>
                        <input class='terug' type='button' value='<- terug naar home pagina'>
                        <!-- die is om terug te gaan naar de home pagina -->
                    </a>
        </form>
    </div>
    <!-- deze script kijk via de id of je op submit klikt. als je klikt vraag hij je om je email te typen ter convermation en als je dat heb gedaan wordt de post gestuurd en aacount verijwerd -->
         <script>
        document.getElementById('formVerwijderen').addEventListener('submit', function(e) {
            const confirmation = prompt('Type je email addres om het te bevestigen');
            if (confirmation !== <?PHP ECHO "'".$_SESSION['email']."'" ?>) {
                e.preventDefault();
                alert('Account verwijdering geannuleerd.');
            }
        });
    </script>
    <script>
       document.getElementById('formUitloggen').addEventListener('submit', function(e) {
            const confirmation = confirm('weet je zeker dat je wilt uitloggen');
            if (!confirmation) {
                e.preventDefault()
            } 
        });
    </script>
    <!-- deze doet ongeveer het zelfd emaar ipv dat je iets moet type moet je gewoon klikken op ja dat je het zeker weet -->
</body>
</html>