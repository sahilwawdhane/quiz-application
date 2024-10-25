<?php
include '../db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $roll_no = $_POST['roll_no'];
    $quiz_id = $_POST['quiz_id'];

    // Insert user details into the users table
    $sql = "INSERT INTO users (name, roll_no) VALUES ('$name', '$roll_no')";
    if ($conn->query($sql) === TRUE) {
        $user_id = $conn->insert_id;

        // Fetch questions for the quiz
        $sql = "SELECT * FROM questions WHERE quiz_id = $quiz_id";
        $questions = $conn->query($sql);
        if (!$questions) {
            die("Error fetching questions: " . $conn->error);
        }

        // Fetch quiz title
        $sql = "SELECT title FROM quizzes WHERE id = $quiz_id";
        $quiz = $conn->query($sql)->fetch_assoc();
    } else {
        die("Error inserting user: " . $conn->error);
    }
} else {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $quiz['title']; ?></title>
    <link rel="stylesheet" href="../css/styles3.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .container {
            display: flex;
            flex-direction: row;
            align-items: flex-start;
        }
        .quiz-content {
            flex: 2;
            margin-right: 20px;
        }
        .dashboard {
            flex: 1;
            padding: 20px;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
            text-align: left;
            position: sticky;
            top: 0;
        }
        h1 {
            color: #333;
        }
        fieldset {
            margin-bottom: 20px;
            padding: 10px;
            display: none; /* Hide all questions initially */
        }
        fieldset.active {
            display: block; /* Show only the active question */
        }
        label {
            display: block;
            margin-top: 10px;
        }
        input[type="radio"] {
            margin-right: 10px;
        }
        .button {
            margin-top: 10px;
            padding: 10px 15px;
            background-color: #007BFF;
            color: #FFF;
            border: none;
            cursor: pointer;
        }
        .button:hover {
            background-color: #0056b3;
        }
        .quiz-banner {
            text-align: center;
            margin-bottom: 20px;
        }
        .quiz-banner img {
            max-width: 100%;
            height: auto;
        }
        .question-box {
            width: 30px;
            height: 30px;
            display: inline-block;
            margin: 5px;
            text-align: center;
            line-height: 30px;
            border: 1px solid #ddd;
            background-color: white;
            cursor: pointer;
        }
        .question-box.answered {
            background-color: #28a745;
            color: white;
        }
        .question-box.current {
            background-color: #ffc107;
        }
        .nav-buttons {
            margin-top: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="quiz-content">
            <h1><?php echo $quiz['title']; ?></h1>
            <form method="POST" action="results.php">
                <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                <input type="hidden" name="quiz_id" value="<?php echo $quiz_id; ?>">
                <?php 
                $question_count = 0;
                while($question = $questions->fetch_assoc()): 
                    $question_count++;
                ?>
                    <fieldset id="question-<?php echo $question_count; ?>">
                        <legend><?php echo $question['question_text']; ?></legend>
                        <?php
                        $question_id = $question['id'];
                        $sql = "SELECT * FROM options WHERE question_id = $question_id";
                        $options = $conn->query($sql);
                        if (!$options) {
                            die("Error fetching options: " . $conn->error);
                        }
                        while ($option = $options->fetch_assoc()):
                        ?>
                            <label>
                                <input type="radio" name="question_<?php echo $question_id; ?>" value="<?php echo $option['id']; ?>" data-question-number="<?php echo $question_count; ?>"> <?php echo $option['option_text']; ?>
                            </label><br>
                        <?php endwhile; ?>
                    </fieldset>
                <?php endwhile; ?>
                <div class="nav-buttons">
                    <button type="button" class="button" id="prev-button">Previous</button>
                    <button type="button" class="button" id="next-button">Next</button>
                    <button type="submit" class="button" id="submit-button" style="display:none;">Submit</button>
                </div>
            </form>
        </div>
        <div class="dashboard">
            <div class="quiz-banner">
                <img src="../images/quiz_banner.jpg" alt="Quiz Banner">
            </div>
            <h2>Dashboard</h2>
            <p><strong>Quiz Name:</strong> <?php echo $quiz['title']; ?></p>
            <p><strong>User:</strong> <?php echo $name; ?> (Roll No: <?php echo $roll_no; ?>)</p>
            <p><strong>Number of Questions:</strong> <?php echo $question_count; ?></p>
            <p><strong>Questions Attempted:</strong> <span id="attempted">0</span></p>
            <p><strong>Questions Unattempted:</strong> <span id="unattempted"><?php echo $question_count; ?></span></p>
            <div id="question-status">
                <?php for ($i = 1; $i <= $question_count; $i++): ?>
                    <div class="question-box" id="question-box-<?php echo $i; ?>"><?php echo $i; ?></div>
                <?php endfor; ?>
            </div>
        </div>
    </div>

    <script>
        // JavaScript to handle question navigation and dashboard updates
        const questions = document.querySelectorAll('fieldset');
        const questionCount = questions.length;
        let currentQuestionIndex = 0;
        const attemptedElement = document.getElementById('attempted');
        const unattemptedElement = document.getElementById('unattempted');
        const questionBoxes = document.querySelectorAll('.question-box');
        let attempted = 0;
        let unattempted = questionCount;

        const showQuestion = (index) => {
            questions.forEach((question, i) => {
                question.classList.toggle('active', i === index);
            });
            questionBoxes.forEach((box, i) => {
                box.classList.toggle('current', i === index);
            });
            document.getElementById('prev-button').style.display = index === 0 ? 'none' : 'inline-block';
            document.getElementById('next-button').style.display = index === questionCount - 1 ? 'none' : 'inline-block';
            document.getElementById('submit-button').style.display = index === questionCount - 1 ? 'inline-block' : 'none';
        };

        document.getElementById('next-button').addEventListener('click', () => {
            if (currentQuestionIndex < questionCount - 1) {
                currentQuestionIndex++;
                showQuestion(currentQuestionIndex);
            }
        });

        document.getElementById('prev-button').addEventListener('click', () => {
            if (currentQuestionIndex > 0) {
                currentQuestionIndex--;
                showQuestion(currentQuestionIndex);
            }
        });

        questionBoxes.forEach((box, index) => {
            box.addEventListener('click', () => {
                currentQuestionIndex = index;
                showQuestion(currentQuestionIndex);
            });
        });

        questions.forEach((question, questionIndex) => {
            const inputs = question.querySelectorAll('input[type="radio"]');
            let isAttempted = false;
            inputs.forEach(input => {
                input.addEventListener('change', () => {
                    if (!isAttempted) {
                        attempted++;
                        unattempted--;
                        isAttempted = true;
                        attemptedElement.textContent = attempted;
                        unattemptedElement.textContent = unattempted;
                    }
                    const questionNumber = input.getAttribute('data-question-number');
                    const questionBox = document.getElementById(`question-box-${questionNumber}`);
                    questionBox.classList.add('answered');
                });
            });
        });

        showQuestion(currentQuestionIndex);
    </script>
</body>
</html>

<?php
// Close the connection after the form is generated
$conn->close();
?>
