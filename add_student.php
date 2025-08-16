<?php
require 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST['name']);
    $index_number = trim($_POST['index_number']);
    $gender = trim($_POST['gender']);
    $password = trim($_POST['password']);

    if (empty($name) || empty($index_number) || empty($password)) {
        echo "Error: Name, Index Number, and Password are required fields.";
        exit;
    }
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO student (name, index_number, gender, password) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("ssss", $name, $index_number, $gender, $hashed_password);

        if ($stmt->execute()) {
            echo "New student record created successfully!";
        } else {

            if ($conn->errno == 1062) {
                echo "Error: A student with this index number already exists.";
            } else {
                echo "Error: " . $stmt->error;
            }
        }

        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
} else {
    echo "Please submit the form first.";
}

$conn->close();
?>