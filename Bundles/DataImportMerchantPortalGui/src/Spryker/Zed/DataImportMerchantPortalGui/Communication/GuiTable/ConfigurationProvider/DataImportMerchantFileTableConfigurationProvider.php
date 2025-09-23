<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\DataImportMerchantPortalGui\Communication\GuiTable\ConfigurationProvider;

use Generated\Shared\Transfer\GuiTableConfigurationTransfer;
use Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface;
use Spryker\Shared\GuiTable\GuiTableFactoryInterface;
use Spryker\Zed\DataImportMerchantPortalGui\Communication\Reader\DataImportMerchantFileReaderInterface;

class DataImportMerchantFileTableConfigurationProvider implements DataImportMerchantFileTableConfigurationProviderInterface
{
    /**
     * @var string
     */
    public const COL_KEY_CREATED_AT = 'createdAt';

    /**
     * @var string
     */
    public const COL_KEY_FILE_NAME = 'fileName';

    /**
     * @var string
     */
    public const COL_KEY_IMPORTER_TYPE = 'importerType';

    /**
     * @var string
     */
    public const COL_KEY_STATUS = 'status';

    /**
     * @var string
     */
    public const COL_KEY_IMPORTED_BY = 'importedBy';

    /**
     * @var string
     */
    public const KEY_AVAILABLE_ACTIONS = 'availableActions';

    /**
     * @var string
     */
    public const ACTION_ID_DOWNLOAD_ERRORS_FILE = 'downloadErrorsFile';

    /**
     * @var string
     */
    public const ACTION_ID_DOWNLOAD_ORIGINAL_FILE = 'downloadOriginalFile';

    /**
     * @var string
     */
    protected const ACTION_TITLE_DOWNLOAD_ERRORS_FILE = 'Download Errors';

    /**
     * @var string
     */
    protected const ACTION_TITLE_DOWNLOAD_ORIGINAL_FILE = 'Download Original File';

    /**
     * @var string
     */
    protected const COL_TITLE_CREATED_AT = 'Created At';

    /**
     * @var string
     */
    protected const COL_TITLE_FILE_NAME = 'File Name';

    /**
     * @var string
     */
    protected const COL_TITLE_ENTITY_TYPE = 'Type';

    /**
     * @var string
     */
    protected const COL_TITLE_STATUS = 'Status';

    /**
     * @var string
     */
    protected const COL_TITLE_IMPORTED_BY = 'Imported By';

    /**
     * @var string
     */
    protected const FORMAT_DATE_CREATED_AT = 'dd.MM.y hh:mm';

    /**
     * @var string
     */
    protected const FILTER_TITLE_ENTITY_TYPES = 'Type';

    /**
     * @var string
     */
    protected const FILTER_TITLE_STATUSES = 'Status';

    /**
     * @var string
     */
    protected const FILTER_TITLE_IMPORTED_BY = 'Imported By';

    /**
     * @var string
     */
    protected const FILTER_TITLE_CREATED_AT = 'Created At';

    /**
     * @var string
     */
    protected const FILTER_ID_CREATED_AT = 'createdAt';

    /**
     * @var string
     */
    public const FILTER_ID_ENTITY_TYPES = 'importerTypes';

    /**
     * @var string
     */
    public const FILTER_ID_STATUSES = 'statuses';

    /**
     * @var string
     */
    public const FILTER_ID_IMPORTED_BY = 'importedBy';

    /**
     * @var string
     */
    protected const STATUS_COLUMN_CHIP_COLOR = 'gray';

    /**
     * @uses \Spryker\Zed\DataImportMerchantPortalGui\Communication\Controller\FilesController::tableDataAction()
     *
     * @var string
     */
    protected const DATA_SOURCE_URL = '/data-import-merchant-portal-gui/files/table-data';

    /**
     * @uses \Spryker\Zed\DataImportMerchantPortalGui\Communication\Controller\DownloadController::errorsFileAction()
     *
     * @var string
     */
    protected const URL_DOWNLOAD_ERRORS = '/data-import-merchant-portal-gui/download/errors-file?uuid=${row.uuid}';

