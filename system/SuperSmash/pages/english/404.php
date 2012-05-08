<!DOCTYPE html>
<html>
<head>
	<title><?php echo configuration('websiteTitle');?> 404 - Not Found</title>
	<link rel="stylesheet" href="<?php echo $websiteURL; ?>/system/SuperSmash/pages/css/main.css" type="text/css"/>
</head>

<body>
	<div id="error-box">
		<img class="error" src="<?php echo $websiteURL; ?>/system/SuperSmash/pages/images/404.png" alt="404 error" />
		<div class="header">The page you are looking for is not at this location</div>
		<div class="message">
			The page you are looking for cannot be located. You may have mis-typed the URL, or the page was deleted. 
			Please check your spelling and try again. If you feel you have reached this page in an error, please email us 
			by clicking on this link >>> <a href="mailto:<?php echo configuration('webmasterEmail');?>">Email us</a>.<br /><br />
		</div>
		<div class="links">
			<a href='<?php echo $websiteURL; ?>'>Return to Index</a> | <a href='javascript: history.go(-1)'>Previous Page</a>
		</div>
	</div>
<br /><br />
        <div id="footer">
                <small>
                    Page rendered in {elapsed} seconds, using {usage}<br />
                    SuperSmash Framework &#169; <?php echo date("Y");?>, <a href="http://www.SuperSmash.nl">SuperSmash</a>
                </small>
        </div> 	
</body>
</html>