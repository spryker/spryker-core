<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductTable;

use Generated\Shared\Transfer\TableConfigurationTransfer;
use Generated\Shared\Transfer\TableDataTransfer;
use Spryker\Zed\ProductOfferGuiPage\Exception\InvalidSortingDataException;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractTable
{
    protected const CONFIG_COLUMNS = 'columns';
    protected const CONFIG_AVAILABLE_PAGE_SIZES = 'pageSizes';
    protected const CONFIG_FILTERS = 'filters';
    protected const CONFIG_ROW_ACTIONS = 'rowActions';

    protected const PARAM_PAGE = 'page';
    protected const PARAM_PAGE_SIZE = 'pageSize';
    protected const PARAM_ORDER_BY = 'orderBy';
    protected const PARAM_SEARCH_TERM = 'search';
    protected const PARAM_FILTERS = 'filters';

    protected const DEFAULT_PAGE = 1;
    protected const DEFAULT_PAGE_SIZE = 10;
    protected const DEFAULT_AVAILABLE_PAGE_SIZES = [10, 25, 50];
    protected const DEFAULT_ORDER_DIRECTION = 'ASC';

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
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function getData(Request $request): array
    {
        $this->initialize($request);
        $tableDataTransfer = $this->provideTableData();

        return $tableDataTransfer->toArray();
    }

    /**
     * @return array
     */
    public function getConfiguration(): array
    {
        $tableConfigurationTransfer = $this->provideTableConfiguration();

        return [
            static::CONFIG_COLUMNS => $this->prepareColumnsConfigurationData($tableConfigurationTransfer),
            static::CONFIG_AVAILABLE_PAGE_SIZES => $this->prepareAvailablePageSizesConfigurationData($tableConfigurationTransfer),
            static::CONFIG_FILTERS => $this->prepareFiltersConfigurationData($tableConfigurationTransfer),
            static::CONFIG_ROW_ACTIONS => $this->prepareRowActions($tableConfigurationTransfer),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\TableConfigurationTransfer $tableConfigurationTransfer
     *
     * @return array
     */
    protected function prepareColumnsConfigurationData(TableConfigurationTransfer $tableConfigurationTransfer): array
    {
        $columnsData = [];

        foreach ($tableConfigurationTransfer->getColumns() as $columnTransfer) {
            $columnsData[] = $columnTransfer->modifiedToArray();
        }

        return $columnsData;
    }

    /**
     * @param \Generated\Shared\Transfer\TableConfigurationTransfer $tableConfigurationTransfer
     *
     * @return int[]
     */
    protected function prepareAvailablePageSizesConfigurationData(TableConfigurationTransfer $tableConfigurationTransfer): array
    {
        return !empty($tableConfigurationTransfer->getAvailablePageSizes())
            ? $tableConfigurationTransfer->getAvailablePageSizes()
            : static::DEFAULT_AVAILABLE_PAGE_SIZES;
    }

    /**
     * @param \Generated\Shared\Transfer\TableConfigurationTransfer $tableConfigurationTransfer
     *
     * @return array
     */
    protected function prepareFiltersConfigurationData(TableConfigurationTransfer $tableConfigurationTransfer): array
    {
        $filtersData = [];

        foreach ($tableConfigurationTransfer->getFilters() as $filterTransfer) {
            $filtersData[] = $filterTransfer->toArray();
        }

        return $filtersData;
    }

    /**
     * @param \Generated\Shared\Transfer\TableConfigurationTransfer $tableConfigurationTransfer
     *
     * @return array
     */
    protected function prepareRowActions(TableConfigurationTransfer $tableConfigurationTransfer): array
    {
        $rowActions = [];

        foreach ($tableConfigurationTransfer->getRowActions() as $rowActionTransfer) {
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
        $sortingData = $request->query->has(static::PARAM_ORDER_BY) ? json_decode($request->query->get(static::PARAM_ORDER_BY), true) : null;
        $defaultSortColumn = $this->provideTableConfiguration()->getDefaultSortColumn();

        if (!$sortingData && $defaultSortColumn) {
            $sortingData = [$defaultSortColumn => static::DEFAULT_ORDER_DIRECTION];
        }

        if (!$sortingData) {
            throw new InvalidSortingDataException('Sorting data is not present.');
        }

        foreach ($sortingData as $field => $direction) {
            $this->sorting[$field] = $direction;
        }
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

        foreach ($filtersData as $filterName => $filterData) {
            $this->filters[$filterName] = $filterData;
        }
    }

    /**
     * @return \Generated\Shared\Transfer\TableDataTransfer
     */
    abstract protected function provideTableData(): TableDataTransfer;

    /**
     * @return \Generated\Shared\Transfer\TableConfigurationTransfer
     */
    abstract protected function provideTableConfiguration(): TableConfigurationTransfer;
}
