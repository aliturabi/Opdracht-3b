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
          <!-- dit is om de header.php in dit index te doen -->
        <?php include './header2.php' ?>
    </header>
      <!-- deze div is zodat ik de website beter kan formgeven -->
    <div class="koop-grid">
        <?php
        $host = "localhost";
        $user = "root";
        $pass = "root";
        $database = "newshub";
          // hier verbind ik de server
        // try catch voor veilig heid van de data
        try{

            $conn = new mysqli($host, $user, $pass, $database);
             // hij zet de variables bij elkaar om op te kunnen sturen
        
                
            if ($conn->connect_error) 
            {
                throw new Exception("oeps er is iets mis gegaan: " . $conn->connect_error);
                // als er een error is dan voert hij dee code uit en geeft hij aan wat de error is
            }

            $query = "SELECT artikel_id, artikel_uitbrenger, artikel_inhoud, artikel_likes, artikel_saves, artikel_datum, artikel_banner, artikel_onderwerp, uitbrengerid FROM artikel WHERE artikel_id = ".$_GET['id']."";
            // dit is de select om te kunnen de product te zien die je hebt gekozen
            $stmt = $conn->prepare($query);
               // preparen van de query zodat het veliger is
            $stmt->execute();
             // hij voer de stament uit

            $stmt->bind_result($artikel_id, $artikel_uitbrenger, $artikel_inhoud, $artikel_likes, $artikel_saves, $artikel_datum, $artikel_banner, $artikel_onderwerp, $uitbrengerid);
            // nu geeft ik al de rijen die ik heb opgehaald een naam en hij doet ze in die variablen op volgorde van dat hij hem ophaalt met de query

                
            while ($stmt->fetch()) {
                 // hij fetched de variable van de stament en blijft de code uit voere zolang er een product is
                if ($artikel_banner) {
                     // dit is zodat hij de blob vernaderd naar een gebruik bare img tag en niet allemaal van die rare taken. hij convert hem eigenlijk
                    $mimeType = 'image/jpeg';
                    $woto = "<img src='data:".$mimeType.";base64,".base64_encode($artikel_banner)."' alt='product foto' class='wotoProduct' >";
                      // woto = werkende foto omdat dit de geconvert versie is 
                   
                }

                echo "<div class='wotoProduct'>".$woto."</div>";
                echo "<div class='productKoop'>
                    <a href='../index.php'>
                        <input class='terug' type='button' value='<- terug'>
                    </a>
                    <div class='eerst'>
                        <h3>".$artikel_uitbrenger."</h3>
                        <h2>".$artikel_onderwerp."</h2>

                        <form>
                        <input type='submit' value='♡'>
                        <input type='submit' value='⎙'> <br>
                        </form>
                       
                        <h4> ".$artikel_inhoud." </h4>
                        <h4>".$beschrijving."</h4>
                    </div>
                        <div class='specs'>
                            <h4>likes : ".$artikel_likes."</h4>
                            <h4>bewaar : ".$artikel_saves."</h4>
                            <h4>uitbreng datum : ".$artikel_datum."</h4>    
                        </div>
                    </div>";
                     // bij die echo gebruikt hij een a om de pagina op te sturen naar de product pagina met de id zodat die product pagina over welke product het gaat en kan ophallen.
                // in de div zit alle dingen dat worden gebruikt om te laten zien. hij haalt de naam prijs enz. op vanuiut de database
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
        ?>
          <!-- hier sluit ik de php code af -->


    </div>
    </div>
</body>
</html>