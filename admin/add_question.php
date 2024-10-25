<?php
include '../db.php';

$quiz_id = $_GET['quiz_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $question_text = $_POST['question_text'];
    $sql = "INSERT INTO questions (quiz_id, question_text) VALUES ('$quiz_id', '$question_text')";
    if ($conn->query($sql) === TRUE) {
        $question_id = $conn->insert_id;
        $options = $_POST['options'];
        $correct_option = $_POST['correct_option'];
        foreach ($options as $index => $option_text) {
            $is_correct = ($index == $correct_option) ? 1 : 0;
            $sql = "INSERT INTO options (question_id, option_text, is_correct) VALUES ('$question_id', '$option_text', '$is_correct')";
            $conn->query($sql);
        }
        header("Location: ../index.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Question</title>
    <link rel="stylesheet" href="../css/styles2.css">
</head>
<body>
    <div class="container">
    <img src="../icon.png" alt="Quiz Maker" class="header-image">
    <h1>Add a Question</h1>
    <form method="POST">
        <label for="question_text">Question:</label>
        <input type="text" id="question_text" name="question_text" style="width: 400px; height: 40px; padding: 10px;" required>
        <br><br>
        <label for="options">Options:</label>
        <br>
        <div id="options">
            <input type="text" name="options[]" style="width: 200px; height: 20px; padding: 10px;" required>
            <br><br>
            <input type="text" name="options[]" style="width: 200px; height: 20px; padding: 10px;" required>
            <br><br>
            <input type="text" name="options[]" style="width: 200px; height: 20px; padding: 10px;" required>
            <br><br>
            <input type="text" name="options[]" style="width: 200px; height: 20px; padding: 10px;" required>
        </div>
        <br>
        <label for="correct_option">Correct Option (index 0-3):</label>
        <input type="number" id="correct_option" name="correct_option" min="0" max="3" style="width: 200px; height: 20px; padding: 10px;" required>
        <br><br>
        <button type="submit">Add Question</button>
    </form>
    </div>
</body>
</html>
