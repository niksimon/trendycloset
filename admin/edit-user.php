<?php
$page_title = "Trendy Closet - Edit User";

include_once("header.php");

$query_select_user = mysqli_query($conn, "SELECT * FROM users WHERE id_user=" . $_GET['id_user']);
$selected_user = mysqli_fetch_array($query_select_user, MYSQLI_ASSOC);
?>
<div class="form-block">
    <h2>EDIT USER</h2>
    <form action="edit-user.php?id_user=<?php echo $_GET['id_user']; ?>" method="post" enctype="multipart/form-data">
        <div class="row"><span>Username:</span><input type="text" name="username" value="<?php echo $selected_user['username']; ?>"/></div>
        <div class="row"><span>Password:</span><input type="password" name="password"/></div>
        <div class="row"><span>E-mail:</span><input type="text" name="email" value="<?php echo $selected_user['email']; ?>"/></div>
        <div class="row"><span>Role:</span><select name="role">
                <?php
                $query_roles = mysqli_query($conn, "SELECT * FROM roles");
                while ($r = mysqli_fetch_array($query_roles, MYSQLI_ASSOC)) {
                    if ($selected_user['id_role'] == $r['id_role'])
                        echo "<option value='" . $r['id_role'] . "' selected='selected'>" . $r['role'] . "</option>";
                    else
                        echo "<option value='" . $r['id_role'] . "'>" . $r['role'] . "</option>";
                }
                ?>
            </select></div>
        <div class="row"><span><input type="submit" value="Save changes" name="btnSave"/></div>
    </form>
</div>
<?php
if (isset($_REQUEST["btnSave"])) {
    $username = $_REQUEST["username"];
    if (!empty($_REQUEST["password"]))
        $password = md5(trim($_REQUEST["password"]));
    $email = $_REQUEST["email"];
    $role = $_REQUEST["role"];

    if (isset($password))
        $query = "UPDATE users SET username='$username', password='$password', email='$email', id_role=$role WHERE id_user=" . $_GET['id_user'];
    else
        $query = "UPDATE users SET username='$username', email='$email', id_role=$role WHERE id_user=" . $_GET['id_user'];

    $query_update = mysqli_query($conn, $query);

    if ($query_update) {
        header("location: list-users.php");
    }
}

include_once("footer.php");
?>