<?php

namespace Leankoala\HealthFoundation\Cli;

use Leankoala\HealthFoundation\Cli\Command\RunCommand;
use Symfony\Component\Console\Formatter\OutputFormatter;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;

class Application extends \Symfony\Component\Console\Application
{
    public function run(InputInterface $input = null, OutputInterface $output = null)
    {
        if (null === $output) {
            $styles = array();
            $styles['failure'] = new OutputFormatterStyle('red');
            $formatter = new OutputFormatter(false, $styles);
            $output = new ConsoleOutput(ConsoleOutput::VERBOSITY_NORMAL, null, $formatter);
        }
        return parent::run($input, $output);
    }

    /**
     * {@inheritdoc}
     */
    public function doRun(InputInterface $input, OutputInterface $output)
    {
        $this->registerCommands();
        return parent::doRun($input, $output);
    }

    /**
     * Initializes all the commands.
     */
    private function registerCommands()
    {
        $this->add(new RunCommand());
    }
}
