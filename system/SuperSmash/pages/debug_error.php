<!DOCTYPE html>
<html>
<head>
	<title><?php echo configuration('websiteTitle');?> >> {ERROR_LEVEL}</title>
	<link rel="stylesheet" href="<?php echo $websiteURL; ?>/system/SuperSmash/pages/css/main.css" type="text/css"/>
	<link rel="shortcut icon" href="<?php echo $websiteURL; ?>/system/SuperSmash/pages/images/favicon.ico">
</head>

<body>
	<div id="error-box">
		<?php $language = system\SuperSmash\SuperSmash::language(); ?>
		<div class="error-copyright"><small>{ERROR_COPYRIGHT}</small></div>
		<div class="error-header">{ERROR_LEVEL}</div>
		<div class="error-message">
			<b><?php echo $language->get('debugMessage'); ?></b> {MESSAGE}<br /><br />
			<b><?php echo $language->get('debugFile'); ?></b> <br />{FILE}<br /><br />
			<b><?php echo $language->get('debugLine'); ?></b> {LINE} <br /><br />
		</div>

		<div class="debug-error-message">
			{DEBUG}			
				<hr><b><?php echo $language->get('debugTrace'); ?> {#}:</b><br />
				<b><?php echo $language->get('debugFile'); ?></b> <br />{FILE}<br /><br />
				<b><?php echo $language->get('debugClass'); ?></b> {CLASS} <br />
				<b><?php echo $language->get('debugLine'); ?></b> {LINE} <br /><br />
				<b><?php echo $language->get('debugFunction'); ?></b> {FUNCTION} <br />
				<b><?php echo $language->get('debugFunctionArguments'); ?></b> {ARGS}<hr> <br /><br />
			{/DEBUG}
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