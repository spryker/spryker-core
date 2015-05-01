<?php

namespace SprykerFeature\Zed\FrontendExporter\Communication\Console;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

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
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $locale = $this->locator->locale()->facade()->getCurrentLocale();
        $exportResults = $this->locator->frontendExporter()->facade()->exportKeyValueForLocale($locale);

        $this->info($this->buildSummary($exportResults));
    }
}
