<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Communication\Console;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\DataImport\Business\DataImportFacadeInterface getFacade()
 */
class DataImportConsole extends Console
{
    public const DEFAULT_IMPORTER_TYPE = 'full';

    public const DEFAULT_NAME = 'data:import';
    public const DEFAULT_DESCRIPTION = 'This command executes your importers (full-import). Add this command with another name e.g. "new DataImportConsole(\'data:import:category\')" to your ConsoleDependencyProvider and you can run a single DataImporter which is mapped to the latter part of the command name.';

    public const IMPORTER_TYPE_DESCRIPTION = 'This command executes your "%s" importer.';

    public const OPTION_FILE_NAME = 'file-name';
    public const OPTION_FILE_NAME_SHORT = 'f';

    public const OPTION_OFFSET = 'offset';
    public const OPTION_OFFSET_SHORT = 'o';

    public const OPTION_LIMIT = 'limit';
    public const OPTION_LIMIT_SHORT = 'l';

    public const OPTION_CSV_DELIMITER = 'delimiter';
    public const OPTION_CSV_DELIMITER_SHORT = 'd';

    public const OPTION_CSV_ENCLOSURE = 'enclosure';
    public const OPTION_CSV_ENCLOSURE_SHORT = 'e';

    public const OPTION_CSV_ESCAPE = 'escape';
    public const OPTION_CSV_ESCAPE_SHORT = 's';

    public const OPTION_CSV_HAS_HEADER = 'has-header';
    public const OPTION_CSV_HAS_HEADER_SHORT = 'r';

    public const OPTION_THROW_EXCEPTION = 'throw-exception';
    public const OPTION_THROW_EXCEPTION_SHORT = 't';
    public const ARGUMENT_IMPORTER = 'importer';

    /**
     * @var \Symfony\Component\Console\Input\InputInterface
     */
    protected $input;

    /**
     * @return void
     */
    protected function configure()
    {
        $this->addArgument(static::ARGUMENT_IMPORTER, InputArgument::OPTIONAL, 'Defines which DataImport plugin should be executed. If not set, full import will be executed. Run data:import:dump to see all applied DataImporter.');

        $this->addOption(static::OPTION_THROW_EXCEPTION, static::OPTION_THROW_EXCEPTION_SHORT, InputOption::VALUE_OPTIONAL, 'Set this option to throw exceptions when they occur.');

        $this->addOption(static::OPTION_FILE_NAME, static::OPTION_FILE_NAME_SHORT, InputOption::VALUE_REQUIRED, 'Defines which file to use for data import.');
        $this->addOption(static::OPTION_OFFSET, static::OPTION_OFFSET_SHORT, InputOption::VALUE_REQUIRED, 'Defines from where a import should start.');
        $this->addOption(static::OPTION_LIMIT, static::OPTION_LIMIT_SHORT, InputOption::VALUE_REQUIRED, 'Defines where a import should end. If not set import runs until the end of data sets.');
        $this->addOption(static::OPTION_CSV_DELIMITER, static::OPTION_CSV_DELIMITER_SHORT, InputOption::VALUE_REQUIRED, 'Sets the csv delimiter.');
        $this->addOption(static::OPTION_CSV_ENCLOSURE, static::OPTION_CSV_ENCLOSURE_SHORT, InputOption::VALUE_REQUIRED, 'Sets the csv enclosure.');
        $this->addOption(static::OPTION_CSV_ESCAPE, static::OPTION_CSV_ESCAPE_SHORT, InputOption::VALUE_REQUIRED, 'Sets the csv escape.');
        $this->addOption(static::OPTION_CSV_HAS_HEADER, static::OPTION_CSV_HAS_HEADER_SHORT, InputOption::VALUE_REQUIRED, 'Set this option to 0 (zero) to disable that the first row of the csv file is a used as keys for the data sets.', true);

        if ($this->isAddedAsNamedDataImportCommand()) {
            $importerType = $this->getImporterType();

            $this->setName(static::DEFAULT_NAME . ':' . $importerType);
            $this->setDescription(sprintf(static::IMPORTER_TYPE_DESCRIPTION, $importerType));

            return;
        }

        $this->setName(static::DEFAULT_NAME)
            ->setDescription(static::DEFAULT_DESCRIPTION);
    }

    /**
     * @return bool
     */
    protected function isAddedAsNamedDataImportCommand()
    {
        return ($this->getName() !== null);
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dataImporterConfigurationTransfer = $this->buildDataImportConfiguration($input);

        $this->info(sprintf('<fg=white>Start "<fg=green>%s</>" import</>', $this->getImporterType($input)));
        $dataImportReportTransfer = $this->getFacade()->import($dataImporterConfigurationTransfer);

        if ($dataImportReportTransfer->getDataImporterReports()) {
            $this->printDataImporterReports($dataImportReportTransfer->getDataImporterReports());
        }

        $this->info('<fg=green>---------------------------------</>');
        $this->info('<fg=white;options=bold>Overall Import status: </>' . $this->getImportStatus($dataImportReportTransfer));

        if ($dataImportReportTransfer->getIsSuccess()) {
            return static::CODE_SUCCESS;
        }

        return static::CODE_ERROR;
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface|null $input
     *
     * @return string
     */
    protected function getImporterType(?InputInterface $input = null): string
    {
        if ($input && $input->getArgument(static::ARGUMENT_IMPORTER)) {
            return $input->getArgument(static::ARGUMENT_IMPORTER);
        }

        if ($this->getName() === static::DEFAULT_NAME) {
            return static::DEFAULT_IMPORTER_TYPE;
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
        if ($dataImportReportTransfer->getIsSuccess()) {
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
            . 'Importable DataSets: <fg=green>%s</>' . PHP_EOL
            . 'Imported DataSets: <fg=green>%s</>' . PHP_EOL
            . 'Import Time Used: <fg=green>%.2f ms</>' . PHP_EOL
            . 'Import status: %s</>';

        $this->info(sprintf(
            $messageTemplate,
            $dataImporterReport->getImportType(),
            $dataImporterReport->getExpectedImportableDataSetCount(),
            $dataImporterReport->getImportedDataSetCount(),
            $dataImporterReport->getImportTime(),
            $this->getImportStatus($dataImporterReport)
        ));
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     *
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    protected function buildDataImportConfiguration(InputInterface $input)
    {
        $dataImporterConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImporterConfigurationTransfer
            ->setImportType($this->getImporterType($input))
            ->setThrowException(false);

        if ($input->hasParameterOption('--' . static::OPTION_THROW_EXCEPTION) || $input->hasParameterOption('-' . static::OPTION_THROW_EXCEPTION_SHORT)) {
            $dataImporterConfigurationTransfer->setThrowException(true);
        }

        if ($input->getArgument(static::ARGUMENT_IMPORTER) !== null || $input->getOption(static::OPTION_FILE_NAME)) {
            $dataImporterReaderConfiguration = $this->buildReaderConfiguration($input);
            $dataImporterConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfiguration);
        }

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
            ->setFileName($input->getOption(static::OPTION_FILE_NAME))
            ->setOffset($input->getOption(static::OPTION_OFFSET))
            ->setLimit($input->getOption(static::OPTION_LIMIT))
            ->setCsvDelimiter($input->getOption(static::OPTION_CSV_DELIMITER))
            ->setCsvEnclosure($input->getOption(static::OPTION_CSV_ENCLOSURE))
            ->setCsvEscape($input->getOption(static::OPTION_CSV_ESCAPE))
            ->setCsvHasHeader($input->getOption(static::OPTION_CSV_HAS_HEADER));

        return $dataImporterReaderConfiguration;
    }
}
