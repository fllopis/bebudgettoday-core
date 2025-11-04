<?php
namespace App; 
use App\Debug as Debug;
use Mysqli;

class Bd
{
	var $conn = '';

	function __construct($autoload = false)
	{
		if( $autoload )
			$this->openBd();
	}

	/* Abrimos conexion */
	function openBd()
	{
		$this->conn = $this->c_();
	}

	/* Cerramos conexion */
	function closeBd()
	{
		if( $this->conn != '' )
			mysqli_close($this->conn);
	}

	/* Conexion */
	function c_()
	{
		if( !empty(bd_host) && !empty(bd_user) )
		{
			$link = new mysqli(bd_host, bd_user, bd_pass) or Debug::mlog(time(),'','Error al conectar con base de datos');
			$link->select_db(bd_name) or Debug::mlog(time(),'','Error seleccionando base de datos');
			return $link;
		}
	}

	/* Ejecuta una consulta */
	function query($sql)
	{
		if( $this->conn != '' )
		{
			$l = $this->conn;
			$q = $l->query($sql) or mysqli_error($l);

			if( $q )
				Debug::mlog(time(),$sql,'Ejecutada correctamente');	
			else
				Debug::mlog(time(),$sql,mysqli_error($l));	

			return $q;
		}
		else
			return false;
	}

	/**
	 * @todo buscar en todo el core
	 */
	function getResponse($sql)
	{
		if ( $this->conn != '' )
		{
			$l = $this->conn;
			$q = $l->query($sql) or mysqli_error($l);
			if( $q )
				return 'Ejecutada correctamente';
			else
				return mysqli_error($l);	
		}
		else
			return false;
	}

	/* Devuelve array con datos encontrados, o bien el dato en concreto, si lo conocemos */
	function fetchArray($sql,$query='')
	{
		$q = $this->query($sql);
		$r = $q->fetch_all(MYSQLI_ASSOC);
		if( $query != '' )
			return $r[$query];
		else
			return $r;
	}

	/* Devuelve un listado con objetos */
	function fetchObject($sql)
	{
		$q = $this->query($sql);
		$cant = $q->num_rows;
		$lista = array();
		
		for ( $i=0; $i<$cant; $i++ )
			$lista[$i] = $q->fetch_object();
		
		return $lista;
	}

	/* Devuelve un listado con objetos */
	function fetchObjectWithKey($sql, $key, $second_key='')
	{
		$q = $this->query($sql);
		$cant = $q->num_rows;
		$lista = array();
		if( $secondkey == '' )
		{
			for ( $i=0; $i<$cant; $i++ )
			{
				$d = $q->fetch_object();
				$lista[$d->$key][] = $d;
			}
		}
		else
		{
			for ( $i=0; $i<$cant; $i++ )
			{
				$d = $q->fetch_object();
				$lista[$d->$key."-".$d->$secondkey][] = $d;
			}
		}
		return $lista;
	}

	function fetchRow($sql, $type="object")
	{
		$result = $this->query($sql);

		if( !empty($result) && $result->num_rows == '1' )
		{
			if( $type == "object" )
				return $result->fetch_object();
			elseif( $type == "array" )
				return $result->fetch_array(MYSQLI_ASSOC);
		}

		return false;
	}

	/* Cuenta filas */
	function countRows($sql)
	{
		$q = $this->query($sql);
		return $q->num_rows;
	}
	
	/* Última id insertada*/
	function lastId()
	{
		return mysqli_insert_id($this->conn);
	}

	/* Inserta */
	function insert($table,$array)
	{	
		$names = '';
		$values = '';
		foreach( $array as $key => $val )
		{
			$names .= $key.',';	
			$values .= ( $val == 'SYSDATE()' ) ? 'SYSDATE(),' : '"'.$val.'",';	
		}
		$names = substr($names,0,strlen($names)-1);
		$values = substr($values,0,strlen($values)-1);
		$sql = 'INSERT INTO '.$table.' ('.$names.') VALUES ('.$values.')';

		return $this->query($sql);
	}

	/* Actualiza */
	function update($table,$array,$where)
	{	
		$names = '';
		foreach( $array as $key => $val )
		{
			$value = ( $val == 'SYSDATE()' ) ? 'SYSDATE(), ' : '"'.$val.'", ';
			$names .= $key.'='.$value;	
		}
		$names = substr($names,0,strlen($names)-2);
		$sql = 'UPDATE '.$table.' SET '.$names.' WHERE '.$where;
		return $this->query($sql);
	}

	public static function getInstance()
	{
		return new Bd(true);
	}
}
?>
