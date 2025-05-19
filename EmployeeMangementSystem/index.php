<?php 
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sms";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Add Student
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add'])) {
    $sql = "INSERT INTO students (roll_no, name, class, section, address, phone, email) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $_POST['roll_no'], $_POST['name'], $_POST['class'], $_POST['section'], $_POST['address'], $_POST['phone'], $_POST['email']);
    $stmt->execute();
}

// Update Student
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $sql = "UPDATE students SET name=?, class=?, section=?, address=?, phone=?, email=? WHERE roll_no=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $_POST['name'], $_POST['class'], $_POST['section'], $_POST['address'], $_POST['phone'], $_POST['email'], $_POST['roll_no']);
    $stmt->execute();
}

// Delete Student
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    $sql = "DELETE FROM students WHERE roll_no=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $_POST['roll_no']);
    $stmt->execute();
}

$students = [];
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['display'])) {
    $result = $conn->query("SELECT * FROM students");
    $students = $result->fetch_all(MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5" >
    <h2 class="text-center text-primary mb-4">Student Management System</h2>

    <!-- Student Form -->
    <div class="card mx-auto shadow p-4 mb-4" style="max-width: 500px;">
        <h4 class="text-center">Add / Update Student</h4>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Roll No</label>
                <input type="text" class="form-control" name="roll_no">
            </div>
            <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" class="form-control" name="name">
            </div>
            <div class="mb-3">
                <label class="form-label">Class</label>
                <input type="text" class="form-control" name="class">
            </div>
            <div class="mb-3">
                <label class="form-label">Section</label>
                <input type="text" class="form-control" name="section">
            </div>
            <div class="mb-3">
                <label class="form-label">Address</label>
                <input type="text" class="form-control" name="address">
            </div>
            <div class="mb-3">
                <label class="form-label">Phone</label>
                <input type="text" class="form-control" name="phone">
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" name="email">
            </div>
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-success" name="add">Add Student</button>
                <button type="submit" class="btn btn-primary" name="display">Display Students</button>
            </div>
        </form>
    </div>

    <?php if (!empty($students)): ?>
        <div class="card shadow p-4">
            <h4 class="text-center">Student List</h4>
            <table class="table table-bordered table-striped mt-3">
                <thead class="table-dark">
                    <tr>
                        <th>Roll No</th><th>Name</th><th>Class</th><th>Section</th><th>Address</th><th>Phone</th><th>Email</th><th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['roll_no']) ?></td>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td><?= htmlspecialchars($row['class']) ?></td>
                            <td><?= htmlspecialchars($row['section']) ?></td>
                            <td><?= htmlspecialchars($row['address']) ?></td>
                            <td><?= htmlspecialchars($row['phone']) ?></td>
                            <td><?= htmlspecialchars($row['email']) ?></td>
                            <td class="text-center">
                                <button class="btn btn-warning btn-sm" onclick="fillUpdateForm('<?= $row['roll_no'] ?>', '<?= $row['name'] ?>', '<?= $row['class'] ?>', '<?= $row['section'] ?>', '<?= $row['address'] ?>', '<?= $row['phone'] ?>', '<?= $row['email'] ?>')">
                                    Update
                                </button>
                                <form method="POST" class="d-inline">
                                    <input type="hidden" name="roll_no" value="<?= $row['roll_no'] ?>">
                                    <button type="submit" name="delete" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<script>
    function fillUpdateForm(rollNo, name, studentClass, section, address, phone, email) {
        document.querySelector('input[name="roll_no"]').value = rollNo;
        document.querySelector('input[name="name"]').value = name;
        document.querySelector('input[name="class"]').value = studentClass;
        document.querySelector('input[name="section"]').value = section;
        document.querySelector('input[name="address"]').value = address;
        document.querySelector('input[name="phone"]').value = phone;
        document.querySelector('input[name="email"]').value = email;
    }
</script>

</body>
</html>
