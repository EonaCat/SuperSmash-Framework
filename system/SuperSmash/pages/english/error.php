<!DOCTYPE html>
<html>
<head>
	<title><?php echo configuration('websiteTitle');?> >> {ERROR_LEVEL}</title>
	<link rel="stylesheet" href="<?php echo $websiteURL; ?>/system/SuperSmash/pages/css/main.css" type="text/css"/>
</head>

<body>
	<div id="error-box">
		<small>{ERROR_COPYRIGHT}</small>
		<div class="error-header">{ERROR_LEVEL}</div>
		<div class="error-message">
			<p>
				We are sorry for the inconvenience, but an unrecoverable error has occured. 
				If the problem persists, please email us by clicking on this link >>> <a href="mailto:<?php echo configuration('webmasterEmail');?>">Email us</a>.
			</p>
			<b>Error Message:</b> {MESSAGE}
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