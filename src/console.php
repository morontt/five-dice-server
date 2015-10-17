<?php

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

$console = new Application('Five-Dice Application', '0.1');
/* @var \Silex\Application $app */
$console->setDispatcher($app['dispatcher']);
$console
    ->register('db:schema')
    ->setDefinition([
        new InputOption('dump-sql', null, InputOption::VALUE_NONE, 'Dumps the generated SQL (does not execute them)'),
    ])
    ->setDescription('Create schema database')
    ->setCode(function (InputInterface $input, OutputInterface $output) use ($app) {
        $queries = $app['fd_database.migrator']->migrate($input->getOption('dump-sql'));
        foreach ($queries as $query) {
            $output->writeln($query);
        }

        if (count($queries)) {
            if ($input->getOption('dump-sql')) {
                $output->writeln('<info>dump SQL schema</info>');
            } else {
                $output->writeln('<info>DB schema updated</info>');
            }
        } else {
            $output->writeln('<info>Nothing to update</info>');
        }
    })
;

return $console;
