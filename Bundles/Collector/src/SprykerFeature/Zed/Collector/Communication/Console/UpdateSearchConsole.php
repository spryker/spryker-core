<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Collector\Communication\Console;

use SprykerFeature\Zed\Collector\Business\CollectorFacade;
use SprykerFeature\Zed\Collector\Communication\CollectorDependencyContainer;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method CollectorDependencyContainer getDependencyContainer()
 * @method CollectorFacade getFacade()
 */
class UpdateSearchConsole extends AbstractExporterConsole
{

    const COMMAND_NAME = 'frontend-exporter:update-search';
    const COMMAND_DESCRIPTION = 'Update search';

    protected function configure()
    {
        $this->setName(self::COMMAND_NAME);
        $this->setDescription(self::COMMAND_DESCRIPTION);

        parent::configure();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $locale = $this->getDependencyContainer()->createLocaleFacade()->getCurrentLocale();
        $exportResults = $this->getFacade()->updateSearchForLocale($locale);

        $this->info($this->buildSummary($exportResults));
    }

}
