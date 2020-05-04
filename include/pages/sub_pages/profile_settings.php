<h1>Profile</h1>
<form action="form_processors/edit_user.php" method="POST">
    <label for="email">Email</label>
    <input type="text" name="email" value="<?php echo $user["email"]; ?>">

    <br>

    <label for="first_name">First name</label>
    <input type="text" name="first_name" value="<?php echo $user["first_name"]; ?>">

    <br>

    <label for="last_name">Last name</label>
    <input type="text" name="last_name" value="<?php echo $user["last_name"]; ?>">

    <br>

    <label for="nickname">Nickname</label>
    <input type="text" name="nickname" value="<?php echo $user["nickname"]; ?>">

    <br>

    <input type="submit" name="edit_user" value="Save changes">
</form>