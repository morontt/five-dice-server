<?php

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

$console = new Application('Five-Dice Application', '0.1');
$console->setDispatcher($app['dispatcher']);
$console
    ->register('db:schema')
    ->setDefinition([])
    ->setDescription('Create schema database')
    ->setCode(function (InputInterface $input, OutputInterface $output) use ($app) {
        $queries = $app['fd_database.migrator']->migrate();
        foreach ($queries as $query) {
            $output->writeln($query);
        }

        if (count($queries)) {
            $output->writeln('<info>DB schema updated</info>');
        } else {
            $output->writeln('<info>Nothing to update</info>');
        }
    })
;

return $console;
