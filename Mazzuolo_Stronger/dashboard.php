<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$user_type = $_SESSION['user_type'];
$info = [];
$error = '';

try {
    switch ($user_type) {
        case 'Docente':
            $stmt = $pdo->prepare("
                SELECT 
                    c.nome AS classe,
                    m.nome AS materia,
                    a.nome AS articolazione,
                    i.nome AS indirizzo
                FROM Piano_Di_Studio ps
                JOIN Classe c ON ps.classe_id = c.id
                JOIN Articolazione a ON c.articolazione_id = a.id
                JOIN Indirizzo i ON a.indirizzo_id = i.id
                JOIN Materia m ON ps.materia_id = m.id
                WHERE ps.docente_id = :user_id
                GROUP BY c.id, m.id
            ");
            break;

        case 'Alunno':
            $stmt = $pdo->prepare("
        SELECT 
            c.nome AS classe,
            ar.nome AS articolazione,  
            i.nome AS indirizzo,
            GROUP_CONCAT(m.nome SEPARATOR ', ') AS materie
        FROM Iscrizione isc
        JOIN Classe c ON isc.classe_id = c.id
        JOIN Articolazione ar ON c.articolazione_id = ar.id  
        JOIN Indirizzo i ON ar.indirizzo_id = i.id
        JOIN Piano_Di_Studio ps ON c.id = ps.classe_id
        JOIN Materia m ON ps.materia_id = m.id
        WHERE isc.alunno_id = :user_id
        GROUP BY c.id
    ");
            break;

        case 'Genitore':
            $stmt = $pdo->prepare("
                SELECT
                    p.nome AS figlio,
                    c.nome AS classe,
                    a.nome AS articolazione,
                    i.nome AS indirizzo
                FROM Genitore_Alunno ga
                JOIN Iscrizione isc ON ga.alunno_id = isc.alunno_id
                JOIN Classe c ON isc.classe_id = c.id
                JOIN Articolazione a ON c.articolazione_id = a.id
                JOIN Indirizzo i ON a.indirizzo_id = i.id
                JOIN Persona p ON ga.alunno_id = p.id
                WHERE ga.genitore_id = :user_id
            ");
            break;

        default:
            throw new Exception("Tipo utente non valido");
    }

    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $info = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $error = "Errore di database: " . $e->getMessage();
} catch (Exception $e) {
    $error = $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Mazzuolo Stronger</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .info-item { margin: 10px 0; }
        .error { color: red; }
    </style>
</head>
<body>
<h1>Benvenuto, <?= htmlspecialchars($_SESSION['nome']) ?></h1>
<h2>Ruolo: <?= htmlspecialchars($user_type) ?></h2>

<?php if ($error): ?>
    <div class="error"><?= $error ?></div>
<?php elseif (!empty($info)): ?>
    <div class="dashboard-content">
        <?php foreach ($info as $row): ?>
            <div class="card">
                <?php switch ($user_type):
                    case 'Docente': ?>
                        <h3>Classe: <?= htmlspecialchars($row['classe']) ?></h3>
                        <div class="info-item">
                            <strong>Materia:</strong> <?= htmlspecialchars($row['materia']) ?>
                        </div>
                        <div class="info-item">
                            <strong>Indirizzo:</strong>
                            <?= htmlspecialchars($row['articolazione']) ?> -
                            <?= htmlspecialchars($row['indirizzo']) ?>
                        </div>
                        <?php break; ?>

                    <?php case 'Alunno': ?>
                        <h3>Classe: <?= htmlspecialchars($row['classe']) ?></h3>
                        <div class="info-item">
                            <strong>Indirizzo:</strong>
                            <?= htmlspecialchars($row['articolazione']) ?> -
                            <?= htmlspecialchars($row['indirizzo']) ?>
                        </div>
                        <div class="info-item">
                            <strong>Materie:</strong> <?= htmlspecialchars($row['materie']) ?>
                        </div>
                        <?php break; ?>

                    <?php case 'Genitore': ?>
                        <h3>Figlio: <?= htmlspecialchars($row['figlio']) ?></h3>
                        <div class="info-item">
                            <strong>Classe:</strong> <?= htmlspecialchars($row['classe']) ?>
                        </div>
                        <div class="info-item">
                            <strong>Indirizzo:</strong>
                            <?= htmlspecialchars($row['articolazione']) ?> -
                            <?= htmlspecialchars($row['indirizzo']) ?>
                        </div>
                        <?php break; ?>
                    <?php endswitch; ?>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <div class="info-item">Nessuna informazione disponibile</div>
<?php endif; ?>

</body>
</html>