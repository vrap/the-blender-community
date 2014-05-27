<?php
namespace Vrap\TheBlenderCommunity\Database;

// Import required namespaces.
use Vrap\TheBlenderCommunity\Utils\Singleton;
use Vrap\TheBlenderCommunity\Database\Driver;

/**
* The database factory return the good database by searching the driver in the config file.
*/
class DatabaseFactory
{
	// Use the singleton trait.
	use Singleton;

	/**
	* Retrieve the database configurations.
	*
	* @return object Return a pdo instance.
	*/
	public static function getDatabase()
	{
		// Retrieve the database configuration.
		$configuration = \Vrap\TheBlenderCommunity\Configurator::getInstance()->get('database', false);

		// If the configuration can not be retrieved (does not exist in the yaml file ?).
		if (false === $configuration) {
			throw new \Exception('Can not retrieve database configuration.');
		}

		// Switch over configuration driver and instanciate the good class.
		switch ($configuration['driver']) {
			case 'mysql':
				$driver = new Driver\DatabaseMySQLDriver();
				break;

			case 'sqlite':
				$driver = new Driver\DatabaseSQLiteDriver();
				break;

			default:
				throw new \Exception('Driver ' . $configuration['driver'] . ' could not be found.');
				break;
		}

		// Create the Database object with the good driver and return it.
		return $driver->create($configuration);
	}
}
