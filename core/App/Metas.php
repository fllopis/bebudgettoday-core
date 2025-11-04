<?php
namespace App;

//Configuracion de metatags
class Metas
{
	var $metaTitle = _TITLE_ ;
	var $metaDescription = _DESCRIPTION_;

	public function getMetas()
	{
		?>
	    <title><?=$this->metaTitle?></title>
	    <meta http-equiv="description" content="<?=$this->metaDescription?>" />
	    <?php
	}
}
?>