<?php

namespace Vrap\TheBlenderCommunity\Cli;

use \Symfony\Component\Console\Command\Command;
use \Symfony\Component\Console\Input\InputArgument;
use \Symfony\Component\Console\Input\InputInterface;
use \Symfony\Component\Console\Input\InputOption;
use \Symfony\Component\Console\Output\OutputInterface;

use Vrap\TheBlenderCommunity\Repositories;
use Vrap\TheBlenderCommunity;

$configuration = TheBlenderCommunity\Configurator::getInstance()->load('config.ini');

class ReadCommand extends Command {
    protected function configure() {
        $this->setName('community:read')
            ->setDescription('Read a particular resource')
            ->addArgument(
                'resource',
                InputArgument::REQUIRED,
                'Resource name'
            )
            ->addOption(
               'name',
               null,
               InputOption::VALUE_NONE,
               'Name of the resource item'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $resource       = $input->getArgument('resourceName');
        $itemName       = $input->getOption('number');
        $singleResource = array();

        switch ($resource) {
            case 'User':
                $singleResource = Repositories\Users::retrieveByUsername($itemName);
        
                break;
            case 'Recipe':
                $singleResource = Repositories\Recipes::retrieveByName($itemName);

                break;
            default:
        
            break;
        }

        if (! empty($singleResource)) {
            $columns = '';
            $items   = '';
            $counter = 0;

            if (! empty($numberOfRows)) {
                $allResource = array_slice($allResource, 0, $numberOfRows);
            }

            foreach ($allResource[0] as $column => $value) {
                $columns .= $column . $this->sizeColumn($column);
            }

            foreach ($allResource as $item => $field) {
                $line = '';

                foreach (array_keys($field) as $key => $value) {
                    $line .= $field[$value] . $this->sizeColumn($field[$value]);
                }

                $items .= $line . "\n";

                $counter++;
            }

            $return = 'List the ' . $counter . ' items of ' . $resource . ':' . "\n\n" .
                $columns . "\n" .
                $items;

            $output->writeln($return);
        }
        else {
            $output->writeln('No ' . $resource . ' founded.' . "\n");

            return;
        }
    }
    }
}

?>
