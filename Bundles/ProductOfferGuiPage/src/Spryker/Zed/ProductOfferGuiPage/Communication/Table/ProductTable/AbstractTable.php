<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductTable;

use Generated\Shared\Transfer\GuiTableConfigurationTransfer;
use Generated\Shared\Transfer\GuiTableDataTransfer;
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
        $sortByColumn = $request->query->get(static::PARAM_SORT_BY) ?? $this->getTableConfiguration()->getDefaultSortColumn();
        $sortDirection = $request->query->get(static::PARAM_SORT_DIRECTION) ?? static::DEFAULT_SORT_DIRECTION;

        if (!$sortByColumn) {
            throw new InvalidSortingDataException('Sorting data is not present.');
        }

        $this->sorting[$sortByColumn] = $sortDirection;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return void
     */
    protected function setFilters(Request $request): void
    {
        $filtersData = json_decode($request->query->get(static::PARAM_FILTERS), true);

        if (!$filtersData) {
            return;
        }

        $allowedFilterNames = $this->getTableConfiguration()->getAllowedFilters();

        foreach ($filtersData as $filterName => $filterData) {
            if (!in_array($filterName, $allowedFilterNames, true)) {
                continue;
            }

            $this->filters[$filterName] = $filterData;
        }
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
