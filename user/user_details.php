<?php
$quiz_id = $_GET['quiz_id'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Enter Details</title>
    <link rel="stylesheet" href="../css/styles2.css">
</head>
<body>
    <div class="container">
    <img src="../icon.png" alt="Quiz Maker" class="header-image">
    <h1>Enter Your Details</h1>
    <form method="POST" action="take_quiz.php">
        <input type="hidden" name="quiz_id" value="<?php echo $quiz_id; ?>">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>
        <br>
        <br>
        <label for="roll_no">Roll Number:</label>
        <input type="text" id="roll_no" name="roll_no" required>
        <br>
        <br>
        <button type="submit">Start Quiz</button>
    </form>
    </div>
</body>
</html>
