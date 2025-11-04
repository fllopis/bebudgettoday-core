<?php
namespace App;

class Configuracion
{
    private $app;

	public function __construct($app)
	{
		$this->app = $app;
	}

	/**
	 * Obtiene un valor de la tabla configuracion
	 *
	 * @param string $nombre
	 * @return string Value
	 */
	public function get($key)
	{
		$result = $this->app['bd']->fetchRow('SELECT valor FROM configuracion WHERE nombre = "'.$key.'"');

		if( !empty($result) )
			return $result->valor;

		return false;
	}

    /**
     * Obtiene varios valores de la tabla configuracion
     *
     * @throws Exception
     * @param array $keys
     * @return array $key => $value
     */
	public function getMultiple($keys)
	{
		if (!is_array($keys))
			throw new \Exception('Variable $keys no es un array');

        $result = array();

        foreach( $keys as $k )
            $result[$k] = $this->get($k);

        return (!empty($result) ? $result : false);
	}

    /**
     * Comprueba si un nombre existe en la tabla
     *
     * @param string $key
     * @return bool
     */
    public function checkKey($key)
    {
        $result = $this->app['bd']->fetchRow('SELECT nombre FROM configuracion WHERE nombre = "'.$key.'"');
        return (!empty($result) ? true : false);
    }

    /**
     * Actualiza un nombre y valor en la base de datos. Si no existe lo crea
     *
     * @param string $key
     * @param string $value
     * @return bool Resultado actualizacion
     */
    public function updateValue($key, $value)
    {
        $result = false;

        $currentTime = $this->app['tools']->datetime();

        if( $this->checkKey($key) )
        {
            $updConfig = array(
                'valor' => $value,
                'date_modified' => $currentTime,
                'date_created' => $currentTime
            );
            if( $this->app['bd']->update('configuracion', $updConfig, 'nombre = "'.$key.'"') )
                $result = true;
        }
        else
        {
            $addConfig = array(
                'nombre' => $key,
                'valor' => $value,
                'date_modified' => $currentTime,
                'date_created' => $currentTime
            );

            if( $this->app['bd']->insert('configuracion', $addConfig) )
                $result = true;
        }

        return $result;
    }
}
?>
