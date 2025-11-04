<?php
namespace App;

class Debug
{
	var $height = 320;
	var $mysql = array();
	var $lore = array();

    //Creamos entrada en log
    public function add($data,$type='lore')
    {
        $data = '['.date('d-m-y H:i:s').'] - '.$data;
    	array_push($this->{$type},$data);

        $f = log_folder.'log';
        $size = @filesize($f);
        if ( $size > log_max_kb*1024 )
        { 
            rename($f,$f.'_'.date('Y-m-d_H-i-s'));
            $file = @fopen($f,'a');
            @fwrite($file,$data.PHP_EOL);
            @fclose($file);
        }
        else
        {
            $file = @fopen($f,'a');
            @fwrite($file,$data.PHP_EOL);
            fclose($file);
        }
    }

     //Creamos entrada en log de mysql
    public static function mlog($time,$sql,$result)
    {
        if( _DEBUG_ )
            $_SESSION['debug']['bd'][] = array($time,$sql,$result);
    }   

    //Llamamos a log al escribir la palabra LOG
    public function callLog()
    {
        ?>
    	<script>
    		var enter_log = 0;
    		function PulsarTecla(event)
            {
			    tecla = event.keyCode;
			    if ( tecla == 76 && enter_log == 0 )
			    	enter_log = 1;
			    else if ( tecla == 79 && enter_log == 1 )
			    	enter_log = 2;
			    else if ( tecla == 71 && enter_log == 2 )
			    	enter_log = 3;
			    else
			    	enter_log = 0;

			    if ( enter_log == 3 )
                {
			    	window.open('<?=_DOMINIO_?>debug/bd/');
			    	enter_log = 0;
			    }
			}
			window.onkeydown=PulsarTecla;
    	</script>
    	<?php
    }
}
?>
