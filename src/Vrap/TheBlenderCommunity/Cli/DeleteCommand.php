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

class DeleteCommand extends Command {
    protected function configure() {
        $this->setName('community:delete')
            ->setDescription('Delete a particular resource')
            ->addArgument(
                'resource',
                InputArgument::REQUIRED,
                'Resource name'
            )
            ->addOption(
               'id',
               NULL,
               InputOption::VALUE_REQUIRED,
               'Id of the resource item to delete'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $resource       = $input->getArgument('resource');
        $itemId         = $input->getOption('id');
        $singleResource = array();
        $isDeleted      = 0;

        switch ($resource) {
            case 'Users':
                $isDeleted = Repositories\Users::remove($itemId);

                break;
            case 'Recipes':
                $isDeleted = Repositories\Recipes::remove($itemId);

                break;
        }       

        if ($isDeleted == 1) {
            $output->writeln('Delete the ' . $resource . ' with id ' . $itemId . '.' . "\n");

            return;
        }
        else {
            $output->writeln('No ' . $resource . ' with id ' . $itemId . ' removed.' . "\n");

            return;
        }
    }
}

?>
