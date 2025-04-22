<?php
session_start();


$host = 'localhost';
$dbname = 'scuola';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connessione fallita: " . $e->getMessage());
}


$messaggio = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $tipi_utente = [
        "Studente" => "Studente",
        "Genitore" => "Genitore",
        "Docente"  => "Docente"
    ];

    foreach ($tipi_utente as $tabella => $tipo) {
        $query = "SELECT p.ID_Persona, p.Nome, p.Cognome, p.Data_di_nascita, u.USER, u.PWD
                  FROM $tabella u
                  INNER JOIN Persona p ON u.ID_" . $tipo . " = p.ID_Persona
                  WHERE u.USER = :user AND u.PWD = :pwd";

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':user', $username);
        $stmt->bindParam(':pwd', $password);
        $stmt->execute();

        if ($stmt->rowCount() == 1) {
            $utente = $stmt->fetch(PDO::FETCH_ASSOC);
            $messaggio = "Login effettuato come $tipo<br>";
            $messaggio .= "Nome: " . $utente['Nome'] . "<br>";
            $messaggio .= "Cognome: " . $utente['Cognome'] . "<br>";

            if ($tipo === "Studente" || $tipo === "Genitore") {
                $ruolo_opposto = $tipo === "Studente" ? "Genitore" : "Studente";
                $query_assoc = "SELECT p.Nome, p.Cognome 
                                FROM $ruolo_opposto u
                                INNER JOIN Persona p ON u.ID_" . $ruolo_opposto . " = p.ID_Persona
                                WHERE u.ID_" . $ruolo_opposto . " = :id";

                $stmt_assoc = $pdo->prepare($query_assoc);
                $stmt_assoc->bindParam(':id', $utente['ID_Persona']);
                $stmt_assoc->execute();

                if ($stmt_assoc->rowCount() > 0) {
                    $associato = $stmt_assoc->fetch(PDO::FETCH_ASSOC);
                    $messaggio .= "<strong>$ruolo_opposto associato:</strong> " . $associato['Nome'] . " " . $associato['Cognome'] . "<br>";
                } else {
                    $messaggio .= "<strong>Nessun $ruolo_opposto associato trovato.</strong><br>";
                }
            }

            break;
        }
    }

    if ($messaggio === "") {
        $messaggio = "Credenziali non valide.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
<h2>Login Utente</h2>
<form method="POST">
    <label>Username:</label><br>
    <input type="text" name="username" required><br><br>

    <label>Password:</label><br>
    <input type="password" name="password" required><br><br>

    <input type="submit" value="Login">
</form>

<br>
<div style="color: blue;">
    <?php echo $messaggio; ?>
</div>
</body>
</html>
