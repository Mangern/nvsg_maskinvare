
<h1>Register new account</h1>

<form action="form_processors/register.php" method="POST">

    <input type="text" name="email" placeholder="Email" />
    <br>

    <input type="text" name="first_name" placeholder="First Name" />
    <br>

    <input type="text" name="last_name" placeholder="Last Name" />
    <br>


    <input type="password" name="password" placeholder="Password" />
    <br>

    <input type="password" name="password_confirm" placeholder="Confirm password" />
    <br>

    <input type="submit" name="register" value="Register" />

    <br>

    <p>Already have an account? <a href="<?php echo sub_url(PAGE_LOGIN, PAGE_LOGIN_LOGIN) ?>">Log in</a></p>

</form>