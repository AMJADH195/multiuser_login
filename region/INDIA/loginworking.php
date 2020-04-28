<?php
// First we execute our common code to connection to the database and start the session
require("connection.php");

// This variable will be used to re-display the user's username to them in the
// login form if they fail to enter the correct password.  It is initialized here
// to an empty value, which will be shown if the user has not submitted the form.
$submitted_username = '';

// This if statement checks to determine whether the login form has been submitted
// If it has, then the login code is run, otherwise the form is displayed
if (!empty($_POST)) {
    // This query retreives the user's information from the database using
    // their username.
    $query = "
        SELECT
            id,
            username,
            password,
            salt,
            email
        FROM users
        WHERE
            username = :username
    ";

    // The parameter values
    $query_params = array(
        ':username' => $_POST['username']
    );

    try { 
        // Execute the query against the database
        $stmt = $db->prepare($query);
        $result = $stmt->execute($query_params);
    }
    catch(PDOException $ex) { 
        // Note: On a production website, you should not output $ex->getMessage().
        // It may provide an attacker with helpful information about your code.
        die("Failed to run query: " . $ex->getMessage());
    }

    // This variable tells us whether the user has successfully logged in or not.
    // We initialize it to false, assuming they have not.
    // If we determine that they have entered the right details, then we switch it to true.
    $login_ok = false;

    // Retrieve the user data from the database.  If $row is false, then the username
    // they entered is not registered.
    $row = $stmt->fetch();
    if ($row) { 
        // Using the password submitted by the user and the salt stored in the database,
        // we now check to see whether the passwords match by hashing the submitted password
        // and comparing it to the hashed version already stored in the database.
        $check_password = hash('sha256', $_POST['password'] . $row['salt']);
        for ($round = 0; $round < 65536; $round++) {
            $check_password = hash('sha256', $check_password . $row['salt']);
        }
        if ($check_password === $row['password']) { 
            // If they do, then we flip this to true
            $login_ok = true;
        }
    }

    // If the user logged in successfully, then we send them to the private members-only page
    // Otherwise, we display a login failed message and show the login form again
    if ($login_ok) { 
        // Here I am preparing to store the $row array into the $_SESSION by
        // removing the salt and password values from it.  Although $_SESSION is
        // stored on the server-side, there is no reason to store sensitive values
        // in it unless you have to.  Thus, it is best practice to remove these
        // sensitive values first.
        unset($row['salt']);
        unset($row['password']);

        // This stores the user's data into the session at the index 'user'.
        // We will check this index on the private members-only page to determine whether
        // or not the user is logged in.  We can also use it to retrieve
        // the user's details.
        $_SESSION['user'] = $row;

        // Redirect the user to the private members-only page.
        header("Location: index.php");
        die("Redirecting to: index.php");
    }
    else {
        // Tell the user they failed
        echo '<script>alert("Bad Credentials Try Again")</script>';

        // Show them their username again so all they have to do is enter a new
        // password.  The use of htmlentities prevents XSS attacks.  You should
        // always use htmlentities on user submitted values before displaying them
        // to any users (including the user that submitted them).  For more information:
        // http://en.wikipedia.org/wiki/XSS_attack
        $submitted_username = htmlentities($_POST['username'], ENT_QUOTES, 'UTF-8');
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<title>Login</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--===============================================================================================-->
	<link rel="icon" type="image/png" href="images/fav.png" />
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="newvendor/bootstrap/css/bootstrap.min.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="newlogincss/util.css">
	<link rel="stylesheet" type="text/css" href="newlogincss/main.css">
	<!--===============================================================================================-->
	<link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet'>

	<style>
		p {
			position: relative;
			font-family: 'Montserrat';
			font-size: 39px;
			color: white;
			margin-bottom: 50px;
			padding-top: 55px;
			line-height: 40px;
		}
	</style>

</head>

<body>

	<!--  -->
	<div class="limiter">
		<div class="container-login100" style="background-image: url('images/login.png');">
			<div class="wrap-login100 p-t-190 p-b-30">
				
					
				<form class="login100-form validate-form" method="post" name="Login_Form">
					<span class="login100-form-title p-b-43" style="color: rgb(100, 98, 98);">
						Login To View
					</span>

					<div class="wrap-input100 validate-input m-b-10" data-validate="Username is required">
						<input class="input100" type="text" value="<?php echo $submitted_username; ?>" name="username" placeholder="Username">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-user"></i>
						</span>
					</div>

					<div class="wrap-input100 validate-input m-b-10" data-validate="Password is required">
						<input class="input100" type="password" name="password" placeholder="Password">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-lock"></i>
						</span>
					</div>

					<div class="container-login100-form-btn p-t-10">
						<button class="login100-form-btn">
							Login
						</button>
					</div>



				</form>

			</div>
		</div>
		
	</div>

</body>

</html>