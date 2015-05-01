<?php

namespace SprykerFeature\Zed\FrontendExporter\Communication\Console;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

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
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $locale = \SprykerEngine\Shared\Kernel\Store::getInstance()->getCurrentLocale();
        $exportResults = $this->locator->frontendExporter()->facade()->updateSearchForLocale($locale);

        $this->info($this->buildSummary($exportResults));
    }
}
