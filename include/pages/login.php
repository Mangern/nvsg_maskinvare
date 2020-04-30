
<h1>Log in</h1>

<form action="form_processors/login.php" method="POST">

    <input type="text" name="email" placeholder="Email" />
    <br>
    <input type="password" name="password" placeholder="Password" />
    <br>

    <input type="submit" name="login" value="Log in" />

    <br>

    <p>Don't have an account? <a href="<?php echo BASE_URL . "?page=" . PAGE_REGISTER; ?>">Register</a></p>
</form>