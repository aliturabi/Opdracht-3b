<?php
// Controleer of het formulier is verzonden
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $naam = $_POST['naam'] ?? 'Geen Naam';
    $email = $_POST['email'] ?? '';
    $klacht = $_POST['klacht'] ?? 'Geen Omschrijving';

    // *** Zorg ervoor dat de email niet leeg is en een basisvalidatie doorstaat
    if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        
        // --- E-MAIL DETAILS ---
        $to = $email;
        $subject = "Bevestiging van uw klacht (Simpel Formulier)";
        
        // De e-mailboodschap in platte tekst (eenvoudiger)
        $message = "Beste " . $naam . ",\n\n";
        $message .= "Bedankt voor uw klacht. We hebben de volgende omschrijving ontvangen:\n\n";
        $message .= "Klacht: " . $klacht . "\n\n";
        $message .= "We nemen spoedig contact met u op.\n\n";
        $message .= "Met vriendelijke groet,\n";
        $message .= "Klantenservice";
        
        // Headers
        $headers = 'From: Klantenservice <noreply@jouwdomein.nl>' . "\r\n" .
                   'Reply-To: noreply@jouwdomein.nl' . "\r\n" .
                   'X-Mailer: PHP/' . phpversion();

        // Probeer de e-mail te versturen
        if (mail($to, $subject, $message, $headers)) {
            $melding = "Uw klacht is verzonden. Een bevestiging is gestuurd naar: " . htmlspecialchars($email);
        } else {
            $melding = "Er is een fout opgetreden bij het versturen van de e-mail.";
        }

    } else {
        $melding = "Vul alstublieft een geldig e-mailadres in.";
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Simpel Klachtenformulier</title>
</head>
<body>

    <h1>Simpel Klachtenformulier</h1>

    <?php 
    // Toon de melding na submit (succes of fout)
    if (isset($melding)) {
        echo "<h2>" . $melding . "</h2>";
    }
    ?>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        
        <p>
            <label for="naam">Naam:</label><br>
            <input type="text" id="naam" name="naam" required>
        </p>
        
        <p>
            <label for="email">E-mail:</label><br>
            <input type="email" id="email" name="email" required>
        </p>
        
        <p>
            <label for="klacht">Omschrijving Klacht:</label><br>
            <textarea id="klacht" name="klacht" required></textarea>
        </p>
        
        <p>
            <button type="submit">Klacht Indienen</button>
        </p>
        
    </form>

</body>
</html>