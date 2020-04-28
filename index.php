<?php include 'settings.php'; //include settings ?>
<!DOCTYPE html>
<html lang="en">

<head>
	<title>Login</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--===============================================================================================-->
	<link rel="icon" type="image/png" href="newlogimages/icons/favicon.ico" />
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="newlogvendor/bootstrap/css/bootstrap.min.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="newlogfonts/font-awesome-4.7.0/css/font-awesome.min.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="newlogfonts/Linearicons-Free-v1.0.0/icon-font.min.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="newlogvendor/animate/animate.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="newlogvendor/css-hamburgers/hamburgers.min.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="newlogvendor/animsition/css/animsition.min.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="newlogvendor/select2/select2.min.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="newlogvendor/daterangepicker/daterangepicker.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="newlogcss/util.css">
	<link rel="stylesheet" type="text/css" href="newlogcss/main.css">
	<!--===============================================================================================-->
</head>

<body>

	<div class="limiter">
		<div class="container-login100">
			<div class="row justify-content-center" style="width: 100%">
				<img src="newlogimages/logo.png" alt="logo" width="200px">
			</div>
			<div class="row">
				<div class="wrap-login100 p-l-85 p-r-85 p-t-55 p-b-55">

					<form class="login100-form validate-form flex-sb flex-w" action="includes/login.php" method="POST">
						<div class="row justify-content-center" style="width: 100%">
							
							<div class="login100-form-title p-b-32">
								NATCAT PORTAL
							</div>
						</div>

						<span class="txt1 p-b-11">
							USERNAME
						</span>
						<div class="wrap-input100 validate-input m-b-36" data-validate="Username is required">
							<input class="input100" type="text" name="login" id="inputEmail">
							<span class="focus-input100"></span>
						</div>

						<span class="txt1 p-b-11">
							PASSWORD
						</span>
						<div class="wrap-input100 validate-input m-b-12" data-validate="Password is required">
							<span class="btn-show-pass">
								<i class="fa fa-eye"></i>
							</span>
							<input class="input100" type="password" name="password">
							<span class="focus-input100"></span>
						</div>

						<div class="flex-sb-m w-full p-b-48">
							<div class="contact100-form-checkbox">
								<input class="input-checkbox100" id="ckb1" type="checkbox" name="remember-me">
								<label class="label-checkbox100" for="ckb1">
									REMEMBER ME
								</label>
							</div>

							<div>
								<!-- <a href="#" class="txt3">
									Forgot Password?
								</a> -->
							</div>
						</div>

						<div class="container-login100-form-btn">
							<button class="login100-form-btn" name="submit" type="submit">
								Login
							</button>
						</div>

					</form>
					<div class="row justify-content-center copyright">
						<!-- <p>&copy;MistEO Pvt Ltd</p> -->
					</div>
				</div>
			</div>
		</div>
	</div>


	<div id="dropDownSelect1"></div>

	<!--===============================================================================================-->
	<script src="newlogvendor/jquery/jquery-3.2.1.min.js"></script>
	<!--===============================================================================================-->
	<script src="newlogvendor/animsition/js/animsition.min.js"></script>
	<!--===============================================================================================-->
	<script src="newlogvendor/bootstrap/js/popper.js"></script>
	<script src="newlogvendor/bootstrap/js/bootstrap.min.js"></script>
	<!--===============================================================================================-->
	<script src="newlogvendor/select2/select2.min.js"></script>
	<!--===============================================================================================-->
	<script src="newlogvendor/daterangepicker/moment.min.js"></script>
	<script src="newlogvendor/daterangepicker/daterangepicker.js"></script>
	<!--===============================================================================================-->
	<script src="newlogvendor/countdowntime/countdowntime.js"></script>
	<!--===============================================================================================-->
	<script src="newlogjs/main.js"></script>

</body>

</html>