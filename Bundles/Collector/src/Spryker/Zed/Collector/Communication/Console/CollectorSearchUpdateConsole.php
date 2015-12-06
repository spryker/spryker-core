<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Collector\Communication\Console;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\Collector\Communication\CollectorCommunicationFactory getFactory()
 * @method \Spryker\Zed\Collector\Business\CollectorFacade getFacade()
 */
class CollectorSearchUpdateConsole extends AbstractCollectorConsole
{

    const COMMAND_NAME = 'collector:search:update';
    const COMMAND_DESCRIPTION = 'Collect update search';

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
        $exportResults = $this->getFacade()->updateSearchForLocale($locale, $output);

        $this->info($this->buildSummary($exportResults));
    }

}
