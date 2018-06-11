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
use Zend\Filter\Word\DashToCamelCase;

/**
 * @method \Spryker\Zed\Development\Business\DevelopmentFacadeInterface getFacade()
 * @method \Spryker\Zed\Development\Business\DevelopmentBusinessFactory getFactory()
 */
class DependencyTreeDependencyViolationConsole extends Console
{
    const COMMAND_NAME = 'dev:dependency:find-violations';
    const ARGUMENT_MODULE = 'module';
    const OPTION_FIX = 'fix';

    const REPLACE_4_WITH_2_SPACES = '/^(  +?)\\1(?=[^ ])/m';

    /**
     * @return void
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName(static::COMMAND_NAME)
            ->setHelp('<info>' . static::COMMAND_NAME . ' -h</info>')
            ->addArgument(static::ARGUMENT_MODULE, InputArgument::OPTIONAL, 'Module to run checks for.')
            ->addOption(static::OPTION_FIX, 'f', InputOption::VALUE_NONE, 'Fix all findings (only adding for now).')
            ->setDescription('Find dependency violations in the dependency tree (Spryker core dev only).');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int|null
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->info('Find dependency violations');

        $modules = $this->getFacade()->getAllModules();
        $module = $input->getArgument(static::ARGUMENT_MODULE);
        if ($module) {
            $filter = new DashToCamelCase();
            $filteredModuleName = ucfirst($filter->filter($module));
            if (!in_array($filteredModuleName, $modules)) {
                $output->writeln(sprintf('Requested module <fg=green>%s</> not found in current scope.', $filteredModuleName));

                return static::CODE_ERROR;
            }
            $modules = [$filteredModuleName];
        }

        $message = sprintf('Checking %d %s for dependency issues.', count($modules), (count($modules) === 1) ? 'Module (' . $modules[0] . ')' : 'Modules');
        $this->info($message);

        $count = 0;
        foreach ($modules as $module) {
            $violations = [];
            $dependencies = $this->getFacade()->showOutgoingDependenciesForModule($module);
            $composerDependencies = $this->getFacade()->getComposerDependencyComparison($dependencies);
            foreach ($composerDependencies as $composerDependency) {
                if (!$composerDependency['tests'] && !$composerDependency['src'] && ($composerDependency['composerRequire'] || $composerDependency['composerRequireDev'])) {
                    if ($composerDependency['composerRequire'] && !$composerDependency['isOwnExtensionModule']) {
                        $violations[] = 'src: - / require: ' . $composerDependency['composerRequire'];
                    }
                }

                if ($this->isDevelopmentOnlyDependency($composerDependency)) {
                    continue;
                }

                if ($this->isMissingInRequire($composerDependency)) {
                    $violations[] = 'src: ' . $composerDependency['src'] . ' / require: -';
                }

                if ($composerDependency['isOptional'] && $composerDependency['composerRequire']) {
                    $violations[] = $composerDependency['src'] . ' is optional but in require';
                }

                $name = null;
                if ($composerDependency['isOptional'] && !$composerDependency['composerRequireDev']) {
                    $name = $composerDependency['src'] ?: $composerDependency['tests'];
                    $violations[] = $name . ' is optional but for testing it must be declared in require-dev';
                    if ($input->getOption(static::OPTION_FIX)) {
                        $this->fix($module, $name, $composerDependency);
                    }
                }

                if ($this->isMissingInSrc($composerDependency)) {
                    $violations[] = 'src: - / require: ' . $composerDependency['composerRequire'];
                }

                if ($this->isMissingInRequireDev($composerDependency)) {
                    $violations[] = 'tests: ' . $composerDependency['tests'] . ' / require-dev: -';
                    if ($input->getOption(static::OPTION_FIX)) {
                        $this->fix($module, $name, $composerDependency);
                    }
                }

                if ($this->isMissingInTests($composerDependency) && !$composerDependency['isOptional']) {
                    $violations[] = 'tests: - / require-dev: ' . $composerDependency['composerRequireDev'];
                }

                if ($composerDependency['composerRequire'] && $composerDependency['composerRequireDev']) {
                    $violations[] = 'defined in require and require-dev: ' . $composerDependency['composerRequireDev'];
                }

                if ($composerDependency['src'] && $composerDependency['isOptional'] && !$composerDependency['suggested']) {
                    $violations[] = $composerDependency['src'] . ' is optional but missing in composer suggest';
                }
            }

            if (!$violations) {
                continue;
            }

            $this->info($module . ':');
            foreach ($violations as $violation) {
                $this->warning(' - ' . $violation);
            }

            $count += count($violations);
        }

        $this->info(sprintf('%d module dependency issues found', $count));

        return $count > 0 ? static::CODE_ERROR : static::CODE_SUCCESS;
    }

    /**
     * @param array $composerDependency
     *
     * @return bool
     */
    protected function isDevelopmentOnlyDependency(array $composerDependency)
    {
        return (!$composerDependency['src'] && !$composerDependency['tests']);
    }

    /**
     * @param array $composerDependency
     *
     * @return bool
     */
    protected function isMissingInRequire($composerDependency)
    {
        return ($composerDependency['src'] && !$composerDependency['composerRequire'] && !$composerDependency['isOptional']);
    }

    /**
     * @param array $composerDependency
     *
     * @return bool
     */
    protected function isMissingInSrc($composerDependency)
    {
        return (!$composerDependency['src'] && $composerDependency['composerRequire']);
    }

    /**
     * @param array $composerDependency
     *
     * @return bool
     */
    protected function isMissingInRequireDev($composerDependency)
    {
        return ($composerDependency['tests'] && !$composerDependency['composerRequire'] && !$composerDependency['composerRequireDev']);
    }

    /**
     * @param array $composerDependency
     *
     * @return bool
     */
    protected function isMissingInTests($composerDependency)
    {
        return (!$composerDependency['tests'] && $composerDependency['composerRequireDev']);
    }

    /**
     * @param string $module
     * @param string $target
     * @param array $composerDependency
     *
     * @return void
     */
    protected function fix(string $module, string $target, array $composerDependency): void
    {
        $corePath = $this->getFactory()->getConfig()->getPathToCore();
        $modulePath = $corePath . $module . DIRECTORY_SEPARATOR;
        $composerJsonFile = $modulePath . 'composer.json';

        $composerJsonContent = file_get_contents($composerJsonFile);
        $composerJsonArray = json_decode($composerJsonContent, true);

        $targetModuleDashed = $this->dasherize($target);
        $composerJsonArray['require-dev']['spryker/' . $targetModuleDashed] = '*';

        $modifiedComposerJson = json_encode($composerJsonArray, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
        $modifiedComposerJson = preg_replace(static::REPLACE_4_WITH_2_SPACES, '$1', $modifiedComposerJson) . PHP_EOL;

        file_put_contents($composerJsonFile, $modifiedComposerJson);
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
