<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./stylesheet/style.css">
</head>
<body>
    <header>
        <?php include './html/header.php' ?>
    </header>
    <div class="gridouter">
    <div class="productsGrid">

        <?php
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
            
                $query = "SELECT `artikel_id`, `artikel_uitbrenger`, `artikel_inhoud`, `artikel_likes`, `artikel_saves`, `artikel_datum`, `artikel_banner`, `artikel_onderwerp`, `uitbrengerid` FROM `artikel`";
            
                
            $stmt = $conn->prepare($query);

            $stmt->execute();


            $stmt->bind_result($artikel_id, $artikel_uitbrenger, $artikel_inhoud, $artikel_likes, $artikel_saves, $artikel_datum, $artikel_banner, $artikel_onderwerp, $uitbrengerid);

                
            while ($stmt->fetch()) {

                if ($artikel_banner) {

                    $mimeType = 'image/jpeg';
                    $woto = "<img src='data:".$mimeType.";base64,".base64_encode($artikel_banner)."' alt='product foto' class='productFoto' >";

                }


                echo "<a class='productBlok' id='productKnop' href='./html/artikel.php?id=".$artikel_id."'>
                    ".$woto."
                    <div>
                        <h2>".$artikel_onderwerp."</h2>
                        
                    </div>
                </a>";

            }
            }catch (Exception $e) {
                echo "Update mislukt: " . $e->getMessage();

                exit;
            }finally {
                if (isset($statement)) $statement->close();
                if (isset($conn)) $conn->close();

            }
        ?>



    </div>
        </div>
    
</body>
</html>