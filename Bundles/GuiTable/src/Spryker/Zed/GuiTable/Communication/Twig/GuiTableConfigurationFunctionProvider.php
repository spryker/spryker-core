<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GuiTable\Communication\Twig;

use Generated\Shared\Transfer\GuiTableBatchActionsConfigurationTransfer;
use Generated\Shared\Transfer\GuiTableConfigurationTransfer;
use Generated\Shared\Transfer\GuiTableDataSourceConfigurationTransfer;
use Generated\Shared\Transfer\GuiTableEditableConfigurationTransfer;
use Generated\Shared\Transfer\GuiTableFiltersConfigurationTransfer;
use Generated\Shared\Transfer\GuiTablePaginationConfigurationTransfer;
use Generated\Shared\Transfer\GuiTableRowActionsConfigurationTransfer;
use Generated\Shared\Transfer\GuiTableTitleConfigurationTransfer;
use Spryker\Shared\GuiTable\Configuration\Expander\ConfigurationDefaultValuesExpanderInterface;
use Spryker\Shared\GuiTable\Configuration\Translator\ConfigurationTranslatorInterface;
use Spryker\Shared\GuiTable\Dependency\Service\GuiTableToUtilEncodingServiceInterface;
use Spryker\Shared\Twig\TwigFunctionProvider;

class GuiTableConfigurationFunctionProvider extends TwigFunctionProvider
{
    protected const CONFIG_ENABLED = 'enabled';
    protected const CONFIG_ITEMS = 'items';

    /**
     * @var \Spryker\Shared\GuiTable\Dependency\Service\GuiTableToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @var \Spryker\Shared\GuiTable\Configuration\Expander\ConfigurationDefaultValuesExpanderInterface
     */
    protected $configurationDefaultValuesExpander;

    /**
     * @var \Spryker\Shared\GuiTable\Configuration\Translator\ConfigurationTranslatorInterface
     */
    protected $configurationTranslator;

    /**
     * @param \Spryker\Shared\GuiTable\Dependency\Service\GuiTableToUtilEncodingServiceInterface $utilEncodingService
     * @param \Spryker\Shared\GuiTable\Configuration\Expander\ConfigurationDefaultValuesExpanderInterface $configurationDefaultValuesExpander
     * @param \Spryker\Shared\GuiTable\Configuration\Translator\ConfigurationTranslatorInterface $configurationTranslator
     */
    public function __construct(
        GuiTableToUtilEncodingServiceInterface $utilEncodingService,
        ConfigurationDefaultValuesExpanderInterface $configurationDefaultValuesExpander,
        ConfigurationTranslatorInterface $configurationTranslator
    ) {
        $this->utilEncodingService = $utilEncodingService;
        $this->configurationDefaultValuesExpander = $configurationDefaultValuesExpander;
        $this->configurationTranslator = $configurationTranslator;
    }

    /**
     * @return string
     */
    public function getFunctionName(): string
    {
        return 'guiTableConfiguration';
    }

