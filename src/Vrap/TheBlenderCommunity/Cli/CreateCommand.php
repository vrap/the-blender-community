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

class CreateCommand extends Command {
    protected function configure() {
        $this->setName('community:create')
            ->setDescription('Create a user')
            ->addArgument(
               'username',
               InputArgument::REQUIRED,
               'Username of the new user'
            )
            ->addOption(
               'email',
               NULL,
               InputOption::VALUE_OPTIONAL,
               'Email of the new user'
            )
            ->addOption(
               'password',
               NULL,
               InputOption::VALUE_OPTIONAL,
               'Password of the new user'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $username = $input->getArgument('username');
        $email    = $input->getOption('email');
        $password = $input->getOption('password');

        if (empty($email)) {
            $email = $username . '@gmail.com';
        }

        if (empty($password)) {
            $password = $email . $username;
        }

        $status = Repositories\Users::save($username, $email, $password);

        if ($status == 1) {
            $output->writeln('User ' . $username . ' is created' . "\n");
        }
        else {
            $output->writeln('User ' . $username . ' is not created' . "\n");
        }
    }
}

?>
