<?php
include '../db.php';

$user_id = $_POST['user_id'];
$quiz_id = $_POST['quiz_id'];
$score = 0;
$total = 0;

foreach ($_POST as $question => $option_id) {
    if (strpos($question, 'question_') === 0) {
        $question_id = str_replace('question_', '', $question);
        $sql = "SELECT is_correct FROM options WHERE id = $option_id";
        $result = $conn->query($sql)->fetch_assoc();
        if ($result['is_correct']) {
            $score++;
        }
        $total++;
    }
}

$sql = "INSERT INTO results (user_id, quiz_id, score) VALUES ('$user_id', '$quiz_id', '$score')";
$conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Quiz Results</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <h1>Quiz Results</h1>
    <p>Your score: <?php echo $score; ?>/<?php echo $total; ?></p>
    <a href="../index.php">Go to Home</a>
</body>
</html>

<?php $conn->close(); ?>
