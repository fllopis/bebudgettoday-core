<?php
if( !empty($_SESSION['js_paths']['top']) )
{
	foreach( $_SESSION['js_paths']['top'] as $js )
	{
		?>
		<script type="text/javascript" src="<?=$js;?>"></script>
		<?php
	}
}
unset($_SESSION['js_paths']['top']);
