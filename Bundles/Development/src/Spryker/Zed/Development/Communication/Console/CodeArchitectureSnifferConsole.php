<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Communication\Console;

use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Zend\Filter\Word\UnderscoreToCamelCase;

/**
 * @method \Spryker\Zed\Development\Business\DevelopmentFacade getFacade()
 * @method \Spryker\Zed\Development\Communication\DevelopmentCommunicationFactory getFactory()
 */
class CodeArchitectureSnifferConsole extends Console
{
    const COMMAND_NAME = 'code:sniff:architecture';
    const OPTION_MODULE = 'module';
    const OPTION_CORE = 'core';
    const OPTION_STRICT = 'strict';
    const OPTION_PRIORITY = 'priority';
    const OPTION_DRY_RUN = 'dry-run';
    const ARGUMENT_SUB_PATH = 'path';
    const APPLICATION_LAYERS = ['Zed', 'Client', 'Yves', 'Service', 'Shared'];

    /**
     * @return void
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName(static::COMMAND_NAME)
            ->setHelp('<info>' . static::COMMAND_NAME . ' -h</info>')
            ->setDescription('Check architecture rules for project or core');

        $this->addOption(static::OPTION_MODULE, 'm', InputOption::VALUE_OPTIONAL, 'Name of module to run architecture sniffer for');
        $this->addOption(static::OPTION_CORE, 'c', InputOption::VALUE_NONE, 'Core (instead of Project)');
        $this->addOption(static::OPTION_PRIORITY, 'p', InputOption::VALUE_OPTIONAL, 'Priority [1 (highest), 2 (medium), 3 (experimental)], defaults to 2.');
        $this->addOption(static::OPTION_STRICT, 's', InputOption::VALUE_NONE, 'Also report those nodes with a @SuppressWarnings annotation');
        $this->addOption(static::OPTION_DRY_RUN, 'd', InputOption::VALUE_NONE, 'Dry-Run the command, display it only');

        $this->addArgument(static::ARGUMENT_SUB_PATH, InputArgument::OPTIONAL, 'Optional path or sub path element');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int Exit code
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $isCore = $this->input->getOption(static::OPTION_CORE);
        $module = $this->input->getOption(static::OPTION_MODULE);
        $message = sprintf('Run Architecture Sniffer for %s', $isCore ? 'CORE' : 'PROJECT');

        if ($module) {
            $module = $this->normalizeModuleName($module);
            $message .= ' in ' . $module . ' module';
        }

        $path = $this->input->getArgument(static::ARGUMENT_SUB_PATH);
        if ($path) {
            $message .= ' (' . $path . ')';
        }

        $this->info($message);

        if ($isCore) {
            $success = $this->runForCore($input, $output, $module, $path);
        } else {
            $success = $this->runForProject($input, $output, $module, $path);
        }

        return $success ? static::CODE_SUCCESS : static::CODE_ERROR;
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param string $module
     * @param string $subPath
     *
     * @return bool
     */
    protected function runForCore(InputInterface $input, OutputInterface $output, $module, $subPath)
    {
        $path = $this->getFactory()->getConfig()->getPathToCore();
        if ($module) {
            $path .= $module . DIRECTORY_SEPARATOR;
        }
        if ($subPath) {
            $path .= trim($subPath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        }

        $output->writeln($path, OutputInterface::VERBOSITY_VERBOSE);
        $violations = $this->getFacade()->runArchitectureSniffer($path, $this->input->getOptions());
        $count = $this->displayViolations($output, $violations);
        $output->writeln($count . ' violations found');

        return $count === 0;
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param string $module
     * @param string $subPath
     *
     * @return bool
     */
    protected function runForProject(InputInterface $input, OutputInterface $output, $module, $subPath)
    {
        $pathToRoot = $this->getFactory()->getConfig()->getPathToRoot();
        $projectNamespaces = $this->getFactory()->getConfig()->getProjectNamespaces();

        $result = 0;
        foreach ($projectNamespaces as $projectNamespace) {
            $output->writeln($projectNamespace, OutputInterface::VERBOSITY_VERBOSE);
            $path = $pathToRoot . 'src' . DIRECTORY_SEPARATOR . $projectNamespace . DIRECTORY_SEPARATOR;

            $paths = [];
            if ($module) {
                foreach (static::APPLICATION_LAYERS as $layer) {
                    $paths[] = $path . $layer . DIRECTORY_SEPARATOR . $module . DIRECTORY_SEPARATOR;
                }
            } else {
                $paths = [$path];
            }

            foreach ($paths as $path) {
                if ($subPath) {
                    $path .= trim($subPath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
                }
                if (!is_dir($path)) {
                    $output->writeln('Path not found, skipping: ' . $path, OutputInterface::VERBOSITY_VERY_VERBOSE);
                    continue;
                }
                $output->writeln('Checking path: ' . $path, OutputInterface::VERBOSITY_VERBOSE);

                $violations = $this->getFacade()->runArchitectureSniffer($path, $this->input->getOptions());
                $count = $this->displayViolations($output, $violations);
                $result += $count;
            }
        }

        $output->writeln($result . ' violations found');

        return $result === 0;
    }

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param array $violations
     *
     * @return int
     */
    protected function displayViolations(OutputInterface $output, array $violations)
    {
        $count = 0;
        foreach ($violations as $violationArray) {
            foreach ($violationArray as $violation) {
                $output->writeln('<error> ' . trim($violation['_']) . ' (l. ' . $violation['beginline'] . ')</error>', OutputInterface::VERBOSITY_VERBOSE);
                $output->writeln(' ' . $violation['ruleset'] . ' > ' . $violation['rule'], OutputInterface::VERBOSITY_VERBOSE);
                $count++;
            }
        }

        return $count;
    }

    /**
     * @param string $module
     *
     * @return string
     */
    protected function normalizeModuleName($module)
    {
        $filter = new UnderscoreToCamelCase();
        $normalized = $filter->filter(str_replace('-', '_', $module));
        $normalized = ucfirst($normalized);

        return $normalized;
    }
}
