<?php
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $tipo = $_POST['tipo'];
    $nome = $_POST['nome'];


    $stmt = $pdo->prepare("INSERT INTO Persona (tipo, nome) VALUES (:tipo, :nome)");
    $stmt->bindParam(':tipo', $tipo);
    $stmt->bindParam(':nome', $nome);
    $stmt->execute();

    $persona_id = $pdo->lastInsertId();

    $stmt = $pdo->prepare("INSERT INTO Credenziali (username, password, persona_id) VALUES (:username, :password, :persona_id)");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':persona_id', $persona_id);
    $stmt->execute();

    echo "Registrazione avvenuta con successo!";
}
?>
<form method="post">
    Username: <input type="text" name="username" required><br>
    Password: <input type="password" name="password" required><br>
    Tipo: <select name="tipo">
        <option value="Docente">Docente</option>
        <option value="Genitore">Genitore</option>
        <option value="Alunno">Alunno</option>
        <option value="Personale">Personale</option>
    </select><br>
    Nome: <input type="text" name="nome" required><br>
    <input type="submit" value="Register">
</form>