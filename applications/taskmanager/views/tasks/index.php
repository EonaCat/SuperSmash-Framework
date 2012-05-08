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
            <table>
                <tr>
                    <td></td>
                </tr>
            </table><?php echo $taskname; ?>
        </div>
    </div>
        <div id="footer"></div>
</body>
</html>