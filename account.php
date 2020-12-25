<?php
include_once("header.php");

if (isset($_SESSION["id_role"])) {
    $query_user = mysqli_query($conn, "SELECT * FROM users WHERE id_user=" . $_SESSION["id_user"]);
    $user = mysqli_fetch_array($query_user, MYSQLI_ASSOC);
    ?>
    <div class="form-block">
        <h2>MY ACCOUNT</h2>
        <img src='uploads/user_images/<?php echo $user['image']; ?>' alt='user image'/>
        <form action="account.php" method="post" enctype="multipart/form-data">
            <div class="row"><span>First name:</span><input type="text" name="firstName" value="<?php echo $user['first_name']; ?>"/></div>
            <div class="row"><span>Last name:</span><input type="text" name="lastName" value="<?php echo $user['last_name']; ?>"/></div>
            <div class="row"><span>Username:</span><input type="text" name="username" value="<?php echo $user['username']; ?>"/></div>
            <div class="row"><span>Password:</span><input type="password" name="password"/></div>
            <div class="row"><span>Repeat password:</span><input type="password" name="password2"/></div>
            <div class="row"><span>E-mail:</span><input type="text" name="email" value="<?php echo $user['email']; ?>"/></div>
            <div class="row"><span>Image:</span><input type="file" name="file"/></div>

            <div class="row"><span><input type="submit" value="Save changes" name="btnSave"/></div>
        </form>
    </div>
    <?php
    if (isset($_REQUEST["btnSave"])) {
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

        if (!preg_match("/^[A-Z][a-z]{2,20}$/", $firstName))
            $errors['first_name'] = "First name is incorrect!";
        if (!preg_match("/^[A-Z][a-z]{2,30}$/", $lastName))
            $errors['last_name'] = "Last name is incorrect!";
        if (!preg_match("/^[a-zA-Z0-9]{4,30}$/", $username)) {
            $errors['username'] = "Username is incorrect!";
        } else if ($username != $_SESSION["username"]) {
            $query_users = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
            if (mysqli_num_rows($query_users) > 0)
                $errors['exists'] = "That username is taken!";
        }
        if (!empty($password) && (!preg_match("/^[a-zA-Z0-9]{4,30}$/", $password) || $password != $password2))
            $errors['password'] = "Password is incorrect!";
        if (!preg_match("/^[a-z]+([\.-]?[a-z]+)*@[a-z]+([\.-]?[a-z]+)*(\.[a-z]+)+$/", $email))
            $errors['email'] = "E-mail is incorrect!";
        if (!empty($file_name)) {
            if ($file_type != "image/jpg" && $file_type != "image/jpeg" && $file_type != "image/png") {
                $errors['file'] = "Wrong file type! Only JPEG, JPG or PNG!";
            } else {
                if (count($errors) == 0) {
                    $new = time() . "_" . $file_name;
                    if (move_uploaded_file($file_tmp, "uploads/user_images/" . $new)) {
                        if (empty($password)) {
                            $query = "UPDATE users SET username='$username', email='$email', first_name='$firstName', last_name='$lastName', image='$new' WHERE id_user=" . $_SESSION['id_user'];
                        }
                        else{
                            $query = "UPDATE users SET username='$username', password='" . md5($password) . "', email='$email', first_name='$firstName', last_name='$lastName', image='$new' WHERE id_user=" . $_SESSION['id_user'];
                        }
                        $query_update = mysqli_query($conn, $query);
                    }
                }
            }
        } else if (!empty($password) && count($errors) == 0) {
            $query = "UPDATE users SET username='$username', password='" . md5($password) . "', email='$email', first_name='$firstName', last_name='$lastName' WHERE id_user=" . $_SESSION['id_user'];
            $query_update = mysqli_query($conn, $query);
        } else if (empty($password) && count($errors) == 0) {
            $query = "UPDATE users SET username='$username', email='$email', first_name='$firstName', last_name='$lastName' WHERE id_user=" . $_SESSION['id_user'];
            $query_update = mysqli_query($conn, $query);
        }
        if (count($errors) > 0)
            foreach ($errors as $e)
                echo "<p class='error'>$e</p>";
        else {
            echo "<p style='text-align:center'>Info updated successfully!</p>";
        }
    }
}

include_once("footer.php");

?>