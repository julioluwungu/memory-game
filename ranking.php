<?php
require_once __DIR__ . '/config/database.php';

$sql = "
SELECT
    users.username,
    scores.moves,
    scores.created_at

FROM scores

INNER JOIN users
ON users.id = scores.user_id

ORDER BY moves ASC
LIMIT 10
";

$stmt = $conn->query($sql);

$scores = $stmt->fetchAll();
?>

<h1>Ranking</h1>

<table border="1">

<tr>
    <th>Jogador</th>
    <th>Movimentos</th>
    <th>Data</th>
</tr>

<?php foreach($scores as $score): ?>

<tr>
    <td><?= $score['username'] ?></td>
    <td><?= $score['moves'] ?></td>
    <td><?= $score['created_at'] ?></td>
</tr>

<?php endforeach; ?>

</table>