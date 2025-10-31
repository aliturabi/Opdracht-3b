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
            $artikel_uitbrenger = htmlspecialchars($_POST["artikel_uitbrenger"]);
            $artikel_inhoud = htmlspecialchars($_POST["artikel_inhoud"]);
            $artikel_onderwerp = htmlspecialchars($_POST["artikel_onderwerp"]);
            $artikel_banner = htmlspecialchars($_POST["artikel_banner"]);
            maken($artikel_uitbrenger, $artikel_inhoud, $artikel_banner, $artikel_onderwerp);
             // hij pakt de wachtwoord, naam, achternaam en email op en vernaderd ze zodat je geen injectie kandoen en zodatje wachtwoord gehased is dan stuurt hij ze naar de functie maken
            exit;
        }
        // dit is de functie maken die je account maakt
        function maken($artikel_uitbrenger, $artikel_inhoud, $artikel_banner, $artikel_onderwerp){
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

                $query = "INSERT INTO artikel(artikel_uitbrenger, artikel_inhoud, artikel_banner, artikel_onderwerp) VALUES (?,?,?,?)";
                  // dit is de query om je info intevoegen op de database en hij haalt die data op via een param
                $statement = $conn->prepare($query);
                if (!$statement) {
                    throw new Exception("Fout bij prepare: " . $conn->error);
                     // als er een error is dan voert hij dee code uit en geeft hij aan wat de error is
                }

                $statement->bind_param("ssss", $artikel_uitbrenger, $artikel_inhoud, $artikel_banner, $artikel_onderwerp);
                // hij diet ze inde insert en hij pakt de data via de post
                if (!$statement->execute()) {
                    throw new Exception("Fout bij uitvoeren: " . $statement->error);
                    // als er geen ecucute is bij se stmt is dan voert hij dee code uit en geeft hij aan wat de error is
                }
                header("Location: ../index.php");
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

        <form class="form11" method="POST" action="recept.php">
            <!-- die is een post form -->
            <h2>artiekel toevoegen</h2>
            <!-- titel -->
            <!-- hier vul je je wachtwoord in -->
        
            <label for="artikel_uitbrenger">Naam Uitbrenger</label>
            <input class="nam11" type="text" id="artikel_uitbrenger" name="artikel_uitbrenger" required>

            <label for="artikel_onderwerp">Onderwerp</label>
            <input class="nam11"  type="text" id="artikel_onderwerp" name="artikel_onderwerp" required>

            <label for="artikel_inhoud">Inhoud</label>
            <textarea class="nam11" id="artikel_inhoud" name="artikel_inhoud" rows="10" required></textarea>

            <label for="artikel_banner">Afbeelding</label>
            <input type="file" id="artikel_banner" name="artikel_banner" accept="image/*">


            <input class="subi1" type="submit" value="maak!">
            <!-- dit is de submit knop die dan de info via een post terug naar deze pagina stuurt -->
             <!-- alles is ook requierd omdat het moet worden in gevuld -->
              
        </form>    
        <form class="log11" action="recept.php">
            <a href='../index.php'>
                        <input class='terug' type='button' value='<- terug naar home pagina'>
                          <!-- die is om terug te gaan naar de home pagina -->
                    </a>
              <!-- dit is voor als je al een account hebt een knop om je te sturen naar de account inlog pagina -->
        </form>
        
</body>
</html>