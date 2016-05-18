<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Communication\Console;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\Collector\Communication\CollectorCommunicationFactory getFactory()
 * @method \Spryker\Zed\Collector\Business\CollectorFacade getFacade()
 */
class CollectorCleanUpTouchTablesConsole extends AbstractCollectorConsole
{

    const COMMAND_NAME = 'collector:cleanup:touch';
    const COMMAND_DESCRIPTION = 'Cleans up the Touch tables by removing outdated / unneeded data';

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
        $locale = $this->getFactory()->getLocaleFacade()->getCurrentLocale();
        $cleanUpResults = $this->getFacade()->cleanupTouchTablesByLocale($locale);

        $output->writeln('');
        $output->writeln('---------------------------------------');
        if ($cleanUpResults) {
            $output->writeln('<fg=yellow>Cleaned up the touch tables.</fg=yellow>');
        } else {
            $output->writeln('<fg=red>FAILED to Clean up the touch tables!! :-(</fg=yellow>');
        }
        $output->writeln('---------------------------------------');
        $output->writeln('');
    }

}
