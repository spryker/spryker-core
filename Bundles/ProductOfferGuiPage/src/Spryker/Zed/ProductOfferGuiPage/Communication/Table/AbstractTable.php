<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferGuiPage\Communication\Table;

use Generated\Shared\Transfer\GuiTableConfigurationTransfer;
use Generated\Shared\Transfer\GuiTableDataTransfer;
use Spryker\Zed\ProductOfferGuiPage\Dependency\Service\ProductOfferGuiPageToUtilEncodingServiceInterface;
use Spryker\Zed\ProductOfferGuiPage\Exception\InvalidSortingDataException;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractTable
{
    protected const CONFIG_COLUMNS = 'columns';
    protected const CONFIG_DATA_URL = 'dataUrl';
    protected const CONFIG_AVAILABLE_PAGE_SIZES = 'pageSizes';
    protected const CONFIG_FILTERS = 'filters';
    protected const CONFIG_ROW_ACTIONS = 'rowActions';
    protected const CONFIG_SEARCH = 'search';

    protected const PARAM_PAGE = 'page';
    protected const PARAM_PAGE_SIZE = 'size';
    protected const PARAM_SORT_BY = 'sortBy';
    protected const PARAM_SORT_DIRECTION = 'sortDirection';
    protected const PARAM_SEARCH_TERM = 'search';
    protected const PARAM_FILTERS = 'filters';

    protected const DEFAULT_PAGE = 1;
    protected const DEFAULT_PAGE_SIZE = 10;
    protected const DEFAULT_AVAILABLE_PAGE_SIZES = [10, 25, 50, 100];
    protected const DEFAULT_SORT_DIRECTION = 'ASC';

    /**
     * @var \Spryker\Zed\ProductOfferGuiPage\Dependency\Service\ProductOfferGuiPageToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

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
     * @param \Spryker\Zed\ProductOfferGuiPage\Dependency\Service\ProductOfferGuiPageToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(ProductOfferGuiPageToUtilEncodingServiceInterface $utilEncodingService)
    {
        $this->utilEncodingService = $utilEncodingService;
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

        return $guiTableDataTransfer->toArray();
    }

    /**
     * @return array
     */
    public function getConfiguration(): array
    {
        $guiTableConfigurationTransfer = $this->getTableConfiguration();

        return [
            static::CONFIG_COLUMNS => $this->prepareColumnsConfigurationData($guiTableConfigurationTransfer),
            static::CONFIG_DATA_URL => $guiTableConfigurationTransfer->getDataUrl(),
            static::CONFIG_AVAILABLE_PAGE_SIZES => $this->prepareAvailablePageSizesConfigurationData($guiTableConfigurationTransfer),
            static::CONFIG_FILTERS => $this->prepareFiltersConfigurationData($guiTableConfigurationTransfer),
            static::CONFIG_ROW_ACTIONS => $this->prepareRowActions($guiTableConfigurationTransfer),
            static::CONFIG_SEARCH => $guiTableConfigurationTransfer->getSearchOptions(),
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
            $columnsData[] = $columnTransfer->modifiedToArray();
        }

        return $columnsData;
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     *
     * @return int[]
     */
    protected function prepareAvailablePageSizesConfigurationData(GuiTableConfigurationTransfer $guiTableConfigurationTransfer): array
    {
        return !empty($guiTableConfigurationTransfer->getAvailablePageSizes())
            ? $guiTableConfigurationTransfer->getAvailablePageSizes()
            : static::DEFAULT_AVAILABLE_PAGE_SIZES;
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     *
     * @return array
     */
    protected function prepareFiltersConfigurationData(GuiTableConfigurationTransfer $guiTableConfigurationTransfer): array
    {
        $filtersData = [];

        foreach ($guiTableConfigurationTransfer->getFilters() as $filterTransfer) {
            $filtersData[] = $filterTransfer->toArray();
        }

        return $filtersData;
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     *
     * @return array
     */
    protected function prepareRowActions(GuiTableConfigurationTransfer $guiTableConfigurationTransfer): array
    {
        $rowActions = [];

        foreach ($guiTableConfigurationTransfer->getRowActions() as $rowActionTransfer) {
            $rowActions[] = $rowActionTransfer->toArray();
        }

        return $rowActions;
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
     * @throws \Spryker\Zed\ProductOfferGuiPage\Exception\InvalidSortingDataException
     *
     * @return void
     */
    protected function setSorting(Request $request): void
    {
        $sortColumn = $this->getSortColumn($request);
        $sortDirection = $request->query->get(static::PARAM_SORT_DIRECTION) ?? static::DEFAULT_SORT_DIRECTION;

        if (!$sortColumn) {
            throw new InvalidSortingDataException('Sorting data is not present.');
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
     * @throws \Spryker\Zed\ProductOfferGuiPage\Exception\InvalidSortingDataException
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

        throw new InvalidSortingDataException(
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
     * @return \Generated\Shared\Transfer\GuiTableDataTransfer
     */
    abstract protected function provideTableData(): GuiTableDataTransfer;

    /**
     * @return \Generated\Shared\Transfer\GuiTableConfigurationTransfer
     */
    abstract protected function buildTableConfiguration(): GuiTableConfigurationTransfer;
}
