<?php

@include 'config.php';
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: *");
header('Content-Type: application/json'); // Set the response content type to JSON

$result = array(); // Initialize the result array

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['phoneNumber'], $data['password'])) {
        $phoneNumber = mysqli_real_escape_string($conn, $data['phoneNumber']);
        $password = md5($data['password']);

        $select = "SELECT * FROM user_form WHERE phoneNumber = '$phoneNumber' AND password = '$password' ";

        $query_result = mysqli_query($conn, $select);

        if (mysqli_num_rows($query_result) > 0) {
            $row = mysqli_fetch_array($query_result);

            if ($row['user_type'] == 'admin') {
                $result['success'] = true;
                $result['message'] = 'Login successful as admin';
            } elseif ($row['user_type'] == 'user') {
                $result['success'] = true;
                $result['message'] = 'Login successful as user';
            }
        } else {
            $result['success'] = false;
            $result['message'] = 'Incorrect phone number or password';
        }
    } else {
        $result['success'] = false;
        $result['message'] = 'Please provide phone number and password';
    }
} else {
    $result['success'] = false;
    $result['message'] = 'Invalid request method';
}

echo json_encode($result);
?>
