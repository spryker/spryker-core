<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Communication\Console;

use Spryker\Zed\Console\Business\Model\Console;
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
        $this->info(sprintf('Checking all %d bundles for dependency issues', count($bundles)));

        $count = 0;
        foreach ($bundles as $bundle) {
            $violations = [];
            $dependencies = $this->getFacade()->showOutgoingDependenciesForBundle($bundle);

            $composerDependencies = $this->getFacade()->getComposerDependencyComparison($dependencies);

            foreach ($composerDependencies as $composerDependency) {
                if ($composerDependency['code'] && ($composerDependency['composerRequire'] || $composerDependency['composerRequireDev'])) {
                    continue;
                }
                if (!$composerDependency['code'] && $composerDependency['composerRequireDev']) {
                    continue;
                }

                if (!$composerDependency['code'] && !$composerDependency['composerRequireDev']) {
                    $composerDependency['code'] = '-';
                }
                if (!$composerDependency['composerRequire']) {
                    $composerDependency['composerRequire'] = '-';
                }

                $violations[] = 'code: ' . $composerDependency['code'] . ' / composer: ' . $composerDependency['composerRequire'];
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

        $this->info(sprintf('%d bundle dependency issues found', $count));
        return $count > 0 ? static::CODE_ERROR : static::CODE_SUCCESS;
    }

}
