<?php
include_once("header.php");
?>
<div class="form-block">
	<h2>CREATE AN ACCOUNT</h2>

	<?php
if(isset($_POST["btnRegister"])){
	$errors = array();
	$firstName = $_POST["firstName"];
	$lastName = $_POST["lastName"];
	$username = $_POST["username"];
	$password = $_POST["password"];
	$password2 = $_POST["password2"];
	$email = $_POST["email"];
	$file_type = $_FILES["file"]["type"];
	$file_name = $_FILES["file"]["name"];
	$file_tmp = $_FILES["file"]["tmp_name"];

	
	if(!preg_match("/^[A-Z][a-z]{2,20}$/", $firstName))
		$errors['first_name'] = "First name is incorrect!";
	if(!preg_match("/^[A-Z][a-z]{2,30}$/", $lastName))
		$errors['last_name'] = "Last name is incorrect!";
	if(!preg_match("/^[a-zA-Z0-9]{4,30}$/", $username)){
		$errors['username'] = "Username is incorrect!";
	}
	else{
		$query_users = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
		if(mysqli_num_rows($query_users) > 0)
			$errors['exists'] = "That username is taken!";
	}
	if(!preg_match("/^[a-zA-Z0-9]{4,30}$/", $password) || empty($password) || $password != $password2)
		$errors['password'] = "Password is incorrect!";
	if(!preg_match("/^[a-z]+([\.-]?[a-z]+)*@[a-z]+([\.-]?[a-z]+)*(\.[a-z]+)+$/", $email))
		$errors['email'] = "E-mail is incorrect!";
	if(empty($file_name)){
		$errors['file'] = "You must choose a file!";
	}
	else{
		if($file_type != "image/jpg" && $file_type != "image/jpeg" && $file_type != "image/png"){
			$errors['file'] = "Wrong file type! Only JPEG, JPG or PNG!";
		}
		else{
			if(count($errors) == 0){
				$new = time()."_".$file_name;
				if(move_uploaded_file($file_tmp, "uploads/user_images/".$new)){
				$query_add_user = mysqli_query($conn, "INSERT INTO users (first_name, last_name, username, password, email, image, creation_date, id_role) VALUES('$firstName', '$lastName', '$username', '".md5(trim($password))."', '$email', '$new', ".time().", 2)");
				
				}
			}
		}
	}
}
?>
	<form action="register.php" method="post" enctype="multipart/form-data">
		<div class="row"><span>First name:</span><input type="text" id="firstName" name="firstName"/></div>
		<?php if(isset($errors['first_name'])) echo "<p class='error'>{$errors['first_name']}</p>"?>
		<div class="row"><span>Last name:</span><input type="text" id="lastName" name="lastName"/></div>
		<?php if(isset($errors['last_name'])) echo "<p class='error'>{$errors['last_name']}</p>"?>
		<div class="row"><span>Username:</span><input type="text" id="username" name="username"/></div>
		<?php if(isset($errors['username'])) echo "<p class='error'>{$errors['username']}</p>"?>
		<div class="row"><span>Password:</span><input type="password" id="password" name="password"/></div>
		<div class="row"><span>Repeat password:</span><input type="password" id="password2" name="password2"/></div>
		<?php if(isset($errors['password'])) echo "<p class='error'>{$errors['password']}</p>"?>
		<div class="row"><span>E-mail:</span><input type="text" id="email" name="email"/></div>
		<?php if(isset($errors['email'])) echo "<p class='error'>{$errors['email']}</p>"?>
		<div class="row"><span>Image:</span><input type="file" name="file"/></div>
		<?php if(isset($errors['file'])) echo "<p class='error'>{$errors['file']}</p>"?>
	<div class="row"><span><input type="submit" value="REGISTER" name="btnRegister"/></div>
	<?php if(isset($errors['exists'])) echo "<p class='error'>{$errors['exists']}</p>"?>
</form>

</div>
<script>
window.addEventListener("load", function(){
	document.getElementById("firstName").addEventListener("blur", function(){
		if(!/^[A-Z][a-z]{2,20}$/.test(this.value))
			this.style.borderColor = "red";
		else
			this.style.borderColor = "#000";
	});
	document.getElementById("lastName").addEventListener("blur", function(){
		if(!/^[A-Z][a-z]{2,20}$/.test(this.value))
			this.style.borderColor = "red";
		else
			this.style.borderColor = "#000";
	});
	document.getElementById("username").addEventListener("blur", function(){
		if(!/^[a-zA-Z0-9]{4,30}$/.test(this.value))
			this.style.borderColor = "red";
		else
			this.style.borderColor = "#000";
	});
	document.getElementById("password").addEventListener("blur", function(){
		if(!/^[a-zA-Z0-9]{4,30}$/.test(this.value) || this.value != document.getElementById("password2").value)
			this.style.borderColor = "red";
		else{
			document.getElementById("password2").style.borderColor = "#000";
			this.style.borderColor = "#000";
		}
	});
	document.getElementById("password2").addEventListener("blur", function(){
		if(!/^[a-zA-Z0-9]{4,30}$/.test(this.value) || this.value != document.getElementById("password").value)
			this.style.borderColor = "red";
		else{
			document.getElementById("password").style.borderColor = "#000";
			this.style.borderColor = "#000";
		}
	});
	document.getElementById("email").addEventListener("blur", function(){
		if(!/^[a-z]+([\.-]?[a-z]+)*@[a-z]+([\.-]?[a-z]+)*(\.[a-z]+)+$/.test(this.value))
			this.style.borderColor = "red";
		else
			this.style.borderColor = "#000";
	});
});
</script>
<?php
include_once("footer.php");
?>