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

// https://github.com/nategood/commando

require_once 'vendor/autoload.php';

use Vrap\TheBlenderCommunity\Repositories;
use Vrap\TheBlenderCommunity;

$configuration = TheBlenderCommunity\Configurator::getInstance()->load('config.ini');

print 'Community CLI - beta (https://github.com/vrap/the-blender-community)' . "\n\n";

$listCmd = new Commando\Command();

// Define a flag "-l" a.k.a. "--list"
$listCmd->flag('l')
    ->require()
    ->aka('list')
    ->describedAs('List all items of a resource')
    ->must(function($arg) {
        $resources = array('User', 'Recipe');

        return in_array($arg, $resources);
    });

// Define a flag "-n" a.k.a. "--number"
$listCmd->flag('n')
    ->aka('number')
    ->describedAs('Number of rows to retrive')
    ->must(function($arg) {
        return (is_numeric($arg) && $arg > 0);
    });

$allResource  = array();
$resource     = $listCmd['l'];
$numberOfRows = (int)$listCmd['n'];

switch ($resource) {
    case 'User':
        $allResource = Repositories\Users::retrieveAll();
        
        break;
    case 'Recipe':
        $allResource = Repositories\Recipes::retrieveAll();

        break;
    default:
        
        break;
}

if (! empty($allResource)) {
    $columns = '';
    $items   = '';
    $counter = 0;

    if (! empty($numberOfRows)) {
        $allResource = array_slice($allResource, 0, $numberOfRows);
    }

    foreach ($allResource[0] as $column => $value) {
        $columns .= $column . sizeColumn($column);
    }

    foreach ($allResource as $item => $field) {
        $line = '';

        foreach (array_keys($field) as $key => $value) {
            $line .= $field[$value] . sizeColumn($field[$value]);
        }

        $items .= $line . "\n";

        $counter++;
    }

    $return = 'List the ' . $counter . ' items of ' . $resource . ':' . "\n\n" .
        $columns . "\n" .
        $items;

    echo $return;
}
else {
    echo 'No ' . $resource . ' founded.' . "\n";

    return;
}

function sizeColumn($column) {
    $widthColumns  = 20;
    $spacesCounter = ($widthColumns - strlen($column)); 
    $spaces        = '';

    for ($i = 0; $i < $spacesCounter; $i++) {
        $spaces .=  ' ';
    }

    $spaces .= '|';

    return $spaces;
}




// Define first option
/*$readCmd->argument()
    ->require()
    ->describedAs('Resource')
    ->must(function($resource) {
        $resources = array('User', 'Recipe');

        return in_array($resource, $resources);
    });*/

//var_dump($readCmd->getArgumentValues());
//var_dump($readCmd->getFlagValues());


/*
// Define a flag "-d" aka "--delete"
$cliCmd->flag('d')
    ->aka('delete')
    ->describedAs('Delete a resource');

// Define a flag "-l" aka "--list"
$cliCmd->flag('l')
    ->aka('list')
    ->describedAs('List all items of a resource');

// Define a flag "--reset-password"
$cliCmd->flag('reset-password')
    ->describedAs('Reset a User password');





// Read a Resource:              cli.php -r Resource ResourceId
// Delete a Resource:            cli.php -d Resource ResourceId
// List all items of a Resource: cli.php -l Resource
// Reset a USer password:        cli.php --reset-password UserId





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