    /**
     * @uses \Spryker\Zed\DataImportMerchantPortalGui\Communication\Controller\DownloadController::sourceFileAction()
     *
     * @var string
     */
    protected const URL_DOWNLOAD_SOURCE_FILE = '/data-import-merchant-portal-gui/download/source-file?uuid=${row.uuid}';

    /**
     * @param \Spryker\Zed\DataImportMerchantPortalGui\Communication\Reader\DataImportMerchantFileReaderInterface $dataImportMerchantFileReader
     * @param \Spryker\Shared\GuiTable\GuiTableFactoryInterface $guiTableFactory
     */
    public function __construct(
        protected DataImportMerchantFileReaderInterface $dataImportMerchantFileReader,
        protected GuiTableFactoryInterface $guiTableFactory
    ) {
    }

    /**
     * @return \Generated\Shared\Transfer\GuiTableConfigurationTransfer
     */
    public function getConfiguration(): GuiTableConfigurationTransfer
    {
        $guiTableConfigurationBuilder = $this->guiTableFactory->createConfigurationBuilder();
        $guiTableConfigurationBuilder->setDataSourceUrl(static::DATA_SOURCE_URL);

        $this->addColumns($guiTableConfigurationBuilder);
        $this->addFilters($guiTableConfigurationBuilder);
        $this->addRowActions($guiTableConfigurationBuilder);

        return $guiTableConfigurationBuilder->createConfiguration();
    }

    /**
     * @param \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder
     *
     * @return void
     */
    protected function addColumns(GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder): void
    {
        $guiTableConfigurationBuilder
            ->addColumnDate(static::COL_KEY_CREATED_AT, static::COL_TITLE_CREATED_AT, true, true, static::FORMAT_DATE_CREATED_AT)
            ->addColumnText(static::COL_KEY_FILE_NAME, static::COL_TITLE_FILE_NAME, false, true)
            ->addColumnText(static::COL_KEY_IMPORTER_TYPE, static::COL_TITLE_ENTITY_TYPE, false, true)
            ->addColumnChip(static::COL_KEY_STATUS, static::COL_TITLE_STATUS, false, true, static::STATUS_COLUMN_CHIP_COLOR)
            ->addColumnText(static::COL_KEY_IMPORTED_BY, static::COL_TITLE_IMPORTED_BY, false, true);
    }

    /**
     * @param \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder
     *
     * @return void
     */
    protected function addFilters(GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder): void
    {
        $options = $this->dataImportMerchantFileReader->getFilterOptions();

        $guiTableConfigurationBuilder->addFilterSelect(
            static::FILTER_ID_ENTITY_TYPES,
            static::FILTER_TITLE_ENTITY_TYPES,
            true,
            $options[static::FILTER_ID_ENTITY_TYPES],
        );
        $guiTableConfigurationBuilder->addFilterSelect(
            static::FILTER_ID_STATUSES,
            static::FILTER_TITLE_STATUSES,
            true,
            $options[static::FILTER_ID_STATUSES],
        );
        $guiTableConfigurationBuilder->addFilterSelect(
            static::FILTER_ID_IMPORTED_BY,
            static::FILTER_TITLE_IMPORTED_BY,
            true,
            $options[static::FILTER_ID_IMPORTED_BY],
        );
        $guiTableConfigurationBuilder->addFilterDateRange(
            static::FILTER_ID_CREATED_AT,
            static::FILTER_TITLE_CREATED_AT,
        );
    }

    /**
     * @param \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder
     *
     * @return void
     */
    protected function addRowActions(GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder): void
    {
        $guiTableConfigurationBuilder->addRowActionRedirect(
            static::ACTION_ID_DOWNLOAD_ERRORS_FILE,
            static::ACTION_TITLE_DOWNLOAD_ERRORS_FILE,
            static::URL_DOWNLOAD_ERRORS,
        );

        $guiTableConfigurationBuilder->addRowActionRedirect(
            static::ACTION_ID_DOWNLOAD_ORIGINAL_FILE,
            static::ACTION_TITLE_DOWNLOAD_ORIGINAL_FILE,
            static::URL_DOWNLOAD_SOURCE_FILE,
        );

        $guiTableConfigurationBuilder->setAvailableRowActionsPath(static::KEY_AVAILABLE_ACTIONS);
    }
}
