<?php
namespace Vrap\TheBlenderCommunity\Database\Driver;

/**
* The MySQL driver define how to access to a MySQL database.
*/
class DatabaseMySQLDriver implements DatabaseDriverInterface
{

	/**
	* Return a pdo object with the good format.
	*
	* @param array $databaseConfiguration An array retrieved by Configurator
	* with databases informations.
	* @return object A pdo object.
	*/
	public function create($databaseConfiguration)
	{
		return new \PDO('mysql:host=' . $databaseConfiguration['host'] . ';port=' . $databaseConfiguration['port'] . ';dbname=' . $databaseConfiguration['name'],
				$databaseConfiguration['user'],
				$databaseConfiguration['password']);
	}
}
