<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Communication\Console;

use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Zend\Filter\Word\DashToCamelCase;

/**
 * @method \Spryker\Zed\Development\Business\DevelopmentFacadeInterface getFacade()
 */
class DependencyTreeDependencyViolationConsole extends Console
{
    const COMMAND_NAME = 'dev:dependency:find-violations';
    const ARGUMENT_MODULE = 'module';

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

                if ($composerDependency['isOptional'] && !$composerDependency['composerRequireDev']) {
                    $violations[] = $composerDependency['src'] . ' is optional but for testing it must be declared in require-dev';
                }

                if ($this->isMissingInSrc($composerDependency)) {
                    $violations[] = 'src: - / require: ' . $composerDependency['composerRequire'];
                }

                if ($this->isMissingInRequireDev($composerDependency)) {
                    $violations[] = 'tests: ' . $composerDependency['tests'] . ' / require-dev: -';
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
}
