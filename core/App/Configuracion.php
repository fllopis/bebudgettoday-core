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
	 * Get a value from the configuration table
	 *
	 * @param string $shortcode
	 * @return string value
	 */
	public function get($shortcode){
		$result = $this->app['bd']->fetchRow('SELECT value FROM configurations WHERE shortcode = "'.$shortcode.'"');

		if( !empty($result) )
			return $result->value;

		return false;
	}

    /**
     * Obtains several values from the configuration table.
     *
     * @throws Exception
     * @param array $shortcodes
     * @return array $shortcodes => $value
     */
	public function getMultiple($shortcodes){
		if (!is_array($shortcodes))
			throw new \Exception('Is not a valid array');

        $result = array();

        foreach( $shortcodes as $shortcode )
            $result[$shortcode] = $this->get($shortcode);

        return (!empty($result) ? $result : false);
	}

    /**
     * Check if a name exists in the table
     *
     * @param string $shortcode
     * @return bool
     */
    public function checkKey($shortcode){
        $result = $this->app['bd']->fetchRow('SELECT shortcode FROM configurations WHERE shortcode = "'.$shortcode.'"');
        return (!empty($result) ? true : false);
    }

    /**
     * Updates a name and value in the database. If it does not exist, it creates it.
     *
     * @param string $shortcode
     * @param string $value
     * @return bool Update result
     */
    public function updateValue($shortcode, $value){
        $result = false;

        $currentTime = $this->app['tools']->datetime();

        if($this->checkKey($shortcode)){
            $updConfig = [
                'value' => $value,
            ];
            if( $this->app['bd']->update('configurations', $updConfig, 'shortcode = "'.$shortcode.'"') )
                $result = true;
        }
        else
        {
            $addConfig = [
                'shortcode' => $shortcode,
                'value' => $value,
            ];

            if( $this->app['bd']->insert('configurations', $addConfig) )
                $result = true;
        }

        return $result;
    }
}
?>
