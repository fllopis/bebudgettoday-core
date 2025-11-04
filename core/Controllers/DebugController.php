<?php
namespace Controllers;

class DebugController
{
	var $page;
	var $app;

	public function execute($page,$app)
	{
		$this->page = $page;
		$this->app = $app;

		$this->app['render']->layout = false;

		//Debug bd
		$this->add('bd',function()
		{
			if( _DEBUG_ )
			{
				if( isset($_GET['id']) && $_GET['id'] == 'clean' )
				{
					unset($_SESSION['debug']['bd']);
					header('Location:'._DOMINIO_.'debug/bd/');
				}
				if( isset($_GET['id']) && $_GET['id'] == 'clean-ok' )
				{
					$cache = $_SESSION['debug']['bd'];
					unset($_SESSION['debug']['bd']);
					$cantidad = count($cache);
					for ( $i=$cantidad-1; $i>=0; $i-- )
					{
						$result = $cache[$i][2];
						if( $result != 'Ejecutada correctamente' ) {
							$_SESSION['debug']['bd'][] = $cache[$i];
						}
					}
					header('Location:'._DOMINIO_.'debug/bd/');
				}
				?>
				<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
				<h1 style="font-family:verdana; font-size:26px; color:#339999;">Db Log:</h1>
				<div style="float:right; margin-top:-40px ">
					<button onclick="document.location='<?=_DOMINIO_?>debug/bd/clean-ok/';" style="padding:10px 20px;">Limpiar Correctas</button>
					<button onclick="document.location='<?=_DOMINIO_?>debug/bd/clean/';" style="padding:10px 20px;">Limpiar Todo</button>
				</div>
				<br clear="all" /><br />
				<script>
					function enviar(n)
					{
						$.ajax({
							type: "POST",
							url: "<?=_DOMINIO_?>debug/ajax-bd-test/",
							data: 'sql='+$('#sql'+n).val(),
							success: function(data)
							{
								if( data == 'Ejecutada correctamente' )
									$('#the_result_'+n).html('<div style="padding:4px 12px; border-radius:5px; color:#fff; background-color:#339999">'+data+'</div>');
								else
									$('#the_result_'+n).html('<div style="padding:4px 12px; border-radius:5px; color:#fff; background-color:#CC3333">'+data+'</div>');
							}
						});	
					}
				</script>
				<?php
				if( isset($_SESSION['debug']['bd']) )
				{
					$cantidad = count($_SESSION['debug']['bd']);
					for ( $i=$cantidad-1; $i>=0; $i-- )
					{
						$time = $_SESSION['debug']['bd'][$i][0];
						$sql = $_SESSION['debug']['bd'][$i][1];
						$result = $_SESSION['debug']['bd'][$i][2];
						?>
						<div style="padding:10px; font-family:verdana; font-size:12px; color:#666; border-radius:5px 5px 0 0; border-top:1px solid #ccc; border-left:1px solid #ccc; border-right:1px solid #ccc;background-color:#f2f2f2">
							<div style="float:left">
								<button onclick="enviar(<?=$i?>);">Ejecutar</button>&nbsp;&nbsp;Ejecutada a las <?=date('H:i:s',$time);?> hs. 
							</div>
							<div style="float:right" id="the_result_<?=$i?>">
								<?= $result == 'Ejecutada correctamente' ? '<div style="padding:4px 12px; border-radius:5px; color:#fff; background-color:#339999">'.$result.'</div>' : '<div style="padding:4px 12px; border-radius:5px; color:#fff; background-color:#CC3333">'.$result.'</div>' ?>
							</div>
							<br clear="all" />
						</div>
						<textarea style="width:100%; border:1px solid #ccc; outline:none; padding:10px; border-radius:0 0 5px 5px; font-size:16px; height:100px" id="sql<?=$i?>"><?=$sql?></textarea>
						<br /><br />
						<br /><br />
						<?php
					}
				}
			}
			else
				header('Location:'._DOMINIO_);
		});
	
		//Ajax bd test
		$this->add('ajax-bd-test',function()
		{
			$sql = $_POST['sql'];
			echo $this->app['bd']->getResponse($sql);
		});
	}

	public function add($page,$data)
	{
		if( $page == $this->page )
			return $data($this->app);
	}
}
?>
