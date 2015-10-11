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
        $x = $app['fd_database']->schemaCreate();
        $output->writeln($x);
        $output->writeln('<info>DB schema created</info>');
    })
;

return $console;
