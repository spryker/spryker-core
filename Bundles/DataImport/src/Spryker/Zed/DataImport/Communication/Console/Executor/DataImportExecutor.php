<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Communication\Console\Executor;

use ArrayObject;
use Generated\Shared\Transfer\DataImportConfigurationActionTransfer;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportMessageTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\DataImport\Business\DataImportFacadeInterface;
use Spryker\Zed\DataImport\Communication\Console\DataImportConsole;
use Spryker\Zed\DataImport\Communication\Console\Parser\DataImportConfigurationParserInterface;
use Symfony\Component\Console\Input\InputInterface;

class DataImportExecutor implements DataImportExecutorInterface
{
    /**
     * @var \Spryker\Zed\DataImport\Communication\Console\Parser\DataImportConfigurationParserInterface
     */
    protected $dataImportConfigurationParser;

    /**
     * @var \Spryker\Zed\DataImport\Business\DataImportFacadeInterface
     */
    protected $dataImportFacade;

    /**
     * @param \Spryker\Zed\DataImport\Communication\Console\Parser\DataImportConfigurationParserInterface $dataImportConfigurationParser
     * @param \Spryker\Zed\DataImport\Business\DataImportFacadeInterface $dataImportFacade
     */
    public function __construct(DataImportConfigurationParserInterface $dataImportConfigurationParser, DataImportFacadeInterface $dataImportFacade)
    {
        $this->dataImportConfigurationParser = $dataImportConfigurationParser;
        $this->dataImportFacade = $dataImportFacade;
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\DataImport\Communication\Console\Executor\DataImportExecutor::executeByConfigAndImporterType()} instead.
     *
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param string $importerType
     *
     * @return \Generated\Shared\Transfer\DataImporterReportTransfer
     */
    public function executeByImporterType(InputInterface $input, string $importerType): DataImporterReportTransfer
    {
        $dataImporterConfigurationTransfer = $this->buildDataImportConfiguration($input, $importerType);

        return $this->dataImportFacade->import($dataImporterConfigurationTransfer);
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param string $configPath
     * @param string|null $importerType
     *
     * @return \Generated\Shared\Transfer\DataImporterReportTransfer
     */
    public function executeByConfigAndImporterType(InputInterface $input, string $configPath, ?string $importerType): DataImporterReportTransfer
    {
        $dataImportConfigurationTransfer = $this->dataImportConfigurationParser->parseConfigurationFile($configPath);

        if ($this->isImporterTypeSpecified($importerType)) {
            $filteredDataImportConfigurationActionTransfers = $this->filterDataImportConfigurationActionTransfersByImporterType(
                $dataImportConfigurationTransfer->getActions(),
                $importerType
            );
            $dataImportConfigurationTransfer->setActions($filteredDataImportConfigurationActionTransfers);
        }

        $overallDataImporterReportTransfer = (new DataImporterReportTransfer())
            ->setIsSuccess(true)
            ->setImportedDataSetCount(0);

        if (!$dataImportConfigurationTransfer->getActions()->count()) {
            $messageTransfer = new DataImporterReportMessageTransfer();
            $messageTransfer->setMessage(sprintf('Requested import type "%s" was not found in %s.', $importerType, $configPath));

            return $overallDataImporterReportTransfer->addMessage($messageTransfer);
        }

        foreach ($dataImportConfigurationTransfer->getActions() as $dataImportConfigurationActionTransfer) {
            $dataImporterConfigurationTransfer = $this->buildDataImportConfiguration($input, $dataImportConfigurationActionTransfer->getDataEntity());
            $dataImporterConfigurationTransfer->getReaderConfiguration()->setFileName($dataImportConfigurationActionTransfer->getSource());

            $dataImporterReportTransfer = $this->dataImportFacade->importByAction($dataImportConfigurationActionTransfer, $dataImporterConfigurationTransfer);

            $overallDataImporterReportTransfer = $this->addDataImporterReportTransferToOverallReport($dataImporterReportTransfer, $overallDataImporterReportTransfer);
        }

        return $overallDataImporterReportTransfer;
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param string|null $importerType
     *
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    protected function buildDataImportConfiguration(InputInterface $input, ?string $importerType): DataImporterConfigurationTransfer
    {
        $dataImporterConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImporterConfigurationTransfer
            ->setImportType($importerType)
            ->setImportGroup($input->getOption(DataImportConsole::OPTION_IMPORT_GROUP))
            ->setThrowException(false);

        if ($input->hasParameterOption('--' . DataImportConsole::OPTION_THROW_EXCEPTION) || $input->hasParameterOption('-' . DataImportConsole::OPTION_THROW_EXCEPTION_SHORT)) {
            $dataImporterConfigurationTransfer->setThrowException(true);
        }

        $dataImporterConfigurationTransfer->setReaderConfiguration($this->buildReaderConfiguration($input));

        return $dataImporterConfigurationTransfer;
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     *
     * @return \Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer
     */
    protected function buildReaderConfiguration(InputInterface $input)
    {
        $dataImporterReaderConfiguration = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfiguration
            ->setFileName($input->getOption(DataImportConsole::OPTION_FILE_NAME))
            ->setOffset($input->getOption(DataImportConsole::OPTION_OFFSET))
            ->setLimit($input->getOption(DataImportConsole::OPTION_LIMIT))
            ->setCsvDelimiter($input->getOption(DataImportConsole::OPTION_CSV_DELIMITER))
            ->setCsvEnclosure($input->getOption(DataImportConsole::OPTION_CSV_ENCLOSURE))
            ->setCsvEscape($input->getOption(DataImportConsole::OPTION_CSV_ESCAPE))
            ->setCsvHasHeader($input->getOption(DataImportConsole::OPTION_CSV_HAS_HEADER));

        return $dataImporterReaderConfiguration;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\DataImportConfigurationActionTransfer[] $dataImportConfigurationActionTransfers
     * @param string $importerType
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\DataImportConfigurationActionTransfer[]
     */
    protected function filterDataImportConfigurationActionTransfersByImporterType(
        ArrayObject $dataImportConfigurationActionTransfers,
        string $importerType
    ): ArrayObject {
        $filteredDataImportConfigurationActionTransfers = array_filter(
            $dataImportConfigurationActionTransfers->getArrayCopy(),
            function (DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer) use ($importerType): bool {
                return $dataImportConfigurationActionTransfer->getDataEntity() === $importerType;
            }
        );

        return new ArrayObject($filteredDataImportConfigurationActionTransfers);
    }

    /**
     * @param string|null $importerType
     *
     * @return bool
     */
    protected function isImporterTypeSpecified(?string $importerType): bool
    {
        return $importerType && $importerType !== DataImportConsole::DEFAULT_IMPORTER_TYPE;
    }

    /**
     * @param \Generated\Shared\Transfer\DataImporterReportTransfer $dataImporterReportTransfer
     * @param \Generated\Shared\Transfer\DataImporterReportTransfer $overallDataImporterReportTransfer
     *
     * @return \Generated\Shared\Transfer\DataImporterReportTransfer
     */
    protected function addDataImporterReportTransferToOverallReport(
        DataImporterReportTransfer $dataImporterReportTransfer,
        DataImporterReportTransfer $overallDataImporterReportTransfer
    ): DataImporterReportTransfer {
        foreach ($dataImporterReportTransfer->getDataImporterReports() as $innerDataImporterReportTransfer) {
            $overallDataImporterReportTransfer->addDataImporterReport($innerDataImporterReportTransfer);
        }
        $overallDataImporterReportTransfer->setImportedDataSetCount(
            $dataImporterReportTransfer->getImportedDataSetCount() + $overallDataImporterReportTransfer->getImportedDataSetCount()
        );

        if (!$dataImporterReportTransfer->getIsSuccess()) {
            $overallDataImporterReportTransfer->setIsSuccess(false);
        }

        return $overallDataImporterReportTransfer;
    }
}
