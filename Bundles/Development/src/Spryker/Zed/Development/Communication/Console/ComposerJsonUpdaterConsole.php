<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Communication\Console;

use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\Development\Business\DevelopmentFacade getFacade()
 */
class ComposerJsonUpdaterConsole extends Console
{

    const COMMAND_NAME = 'dev:dependency:update-composer-files';
    const OPTION_BUNDLE = 'module';
    const VERBOSE = 'verbose';

    /**
     * @return void
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName(static::COMMAND_NAME)
            ->setHelp('<info>' . static::COMMAND_NAME . ' -h</info>')
            ->setDescription('Update composer.json of core modules (Spryker core dev only).');

        $this->addOption(static::OPTION_BUNDLE, 'm', InputOption::VALUE_OPTIONAL, 'Name of core module (comma separated for multiple ones)');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @throws \Exception
     *
     * @return void
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $bundles = [];
        $bundleList = $this->input->getOption(static::OPTION_BUNDLE);
        if ($bundleList) {
            $bundles = explode(',', $this->input->getOption(static::OPTION_BUNDLE));
        }

        $processedBundles = $this->getFacade()->updateComposerJsonInBundles($bundles);
        if ($this->input->getOption(static::VERBOSE)) {
            $this->output->writeln(count($processedBundles) . ' modules updated:');
            foreach ($processedBundles as $processedBundle) {
                $this->output->writeln('- '. $processedBundle);
            }
        }
    }

}
