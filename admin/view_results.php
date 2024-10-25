<?php
include '../db.php';

$sql = "SELECT users.name, users.roll_no, quizzes.title, results.score 
        FROM results 
        JOIN users ON results.user_id = users.id 
        JOIN quizzes ON results.quiz_id = quizzes.id 
        ORDER BY quizzes.title, users.roll_no";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Results</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <h1>All Results</h1>
    <table border="1">
        <tr>
            <th>Quiz Title</th>
            <th>Name</th>
            <th>Roll No</th>
            <th>Score</th>
        </tr>
        <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['title']; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['roll_no']; ?></td>
                <td><?php echo $row['score']; ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>

<?php $conn->close(); ?>
