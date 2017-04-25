<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FactFinder\Communication\Console;

use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\FactFinder\Communication\FactFinderCommunicationFactory getFactory()
 * @method \Spryker\Zed\FactFinder\Business\FactFinderFacade getFacade()
 */
class FactFinderExportConsole extends Console
{

    const COMMAND_NAME = 'fact-finder:export:products';
    const COMMAND_DESCRIPTION = 'Export product data for Fact Finder';

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName(self::COMMAND_NAME);
        $this->setDescription(self::COMMAND_DESCRIPTION);

        parent::configure();
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $locales = $this->getFacade()
            ->getLocaleQuery()
            ->find();

        foreach ($locales as $key => $locale) {
            $this->getFacade()
                ->createFactFinderCsv($locale);
        }
    }

}
