<?php
include '../db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $sql = "INSERT INTO quizzes (title) VALUES ('$title')";
    if ($conn->query($sql) === TRUE) {
        header("Location: index.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Quiz</title>
    <link rel="stylesheet" href="../css/styles2.css">
</head>
<body>
    <div class = "container">
    <img src="../icon.png" alt="Quiz Maker" class="header-image">
    <h1>Create a New Quiz</h1>
    <form method="POST">
        <label for="title">Quiz Title:</label>
        <input type="text" id="title" name="title" required>
        <button type="submit">Create</button>
    </form>
    </div>
</body>
</html>
