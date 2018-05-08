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
use Zend\Filter\FilterChain;
use Zend\Filter\StringToLower;
use Zend\Filter\Word\CamelCaseToDash;
use Zend\Filter\Word\UnderscoreToCamelCase;

/**
 * @method \Spryker\Zed\Development\Business\DevelopmentFacadeInterface getFacade()
 * @method \Spryker\Zed\Development\Communication\DevelopmentCommunicationFactory getFactory()
 */
class CodeArchitectureSnifferConsole extends Console
{
    protected const COMMAND_NAME = 'code:sniff:architecture';
    protected const OPTION_MODULE = 'module';
    protected const OPTION_STRICT = 'strict';
    protected const OPTION_PRIORITY = 'priority';
    protected const OPTION_DRY_RUN = 'dry-run';
    protected const ARGUMENT_SUB_PATH = 'path';
    protected const APPLICATION_LAYERS = ['Zed', 'Client', 'Yves', 'Service', 'Shared'];

    protected const NAMESPACE_SPRYKER_SHOP = 'SprykerShop';
    protected const NAMESPACE_SPRYKER = 'Spryker';

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

        $this->addOption(static::OPTION_MODULE, 'm', InputOption::VALUE_OPTIONAL, 'Name of module to run architecture sniffer for. You can use dot syntax for namespaced ones, e.g. `SprykerEco.FooBar`. `Spryker.all`/`SprykerShop.all` is reserved for CORE internal usage.');
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
        $module = $this->input->getOption(static::OPTION_MODULE);
        $isCore = strpos($module, '.') !== false;
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
            $success = $this->runForCore($output, $module, $path);
        } else {
            $pathToRoot = $this->getFactory()->getConfig()->getPathToRoot();
            $customPath = $pathToRoot . $path;
            if (!$module && file_exists($customPath)) {
                $success = $this->runCustomPath($output, $customPath);
            } else {
                $success = $this->runForProject($output, $module, $path);
            }
        }

        return $success ? static::CODE_SUCCESS : static::CODE_ERROR;
    }

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param string $module
     * @param string $subPath
     *
     * @return bool
     */
    protected function runForCore(OutputInterface $output, $module, $subPath)
    {
        $path = $this->getCorePath($module, $subPath);
        if (!is_dir($path)) {
            $output->writeln(sprintf('<error>Path not found: %s</error>', $path));

            return false;
        }

        $output->writeln($path, OutputInterface::VERBOSITY_VERBOSE);
        $violations = $this->getFacade()->runArchitectureSniffer($path, $this->input->getOptions());
        $count = $this->displayViolations($output, $violations);
        $output->writeln($count . ' violations found');

        return $count === 0;
    }

    /**
     * @param string $module
     * @param string $pathSuffix
     *
     * @return string
     */
    protected function getCorePath($module, $pathSuffix)
    {
        $namespace = null;
        if (strpos($module, '.') !== false) {
            list ($namespace, $module) = explode('.', $module, 2);
        }

        if ($namespace === static::NAMESPACE_SPRYKER && is_dir($this->getFactory()->getConfig()->getPathToCore() . $module)) {
            return $this->buildPath($this->getFactory()->getConfig()->getPathToCore() . $module . DIRECTORY_SEPARATOR, $pathSuffix);
        }

        if ($namespace === static::NAMESPACE_SPRYKER_SHOP && is_dir($this->getFactory()->getConfig()->getPathToShop() . $module)) {
            return $this->buildPath($this->getFactory()->getConfig()->getPathToShop() . $module . DIRECTORY_SEPARATOR, $pathSuffix);
        }

        $vendor = $this->dasherize($namespace);
        $module = $this->dasherize($module);
        $pathToModule = $this->getFactory()->getConfig()->getPathToRoot() . 'vendor' . DIRECTORY_SEPARATOR . $vendor . DIRECTORY_SEPARATOR;
        $path =  $pathToModule . $module . DIRECTORY_SEPARATOR;

        return $this->buildPath($path, $pathSuffix);
    }

    /**
     * @param string $path
     * @param string $suffix
     *
     * @return string
     */
    protected function buildPath($path, $suffix)
    {
        if (!$suffix) {
            return $path;
        }

        return $path . $suffix;
    }

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param string $module
     * @param string $subPath
     *
     * @return bool
     */
    protected function runForProject(OutputInterface $output, $module, $subPath)
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

        if (!isset($violations)) {
            $output->writeln('<error>No paths found for checking</error>');

            return false;
        }

        $output->writeln($result . ' violations found');

        return $result === 0;
    }

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param string $customPath
     *
     * @return bool
     */
    protected function runCustomPath(OutputInterface $output, $customPath)
    {
        $output->writeln($customPath, OutputInterface::VERBOSITY_VERBOSE);

        $violations = $this->getFacade()->runArchitectureSniffer($customPath, $this->input->getOptions());
        $count = $this->displayViolations($output, $violations);

        $output->writeln($count . ' violations found');

        return $count === 0;
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

    /**
     * @param string $name
     *
     * @return string
     */
    protected function dasherize($name)
    {
        $filterChain = new FilterChain();
        $filterChain
            ->attach(new CamelCaseToDash())
            ->attach(new StringToLower());

        return $filterChain->filter($name);
    }

}
