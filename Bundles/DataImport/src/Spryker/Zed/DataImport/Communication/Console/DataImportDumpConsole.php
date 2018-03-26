<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Communication\Console;

use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\DataImport\Business\DataImportFacadeInterface getFacade()
 */
class DataImportDumpConsole extends Console
{
    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName('data:import:dump')
            ->setDescription('Dump all registered DataImportPlugins and DataImporter.');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dataImporter = $this->getFacade()->listImporters();
        if (!$dataImporter) {
            return static::CODE_ERROR;
        }

        $formattedDataImporterList = $this->formatForTable($dataImporter);
        $table = new Table($output);
        $table
            ->setHeaders(['Import Type', 'Class Name'])
            ->setRows($formattedDataImporterList);

        $table->render();

        return static::CODE_SUCCESS;
    }

    /**
     * @param array $dataImporter
     *
     * @return array[]
     */
    protected function formatForTable(array $dataImporter): array
    {
        $reordered = [];
        foreach ($dataImporter as $dataImportType => $dataImportClass) {
            $reordered[] = [$dataImportType, $dataImportClass];
        }

        return $reordered;
    }
}
