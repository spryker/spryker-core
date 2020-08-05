<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GuiTable\Communication\Twig;

use Generated\Shared\Transfer\GuiTableConfigurationTransfer;
use Spryker\Shared\Twig\TwigFunction;
use Spryker\Zed\GuiTable\Communication\Setter\ConfigurationDefaultValuesSetterInterface;
use Spryker\Zed\GuiTable\Communication\Translator\ConfigurationTranslatorInterface;
use Spryker\Zed\GuiTable\Dependency\Service\GuiTableToUtilEncodingServiceInterface;

class GuiTableConfigurationFunction extends TwigFunction
{
    protected const CONFIG_COLUMNS = 'columns';
    protected const CONFIG_DATA_SOURCE = 'dataSource';
    protected const CONFIG_DATA_SOURCE_URL = 'url';
    protected const CONFIG_DATA_SOURCE_TYPE = 'type';
    protected const CONFIG_PAGINATION = 'pagination';
    protected const CONFIG_FILTERS = 'filters';
    protected const CONFIG_ROW_ACTIONS = 'rowActions';
    protected const CONFIG_ROW_ACTIONS_CLICK = 'click';
    protected const CONFIG_BATCH_ACTIONS = 'batchActions';
    protected const CONFIG_BATCH_NO_ACTIONS_MESSAGE = 'noActionsMessage';
    protected const CONFIG_AVAILABLE_ACTIONS_PATH = 'availableActionsPath';
    protected const CONFIG_ROW_ID_PATH = 'rowIdPath';
    protected const CONFIG_SEARCH = 'search';
    protected const CONFIG_ITEM_SELECTION = 'itemSelection';
    protected const CONFIG_SYNC_STATE_URL = 'syncStateUrl';
    protected const CONFIG_ENABLED = 'enabled';
    protected const CONFIG_ACTIONS = 'actions';
    protected const CONFIG_SIZES = 'sizes';
    protected const CONFIG_ITEMS = 'items';

    /**
     * @var \Spryker\Zed\GuiTable\Dependency\Service\GuiTableToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @var \Spryker\Zed\GuiTable\Communication\Setter\ConfigurationDefaultValuesSetterInterface
     */
    protected $configurationDefaultValuesSetter;

    /**
     * @var \Spryker\Zed\GuiTable\Communication\Translator\ConfigurationTranslatorInterface
     */
    protected $configurationTranslator;

    /**
     * @param \Spryker\Zed\GuiTable\Dependency\Service\GuiTableToUtilEncodingServiceInterface $utilEncodingService
     * @param \Spryker\Zed\GuiTable\Communication\Setter\ConfigurationDefaultValuesSetterInterface $configurationDefaultValuesSetter
     * @param \Spryker\Zed\GuiTable\Communication\Translator\ConfigurationTranslatorInterface $configurationTranslator
     */
    public function __construct(
        GuiTableToUtilEncodingServiceInterface $utilEncodingService,
        ConfigurationDefaultValuesSetterInterface $configurationDefaultValuesSetter,
        ConfigurationTranslatorInterface $configurationTranslator
    ) {
        parent::__construct();
        $this->utilEncodingService = $utilEncodingService;
        $this->configurationDefaultValuesSetter = $configurationDefaultValuesSetter;
        $this->configurationTranslator = $configurationTranslator;
    }

    /**
     * @return string
     */
    protected function getFunctionName(): string
    {
        return 'guiTableConfiguration';
    }

