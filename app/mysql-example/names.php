<?php
$host = "db";  // Use your MySQL container name in Docker
$dbname = "phpdb";
$username = "root";
$password = "test";

try {
    // Database connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    // SQL statement to create the table if it doesn't exist
    $sql = "CREATE TABLE IF NOT EXISTS names (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL
    )";

    // Execute the query
    $pdo->exec($sql);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_POST["name"])) {
    $name = trim($_POST["name"]);

    // Insert into database
    $stmt = $pdo->prepare("INSERT INTO names (name) VALUES (:name)");
    $stmt->execute(["name" => $name]);

    // Redirect to prevent form resubmission
    header("Location: names.php");
    exit();
}

// Handle delete request
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["delete_id"])) {
    $delete_id = $_POST["delete_id"];

    $stmt = $pdo->prepare("DELETE FROM names WHERE id = :id");
    $stmt->execute(["id" => $delete_id]);

    // Redirect to prevent form resubmission
    header("Location: names.php");
    exit();
}

// Fetch all names
$stmt = $pdo->query("SELECT * FROM names ORDER BY id DESC");
$names = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Name Submission</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        form {
            margin-bottom: 20px;
        }

        input,
        button {
            padding: 8px;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            background: #f3f3f3;
            margin: 5px 0;
            padding: 10px;
            border-radius: 5px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .delete-btn {
            background: red;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 3px;
        }

        .delete-btn:hover {
            background: darkred;
        }
    </style>
</head>

<body>
    <h4>
        <a href="../">Go Back</a>
    </h4>
    <p>This example is using The db container running MySQL</p>

    <h2>Enter a Name</h2>
    <form method="POST">
        <input type="text" name="name" required placeholder="Enter your name">
        <button type="submit">Submit</button>
    </form>

    <h3>List of Names</h3>
    <ul>
        <?php foreach ($names as $row): ?>
            <li>
                <?= htmlspecialchars($row["name"]) ?>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="delete_id" value="<?= $row["id"] ?>">
                    <button type="submit" class="delete-btn">Delete</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>

</body>

</html>