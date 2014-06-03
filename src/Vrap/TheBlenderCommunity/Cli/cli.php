#!/bin/php
<?php

/**
 * Welcome to the Community CLI script
 *
 * It allow to make some operations on Community Recipes and Users.
 */

// https://github.com/nategood/commando

require_once 'vendor/autoload.php';

use Vrap\TheBlenderCommunity\Repositories;
use Vrap\TheBlenderCommunity;

$configuration = TheBlenderCommunity\Configurator::getInstance()->load('config.ini');

print 'Community CLI - beta (https://github.com/vrap/the-blender-community)' . "\n\n";

// List command
// Usage: cli.php -l Resource [-n]
/*
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
}*/

// Read command
// cli.php -r Resource ResourceId

$readCmd = new Commando\Command();

// Define a flag "-r" a.k.a. "--read"
$readCmd->flag('r')
    ->require()
    ->aka('read')
    ->describedAs('Read a particular resource')
    ->must(function($arg) {
        $resources = array('User', 'Recipe');

        return in_array($arg, $resources);
    });

$readCmd->argument()
    ->require()
    ->describedAs('Resource name')
    ->needs('r')
    ->must(function($arg) {
        return (is_string($arg));
    });

$singleResource = array();
$resource       = $readCmd['r'];
$resourceName   = $readCmd[0];

switch ($resource) {
    case 'User':
        $singleResource = Repositories\Users::retrieveByUser();
        
        break;
    case 'Recipe':
        $singleResource = Repositories\Recipes::retrieveByUser();

        break;
    default:
        
        break;
}

if (! empty($singleResource)) {
    
}














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
