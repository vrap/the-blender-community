#!/bin/php
<?php

/**
 * Welcome to the Community CLI script
 *
 * It allow to make CRUD (Create, Read, Update, Delete) operations on Community Recipes and Users.
 */

/**
 * Remember section (development help)
 * 
 * $argc: number of arguments passed to the script from the CLI
 * $argv: array of these arguments
 * Use print function to display anything to CLI
 */

// FIY, behavior is largely inspired by the behavior of namp.

require_once 'vendor/autoload.php';

print 'Community CLI - beta (https://github.com/vrap/the-blender-community)' . "\n";

$cliCmd = new Commando\Command();

// Define first option
$cliCmd->option()
    ->require()
    ->describedAs('Resource');

// Define a flag "-c" a.k.a. "--read"
$cliCmd->option('r')
    ->aka('read')
    ->describedAs('Read a resource')
    ->must(function($title) {
        $titles = array('Mister', 'Mr', 'Misses', 'Mrs', 'Miss', 'Ms');
        
        return in_array($title, $titles);
    });

// Define a flag "-d" aka "--delete"
$cliCmd->option('d')
    ->aka('delete')
    ->describedAs('Delete a resource');


/*
$helpText = 'Usage: php cli.php [Operation] [Resource]' . "\n" .
            'OPERATION:' . "\n" .
                "\t" . '- Read:' . "\n" .
                "\t" . '- Delete:' . "\n" .
            'RESOURCE:' . "\n" .
            'EXAMPLES:' . "\n" .
                "\t" . '- Read Recipe:   php cli.php -r Recipe'  . "\n" .
                "\t" . '- Delete User:   php cli.php -d User' . "\n";

if ($argc < 1) {

}
else {
    print $helpText;
}
*/
/*
May be useful:

for ($i = 0; $i < $argc; $i++) {
    print "$i: $argv[$i]\n";
}

echo "Are you sure you want to do this?  Type 'yes' to continue: ";

$handle = fopen ("php://stdin","r");
$line = fgets($handle);

if (trim($line) != 'yes'){
    echo "ABORTING!\n";
    exit;
}

echo "\n";
echo "Thank you, continuing...\n";
*/

?>
