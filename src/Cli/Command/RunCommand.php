<?php

namespace Leankoala\HealthFoundation\Cli\Command;

use Leankoala\HealthFoundation\Check\Check;
use Leankoala\HealthFoundation\HealthFoundation;
use Leankoala\HealthFoundation\Result\Format\Ietf\IetfFormat;
use PhmLabs\Components\Init\Init;
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
        /** @var string $configFile */
        $configFile = $input->getArgument('config');

        if (!file_exists($configFile)) {
            throw new \RuntimeException('Unable to find config file.');
        }

        $configArray = Yaml::parse(file_get_contents($configFile));

        $format = $this->initFormat($configArray);
        $messages = $this->initMessages($configArray);

        $healthFoundation = $this->initHealthFoundation($configArray);

        $runResult = $healthFoundation->runHealthCheck();

        $format->handle($runResult, $messages['success'], $messages['failure']);
    }

    /**
     * @param array $configArray
     *
     * @return HealthFoundation
     */
    private function initHealthFoundation($configArray)
    {
        if (!array_key_exists('checks', $configArray)) {
            throw new \RuntimeException('The mandatory config element "checks" is missing.');
        }

        $healthFoundation = new HealthFoundation();

        foreach ($configArray['checks'] as $key => $checkArray) {

            /** @var Check $check */
            $check = Init::initialize($checkArray, 'check');

            if (array_key_exists('description', $checkArray)) {
                $description = $checkArray['description'];
            } else {
                $description = "";
            }

            if (array_key_exists('identifier', $checkArray)) {
                $identifier = $checkArray['identifier'];
            } else {
                $identifier = $key;
            }

            $healthFoundation->registerCheck($check, $identifier, $description);
        }

        return $healthFoundation;
    }

    private function initMessages($configArray)
    {
        $successMessage = 'The health check was passed.';
        $failureMessage = 'The health check failed';

        if (array_key_exists('foundation', $configArray)) {
            if (array_key_exists('messages', $configArray['foundation'])) {
                if (array_key_exists('success', $configArray['foundation']['messages'])) {
                    $successMessage = $configArray['foundation']['messages']['success'];
                }

                if (array_key_exists('failure', $configArray['foundation']['messages'])) {
                    $failureMessage = $configArray['foundation']['messages']['failure'];
                }
            }
        }

        return ['success' => $successMessage, 'failure' => $failureMessage];
    }

    private function initFormat($configArray)
    {
        if (array_key_exists('format', $configArray)) {
            $format = Init::initialize($configArray['format']);
        } else {
            $format = new IetfFormat();
        }

        return $format;
    }
}
