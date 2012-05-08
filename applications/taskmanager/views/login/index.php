<!DOCTYPE html>
<html>
<head>
    <title><?php echo configuration('websiteTitle');?></title>
    <link href="<?php echo $websiteURL . "/" . "applications" . "/" . configuration('applicationName') . $viewPath;?>/images/favicon.ico" rel="shortcut icon" type="image/x-icon"/>
    <link rel="stylesheet" type="text/css" href="<?php echo $websiteURL . "/" . "applications" . "/" . configuration('applicationName') . $viewPath;?>/css/login.css"/>
</head>

<body>
    <div id="header">
        <br/><br/><h1>SuperSmash Task manager</h1>
    </div>
    <div id="container">
        <div id="content">
            <form action="<?php echo "$websiteURL/login/check"; ?>" method="post" />
                <div id="login-box">
                    <?php echo $loginMessage; ?>
                    <br />
                    <?php if(isset($errorMessage)) echo "<font color=\"red\">" . $errorMessage . "</font><br />"; ?>
                    <div id="login-box-name" style="margin-top:20px;">Username:</div>
                        <div id="login-box-field" style="margin-top:20px;">
                            <input name="username" class="form-login" title="Username" value="" size="30" maxlength="50" />
                        </div>

                    <div id="login-box-name">Password:</div>

                    <div id="login-box-field">
                        <input name="password" type="password" class="form-login" title="Password" value="" size="30" maxlength="50" />
                    </div>

                    <br />  
                    <span class="login-box-options"><input type="checkbox" name="1" value="1"> Remember Me <a href="forget.php" style="margin-left:30px;">Forgot password?</a>
                     <br />
                    <br />
                    <button class="loginButton" type="submit" />&nbsp</button>
                </div>     
            </form>     
        </div>
    </div>
        <div id="footer"></div>
</body>
</html>