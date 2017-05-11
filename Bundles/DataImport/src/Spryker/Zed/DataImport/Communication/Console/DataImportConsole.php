<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Communication\Console;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\DataImport\Business\DataImportFacade getFacade()
 */
class DataImportConsole extends Console
{

    const DEFAULT_EXPORTER_TYPE = 'full';

    const DEFAULT_NAME = 'data:import';
    const DEFAULT_DESCRIPTION = 'This command executes your importers (full-import). You can add this command with a different names more than once to you ConsoleDependencyProvider. '
        . 'Add this command with another name e.g. data:import:category and you can run a single DataImporter which can be mapped to the latter part of the command name.';

    const IMPORTER_TYPE_DESCRIPTION = 'This command executes your "%s" importer.';

    /**
     * @return void
     */
    protected function configure()
    {
        if ($this->getName()) {
            $importerType = $this->getImporterType();

            $this->setDescription(sprintf(static::IMPORTER_TYPE_DESCRIPTION, $importerType));

            return;
        }
        $this->setName(static::DEFAULT_NAME)
            ->setDescription(static::DEFAULT_DESCRIPTION);
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dataImporterConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImporterConfigurationTransfer->setImportType($this->getImporterType());

        $this->info(sprintf('<fg=white>Start "<fg=green>%s</>" import</>', $this->getImporterType()));
        $dataImportReportTransfer = $this->getFacade()->import($dataImporterConfigurationTransfer);

        $this->info('<fg=white>Import status: </>' . $this->getImportStatus($dataImportReportTransfer));

        if ($dataImportReportTransfer->getDataImporterReports()) {
            $this->printDataImporterReports($dataImportReportTransfer->getDataImporterReports());
        }

        if ($dataImportReportTransfer->getSuccess()) {
            return static::CODE_SUCCESS;
        }

        return static::CODE_ERROR;
    }

    /**
     * @return mixed
     */
    protected function getImporterType()
    {
        if ($this->getName() === static::DEFAULT_NAME) {
            return static::DEFAULT_EXPORTER_TYPE;
        }
        $commandNameParts = explode(':', $this->getName());
        $importerType = array_pop($commandNameParts);

        return $importerType;
    }

    /**
     * @param \Generated\Shared\Transfer\DataImporterReportTransfer $dataImportReportTransfer
     *
     * @return string
     */
    protected function getImportStatus(DataImporterReportTransfer $dataImportReportTransfer)
    {
        if ($dataImportReportTransfer->getSuccess()) {
            return '<fg=green>Successful</>';
        }

        return '<fg=red>Failed</>';
    }

    /**
     * @param \Generated\Shared\Transfer\DataImporterReportTransfer[] $dataImporterReports
     *
     * @return void
     */
    private function printDataImporterReports($dataImporterReports)
    {
        foreach ($dataImporterReports as $dataImporterReport) {
            $this->printDataImporterReport($dataImporterReport);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\DataImporterReportTransfer $dataImporterReport
     *
     * @return void
     */
    private function printDataImporterReport(DataImporterReportTransfer $dataImporterReport)
    {
        $messageTemplate = PHP_EOL . '<fg=white>'
            . 'Importer type: <fg=green>%s</>' . PHP_EOL
            . 'Imported DataSets: <fg=green>%s</>' . PHP_EOL
            . 'Imported status: %s</>';

        $this->info(sprintf(
            $messageTemplate,
            $dataImporterReport->getImportType(),
            $dataImporterReport->getImportedDataSets(),
            $this->getImportStatus($dataImporterReport)
        ));
    }

}
