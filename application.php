#!/usr/bin/env php

<?php

require 'vendor/autoload.php';

use Vrap\TheBlenderCommunity\Cli\ListCommand;
use \Symfony\Component\Console\Application;

$application = new Application('Community', 'beta');

$application->add(new ListCommand);
$application->run();

?>
