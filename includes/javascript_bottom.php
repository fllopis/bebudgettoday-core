<?php
if( !empty($_SESSION['js_paths']['bottom']) )
{
	foreach( $_SESSION['js_paths']['bottom'] as $js )
	{
		?>
		<script type="text/javascript" src="<?=$js;?>"></script>
		<?php
	}
}
unset($_SESSION['js_paths']['bottom']);
