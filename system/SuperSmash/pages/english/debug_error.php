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
			<b>Message:</b> {MESSAGE}<br /><br />
			<b>File that reports the error:</b> <br />{FILE}<br /><br />
			<b>Line:</b> {LINE} <br /><br />

			{DEBUG}			
				<hr><b>Trace {#}:</b><br />
				<b>File:</b> <br />{FILE}<br /><br />
				<b>Class:</b> {CLASS} <br />
				<b>Line:</b> {LINE} <br /><br />
				<b>Function:</b> {FUNCTION} <br />
				<b>Function Arguments:</b> {ARGS}<hr> <br /><br />
			{/DEBUG}
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