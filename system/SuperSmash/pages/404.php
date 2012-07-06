<!DOCTYPE html>
<html>
<head>
	<title><?php echo configuration('websiteTitle');?> 404 - Not Found</title>
	<link rel="stylesheet" href="<?php echo $websiteURL; ?>/system/SuperSmash/pages/css/main.css" type="text/css"/>
	<link rel="shortcut icon" href="<?php echo $websiteURL; ?>/system/SuperSmash/pages/images/favicon.ico">
</head>

<body>
	<div id="error-box">
		<?php $language = system\SuperSmash\SuperSmash::language(); ?>
		<img class="error" src="<?php echo $websiteURL; ?>/system/SuperSmash/pages/images/404.png" alt="404 error" />
		<div class="header"><?php echo $language->get('notFoundTitle'); ?></div>
		<div class="message">
			<?php echo $language->get('notFoundMessage'); ?> <a href="mailto:<?php echo configuration('webmasterEmail');?>"><?php echo $language->get('notFoundEmail'); ?> </a>. <br /><br />
		</div>
		<div class="links">
			<a href='<?php echo $websiteURL; ?>'><?php echo $language->get('startPage'); ?></a> | <a href='javascript: history.go(-1)'><?php echo $language->get('previousPage'); ?></a>
		</div>
	</div>
<br /><br />
        <div id="footer">
                <small>
                    <?php echo $language->get('footerRendered'); ?> {elapsed} <?php echo $language->get('footerSeconds'); ?> {usage}<br />
                    SuperSmash Framework &#169; <?php echo date("Y");?>, <a href="http://www.SuperSmash.nl">SuperSmash</a>
                </small>
        </div> 	
</body>
</html>