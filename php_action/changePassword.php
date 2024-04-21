<?php 

require_once 'core.php';

// Check if the request is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Initialize response array
    $response = array('success' => false, 'message' => '');

    // Retrieve and validate user input
    $currentPassword = $_POST['password'] ?? '';
    $newPassword = $_POST['npassword'] ?? '';
    $confirmPassword = $_POST['cpassword'] ?? '';
    $userId = $_POST['user_id'] ?? '';

    if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword) || empty($userId)) {
        $response['message'] = "All fields are required.";
        echo json_encode($response);
        exit();
    }

    if ($newPassword !== $confirmPassword) {
        $response['message'] = "New password and confirm password do not match.";
        echo json_encode($response);
        exit();
    }

    // Fetch the stored password for the user from the database
    $stmt = $connect->prepare("SELECT password FROM users WHERE user_id = ?");
    if ($stmt === false) {
        $response['message'] = "Failed to prepare SQL statement.";
        echo json_encode($response);
        exit();
    }

    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->bind_result($storedPassword);
    $stmt->fetch();
    $stmt->close();

    // Verify the current password
    if (password_verify($currentPassword, $storedPassword)) {
        // Hash the new password
        $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // Update the password in the database
        $updateStmt = $connect->prepare("UPDATE users SET password = ? WHERE user_id = ?");
        if ($updateStmt === false) {
            $response['message'] = "Failed to prepare update statement.";
            echo json_encode($response);
            exit();
        }

        $updateStmt->bind_param("si", $hashedNewPassword, $userId);

        if ($updateStmt->execute()) {
            $response['success'] = true;
            $response['message'] = "Password successfully updated.";
            // Redirect to the settings page
            header('Location: ../setting.php');
        } else {
            $response['message'] = "Error while updating the password.";
        }

        $updateStmt->close();
    } else {
        $response['message'] = "Current password is incorrect.";
    }

    // Close the database connection
    $connect->close();

    // Return the response as JSON
    echo json_encode($response);
}

?>
