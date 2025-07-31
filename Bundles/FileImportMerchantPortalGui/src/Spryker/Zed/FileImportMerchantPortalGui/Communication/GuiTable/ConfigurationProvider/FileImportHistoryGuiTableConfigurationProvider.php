<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\FileImportMerchantPortalGui\Communication\GuiTable\ConfigurationProvider;

use Generated\Shared\Transfer\GuiTableConfigurationTransfer;
use Spryker\Shared\FileImportMerchantPortalGui\FileImportMerchantPortalGuiConfig as SharedFileImportMerchantPortalGuiConfig;
use Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface;
use Spryker\Shared\GuiTable\GuiTableFactoryInterface;
use Spryker\Zed\FileImportMerchantPortalGui\Dependency\Facade\FileImportMerchantPortalGuiToTranslatorFacadeInterface;
use Spryker\Zed\FileImportMerchantPortalGui\FileImportMerchantPortalGuiConfig;

class FileImportHistoryGuiTableConfigurationProvider implements FileImportHistoryGuiTableConfigurationProviderInterface
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
    public const COL_KEY_ENTITY_TYPE = 'entityType';

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
    protected const FILTER_TITLE_CREATED_AT = 'Created At';

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
    protected const FILTER_ID_CREATED_AT = 'createdAt';

    /**
     * @var string
     */
    protected const FILTER_ID_ENTITY_TYPES = 'entityTypes';

    /**
     * @var string
     */
    protected const FILTER_ID_STATUSES = 'statuses';

    /**
     * @var string
     */
    protected const STATUS_COLUMN_CHIP_COLOR = 'gray';

    /**
     * @uses \Spryker\Zed\FileImportMerchantPortalGui\Communication\Controller\HistoryController::tableDataAction()
     *
     * @var string
     */
    protected const DATA_SOURCE_URL = '/file-import-merchant-portal-gui/history/table-data';

    /**
     * @var string
     */
    protected const URL_DOWNLOAD_ERRORS = '/file-import-merchant-portal-gui/download/errors-file?uuidMerchantFileImport=${row.uuidMerchantFileImport}';

    /**
     * @var string
     */
    protected const URL_DOWNLOAD_SOURCE_FILE = '/file-import-merchant-portal-gui/download/source-file?uuidMerchantFile=${row.uuidMerchantFile}';

    /**
     * @param \Spryker\Zed\FileImportMerchantPortalGui\FileImportMerchantPortalGuiConfig $config
     * @param \Spryker\Shared\GuiTable\GuiTableFactoryInterface $guiTableFactory
     * @param \Spryker\Zed\FileImportMerchantPortalGui\Dependency\Facade\FileImportMerchantPortalGuiToTranslatorFacadeInterface $translatorFacade
     */
    public function __construct(
        protected FileImportMerchantPortalGuiConfig $config,
        protected GuiTableFactoryInterface $guiTableFactory,
        protected FileImportMerchantPortalGuiToTranslatorFacadeInterface $translatorFacade
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
            ->addColumnText(static::COL_KEY_ENTITY_TYPE, static::COL_TITLE_ENTITY_TYPE, false, true)
            ->addColumnChip(static::COL_KEY_STATUS, static::COL_TITLE_STATUS, false, true, static::STATUS_COLUMN_CHIP_COLOR, $this->getStatusColumnChipColorMapping())
            ->addColumnText(static::COL_KEY_IMPORTED_BY, static::COL_TITLE_IMPORTED_BY, false, true);
    }

    /**
     * @param \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder
     *
     * @return void
     */
    protected function addFilters(GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder): void
    {
        $guiTableConfigurationBuilder->addFilterSelect(static::FILTER_ID_ENTITY_TYPES, static::FILTER_TITLE_ENTITY_TYPES, true, $this->getEntityTypeOptions());
        $guiTableConfigurationBuilder->addFilterSelect(static::FILTER_ID_STATUSES, static::FILTER_TITLE_STATUSES, true, $this->getStatusOptions());
        $guiTableConfigurationBuilder->addFilterDateRange(static::FILTER_ID_CREATED_AT, static::FILTER_TITLE_CREATED_AT);
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

    /**
     * @return array<string, string>
     */
    protected function getEntityTypeOptions(): array
    {
        return array_combine($this->config->getImportTypes(), $this->config->getImportTypes());
    }

    /**
     * @return array<string, string>
     */
    protected function getStatusOptions(): array
    {
        return [
            SharedFileImportMerchantPortalGuiConfig::STATUS_PENDING => SharedFileImportMerchantPortalGuiConfig::STATUS_PENDING,
            SharedFileImportMerchantPortalGuiConfig::STATUS_IN_PROGRESS => SharedFileImportMerchantPortalGuiConfig::STATUS_IN_PROGRESS,
            SharedFileImportMerchantPortalGuiConfig::STATUS_SUCCESSFUL => SharedFileImportMerchantPortalGuiConfig::STATUS_SUCCESSFUL,
            SharedFileImportMerchantPortalGuiConfig::STATUS_FAILED => SharedFileImportMerchantPortalGuiConfig::STATUS_FAILED,
            SharedFileImportMerchantPortalGuiConfig::STATUS_IMPORTED_WITH_ERRORS => SharedFileImportMerchantPortalGuiConfig::STATUS_IMPORTED_WITH_ERRORS,
        ];
    }

    /**
     * @return array<string, string>|null
     */
    protected function getStatusColumnChipColorMapping(): ?array
    {
        return [
            $this->translatorFacade->trans(SharedFileImportMerchantPortalGuiConfig::STATUS_PENDING) => 'yellow',
            $this->translatorFacade->trans(SharedFileImportMerchantPortalGuiConfig::STATUS_IN_PROGRESS) => 'blue',
            $this->translatorFacade->trans(SharedFileImportMerchantPortalGuiConfig::STATUS_SUCCESSFUL) => 'green',
            $this->translatorFacade->trans(SharedFileImportMerchantPortalGuiConfig::STATUS_FAILED) => 'red',
            $this->translatorFacade->trans(SharedFileImportMerchantPortalGuiConfig::STATUS_IMPORTED_WITH_ERRORS) => 'orange',
        ];
    }
}
