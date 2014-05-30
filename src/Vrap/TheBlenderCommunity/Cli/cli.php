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

print 'Community CLI - beta (https://github.com/vrap/the-blender-community)' . "\n";

$helpText = 'Usage: php cli.php [Operation] [Resource]' . "\n" .
            'OPERATION:' . "\n" .
                "\t" . '- Create: ' . "\n" .
                "\t" . '- Read:' . "\n" .
                "\t" . '- Update:' . "\n" .
                "\t" . '- Delete:' . "\n" .
            'RESOURCE:' . "\n" .
            'EXAMPLES:' . "\n" .
                "\t" . '- Create User:   php cli.php -c User' . "\n" .
                "\t" . '- Read Recipe:   php cli.php -r Recipe'  . "\n" .
                "\t" . '- Update Recipe: php cli.php -u Recipe'  . "\n" .
                "\t" . '- Delete User:   php cli.php -d User' . "\n";

if ($argc < 1) {

}
else {
    print $helpText;
}

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
