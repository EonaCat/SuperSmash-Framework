<!DOCTYPE html>
<html>
<head>
    <title><?php echo configuration('websiteTitle');?></title>
    <link href="<?php echo $websiteURL . "/" . "applications" . "/" . configuration('applicationName') . $viewPath;?>/images/favicon.ico" rel="shortcut icon" type="image/x-icon"/>
    <link rel="stylesheet" type="text/css" href="<?php echo $websiteURL . "/" . "applications" . "/" . configuration('applicationName') . $viewPath;?>/css/style.css"/>
</head>

<body>
    <div id="header">
        <h1>
            <img src="<?php echo $websiteURL . "/" . "applications" . "/" . configuration('applicationName') . $viewPath;?>/images/logo.png" alt="SuperSmash Logo" />
                Welcome to the SuperSmash Framework!
            <img src="<?php echo $websiteURL . "/" . "applications" . "/" . configuration('applicationName') . $viewPath;?>/images/logo.png" alt="SuperSmash Logo" />
        </h1>
    </div>
    <div id="container">
        <div id="content">
            <div id="dynamic"><?php echo $introductionMessage; ?></div>
                You can edit the <b>Model</b> by going here:
                <pre>applications/start/models/welcome.php
                    </pre>
                <br />

                You can edit the <b>View</b> by going here:
                <pre>applications/start/views/welcome.php
                </pre>
                <br />

                You can edit the <b>Controller</b> by going here:
                <pre>applications/start/controllers/welcome.php
                </pre>
    

         <br /><img src="<?php echo $websiteURL . "/" . "applications" . "/" . configuration('applicationName') . $viewPath;?>/images/mvc.png" alt="MVC Model" /><br />

        </div>
    </div> <br /><br />
        <div id="footer">
                <small>
                    Page rendered in {elapsed} seconds, using {usage}<br />
                </small>
        </div>      

           <!-- Show some items -->
            <a target="_blank" href="http://www.HTML5.com"><img id="html5" src="<?php echo $websiteURL . "/" . "applications" . "/" . configuration('applicationName') . $viewPath;?>/images/html5.png" alt="HTML5" /></a>
            <a target="_blank" href="http://twitter.com/#!/SuperSmash007"><img id="twitter" src="<?php echo $websiteURL . "/" . "applications" . "/" . configuration('applicationName') . $viewPath;?>/images/twitter.png" alt="Twitter" /></a>
</body>
</html>