<?php
include 'db.php';

// إضافة بيانات
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['Name'], $_POST['Age'])) {
    $name = $_POST['Name'];
    $age = $_POST['Age'];
    $stmt = $conn->prepare("INSERT INTO users (Name, Age) VALUES (?, ?)");
    $stmt->bind_param("si", $name, $age);
    $stmt->execute();
    $stmt->close();
}

// تبديل الحالة
if (isset($_GET['toggle'])) {
    $id = (int)$_GET['toggle'];
    $result = $conn->query("SELECT Status FROM users WHERE ID = $id");
    if ($row = $result->fetch_assoc()) {
        $newStatus = $row['Status'] == 1 ? 0 : 1;
        $conn->query("UPDATE users SET Status = $newStatus WHERE ID = $id");
    }
    header("Location: index.php");
    exit;
}

// عرض البيانات
$result = $conn->query("SELECT * FROM users");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Simple Form</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        table { border-collapse: collapse; width: 60%; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: center; }
        input { margin: 5px; padding: 5px; }
    </style>
</head>
<body>

<form method="post" action="">
    <input type="text" name="Name" placeholder="Name" required>
    <input type="number" name="Age" placeholder="Age" required>
    <button type="submit">Submit</button>
</form>

<table>
    <tr>
        <th>ID</th><th>Name</th><th>Age</th><th>Status</th><th>Action</th>
    </tr>

    <?php if ($result && $result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['ID']) ?></td>
                <td><?= htmlspecialchars($row['Name']) ?></td>
                <td><?= htmlspecialchars($row['Age']) ?></td>
                <td><?= $row['Status'] ?></td>
                <td><a href="?toggle=<?= $row['ID'] ?>">Toggle</a></td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr><td colspan="5">No records found.</td></tr>
    <?php endif; ?>
</table>

</body>
</html>
