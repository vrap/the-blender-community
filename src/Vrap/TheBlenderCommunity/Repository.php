<?php
namespace Vrap\TheBlenderCommunity;

// Import the required namespaces.
use Vrap\TheBlenderCommunity\Database\DatabaseFactory;

/**
* Repository describe how repositories classes need to be. It also provide some
* methods that can be accessible from all repositories.
*/
abstract class Repository
{
	/**
	* Return an instance of the database created by the DatabaseFactory.
	*
	* @return object A Database handler.
	*/
	public static function getDatabase()
	{
		return DatabaseFactory::getInstance()->getDatabase();
	}
}
