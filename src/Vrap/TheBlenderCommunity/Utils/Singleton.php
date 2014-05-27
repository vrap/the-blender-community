<?php
namespace Vrap\TheBlenderCommunity\Utils;

/**
* The Singleton trait let us create Singleton by just importing the Trait inside a class.
*/
trait Singleton
{
	/* This attribute will contain the singleton instance. */
	private static $instance;

	/**
	* Prevent user to instanciate the constructor.
	*
	* @return null
	*/
	final private function __construct()
	{
	}

	/**
	* Prevent user to clone the object.
	*
	* @return null
	*/
	final private function __clone()
	{
		throw new Exception('Can\'t clone "' . get_called_class() . '"  as it is a singleton object.');
	}

	/**
	* Retrieve the singleton instance.
	*
	* @return object The instance of the singleton.
	*/
	public static function getInstance()
	{
		/* Check if instance is already set. */
		if (!isset(self::$instance)) {
			$className = get_called_class();

			/* Instanciate the class and store it in instance attribute. */
			self::$instance = new $className();
		}

		/* Return the instance. */
		return self::$instance;
	}
}
