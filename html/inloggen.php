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
    // sesion start om je sessie te beginnen
        if(!isset($_SESSION['ingelogd'])){
            $_SESSION['ingelogd'] = null;
            // als de sessie ingelogd nog niet is gezet maakt hi het en zij hij hem op null
        }
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $bEmail = htmlspecialchars($_POST["email"]);
            echo $_POST["email"];
            $bWachtwoord = htmlspecialchars($_POST["wachtwoord"]);
            maken($bEmail, $bWachtwoord);
            exit;
            // hij pakt de wachtwoord en email op en vernaderd ze zodat je geen injectie kandoen en dan stuurt hij ze naar de functie maken
        }

        // dit is de functie maken die kijk of je kan inloggen
        function maken($bEmail, $bWachtwoord){
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

                $query = "SELECT klant_email, klant_wachtwoord FROM klant WHERE klant_email='".$bEmail."'";
                // dit is de query om je info op te halen van je email die je hebt ingevoerd
                $statement = $conn->prepare($query);
                 // preparen van de query zodat het veliger is
                $statement->execute();
                // hij voer de stament uit
                if (!$statement) {
                    throw new Exception("Fout bij prepare: " . $conn->error);
                     // als er een error is dan voert hij dee code uit en geeft hij aan wat de error is
                }

                $statement->bind_result($email, $wachtwoord);
                 // nu geeft ik al de rijen die ik heb opgehaald een naam en hij doet ze in die variablen op volgorde van dat hij hem ophaalt met de query
                $statement->fetch();
                // hij fetched de variable van de stament

                if (!$statement->execute()) {
                    throw new Exception("Fout bij uitvoeren: " . $statement->error);
                     // als er geen ecucute is bij se stmt is dan voert hij dee code uit en geeft hij aan wat de error is
                }
                if(password_verify($bWachtwoord, $wachtwoord) && $email === $bEmail){
                    $_SESSION['ingelogd'] = true;
                    $_SESSION['email'] = $email;
                    header("Location: ../index.php");
                    // als je wachtwoord klopt dan zet hij de sessie op ingelogd en stuurt hij je naar de index
                    // en hij stuurt je email op in de sessie email
                } else {
                    $_SESSION['fout'] = "wachtwoord is fout";
                    // maar als het fout is dan stuurt hij dat de wachtwoord fout is
                }

                if(isset($_SESSION['fout'])){
                    echo "<h1>wachtwoord en/of email fout ingevuld</h1>";
                    echo '<form class="log11" action="inloggen.php">
                        <button class="terug" type="submit">probeer opnieuw</button>
                    </form>';
                    $_SESSION['fout'] = null;
                    // als je wachtwoord fout is dan wordt deze code uitgevoerd die aan geeft dat het niet klopt en een knop om je terug te sturen naar de inlog pagina
                }

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
    ?>
    <div>
        
        <form class="form22" method="POST" action="inloggen.php">
            <!-- dit is de form voor het inloggen -->
            
            <h2 class="inlog2">inloggen</h2>
            <!-- de naam -->

            <label class="email22">email</label>
            <input class="email2" type="email" name="email" required>
            <!-- hier moet je je email invoeren -->

            <label class="pasword22">Wachtwoord</label>
            <input class="pasword2" type="password" name="wachtwoord" required>
            <!-- en hier moet je je wachtwoord invoeren -->

            <input class="submit2" type="submit" value="log in">
            <!-- dit is de submit knop die dan de info via een post terug naar deze pagina stuurt -->
        </form>
        <form class="form222" action="maken.php">
            <a href='../index.php'>
                        <input class='terug' type='button' value='<- terug naar home pagina'>
                        <!-- die is om terug te gaan naar de home pagina -->
                    </a>
            <label class="al2" for="account">nog geen account?</label>
            <button class="subi2" type="submit">maak en account aan!</button>
            <!-- dit is voor als je nog geen account hebt een knop om je te sturen naar de account maken pagina -->
        </form>
        
    </div>
    <!-- en alles in een div voor opmaak makelijk te maken -->
</body>
</html>