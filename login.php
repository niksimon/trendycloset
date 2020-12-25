<?php
include_once("header.php");
?>
<div class="form-block">
    <h2>SIGN IN</h2>
    <form action="login.php" method="post">
        <?php
        if(isset($login_error)){
            ?>
        <div class="row"><p style='text-align:center;color:#fc2a4a'>Wrong username or password!</p></div>
        <?php
        }
        ?>
        <div class="row"><span>Username:</span><input type="text" id="loginUsername" name="loginUsername"/></div>
        <div class="row"><span>Password:</span><input type="password" id="loginPassword" name="loginPassword"/></div>
        <div class="row"><span><input type="submit" value="SIGN IN" name="btnLogin"/></div>
        <div class="row"><a href="register.php">Don't have an account?</a></div>
    </form>
</div>
<?php
include_once("footer.php");
?>