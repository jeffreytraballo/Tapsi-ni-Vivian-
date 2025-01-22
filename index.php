<?php 

session_start();

	include("connection.php");
	include("functions.php");


	if($_SERVER['REQUEST_METHOD'] == "POST")
	{
		//something was posted
		$user_name = $_POST['user_name'];
		$password = $_POST['password'];

		if(!empty($user_name) && !empty($password) && !is_numeric($user_name))
		{

			//read from database
			$query = "select * from users where user_name = '$user_name' limit 1";
			$result = mysqli_query($con, $query);

			if($result)
			{
				if($result && mysqli_num_rows($result) > 0)
				{

					$user_data = mysqli_fetch_assoc($result);
					
					if($user_data['password'] === $password)
					{

						$_SESSION['user_id'] = $user_data['id'];
            $_SESSION['user_email'] = $user_data['email'];
						header("Location: index1.php");
						die;
					}
				}
			}
			
			echo "<script type='text/javascript'>alert('Invalid Credentials!');</script>";
		}else
		{
			echo "<script type='text/javascript'>alert('Invalid Credentials!');</script>";
		}
	}

?>


<!DOCTYPE html>
<html>
<head>
	<title>Login</title>
    <link rel="stylesheet" href="css/main.css" />
    <style>
    img[src*="https://cloud.githubusercontent.com/assets/23024110/20663010/9968df22-b55e-11e6-941d-edbc894c2b78.png"] {
    display: none;}
</style>

</head>
<body>


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
  width: 300px;
}

.login-form h2 {
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

.sign-up-link {
  color: #007BFF;
  text-decoration: none;
}

.sign-up-link:hover {
  text-decoration: underline;
}
	</style>
    
	<div class="form-container">
  <form class="login-form"  method="post">
    <h2>Login</h2>
    <div class="input-group">
      <label for="username">Username</label>
      <input type="text" id="username" placeholder="Enter your username" name="user_name"required>
    </div>
    <div class="input-group">
      <label for="password">Password</label>
      <input type="password" id="password" placeholder="Enter your password" name="password" required>
    </div>
    <button type="submit" class="action-button" id="button">Log In</button>
    <p class="form-footer">Don't have an account? <a href="signup.php" class="sign-up-link">Sign Up</a></p>
  </form>
</div>

</body>
</html>