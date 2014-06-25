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

class ListCommand extends Command {
    protected function configure() {
        $this->setName('community:list')
            ->setDescription('List all items of a resource')
            ->addArgument(
                'resource',
                InputArgument::REQUIRED,
                'Resource name'
            )
            ->addOption(
               'number',
               null,
               InputOption::VALUE_NONE,
               'Number of rows to retrive'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $resource     = $input->getArgument('resource');
        $numberOfRows = $input->getOption('number');
        $allResource  = array();

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

    protected function sizeColumn($column) {
        $widthColumns  = 20;
        $spacesCounter = ($widthColumns - strlen($column)); 
        $spaces        = '';

        for ($i = 0; $i < $spacesCounter; $i++) {
            $spaces .=  ' ';
        }

        $spaces .= '|';

        return $spaces;
    }
}

?>
