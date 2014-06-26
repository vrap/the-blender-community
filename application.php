#!/usr/bin/env php

<?php

require 'vendor/autoload.php';

use Vrap\TheBlenderCommunity\Cli\ListCommand;
use Vrap\TheBlenderCommunity\Cli\ReadCommand;
use Vrap\TheBlenderCommunity\Cli\DeleteCommand;
use \Symfony\Component\Console\Application;

$application = new Application('Community', 'beta');

$application->add(new ListCommand);
$application->add(new ReadCommand);
$application->add(new DeleteCommand);
$application->run();

?>
