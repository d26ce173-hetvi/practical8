<?php
require_once "db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $student_name = trim($_POST["student_name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $department = trim($_POST["department"] ?? "");

    if (
        $student_name === "" ||
        !filter_var($email, FILTER_VALIDATE_EMAIL) ||
        $department === ""
    ) {
        $message = "Please enter valid details.";
    } else {
        try {
            $sql = "INSERT INTO students
                    (student_name, email, department)
                    VALUES (:name, :email, :department)";

            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                ":name" => $student_name,
                ":email" => $email,
                ":department" => $department
            ]);

            $message = "Student registered successfully!";

        } catch (PDOException $e) {
            if ($e->getCode() === "23000") {
                $message = "This email may already be registered.";
            } else {
                error_log($e->getMessage());
                $message = "Unable to register student.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StudentHub Database Connectivity</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f1f5f9;
            padding: 30px 15px;
        }

        .container {
            max-width: 450px;
            margin: auto;
            padding: 25px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 3px 12px #cbd5e1;
        }

        h2 {
            text-align: center;
            color: #1e3a8a;
        }

        label {
            display: block;
            margin-top: 15px;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 12px;
            margin-top: 20px;
            background: #2563eb;
            color: white;
            border: none;
            cursor: pointer;
        }

        .message {
            margin-top: 15px;
            padding: 10px;
            background: #dcfce7;
            color: #166534;
        }
    </style>
</head>

<body>
<div class="container">

    <h2>StudentHub Registration</h2>

    <?php if ($message !== ""): ?>
        <p class="message">
            <?php echo htmlspecialchars($message); ?>
        </p>
    <?php endif; ?>

    <form method="POST" action="prac8.php">

        <label for="student_name">Student Name</label>
        <input
            type="text"
            id="student_name"
            name="student_name"
            required
        >

        <label for="email">Email Address</label>
        <input
            type="email"
            id="email"
            name="email"
            required
        >

        <label for="department">Department</label>
        <input
            type="text"
            id="department"
            name="department"
            required
        >

        <button type="submit">Register Student</button>

    </form>

</div>
</body>
</html>