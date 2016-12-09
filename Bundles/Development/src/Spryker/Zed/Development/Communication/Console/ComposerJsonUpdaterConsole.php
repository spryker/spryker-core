<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Communication\Console;

use Spryker\Zed\Console\Business\Model\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\Development\Business\DevelopmentFacade getFacade()
 */
class ComposerJsonUpdaterConsole extends Console
{

    const COMMAND_NAME = 'dev:composer-json:update';
    const OPTION_BUNDLE = 'bundle';
    const VERBOSE = 'verbose';

    /**
     * @return void
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName(self::COMMAND_NAME)
            ->setHelp('<info>' . self::COMMAND_NAME . ' -h</info>')
            ->setDescription('Update composer.json of core bundles');

        $this->addOption(self::OPTION_BUNDLE, 'b', InputOption::VALUE_OPTIONAL, 'Name of core bundle (comma separated for multiple ones)');
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
        $bundleList = $this->input->getOption(self::OPTION_BUNDLE);
        if ($bundleList) {
            $bundles = explode(',', $this->input->getOption(self::OPTION_BUNDLE));
        }

        $processedBundles = $this->getFacade()->updateComposerJsonInBundles($bundles);
        if ($this->input->getOption(self::VERBOSE)) {
            $this->output->writeln(count($processedBundles) . ' bundles updated:');
            foreach ($processedBundles as $processedBundle) {
                $this->output->writeln('- '. $processedBundle);
            }
        }
    }

}
