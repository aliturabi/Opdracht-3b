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
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $bhWachtwoord = htmlspecialchars($_POST["hWachtwoord"]);
            $bnWachtwoord = htmlspecialchars($_POST["nWachtwoord"]);
            $bhnWachtwoord = htmlspecialchars($_POST["hnWachtwoord"]);
            veranderen($bhWachtwoord, $bnWachtwoord, $bhnWachtwoord);
             // hij pakt de posts op en vernaderd ze zodat je geen injectie kan doen en dan stuurt hij ze naar de functie maken
            
        }

        
        function veranderen($bhWachtwoord, $bnWachtwoord, $bhnWachtwoord){
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

                $query = "SELECT klant_email, klant_wachtwoord FROM klant WHERE klant_email='".$_SESSION["email"]."'";
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
                if($bnWachtwoord===$bhnWachtwoord){
                    if(password_verify($bhWachtwoord, $wachtwoord)){
                                        
    
                            $host = "localhost";
                            $user = "root";
                            $pass = "root";
                            $database = "newshub";
                                // hier verbind ik de server
                            // try catch voor veilig heid van de data
                            try{
                            $conn = new mysqli($host, $user, $pass, $database);
                            
                                if ($conn->connect_error) 
                                {
                                    throw new Exception("oeps er is iets mis gegaan: " . $conn->connect_error);
                                    // als er een error is dan voert hij dee code uit en geeft hij aan wat de error is
                                }
                                
                                $query = "UPDATE klant SET klant_wachtwoord=? WHERE klant_email=?";
                                // dit is de select om te kunnen de product te zien die je hebt gekozen
                                $bhhnWachtwoord = password_hash($bhnWachtwoord, PASSWORD_DEFAULT);
                                $stmt = $conn->prepare($query);
                                $stmt->bind_param("ss", $bhhnWachtwoord, $_SESSION['email']);
                                    
                                // preparen van de query zodat het veliger is
                                $stmt->execute();
                                // hij voer de stament uit
                                
                                // nu geeft ik al de rijen die ik heb opgehaald een naam en hij doet ze in die variablen op volgorde van dat hij hem ophaalt met de query

                                    
                                
                                }catch (Exception $e) {
                                    echo "Update mislukt: " . $e->getMessage();
                                    // als iets niet werkt breekt hij de rest af en echood hij de error code
                                    exit;
                                }finally {
                                    // if (isset($statement)) $statement->close();
                                    // if (isset($conn)) $conn->close();
                                    // sluit alles af voor de veiligheid
                                }
                                header("Location: aanpasen.php");
                } else {
                    $_SESSION['fout'] = "huidig";
                    // maar als het fout is dan stuurt hij dat de wachtwoord fout is
                }
                }else{
                    $_SESSION['fout'] = "kloppen";
                }
                
                if($_SESSION['fout'] === "kloppen"){
                    echo "<h1>de nieuwe wachtwoorden komen niet overeen</h1>";
                    echo '<form class="log11" action="wachtwoord.php">
                        <button class="terug" type="submit">probeer opnieuw</button>
                    </form>';
                    $_SESSION['fout'] = null;
                  
                }


                if (!isset($_POST['hWachtwoord'] )){

                }else{
                if($_SESSION['fout'] === "huidig"){
                    echo "<h1>de huidige wachtwoord klopt niet</h1>";
                    echo '<form class="log11" action="wachtwoord.php">
                        <button class="terug" type="submit">probeer opnieuw</button>
                    </form>';
                    $_SESSION['fout'] = null;
                
                }
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
        <form class="form222" action="aanpassen.php">
            <a href='aanpasen.php'>
                        <input class='terug' type='button' value='<- terug naar instellingen'>
                        <!-- die is om terug te gaan naar de home pagina -->
                    </a>
            <!-- dit is voor als je nog geen account hebt een knop om je te sturen naar de account maken pagina -->
        </form>
        <form class="form22" method="POST" action="wachtwoord.php">
            <!-- dit is de form voor het inloggen -->
            
            <h2 class="inlog2">wachtwoord veranderen</h2>
            <!-- de naam -->

            <label class="pasword22">huidige Wachtwoord</label>
            <input class="pasword2" type="password" name="hWachtwoord" required>
            <!-- en hier moet je je wachtwoord invoeren -->

            <label class="pasword22">nieuwe Wachtwoord</label>
            <input class="pasword2" type="password" name="nWachtwoord" required>

             <label class="pasword22">herhaal nieuwe Wachtwoord</label>
            <input class="pasword2" type="password" name="hnWachtwoord" required>

            <input class="submit2" type="submit" value="verander">
            <!-- dit is de submit knop die dan de info via een post terug naar deze pagina stuurt -->
        </form>
        
        
    </div>
    <!-- en alles in een div voor opmaak makelijk te maken -->
</body>
</html>