    /**
     * @return callable
     */
    protected function getFunction(): callable
    {
        return function (GuiTableConfigurationTransfer $guiTableConfigurationTransfer, bool $jsonEncode = true, array $overwrite = []) {
            $guiTableConfigurationTransfer = $this->configurationDefaultValuesSetter->setDefaultValues($guiTableConfigurationTransfer);
            $guiTableConfigurationTransfer = $this->configurationTranslator->translateConfiguration($guiTableConfigurationTransfer);

            $configuration = [
                static::CONFIG_COLUMNS => $this->prepareColumnsConfigurationData($guiTableConfigurationTransfer),
                static::CONFIG_DATA_SOURCE => $this->prepareDataSourceData($guiTableConfigurationTransfer),
                static::CONFIG_PAGINATION => $this->preparePaginationData($guiTableConfigurationTransfer),
                static::CONFIG_FILTERS => $this->prepareFiltersConfigurationData($guiTableConfigurationTransfer),
                static::CONFIG_ROW_ACTIONS => $this->prepareRowActions($guiTableConfigurationTransfer),
                static::CONFIG_BATCH_ACTIONS => $this->prepareBatchActions($guiTableConfigurationTransfer),
                static::CONFIG_SEARCH => $this->prepareSearchData($guiTableConfigurationTransfer),
                static::CONFIG_ITEM_SELECTION => $this->prepareItemSelectionData($guiTableConfigurationTransfer),
                static::CONFIG_SYNC_STATE_URL => $this->prepareSyncStateUrlData($guiTableConfigurationTransfer),
            ];

            if (count($overwrite)) {
                $configuration = array_replace_recursive($configuration, $overwrite);
            }

            return $jsonEncode ? $this->utilEncodingService->encodeJson($configuration) : $configuration;
        };
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     *
     * @return array
     */
    protected function prepareColumnsConfigurationData(GuiTableConfigurationTransfer $guiTableConfigurationTransfer): array
    {
        $columnsData = [];

        foreach ($guiTableConfigurationTransfer->getColumns() as $columnTransfer) {
            $columnsData[] = $columnTransfer->modifiedToArray(true, true);
        }

        return $columnsData;
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     *
     * @return array
     */
    protected function prepareDataSourceData(GuiTableConfigurationTransfer $guiTableConfigurationTransfer): array
    {
        $guiTableDataSourceConfigurationTransfer = $guiTableConfigurationTransfer->getDataSource();

        return [
            static::CONFIG_DATA_SOURCE_TYPE => $guiTableDataSourceConfigurationTransfer->getType(),
            static::CONFIG_DATA_SOURCE_URL => $guiTableDataSourceConfigurationTransfer->getUrl(),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     *
     * @return array
     */
    protected function preparePaginationData(GuiTableConfigurationTransfer $guiTableConfigurationTransfer): array
    {
        $guiTablePaginationConfigurationTransfer = $guiTableConfigurationTransfer->getPagination();

        return [
            static::CONFIG_ENABLED => $guiTablePaginationConfigurationTransfer->getIsEnabled(),
            static::CONFIG_SIZES => $guiTablePaginationConfigurationTransfer->getSizes(),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     *
     * @return array
     */
    protected function prepareFiltersConfigurationData(GuiTableConfigurationTransfer $guiTableConfigurationTransfer): array
    {
        $guiTableFiltersConfigurationTransfer = $guiTableConfigurationTransfer->getFilters();

        $filtersItems = [];

        foreach ($guiTableFiltersConfigurationTransfer->getItems() as $filterTransfer) {
            $filtersItems[] = $filterTransfer->toArray(true, true);
        }

        return [
            static::CONFIG_ENABLED => $guiTableFiltersConfigurationTransfer->getIsEnabled(),
            static::CONFIG_ITEMS => $filtersItems,
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     *
     * @return array
     */
    protected function prepareRowActions(GuiTableConfigurationTransfer $guiTableConfigurationTransfer): array
    {
        $guiTableRowActionsConfigurationTransfer = $guiTableConfigurationTransfer->getRowActions();

        $actions = [];

        foreach ($guiTableRowActionsConfigurationTransfer->getActions() as $rowActionTransfer) {
            $actions[] = $rowActionTransfer->toArray(true, true);
        }

        return [
            static::CONFIG_ENABLED => $guiTableRowActionsConfigurationTransfer->getIsEnabled(),
            static::CONFIG_ROW_ACTIONS_CLICK => $guiTableRowActionsConfigurationTransfer->getClick(),
            static::CONFIG_AVAILABLE_ACTIONS_PATH => $guiTableRowActionsConfigurationTransfer->getAvailableActionsPath(),
            static::CONFIG_ROW_ID_PATH => $guiTableRowActionsConfigurationTransfer->getRowIdPath(),
            static::CONFIG_ACTIONS => $actions,
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     *
     * @return array
     */
    protected function prepareBatchActions(GuiTableConfigurationTransfer $guiTableConfigurationTransfer): array
    {
        $guiTableBatchActionsConfigurationTransfer = $guiTableConfigurationTransfer->getBatchActions();

        $actions = [];

        foreach ($guiTableBatchActionsConfigurationTransfer->getActions() as $batchActionTransfer) {
            $actions[] = $batchActionTransfer->toArray(true, true);
        }

        return [
            static::CONFIG_ENABLED => $guiTableBatchActionsConfigurationTransfer->getIsEnabled(),
            static::CONFIG_AVAILABLE_ACTIONS_PATH => $guiTableBatchActionsConfigurationTransfer->getAvailableActionsPath(),
            static::CONFIG_ROW_ID_PATH => $guiTableBatchActionsConfigurationTransfer->getRowIdPath(),
            static::CONFIG_BATCH_NO_ACTIONS_MESSAGE => $guiTableBatchActionsConfigurationTransfer->getNoActionsMessage(),
            static::CONFIG_ACTIONS => $actions,
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     *
     * @return array
     */
    protected function prepareItemSelectionData(GuiTableConfigurationTransfer $guiTableConfigurationTransfer): array
    {
        return [
            static::CONFIG_ENABLED => $guiTableConfigurationTransfer->getItemSelection()->getIsEnabled(),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     *
     * @return array
     */
    protected function prepareSyncStateUrlData(GuiTableConfigurationTransfer $guiTableConfigurationTransfer): array
    {
        return [
            static::CONFIG_ENABLED => $guiTableConfigurationTransfer->getSyncStateUrl()->getIsEnabled(),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     *
     * @return array
     */
    protected function prepareSearchData(GuiTableConfigurationTransfer $guiTableConfigurationTransfer): array
    {
        $guiTableSearchConfigurationTransfer = $guiTableConfigurationTransfer->getSearch();

        return $guiTableSearchConfigurationTransfer->getSearch() + [
                static::CONFIG_ENABLED => $guiTableSearchConfigurationTransfer->getIsEnabled(),
            ];
    }
}
