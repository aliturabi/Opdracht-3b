<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./style.css">
</head>
<body>
        <header>
     <?php include './header2.php' ?>
    </header>
    <?php

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $bEmail = htmlspecialchars($_POST["email"]);
            $bWachtwoord = password_hash($_POST["wachtwoord"], PASSWORD_DEFAULT);
            $bNaam = htmlspecialchars($_POST["naam"]);
            $bAchternaam = htmlspecialchars($_POST["achternaam"]);
            maken($bEmail, $bWachtwoord, $bNaam, $bAchternaam);
             // hij pakt de wachtwoord, naam, achternaam en email op en vernaderd ze zodat je geen injectie kandoen en zodatje wachtwoord gehased is dan stuurt hij ze naar de functie maken
            exit;
        }
        // dit is de functie maken die je account maakt
        function maken($email,$wachtwoord ,$naam, $achternaam){
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

                $query = "INSERT INTO klant(klant_email, klant_wachtwoord, klant_naam, klant_achternaam) VALUES (?,?,?,?)";
                  // dit is de query om je info intevoegen op de database en hij haalt die data op via een param
                $statement = $conn->prepare($query);
                if (!$statement) {
                    throw new Exception("Fout bij prepare: " . $conn->error);
                     // als er een error is dan voert hij dee code uit en geeft hij aan wat de error is
                }

                $statement->bind_param("ssss",$email, $wachtwoord, $naam, $achternaam,);
                // hij diet ze inde insert en hij pakt de data via de post
                if (!$statement->execute()) {
                    throw new Exception("Fout bij uitvoeren: " . $statement->error);
                    // als er geen ecucute is bij se stmt is dan voert hij dee code uit en geeft hij aan wat de error is
                }
                
                header("Location: inloggen.php");
                // hij stuurt je naar de inlog pagina
            }catch (Exception $e) {
                echo "Update mislukt: " . $e->getMessage();
                exit;
                 // als iets niet werkt breekt hij de rest af en echood hij de error code
            }finally {
                if (isset($statement)) $statement->close();
                if (isset($conn)) $conn->close();
                // sluit alles af voor de veiligheid
            }
        }
    ?>

        <form class="form11" method="POST" action="maken.php">
            <!-- die is een post form -->
            <h2>account maken</h2>
            <!-- titel -->
            <label class="naam1">Naam</label>
            <input class="nam11" type="text" name="naam" required>
            <!-- hier vul je je naam in -->

            <label class="achter1">Achternaam</label>
            <input class="achter11" type="text" name="achternaam" required>
            <!-- hier vul je je achternaam in -->

            <label class="email1">email</label>
            <input class="email11" type="email" name="email" required>
            <!-- hier vul je je email in -->

            <label class="wacht1">Wachtwoord</label>
            <input class="wacht11" type="password" name="wachtwoord" required>
            <!-- hier vul je je wachtwoord in -->

            <input class="subi1" type="submit" value="maak!">
            <!-- dit is de submit knop die dan de info via een post terug naar deze pagina stuurt -->
             <!-- alles is ook requierd omdat het moet worden in gevuld -->
        </form>    
        <form class="log11" action="inloggen.php">
            <a href='index.php'>
                        <input class='terug' type='button' value='<- terug naar home pagina'>
                          <!-- die is om terug te gaan naar de home pagina -->
                    </a>
            <label class="acc11" for="account">heb je al een account</label>
            <button class="subi111" type="submit">login!</button>
              <!-- dit is voor als je al een account hebt een knop om je te sturen naar de account inlog pagina -->
        </form>
        
</body>
</html>