<?php

try {
    // Database connection
    $db = new SQLite3('../db/sqlite/sqlite.db');

    // SQL statement to create the table if it doesn't exist
    $db->exec("CREATE TABLE IF NOT EXISTS names (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL
    )");


} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_POST["name"])) {
    $name = trim($_POST["name"]);

    $stmt = $db->prepare("INSERT INTO names (name) VALUES (:name)");
    $stmt->bindValue(':name', $name, SQLITE3_TEXT);
    $stmt->execute();


    // Redirect to prevent form resubmission
    header("Location: names.php");
    exit();

}

// Handle delete request
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["delete_name"])) {
    $name = $_POST["delete_name"];

    $stmt = $db->prepare("DELETE FROM names WHERE name = :name");
    $stmt->bindValue(':name', $name, SQLITE3_TEXT);
    $stmt->execute();

    // Redirect to prevent form resubmission
    header("Location: names.php");
    exit();
}

// Fetch all names
$result = $db->query("SELECT name FROM names ORDER BY name");
$rows = [];

while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $rows[] = $row;
}
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
    <p>This example is using SQLite3, saves to the file 'sqlite.db'</p>
    <h2>Enter a Name</h2>
    <form method="POST">
        <input type="text" name="name" required placeholder="Enter your name">
        <button type="submit">Submit</button>
    </form>

    <h3>List of Names</h3>
    <ul>
        <?php foreach ($rows as $row): ?>
            <li>
                <?= htmlspecialchars($row['name']) ?>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="delete_name" value="<?= $row['name'] ?>">
                    <button type="submit" class="delete-btn">Delete</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>

</body>

</html>