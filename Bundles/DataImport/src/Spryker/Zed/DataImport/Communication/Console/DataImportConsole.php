<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Communication\Console;

use Exception;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\DataImport\DataImportConfig;
use Spryker\Zed\Kernel\BundleConfigResolverAwareTrait;
use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\DataImport\Business\DataImportFacadeInterface getFacade()
 * @method \Spryker\Zed\DataImport\Communication\DataImportCommunicationFactory getFactory()
 * @method \Spryker\Zed\DataImport\DataImportConfig getConfig()
 */
class DataImportConsole extends Console
{
    use BundleConfigResolverAwareTrait;

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

    public const OPTION_IMPORT_GROUP = 'group';
    public const OPTION_IMPORT_GROUP_SHORT = 'g';

    public const OPTION_CONFIG = 'config';
    public const OPTION_CONFIG_SHORT = 'c';

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
        $this->addOption(static::OPTION_IMPORT_GROUP, static::OPTION_IMPORT_GROUP_SHORT, InputOption::VALUE_REQUIRED, 'Defines the import group. Import group determines a specific subset of data importers to be used.', DataImportConfig::IMPORT_GROUP_FULL);
        $this->addOption(static::OPTION_CONFIG, static::OPTION_CONFIG_SHORT, InputOption::VALUE_REQUIRED, 'Defines the relative path of the data import configuration .yml file.');

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
        try {
            return $this->getName() !== null;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (!$this->checkImportTypeAndGroupConfiguration($input)) {
            $this->error(
                sprintf('No import group (except "%s") can be used when an import type is specified', DataImportConfig::IMPORT_GROUP_FULL)
            );

            return static::CODE_ERROR;
        }

        $importerType = $this->getImporterType($input);
        $configPath = $this->getYamlConfigPath($input);
        if ($configPath !== null) {
            return $this->executeByConfigAndImporterType($input, $configPath, $importerType);
        }

        return $this->executeByImportType($input, $importerType);
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param string|null $configPath
     * @param string $importerType
     *
     * @return int
     */
    protected function executeByConfigAndImporterType(InputInterface $input, ?string $configPath, string $importerType): int
    {
        $this->info(sprintf('<fg=white>Starting import with %s configuration file.</>', $configPath));
        $dataImporterReportTransfer = $this->getFactory()
            ->createDataImportExecutor()
            ->executeByConfigAndImporterType($input, $configPath, $importerType);

        $this->printOverallDataImporterReport($dataImporterReportTransfer);

        return $this->getExitCodeByDataImporterReportTransfer($dataImporterReportTransfer);
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param string $importerType
     *
     * @return int
     */
    protected function executeByImportType(InputInterface $input, string $importerType): int
    {
        $this->warning(sprintf(
            'Using a data import without config specified is deprecated. ' .
            'Please, define default configuration yaml file in %s or pass it with --config option',
            DataImportConfig::class
        ));

        $this->info(sprintf('<fg=white>Start "<fg=green>%s</>" import</>', $importerType));
        $dataImporterReportTransfer = $this->getFactory()
            ->createDataImportExecutor()
            ->executeByImporterType($input, $importerType);

        $this->printOverallDataImporterReport($dataImporterReportTransfer);

        return $this->getExitCodeByDataImporterReportTransfer($dataImporterReportTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\DataImporterReportTransfer $dataImporterReportTransfer
     *
     * @return void
     */
    protected function printOverallDataImporterReport(DataImporterReportTransfer $dataImporterReportTransfer): void
    {
        $dataImporterReportMessageTransfers = $dataImporterReportTransfer->getMessages();
        if ($dataImporterReportMessageTransfers->count()) {
            $this->info('<fg=green>---------------------------------</>');
            foreach ($dataImporterReportMessageTransfers as $dataImporterReportMessageTransfer) {
                $this->info($dataImporterReportMessageTransfer->getMessage());
            }
        }

        $dataImporterReports = $dataImporterReportTransfer->getDataImporterReports();
        if ($dataImporterReports->count()) {
            $this->printDataImporterReports($dataImporterReports);
        }

        $this->info('<fg=green>---------------------------------</>');
        $this->info('<fg=white;options=bold>Overall Import status: </>' . $this->getImportStatusByDataImportReportStatus($dataImporterReportTransfer));
    }

    /**
     * @param \Generated\Shared\Transfer\DataImporterReportTransfer $dataImporterReportTransfer
     *
     * @return int
     */
    protected function getExitCodeByDataImporterReportTransfer(DataImporterReportTransfer $dataImporterReportTransfer): int
    {
        return $dataImporterReportTransfer->getIsSuccess() ? static::CODE_SUCCESS : static::CODE_ERROR;
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
    protected function getImportStatusByDataImportReportStatus(DataImporterReportTransfer $dataImportReportTransfer): string
    {
        if ($dataImportReportTransfer->getIsSuccess()) {
            return '<fg=green>Successful</>';
        }

        return '<fg=red>Failed</>';
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\DataImporterReportTransfer[] $dataImporterReports
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
        $source = '';
        if ($dataImporterReport->getSource()) {
            $source = sprintf('Import source: <fg=green>%s</>' . PHP_EOL, $dataImporterReport->getSource());
        }

        $messageTemplate = PHP_EOL . '<fg=white>'
            . 'Importer type: <fg=green>%s</>' . PHP_EOL
            . '%s'
            . 'Importable DataSets: <fg=green>%s</>' . PHP_EOL
            . 'Imported DataSets: <fg=green>%s</>' . PHP_EOL
            . 'Import Time Used: <fg=green>%.2f s</>' . PHP_EOL
            . 'Import status: %s</>';

        $this->info(sprintf(
            $messageTemplate,
            $dataImporterReport->getImportType(),
            $source,
            $dataImporterReport->getExpectedImportableDataSetCount(),
            $dataImporterReport->getImportedDataSetCount(),
            $dataImporterReport->getImportTime(),
            $this->getImportStatusByDataImportReportStatus($dataImporterReport)
        ));
    }

    /**
     * Checks that import type and import group are not used at the same time.
     *
     * @param \Symfony\Component\Console\Input\InputInterface $input
     *
     * @return bool
     */
    protected function checkImportTypeAndGroupConfiguration(InputInterface $input): bool
    {
        $importType = $input->getArgument(static::ARGUMENT_IMPORTER);
        $importGroup = $input->getOption(static::OPTION_IMPORT_GROUP);

        return ($importType === null || $importType === static::DEFAULT_IMPORTER_TYPE) || $importGroup === DataImportConfig::IMPORT_GROUP_FULL;
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     *
     * @return string|null
     */
    protected function getYamlConfigPath(InputInterface $input): ?string
    {
        if ($input->hasParameterOption('--' . static::OPTION_CONFIG) || $input->hasParameterOption('-' . static::OPTION_CONFIG_SHORT)) {
            return $input->getOption(static::OPTION_CONFIG);
        }

        return $this->getConfig()->getDefaultYamlConfigPath();
    }
}
