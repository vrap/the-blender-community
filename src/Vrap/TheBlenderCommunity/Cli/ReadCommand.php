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
               NULL,
               InputOption::VALUE_NONE,
               'Name of the resource item to read'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $resource       = $input->getArgument('resource');
        $itemName       = $input->getOption('name');
        $singleResource = array();

        switch ($resource) {
            case 'Users':
                $singleResource = Repositories\Users::retrieveByUsername($itemName);
        
                break;
            case 'Recipes':
                $singleResource = Repositories\Recipes::retrieveByName($itemName);

                break;
        }

        if (empty($singleResource) === FALSE) {
            $columns = '';
            $items   = '';

            foreach ($singleResource[0] as $column => $value) {
                $columns .= $column . $this->sizeColumn($column);
            }

            foreach ($singleResource as $item => $field) {
                $line = '';

                foreach (array_keys($field) as $key => $value) {
                    $line .= $field[$value] . $this->sizeColumn($field[$value]);
                }

                $items .= $line . "\n";
            }

            $return = 'Read the ' . $resource . ' with name ' . $itemName . ':' . "\n\n" .
                $columns . "\n" .
                $items;

            $output->writeln($return);
        }
        else {
            $output->writeln('No ' . $resource . ' with name ' . $itemName . ' founded.' . "\n");

            return;
        }
    }
}

?>
