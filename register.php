<?php
include 'config.php';


$message = [];


header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

if(isset($_POST['submit']))
{
    $name = $_POST['name'];
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $email = $_POST['email'];
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $pass = $_POST['pass'];
    $cpass = $_POST['cpass'];

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message[] = 'Invalid email format!';
    }

    $select = $conn->prepare("SELECT * FROM `account` WHERE email = ?");
    $select->execute([$email]);

    if($select->rowCount() > 0 ){
        $message[] = 'User email already exists!';
    }

    // Validate password length
    if (strlen($pass) < 8) {
        $message[] = 'Password must be at least 8 characters!';
    }
    elseif($pass != $cpass){
        $message[] = 'Confirm password does not match!';
    }
    else{
       
        $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);

       
        $insert = $conn->prepare("INSERT INTO `account` (name, email, password, image, user_type) VALUES (?, ?, ?, '', 'user')");
        $insert->execute([$name, $email, $hashed_pass]);

        if($insert){
            $message[] = 'Registered successfully!';
        }else{
            $message[] = 'Registration failed!';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Pawradise Pet Adoption</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-image: url('Background.svg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            font-family: 'Arial', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .form-container {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
        }
        .btn-primary {
            background-color: #ff6b6b;
            border-color: #ff6b6b;
        }
        .btn-primary:hover {
            background-color: #ff5252;
            border-color: #ff5252;
        }
        .message {
            margin: 10px;
            padding: 10px;
            background-color: #ffeaa7;
            color: #d63031;
            border: 1px solid #fdcb6e;
            border-radius: 5px;
        }
    </style>
</head>
<body>

<section class="form-container">
<?php
if(isset($message)){
    foreach($message as $msg){
        $close_icon = (strpos($msg, 'successfully') === false) ? '<i class="fas fa-times" onclick="this.parentElement.remove();"></i>' : '';
        echo '<div class="message">
                <span>'.$msg.'</span>
                '.$close_icon.'
              </div>';
    }
}
?>
    <form action="" method="POST" onsubmit="return validateForm()">
        <h3>Register Now</h3>
        <input type="text" name="name" class="form-control mb-2" placeholder="Enter your name" required>
        <input type="email" name="email" class="form-control mb-2" placeholder="Enter your email" required id="email">
        <input type="password" name="pass" class="form-control mb-2" placeholder="Enter your password" required id="pass">
        <input type="password" name="cpass" class="form-control mb-2" placeholder="Confirm your password" required id="cpass">
        <input type="submit" name="submit" value="Register Now" class="btn btn-primary w-100">
        <p class="text-center mt-3">Already have an account? <a href="Login.php">Login</a></p>
    </form>
</section>

    <script>
        function validateForm() {
            const email = document.getElementById('email').value;
            const pass = document.getElementById('pass').value;
            const cpass = document.getElementById('cpass').value;

            // Email validation
            const emailRegex = /^\S+@\S+\.\S+$/;
            if (!emailRegex.test(email)) {
                alert('Please enter a valid email address.');
                return false;
            }

            // Password length validation
            if (pass.length < 8) {
                alert('Password must be at least 8 characters long.');
                return false;
            }

            // Password confirmation
            if (pass !== cpass) {
                alert('Passwords do not match.');
                return false;
            }

            return true;
        }
    </script>
</body>
</html>
