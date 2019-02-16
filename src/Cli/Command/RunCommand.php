<?php

namespace Leankoala\HealthFoundation\Cli\Command;

use Leankoala\HealthFoundation\Config\FormatFactory;
use Leankoala\HealthFoundation\Config\HealthFoundationFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;


class RunCommand extends Command
{
    protected function configure()
    {
        $this
            ->setDefinition([
                new InputArgument('config', InputArgument::REQUIRED, 'the configuration file')
            ])
            ->setDescription('Run health checks.')
            ->setHelp('The <info>run</info> command for the health checker.')
            ->setName('run');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $configFile = (string)$input->getArgument('config');

        if (!file_exists($configFile)) {
            throw new \RuntimeException('Unable to find config file.');
        }

        $configArray = Yaml::parse(file_get_contents($configFile));

        $healthFoundation = HealthFoundationFactory::from($configArray);
        $format = FormatFactory::from($configArray);

        $runResult = $healthFoundation->runHealthCheck();

        $format->handle($runResult);
    }
}
