<?php
$verifyMessage = "";
$uploadDir = "uploads/";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username_teacher = $_POST['username'];

    // Ensure both files are uploaded without errors
    if (
        isset($_FILES['image']) && $_FILES['image']['error'] === 0 &&
        isset($_FILES['sig']) && $_FILES['sig']['error'] === 0
    ) {
        // Check MIME type for image
        $allowedImageTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        $imageType = mime_content_type($_FILES['image']['tmp_name']);

        if (!in_array($imageType, $allowedImageTypes)) {
            $verifyMessage = "Invalid image file type.";
        } else {
            // Move uploaded files
            if (!file_exists($uploadDir)) mkdir($uploadDir, 0777, true);
            $imagePath = $uploadDir . basename($_FILES['image']['name']);
            $sigPath = $uploadDir . basename($_FILES['sig']['name']);

            move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);
            move_uploaded_file($_FILES['sig']['tmp_name'], $sigPath);

            // Fetch public key from DB
            // Conneting To Database
            $servername = "localhost";
            $username = "root";
            $password = "";
            $database = "users";

            // Create A Connection
            $conn = mysqli_connect($servername, $username, $password, $database);

            // Check connection
            if (!$conn) {
                die("Sorry, we failed to connect: " . mysqli_connect_error());
            }
            $query = "SELECT public_key FROM `teacher` WHERE username = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, 's', $username_teacher);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $publicKey);
            mysqli_stmt_fetch($stmt);
            mysqli_stmt_close($stmt);

            if (!$publicKey) {
                $verifyMessage = "No public key found for this user.". $username_teacher;
            } else {
                // Verify signature
                $imageData = file_get_contents($imagePath);
                $sigData = file_get_contents($sigPath);

                $fullPath = "public_keys" . "/$publicKey";  // Adjust path as needed
                $publicKeyContent = file_get_contents($fullPath);

                
                if (!$publicKey) {
                    $verifyMessage = "Error: Could not read public key from file.";
                }

                $ok = openssl_verify($imageData, $sigData, $publicKeyContent, OPENSSL_ALGO_SHA256);

                if ($ok === 1) {
                    $verifyMessage = "<span style='color:green;'>Signature is valid.</span>";
                } elseif ($ok === 0) {
                    $verifyMessage = "<span style='color:red;'>Signature is invalid.</span>";
                } else {
                    $verifyMessage = "Verification error: " . openssl_error_string();
                }
            }
        }
    } else {
        $verifyMessage = "Please upload both files correctly.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Image and Signature Verification</title>
</head>
<body>
    <h2>Upload Image and Signature File</h2>
    <form method="POST" enctype="multipart/form-data">
        Username: <input type="text" name="username" required><br><br>
        Select Image File: <input type="file" name="image" accept=".jpg,.jpeg,.png" required><br><br>
        Select Signature File: <input type="file" name="sig" accept=".sig" required><br><br>
        <input type="submit" value="Upload & Verify">
    </form>

    <?php if ($verifyMessage): ?>
        <p><strong>Result:</strong> <?= $verifyMessage ?></p>
    <?php endif; ?>
</body>
</html>
