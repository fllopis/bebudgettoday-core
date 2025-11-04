<?php
if( !empty($_SESSION['css_paths']) )
{
	foreach( $_SESSION['css_paths'] as $css )
	{
		?>
		<link href="<?=$css;?>" rel="stylesheet" type="text/css" />
		<?php
	}
}
unset($_SESSION['css_paths']);