    /**
     * @return callable
     */
    public function getFunction(): callable
    {
        return function (GuiTableConfigurationTransfer $guiTableConfigurationTransfer, bool $jsonEncode = true, array $overwrite = []) {
            $guiTableConfigurationTransfer = $this->configurationDefaultValuesExpander->setDefaultValues($guiTableConfigurationTransfer);
            $guiTableConfigurationTransfer = $this->configurationTranslator->translateConfiguration($guiTableConfigurationTransfer);

            $configuration = [
                GuiTableConfigurationTransfer::TITLE => $this->prepareTitle($guiTableConfigurationTransfer),
                GuiTableConfigurationTransfer::COLUMNS => $this->prepareColumnsConfigurationData($guiTableConfigurationTransfer),
                GuiTableConfigurationTransfer::DATA_SOURCE => $this->prepareDataSourceData($guiTableConfigurationTransfer),
                GuiTableConfigurationTransfer::PAGINATION => $this->preparePaginationData($guiTableConfigurationTransfer),
                GuiTableConfigurationTransfer::FILTERS => $this->prepareFiltersConfigurationData($guiTableConfigurationTransfer),
                GuiTableConfigurationTransfer::ROW_ACTIONS => $this->prepareRowActions($guiTableConfigurationTransfer),
                GuiTableConfigurationTransfer::BATCH_ACTIONS => $this->prepareBatchActions($guiTableConfigurationTransfer),
                GuiTableConfigurationTransfer::SEARCH => $this->prepareSearchData($guiTableConfigurationTransfer),
                GuiTableConfigurationTransfer::ITEM_SELECTION => $this->prepareItemSelectionData($guiTableConfigurationTransfer),
                GuiTableConfigurationTransfer::SYNC_STATE_URL => $this->prepareSyncStateUrlData($guiTableConfigurationTransfer),
                GuiTableConfigurationTransfer::EDITABLE => $this->prepareEditableData($guiTableConfigurationTransfer),
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
            GuiTableDataSourceConfigurationTransfer::TYPE => $guiTableDataSourceConfigurationTransfer->getType(),
            GuiTableDataSourceConfigurationTransfer::URL => $guiTableDataSourceConfigurationTransfer->getUrl(),
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
            GuiTablePaginationConfigurationTransfer::SIZES => $guiTablePaginationConfigurationTransfer->getSizes(),
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
            GuiTableFiltersConfigurationTransfer::ITEMS => $filtersItems,
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
            GuiTableRowActionsConfigurationTransfer::CLICK => $guiTableRowActionsConfigurationTransfer->getClick(),
            GuiTableRowActionsConfigurationTransfer::AVAILABLE_ACTIONS_PATH => $guiTableRowActionsConfigurationTransfer->getAvailableActionsPath(),
            GuiTableRowActionsConfigurationTransfer::ROW_ID_PATH => $guiTableRowActionsConfigurationTransfer->getRowIdPath(),
            GuiTableRowActionsConfigurationTransfer::ACTIONS => $actions,
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
            GuiTableBatchActionsConfigurationTransfer::AVAILABLE_ACTIONS_PATH => $guiTableBatchActionsConfigurationTransfer->getAvailableActionsPath(),
            GuiTableBatchActionsConfigurationTransfer::ROW_ID_PATH => $guiTableBatchActionsConfigurationTransfer->getRowIdPath(),
            GuiTableBatchActionsConfigurationTransfer::NO_ACTIONS_MESSAGE => $guiTableBatchActionsConfigurationTransfer->getNoActionsMessage(),
            GuiTableBatchActionsConfigurationTransfer::ACTIONS => $actions,
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

    /**
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     *
     * @return array
     */
    protected function prepareTitle(GuiTableConfigurationTransfer $guiTableConfigurationTransfer): array
    {
        $guiTableTitleConfigurationTransfer = $guiTableConfigurationTransfer->getTitle();
        if (
            !$guiTableTitleConfigurationTransfer
            || !$guiTableTitleConfigurationTransfer->getIsEnabled()
            || !$guiTableTitleConfigurationTransfer->getTitle()
        ) {
            return [
                static::CONFIG_ENABLED => false,
            ];
        }

        return [
            static::CONFIG_ENABLED => true,
            GuiTableTitleConfigurationTransfer::TITLE => $guiTableTitleConfigurationTransfer->getTitle(),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     *
     * @return array
     */
    protected function prepareEditableData(GuiTableConfigurationTransfer $guiTableConfigurationTransfer): array
    {
        $editable = $guiTableConfigurationTransfer->getEditable();

        if (!$editable) {
            return [
                static::CONFIG_ENABLED => false
            ];
        }

        $editable = $guiTableConfigurationTransfer->getEditable()->toArray(true, true);
        $editable[GuiTableEditableConfigurationTransfer::COLUMNS] = array_values($editable[GuiTableEditableConfigurationTransfer::COLUMNS]);

        return $editable;
    }
}
