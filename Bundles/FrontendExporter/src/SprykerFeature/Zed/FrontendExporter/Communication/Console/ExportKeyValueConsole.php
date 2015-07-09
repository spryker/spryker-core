<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\FrontendExporter\Communication\Console;

use SprykerFeature\Zed\FrontendExporter\Business\FrontendExporterFacade;
use SprykerFeature\Zed\FrontendExporter\Communication\FrontendExporterDependencyContainer;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method FrontendExporterDependencyContainer getDependencyContainer()
 * @method FrontendExporterFacade getFacade()
 */
class ExportKeyValueConsole extends AbstractExporterConsole
{

    const COMMAND_NAME = 'frontend-exporter:export-key-value';
    const COMMAND_DESCRIPTION = 'Export key value';

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
        $exportResults = $this->getFacade()->exportKeyValueForLocale($locale);

        $this->info($this->buildSummary($exportResults));
    }

}
