<link rel="stylesheet" href="../stylesheet/style.css">
<div id="headerbox">
<div class="logo"></div>
<?php
    session_start();

    $host = "localhost";
    $user = "root";
    $pass = "root";
    $database = "newshub";

    try{

        $conn = new mysqli($host, $user, $pass, $database);

        
        if ($conn->connect_error) 
        {
            throw new Exception("oeps er is iets mis gegaan: " . $conn->connect_error);

        }
        $query = "SELECT `klant_id`, `klant_naam`, `klant_achternaam`, `klant_email` FROM `klant` WHERE klant_email = '".$_SESSION['email']."'";
            
                
        $stmt = $conn->prepare($query);

        $stmt->execute();


        $stmt->bind_result($klant_id, $klant_naam, $klant_achternaam, $klant_email);

                
        $stmt->fetch();

        if($_SESSION['ingelogd']== true){

            echo "<a href='./aanpasen.php'>
                <input class='uitloggen' type='button' value=".$klant_naam.">
            </a>";
            echo "<a href='./recept.php'>
                <input class='uitloggen' type='button' value='voeg artiekel toe'>
            </a>";

        } else {
            echo '<form action="./inloggen.php">
                <button class="uitloggen" id="login" type="submit">login</button>
            </form>';

        }
        echo '<div id="headerKnop">
        <form action="../index.php">
                <button class="uitloggen" id="home" type="winkel mand">home pagina</button>
        </form>
        </div>
        ';

                
            
        }catch (Exception $e) {
            echo "Update mislukt: " . $e->getMessage();
            exit;

        }finally {
            if (isset($statement)) $statement->close();
            if (isset($conn)) $conn->close();

        }
?>
</div>