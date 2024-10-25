<?php
include 'db.php';

// Fetch all quizzes
$sql = "SELECT * FROM quizzes";
$quizzes = $conn->query($sql);

// Fetch all results
$sql_results = "SELECT users.name, users.roll_no, quizzes.title, results.score 
                FROM results 
                JOIN users ON results.user_id = users.id 
                JOIN quizzes ON results.quiz_id = quizzes.id 
                ORDER BY quizzes.title, users.roll_no";
$results = $conn->query($sql_results);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Quiz Application</title>
    <link rel="stylesheet" href="css/styles1.css">
</head>
<body>
    <div class="container">
        <img src="icon.png" alt="Quiz Maker" class="header-image">
        <h1>Welcome to Quiz Appliaction</h1>
        <div class="panel">
            <div class="admin">
                <h2>Quiz Creator</h2>
                <a href="admin/create_quiz.php" class="button">Create Quiz</a>
                <h3>Quizzes</h3>
                <ul>
                    <?php while($row = $quizzes->fetch_assoc()): ?>
                        <li><?php echo htmlspecialchars($row['title']); ?> <a href="admin/add_question.php?quiz_id=<?php echo $row['id']; ?>" class="button">Add Questions</a></li>
                    <?php endwhile; ?>
                </ul>
            </div>
            <div class="user">
                <h2>User</h2>
                <h3>Available Quizzes</h3>
                <ul>
                    <?php
                    $quizzes->data_seek(0); // Reset result pointer to the beginning
                    while($row = $quizzes->fetch_assoc()): ?>
                        <li><?php echo htmlspecialchars($row['title']); ?> <a href="user/user_details.php?quiz_id=<?php echo $row['id']; ?>" class="button">Take Quiz</a></li>
                    <?php endwhile; ?>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>

<?php $conn->close(); ?>
