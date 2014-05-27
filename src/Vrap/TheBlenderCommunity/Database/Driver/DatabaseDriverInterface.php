<?php
namespace Vrap\TheBlenderCommunity\Database\Driver;

/**
* Define the databbase drivers interface. All database drivers need to
* implements this interface.
*/
interface DatabaseDriverInterface
{
	public function create($databaseConfiguration);
}
