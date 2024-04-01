<?php 
require __DIR__.'/lib/csrf_nonce.php';
?>

<html>
<head>
    <title>Login</title>
</head>
<body>
<form action="auth-process.php" method="post">
    <?php if (isset($_GET['error'])){
    		echo '<p class = "error">'. $_GET['error'] . '</p>';
	}
    ?>
    <label for="email">Email:</label>
    <input type="email" name="email" id="email" required/>
    <br>
    <label for="password">Password:</label>
    <input type="password" name="password" id="password" required/>
    <br>
    <button type="submit">Login</button>
    <input type="hidden" name='nonce' value="<?php echo generateNonce(33, 'login', 30); ?>"/>
</form>

<form action="pwd-change.php" method="post">
    <legend> Change Password</legend>
    <label for="email">Email:</label>
    <input type="email" name="email" id="email" required/>
    <br>
    <label for="password">Old Password:</label>
    <input type="password" name="old_password" id="password" required/>
    <br>
    <label for="password">New Password:</label>
    <input type="password" name="new_password" id="password" required/>
    <br>
    <button type="submit">Change</button>
    <input type="hidden" name='nonce' value="<?php echo generateNonce(33, 'Change_Password', 30); ?>"/>
</form>

</body>
</html>
