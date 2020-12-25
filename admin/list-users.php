<?php
$page_title = "Trendy Closet - Users";

include_once("header.php");
$query_all = mysqli_query($conn, "SELECT * FROM users u JOIN roles r ON u.id_role=r.id_role");
?>
<h2>USERS</h2>
<form method='post' action="list-users.php">
    <table border='1' style='border-collapse: collapse;' cellpadding='5'><tr style='background-color: #eee'><th>Username</th><th>Role</th><th>Email</th><th>Image</th><th>Date created</th><th>Edit</th><th>Delete</th></tr>
        <?php
        while ($r = mysqli_fetch_array($query_all, MYSQLI_ASSOC)) {
            echo "<tr align='center'><td>" . $r['username'] . "</td><td>" . $r['role'] . "</td><td>" . $r['email'] . "</td><td><img width='80px' height='80px' src='../uploads/user_images/" . $r['image'] . "'/></td><td>" . @date("m-d-Y h:i:s", $r['creation_date']) . "</td><td><a class='edit-link' href='edit-user.php?id_user=" . $r['id_user'] . "'><img width='36px' height='36px' src='../img/edit_icon.png' alt='Edit icon'/></a></td><td><input type='checkbox' name='delete[]' value='" . $r['id_user'] . "'/></td></tr>";
        }
        echo "<tr><td colspan='7' align='center'><input type='submit' name='btnDelete' value='Delete'/></td></tr>";
        echo "</table></form>";

        if (isset($_REQUEST['btnDelete'])) {
            $delete = $_REQUEST["delete"];
            foreach ($delete as $d) {
                $query_delete_user = mysqli_query($conn, "DELETE FROM users WHERE id_user=" . $d);
            }
            header("Location: list-users.php");
        }
        
        include_once("footer.php");
        ?>