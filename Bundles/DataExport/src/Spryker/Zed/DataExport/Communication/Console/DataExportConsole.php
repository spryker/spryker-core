<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataExport\Communication\Console;

use Spryker\Zed\Kernel\BundleConfigResolverAwareTrait;
use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\DataExport\Business\DataExportFacadeInterface getFacade()
 * @method \Spryker\Zed\DataExport\Communication\DataExportCommunicationFactory getFactory()
 * @method \Spryker\Zed\DataExport\DataExportConfig getConfig()
 */
class DataExportConsole extends Console
{
    use BundleConfigResolverAwareTrait;

    protected const COMMAND_NAME = 'data:export';

    protected const OPTION_CONFIG = 'config';
    protected const OPTION_SHORTCUT_CONFIG = 'c';

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName(static::COMMAND_NAME);
        $this->addOption(static::OPTION_CONFIG, static::OPTION_SHORTCUT_CONFIG, InputOption::VALUE_OPTIONAL, 'Define configuration file');

        parent::configure();
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (!$this->isConfigOptionProvided($input)) {
            $this->error(sprintf('Option "--%s" with configuration file name should be provided for data exporting.', static::OPTION_CONFIG));

            return static::CODE_ERROR;
        }

        $exportConfigurationsPath = $this->getConfig()
                ->getExportConfigurationsPath() . DIRECTORY_SEPARATOR . $input->getOption(static::OPTION_CONFIG);
        $exportConfigurations = $this->getFactory()
            ->getDataExportService()
            ->parseConfiguration($exportConfigurationsPath);

        $dataExportReportTransfers = $this->getFacade()->exportDataEntities($exportConfigurations);

        return $this->printDataExportReport($output, $dataExportReportTransfers);
    }

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param \Generated\Shared\Transfer\DataExportReportTransfer[] $dataExportReportTransfers
     *
     * @return int
     */
    protected function printDataExportReport(OutputInterface $output, array $dataExportReportTransfers): int
    {
        $isSuccessful = true;
        foreach ($dataExportReportTransfers as $dataExportReportTransfer) {
            if (!$dataExportReportTransfer->getIsSuccessful()) {
                $isSuccessful = false;
            }
            foreach ($dataExportReportTransfer->getDataExportResults() as $dataExportResultTransfer) {
                $output->writeln(sprintf(
                    '<fg=white>File name: %s, DataEntity: %s, Count: %d</fg=white>',
                    $dataExportResultTransfer->getFileName(),
                    $dataExportResultTransfer->getDataEntity(),
                    $dataExportResultTransfer->getExportCount()
                ));
            }
        }

        return $isSuccessful ? static::CODE_SUCCESS : static::CODE_ERROR;
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     *
     * @return bool
     */
    protected function isConfigOptionProvided(InputInterface $input): bool
    {
        return $input->hasParameterOption([
                '--' . static::OPTION_CONFIG,
                '-' . static::OPTION_SHORTCUT_CONFIG,
            ]) && $input->getOption(static::OPTION_CONFIG) !== null;
    }
}
