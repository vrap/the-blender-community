<?php
namespace Vrap\TheBlenderCommunity;

// Import the required namespaces.
use Vrap\TheBlenderCommunity\Utils\Singleton;

/**
* Configurator manage the configurations by loading a specified yaml file and let access us the data by the get method.
*/
class Configurator
{
	use Singleton;

	// $configurations contain the loaded ini file.
	private $configurations;

	/**
	* Load a ini config file.
	*
	* @param string $filePath Config file path.
	*/
	public function load($filePath)
	{
		// Parse $filePath and set the result to $this->configurations attribute.
		$this->configurations = parse_ini_file($filePath, true);
	}

	/**
	* Retrieve a configuration value.
	*
	* @param string $name Name of the configuration to retrieve.
	* @param string $defaultValue A value to set if $name does not exist.
	* @return mixed Return the result as a string for direct value or array for multiple ones.
	 */
	public function get($name, $defaultValue = null)
	{
		if (!isset($this->configurations[$name])) {
			return $defaultValue;
		}

		return $this->configurations[$name];
	}
}
