<?php

namespace MyModule\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MyTestCommand extends Command 
{
    protected function configure()
    {
        $this->setName('my-module:my-test-command');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("Test command executed"); //Write line
        // $ouput->write(); //no line
    }
}