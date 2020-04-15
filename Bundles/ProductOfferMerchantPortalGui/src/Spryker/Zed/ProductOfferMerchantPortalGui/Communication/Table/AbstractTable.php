<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table;

use ArrayObject;
use Generated\Shared\Transfer\GuiTableColumnConfigurationTransfer;
use Generated\Shared\Transfer\GuiTableConfigurationTransfer;
use Generated\Shared\Transfer\GuiTableDataTransfer;
use Generated\Shared\Transfer\GuiTableFilterTransfer;
use Generated\Shared\Transfer\GuiTableRowActionTransfer;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\Filter\TableFilterDataProviderInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToTranslatorFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Service\ProductOfferMerchantPortalGuiToUtilEncodingServiceInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Exception\InvalidSortingConfigurationException;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractTable
{
    protected const CONFIG_COLUMNS = 'columns';
    protected const CONFIG_DATA_URL = 'dataUrl';
    protected const CONFIG_PAGINATION = 'pagination';
    protected const CONFIG_FILTERS = 'filters';
    protected const CONFIG_ROW_ACTIONS = 'rowActions';
    protected const CONFIG_SEARCH = 'search';
    protected const CONFIG_COLUMN_CONFIGURATOR = 'columnConfigurator';
    protected const CONFIG_ITEM_SELECTION = 'itemselection';
    protected const CONFIG_SYNC_STATE_URL = 'syncStateUrl';
    protected const CONFIG_ENABLED = 'enabled';
    protected const CONFIG_ACTIONS = 'actions';
    protected const CONFIG_SIZES = 'sizes';
    protected const CONFIG_ITEMS = 'items';

    protected const PARAM_PAGE = 'page';
    protected const PARAM_PAGE_SIZE = 'pageSize';
    protected const PARAM_SORT_BY = 'sortBy';
    protected const PARAM_SORT_DIRECTION = 'sortDirection';
    protected const PARAM_SEARCH_TERM = 'search';
    protected const PARAM_FILTERS = 'filters';

    protected const DEFAULT_PAGE = 1;
    protected const DEFAULT_PAGE_SIZE = 10;
    protected const DEFAULT_AVAILABLE_PAGE_SIZES = [10, 25, 50, 100];
    protected const DEFAULT_SORT_DIRECTION = 'ASC';

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Service\ProductOfferMerchantPortalGuiToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToTranslatorFacadeInterface
     */
    protected $translatorFacade;

    /**
     * @var string|null
     */
    protected $searchTerm;

    /**
     * @var array
     */
    protected $sorting;

    /**
     * @var int
     */
    protected $page;

    /**
     * @var int
     */
    protected $pageSize;

    /**
     * @var array
     */
    protected $filters = [];

    /**
     * @var \Generated\Shared\Transfer\GuiTableConfigurationTransfer
     */
    protected $guiTableConfigurationTransfer;

    /**
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Service\ProductOfferMerchantPortalGuiToUtilEncodingServiceInterface $utilEncodingService
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToTranslatorFacadeInterface $translatorFacade
     */
    public function __construct(
        ProductOfferMerchantPortalGuiToUtilEncodingServiceInterface $utilEncodingService,
        ProductOfferMerchantPortalGuiToTranslatorFacadeInterface $translatorFacade
    ) {
        $this->utilEncodingService = $utilEncodingService;
        $this->translatorFacade = $translatorFacade;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function getData(Request $request): array
    {
        $this->initialize($request);
        $guiTableDataTransfer = $this->provideTableData();

        return $guiTableDataTransfer->toArray(true, true);
    }

    /**
     * @return array
     */
    public function getConfiguration(): array
    {
        $guiTableConfigurationTransfer = $this->getTableConfiguration();
        $guiTableConfigurationTransfer = $this->translateConfiguration($guiTableConfigurationTransfer);

        return [
            static::CONFIG_COLUMNS => $this->prepareColumnsConfigurationData($guiTableConfigurationTransfer),
            static::CONFIG_DATA_URL => $guiTableConfigurationTransfer->getDataUrl(),
            static::CONFIG_PAGINATION => $this->preparePaginationData($guiTableConfigurationTransfer),
            static::CONFIG_FILTERS => $this->prepareFiltersConfigurationData($guiTableConfigurationTransfer),
            static::CONFIG_ROW_ACTIONS => $this->prepareRowActions($guiTableConfigurationTransfer),
            static::CONFIG_SEARCH => $this->prepareSearchData($guiTableConfigurationTransfer),
            static::CONFIG_COLUMN_CONFIGURATOR => $this->prepareColumnConfiguratorData($guiTableConfigurationTransfer),
            static::CONFIG_ITEM_SELECTION => $this->prepareItemSelectionData($guiTableConfigurationTransfer),
            static::CONFIG_SYNC_STATE_URL => $this->prepareSyncStateUrlData($guiTableConfigurationTransfer),
        ];
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
    protected function preparePaginationData(GuiTableConfigurationTransfer $guiTableConfigurationTransfer): array
    {
        $sizes = !empty($guiTableConfigurationTransfer->getAvailablePageSizes())
            ? $guiTableConfigurationTransfer->getAvailablePageSizes()
            : static::DEFAULT_AVAILABLE_PAGE_SIZES;

        return [
            static::CONFIG_ENABLED => $guiTableConfigurationTransfer->getIsPageSizedEnabled() ?? true,
            static::CONFIG_SIZES => $sizes,
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     *
     * @return array
     */
    protected function prepareFiltersConfigurationData(GuiTableConfigurationTransfer $guiTableConfigurationTransfer): array
    {
        $filtersItems = [];

        foreach ($guiTableConfigurationTransfer->getFilters() as $filterTransfer) {
            $filtersItems[] = $filterTransfer->toArray(true, true);
        }

        return [
            static::CONFIG_ENABLED => $guiTableConfigurationTransfer->getIsFiltersEnabled() ?? true,
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
        $actions = [];

        foreach ($guiTableConfigurationTransfer->getRowActions() as $rowActionTransfer) {
            $actions[] = $rowActionTransfer->toArray(true, true);
        }

        return [
            static::CONFIG_ENABLED => $guiTableConfigurationTransfer->getIsRowActionsEnabled() ?? true,
            static::CONFIG_ACTIONS => $actions,
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     *
     * @return array
     */
    protected function prepareColumnConfiguratorData(GuiTableConfigurationTransfer $guiTableConfigurationTransfer): array
    {
        return [
            static::CONFIG_ENABLED => $guiTableConfigurationTransfer->getIsColumnConfiguratorEnabled() ?? true,
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
            static::CONFIG_ENABLED => $guiTableConfigurationTransfer->getIsItemSelectionEnabled() ?? true,
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
            static::CONFIG_ENABLED => $guiTableConfigurationTransfer->getIsSyncStateUrlEnabled() ?? true,
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     *
     * @return array
     */
    protected function prepareSearchData(GuiTableConfigurationTransfer $guiTableConfigurationTransfer): array
    {
        return $guiTableConfigurationTransfer->getSearch() + [
            static::CONFIG_ENABLED => $guiTableConfigurationTransfer->getIsSearchEnabled() ?? true,
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return void
     */
    protected function initialize(Request $request): void
    {
        $this->searchTerm = $request->query->get(static::PARAM_SEARCH_TERM);
        $this->page = $request->query->get(static::PARAM_PAGE, static::DEFAULT_PAGE);
        $this->pageSize = $request->query->get(static::PARAM_PAGE_SIZE, static::DEFAULT_PAGE_SIZE);
        $this->setSorting($request);
        $this->setFilters($request);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Spryker\Zed\ProductOfferMerchantPortalGui\Exception\InvalidSortingConfigurationException
     *
     * @return void
     */
    protected function setSorting(Request $request): void
    {
        $sortColumn = $this->getSortColumn($request);
        $sortDirection = $request->query->get(static::PARAM_SORT_DIRECTION) ?? static::DEFAULT_SORT_DIRECTION;

        if (!$sortColumn) {
            throw new InvalidSortingConfigurationException('Sort column is not present.');
        }

        $this->sorting[$sortColumn] = $sortDirection;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return string|null
     */
    protected function getSortColumn(Request $request): ?string
    {
        $sortColumn = $request->query->get(static::PARAM_SORT_BY, $this->getTableConfiguration()->getDefaultSortColumn());
        $this->assertSortColumn($sortColumn);

        return $sortColumn;
    }

    /**
     * @param string $sortColumn
     *
     * @throws \Spryker\Zed\ProductOfferMerchantPortalGui\Exception\InvalidSortingConfigurationException
     *
     * @return void
     */
    protected function assertSortColumn(string $sortColumn): void
    {
        foreach ($this->getTableConfiguration()->getColumns() as $tableColumnConfigurationTransfer) {
            if ($tableColumnConfigurationTransfer->getSortable() && $tableColumnConfigurationTransfer->getId() === $sortColumn) {
                return;
            }
        }

        throw new InvalidSortingConfigurationException(
            sprintf('Sort column %s is not present in the configured list of sortable columns of `%s`', $sortColumn, static::class)
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return string
     */
    protected function getSortDirection(Request $request): string
    {
        $sortDirection = $request->query->get(static::PARAM_SORT_DIRECTION, $this->getTableConfiguration()->getDefaultSortDirection());

        return $sortDirection ?? static::DEFAULT_SORT_DIRECTION;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return void
     */
    protected function setFilters(Request $request): void
    {
        $filtersData = $this->utilEncodingService->decodeJson($request->query->get(static::PARAM_FILTERS), true);

        if (!$filtersData) {
            return;
        }

        $availableFilterIds = $this->getAvailableFilterIds();

        foreach ($filtersData as $filterId => $filterData) {
            if (!in_array($filterId, $availableFilterIds, true)) {
                continue;
            }

            $this->filters[$filterId] = $filterData;
        }
    }

    /**
     * @return string[]
     */
    protected function getAvailableFilterIds(): array
    {
        $availableFilterIds = [];

        foreach ($this->getTableConfiguration()->getFilters() as $filterTransfer) {
            $availableFilterIds[] = $filterTransfer->getId();
        }

        return $availableFilterIds;
    }

    /**
     * @return \Generated\Shared\Transfer\GuiTableConfigurationTransfer
     */
    protected function getTableConfiguration(): GuiTableConfigurationTransfer
    {
        if ($this->guiTableConfigurationTransfer === null) {
            $this->guiTableConfigurationTransfer = $this->buildTableConfiguration();
        }

        return $this->guiTableConfigurationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableConfigurationTransfer
     */
    protected function translateConfiguration(
        GuiTableConfigurationTransfer $guiTableConfigurationTransfer
    ): GuiTableConfigurationTransfer {
        $guiTableConfigurationTransfer = $this->translateColumns($guiTableConfigurationTransfer);
        $guiTableConfigurationTransfer = $this->translateFilters($guiTableConfigurationTransfer);
        $guiTableConfigurationTransfer = $this->translateRowActions($guiTableConfigurationTransfer);
        $guiTableConfigurationTransfer = $this->translateSearch($guiTableConfigurationTransfer);

        return $guiTableConfigurationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableConfigurationTransfer
     */
    protected function translateColumns(
        GuiTableConfigurationTransfer $guiTableConfigurationTransfer
    ): GuiTableConfigurationTransfer {
        $translatedGuiTableColumnConfigurationTransfers = new ArrayObject();

        foreach ($guiTableConfigurationTransfer->getColumns() as $guiTableColumnConfigurationTransfer) {
            $translatedGuiTableColumnConfigurationTransfers[] = $this->translateColumn($guiTableColumnConfigurationTransfer);
        }

        $guiTableConfigurationTransfer->setColumns($translatedGuiTableColumnConfigurationTransfers);

        return $guiTableConfigurationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableColumnConfigurationTransfer $guiTableColumnConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableColumnConfigurationTransfer
     */
    protected function translateColumn(
        GuiTableColumnConfigurationTransfer $guiTableColumnConfigurationTransfer
    ): GuiTableColumnConfigurationTransfer {
        $columnTitle = $guiTableColumnConfigurationTransfer->getTitle();

        if ($columnTitle) {
            $guiTableColumnConfigurationTransfer->setTitle($this->translate($columnTitle));
        }

        return $guiTableColumnConfigurationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableConfigurationTransfer
     */
    protected function translateFilters(
        GuiTableConfigurationTransfer $guiTableConfigurationTransfer
    ): GuiTableConfigurationTransfer {
        $translatedGuiTableFilterTransfers = new ArrayObject();

        foreach ($guiTableConfigurationTransfer->getFilters() as $guiTableFilterTransfer) {
            $translatedGuiTableFilterTransfers[] = $this->translateFilter($guiTableFilterTransfer);
        }

        $guiTableConfigurationTransfer->setFilters($translatedGuiTableFilterTransfers);

        return $guiTableConfigurationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableFilterTransfer $guiTableFilterTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableFilterTransfer
     */
    protected function translateFilter(GuiTableFilterTransfer $guiTableFilterTransfer): GuiTableFilterTransfer
    {
        $filterTitle = $guiTableFilterTransfer->getTitle();

        if ($filterTitle) {
            $guiTableFilterTransfer->setTitle($this->translate($filterTitle));
        }

        $typeOptions = $guiTableFilterTransfer->getTypeOptions();

        if (!isset($typeOptions[TableFilterDataProviderInterface::OPTION_NAME_VALUES])) {
            return $guiTableFilterTransfer;
        }

        $optionNameValues = $typeOptions[TableFilterDataProviderInterface::OPTION_NAME_VALUES];

        foreach ($optionNameValues as $key => $optionNameValue) {
            if (isset($optionNameValue[TableFilterDataProviderInterface::OPTION_VALUE_KEY_TITLE])) {
                $titleTranslated = $this->translate($optionNameValue[TableFilterDataProviderInterface::OPTION_VALUE_KEY_TITLE]);
                $optionNameValues[$key][TableFilterDataProviderInterface::OPTION_VALUE_KEY_TITLE] = $titleTranslated;
            }
        }

        $typeOptions[TableFilterDataProviderInterface::OPTION_NAME_VALUES] = $optionNameValues;
        $guiTableFilterTransfer->setTypeOptions($typeOptions);

        return $guiTableFilterTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableConfigurationTransfer
     */
    protected function translateRowActions(
        GuiTableConfigurationTransfer $guiTableConfigurationTransfer
    ): GuiTableConfigurationTransfer {
        $translatedGuiTableRowActionTransfers = new ArrayObject();

        foreach ($guiTableConfigurationTransfer->getRowActions() as $guiTableRowActionTransfer) {
            $translatedGuiTableRowActionTransfers[] = $this->translateRowAction($guiTableRowActionTransfer);
        }

        $guiTableConfigurationTransfer->setRowActions($translatedGuiTableRowActionTransfers);

        return $guiTableConfigurationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableRowActionTransfer $guiTableRowActionTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableRowActionTransfer
     */
    protected function translateRowAction(GuiTableRowActionTransfer $guiTableRowActionTransfer): GuiTableRowActionTransfer
    {
        $rowActionTitle = $guiTableRowActionTransfer->getTitle();

        if ($rowActionTitle) {
            $guiTableRowActionTransfer->setTitle($this->translate($rowActionTitle));
        }

        return $guiTableRowActionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableConfigurationTransfer
     */
    protected function translateSearch(
        GuiTableConfigurationTransfer $guiTableConfigurationTransfer
    ): GuiTableConfigurationTransfer {
        $search = $guiTableConfigurationTransfer->getSearch();

        foreach ($search as $key => $searchOption) {
            $search[$key] = $this->translate($searchOption);
        }

        $guiTableConfigurationTransfer->setSearch($search);

        return $guiTableConfigurationTransfer;
    }

    /**
     * @param string $key
     *
     * @return string
     */
    protected function translate(string $key): string
    {
        return $this->translatorFacade->trans($key);
    }

    /**
     * @return \Generated\Shared\Transfer\GuiTableDataTransfer
     */
    abstract protected function provideTableData(): GuiTableDataTransfer;

    /**
     * @return \Generated\Shared\Transfer\GuiTableConfigurationTransfer
     */
    abstract protected function buildTableConfiguration(): GuiTableConfigurationTransfer;
}
