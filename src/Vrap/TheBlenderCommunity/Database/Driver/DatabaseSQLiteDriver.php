<?php
namespace Vrap\TheBlenderCommunity\Database\Driver;

/**
* The SQLite driver define how to access to a SQLite database.
*/
class DatabaseSQLiteDriver implements DatabaseDriverInterface {

	/**
	* Return a pdo object with the good format.
	*
	* @param array $databaseConfiguration An array retrieved by Configurator
	* with databases informations.
	* @return object A pdo object.
	*/
	public function create($databaseConfiguration)
	{
		return new \PDO('sqlite:'.$databaseConfiguration['name']);
	}
}
