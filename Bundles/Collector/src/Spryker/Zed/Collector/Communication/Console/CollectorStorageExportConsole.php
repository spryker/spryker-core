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
 * @method \Spryker\Zed\Collector\Business\CollectorFacadeInterface getFacade()
 */
class CollectorStorageExportConsole extends AbstractCollectorConsole
{
    public const COMMAND_NAME = 'collector:storage:export';
    public const COMMAND_DESCRIPTION = 'Collector export storage';

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
        $enabledCollectors = $this->getFacade()->getEnabledCollectorTypes();
        $allCollectors = $this->getFacade()->getAllCollectorTypes();

        $collectorInfo = sprintf(
            '<fg=yellow>%d out of %d collectors available.</fg=yellow>',
            count($enabledCollectors),
            count($allCollectors)
        );

        $output->write(PHP_EOL);
        $output->writeln($collectorInfo);

        $exportResults = $this->getFacade()->exportStorage($output);
        $message = $this->buildNestedSummary($exportResults);
        $message = '<info>' . $message . '</info>';

        $output->write($message);
    }
}
