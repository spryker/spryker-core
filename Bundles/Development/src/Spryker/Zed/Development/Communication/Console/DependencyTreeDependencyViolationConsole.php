<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Communication\Console;

use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\Development\Business\DevelopmentFacade getFacade()
 */
class DependencyTreeDependencyViolationConsole extends Console
{

    const COMMAND_NAME = 'dev:dependency:find-violations';

    /**
     * @return void
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName(static::COMMAND_NAME)
            ->setHelp('<info>' . static::COMMAND_NAME . ' -h</info>')
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

        $dependencyViolations = $this->getFacade()->getDependencyViolations();

        $this->info(sprintf('Found %d dependencies', count($dependencyViolations)));

        foreach ($dependencyViolations as $dependencyViolation) {
            $this->info($dependencyViolation);
        }

        $this->printLineSeparator();

        $bundles = $this->getFacade()->getAllBundles();
        $this->info(sprintf('Checking all %d modules for dependency issues', count($bundles)));

        $count = 0;
        foreach ($bundles as $bundle) {
            $violations = [];
            $dependencies = $this->getFacade()->showOutgoingDependenciesForBundle($bundle);
            $composerDependencies = $this->getFacade()->getComposerDependencyComparison($dependencies);
            foreach ($composerDependencies as $composerDependency) {

                if (!$composerDependency['tests'] && !$composerDependency['src'] && ($composerDependency['composerRequire'] || $composerDependency['composerRequireDev'])) {
                    if ($composerDependency['composerRequire']) {
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

                if ($this->isMissingInSrc($composerDependency)) {
                    $violations[] = 'src: - / require: ' . $composerDependency['composerRequire'];
                }

                if ($this->isMissingInRequireDev($composerDependency)) {
                    $violations[] = 'tests: ' . $composerDependency['tests'] . ' / require-dev: -';
                }

                if ($this->isMissingInTests($composerDependency)) {
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

            $this->info($bundle . ':');
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
