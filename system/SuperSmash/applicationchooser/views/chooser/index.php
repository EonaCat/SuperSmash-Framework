<!DOCTYPE html>
<html>
<head>
    <title><?php echo configuration('websiteTitle');?></title>
    <link href="<?php echo $websitePath . $viewPath;?>/images/favicon.ico" rel="shortcut icon" type="image/x-icon"/>
    <link rel="stylesheet" type="text/css" href="<?php echo $websitePath . $viewPath;?>/css/style.css"/>
</head>

<body style="background:transparent url(<?php echo $websitePath . $viewPath;?>/images/background.png) scroll; background-color:#0a0a0a; text-align:center; margin:0px; padding:0px;">
    <div id="header"></div>
    <div id="container">
        <div id="content">
            <div id="dynamic"><?php echo $chooserMessage; ?></div>
            <?php 
                foreach ($applications as &$application){
                    $application = str_replace(" ", "&nbsp", $application);
                    
                    // Check if there is an application in the denyList
                    if (strlen(strstr($denyList,$application))>0)
                    {
                        continue;
                    }

                    echo "  <form action=\"$websiteURL/index.php\" method=\"POST\">";
                            if (file_exists(ROOT . "/applications/$application/portal.png")){
                                echo "<button class=\"button\" name=\"changepage\" type=\"submit\" style=\"background-color:transparent; background-image:url($websiteURL/applications/" . "$application" . "/portal.png)\" value=\"$application\" title=\"$application\"></button>";
                            } else {
                                echo "<button class=\"button\" name=\"changepage\" type=\"submit\" style=\"background-color:transparent; background-image:url($websitePath/portal.png)\" value=\"$application\" title=\"$application\"></button>";
                            }
                    echo "  <br />$application
                            </form>
                            <br />";
                };
            ?>   
        </div>
    </div> <br /><br />
        <div id="footer">
                <small>
                    Page rendered in {elapsed} seconds, using {usage}<br />
                </small>
        </div>            
</body>
</html>