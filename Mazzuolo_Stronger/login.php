<?php
require 'db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("
        SELECT c.id, c.password, p.nome, p.tipo 
        FROM Credenziali c 
        JOIN Persona p ON c.persona_id = p.id 
        WHERE c.username = :username
    ");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['nome'] = $user['nome'];
        $_SESSION['user_type'] = $user['tipo'];

        header("Location: dashboard.php");
        exit;
    } else {
        echo "Credenziali non valide!";
    }
}
?>
<form method="post">
    Username: <input type="text" name="username" required><br>
    Password: <input type="password" name="password" required><br>
    <input type="submit" value="Login">
</form>