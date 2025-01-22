<?php 
session_start();

	include("connection.php");
	include("functions.php");

  $showAlert = false;  // Flag to show alert message
  $alertMessage = ''; 

	if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // Something was posted
    $user_name = $_POST['user_name'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    if (!empty($user_name) && !empty($password) && !is_numeric($user_name)) {
        // Save to database
        $user_id = random_num(20);
        $query = "INSERT INTO users (user_id, user_name, password, email) 
                  VALUES ('$user_id', '$user_name', '$password', '$email')";

        if (mysqli_query($con, $query)) {
            $showAlert = true;  // Set flag to show alert
            $alertMessage = 'Successfully Registered!';  // Success message
        } else {
            $showAlert = true;  // Set flag to show alert
            $alertMessage = 'There was an error. Please try again later.';  // Error message
        }
    } else {
        $alertMessage = 'Please enter valid information!';  // Validation error
        $showAlert = true;  // Set flag to show alert
    }
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Signup</title>
    
	<style type="text/css">
	
	body {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
  margin: 0;
  background-color: #f2f2f2;
  font-family: Arial, sans-serif;
}

.form-container {
  background-color: #ffffff;
  padding: 20px;
  border-radius: 10px;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
  width: 350px;
}

.register-form h2 {
  text-align: center;
  margin-bottom: 20px;
}

.input-group {
  margin-bottom: 15px;
}

.input-group label {
  display: block;
  margin-bottom: 5px;
  font-weight: bold;
}

.input-group input {
  width: 100%;
  padding: 8px;
  border: 1px solid #ccc;
  border-radius: 5px;
}

.action-button {
  width: 100%;
  padding: 10px;
  background-color: #4CAF50;
  color: #ffffff;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-size: 16px;
}

.action-button:hover {
  background-color: #45a049;
}

.form-footer {
  text-align: center;
  margin-top: 15px;
}

.login-link {
  color: #007BFF;
  text-decoration: none;
}

.login-link:hover {
  text-decoration: underline;
}
	</style>

<div class="form-container">

  <form class="register-form" method="post">
    <h2>Register</h2>
    <div class="input-group">
      <label for="username">Username</label>
      <input type="text" id="username" name="user_name" placeholder="Choose a username" required>
    </div>
    <div class="input-group">
      <label for="email">Email</label>
      <input type="email" id="email" name="email" placeholder="Enter your email" required>
    </div>
    <div class="input-group">
      <label for="password">Password</label>
      <input type="password" id="password" name="password" placeholder="Create a password" required>
    </div>
    <button type="submit" class="action-button" id="button">Sign Up</button>
    <p class="form-footer">Already have an account? <a href="index.php" class="login-link">Log In</a></p>
  </form>
</div>

<script type="text/javascript">
        <?php if ($showAlert): ?>
            alert("<?php echo $alertMessage; ?>");
        <?php endif; ?>
    </script>
</body>
</html>