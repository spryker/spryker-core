<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\GuiTable\Http\DataRequest;

use Generated\Shared\Transfer\GuiTableConfigurationTransfer;
use Generated\Shared\Transfer\GuiTableDataRequestTransfer;
use Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface;
use Spryker\Shared\GuiTable\Configuration\GuiTableConfigInterface;
use Spryker\Shared\GuiTable\Dependency\Service\GuiTableToUtilEncodingServiceInterface;
use Spryker\Shared\GuiTable\Normalizer\DateRangeRequestFilterValueNormalizerInterface;
use Symfony\Component\HttpFoundation\Request;

class DataRequestBuilder implements DataRequestBuilderInterface
{
    protected const DEFAULT_SORT_DIRECTION = 'ASC';

    /**
     * @var \Spryker\Shared\GuiTable\Dependency\Service\GuiTableToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @var \Spryker\Shared\GuiTable\Configuration\GuiTableConfigInterface
     */
    protected $guiTableConfig;

    /**
     * @var \Spryker\Shared\GuiTable\Normalizer\DateRangeRequestFilterValueNormalizerInterface
     */
    protected $dateRangeRequestFilterValueNormalizer;

    /**
     * @param \Spryker\Shared\GuiTable\Dependency\Service\GuiTableToUtilEncodingServiceInterface $utilEncodingService
     * @param \Spryker\Shared\GuiTable\Configuration\GuiTableConfigInterface $guiTableConfig
     * @param \Spryker\Shared\GuiTable\Normalizer\DateRangeRequestFilterValueNormalizerInterface $dateRangeRequestFilterValueNormalizer
     */
    public function __construct(
        GuiTableToUtilEncodingServiceInterface $utilEncodingService,
        GuiTableConfigInterface $guiTableConfig,
        DateRangeRequestFilterValueNormalizerInterface $dateRangeRequestFilterValueNormalizer
    ) {
        $this->utilEncodingService = $utilEncodingService;
        $this->guiTableConfig = $guiTableConfig;
        $this->dateRangeRequestFilterValueNormalizer = $dateRangeRequestFilterValueNormalizer;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableDataRequestTransfer
     */
    public function buildGuiTableDataRequestFromRequest(
        Request $request,
        GuiTableConfigurationTransfer $guiTableConfigurationTransfer
    ): GuiTableDataRequestTransfer {
        $guiTableDataRequestTransfer = new GuiTableDataRequestTransfer();

        $guiTableDataRequestTransfer->setSearchTerm($request->get('search'));
        $guiTableDataRequestTransfer = $this->addPaginationToDataRequest($request, $guiTableConfigurationTransfer, $guiTableDataRequestTransfer);
        $guiTableDataRequestTransfer = $this->addOrderToDataRequest($request, $guiTableDataRequestTransfer);
        $guiTableDataRequestTransfer = $this->addFiltersToDataRequest($request, $guiTableConfigurationTransfer, $guiTableDataRequestTransfer);

        return $guiTableDataRequestTransfer;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     * @param \Generated\Shared\Transfer\GuiTableDataRequestTransfer $guiTableDataRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableDataRequestTransfer
     */
    protected function addFiltersToDataRequest(
        Request $request,
        GuiTableConfigurationTransfer $guiTableConfigurationTransfer,
        GuiTableDataRequestTransfer $guiTableDataRequestTransfer
    ): GuiTableDataRequestTransfer {
        $requestFilters = $this->utilEncodingService->decodeJson($request->get('filter') ?? '[]', true);
        $guiTableFiltersConfigurationTransfer = $guiTableConfigurationTransfer->getFilters();

        if (!$guiTableFiltersConfigurationTransfer) {
            return $guiTableDataRequestTransfer;
        }

        foreach ($guiTableFiltersConfigurationTransfer->getItems() as $guiTableFilterTransfer) {
            $idFilter = $guiTableFilterTransfer->getId();
            if (!array_key_exists($idFilter, $requestFilters)) {
                continue;
            }

            $filterValue = $requestFilters[$idFilter];

            if ($guiTableFilterTransfer->getType() === GuiTableConfigurationBuilderInterface::FILTER_TYPE_DATE_RANGE) {
                $filterValue = $this->dateRangeRequestFilterValueNormalizer->normalizeFilterValue($filterValue);
            }

            $guiTableDataRequestTransfer->addFilter($idFilter, $filterValue);
        }

        return $guiTableDataRequestTransfer;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\GuiTableDataRequestTransfer $guiTableDataRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableDataRequestTransfer
     */
    protected function addOrderToDataRequest(
        Request $request,
        GuiTableDataRequestTransfer $guiTableDataRequestTransfer
    ): GuiTableDataRequestTransfer {
        if ($request->get('sortBy')) {
            $guiTableDataRequestTransfer->setOrderBy($request->get('sortBy'));
            $guiTableDataRequestTransfer->setOrderDirection($request->get('sortDirection') ?? static::DEFAULT_SORT_DIRECTION);
        }

        return $guiTableDataRequestTransfer;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     * @param \Generated\Shared\Transfer\GuiTableDataRequestTransfer $guiTableDataRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableDataRequestTransfer
     */
    protected function addPaginationToDataRequest(
        Request $request,
        GuiTableConfigurationTransfer $guiTableConfigurationTransfer,
        GuiTableDataRequestTransfer $guiTableDataRequestTransfer
    ): GuiTableDataRequestTransfer {
        $guiTableDataRequestTransfer
            ->setPage($request->get('page') ? (int)$request->get('page') : 1)
            ->setPageSize(
                $request->get('pageSize') ? (int)$request->get('pageSize') : $this->getDefaultPageSize($guiTableConfigurationTransfer)
            );

        return $guiTableDataRequestTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     *
     * @return int
     */
    protected function getDefaultPageSize(GuiTableConfigurationTransfer $guiTableConfigurationTransfer): int
    {
        $guiTablePaginationConfigurationTransfer = $guiTableConfigurationTransfer->getPagination();

        if (!$guiTablePaginationConfigurationTransfer) {
            return $this->guiTableConfig->getDefaultPageSize();
        }

        return $guiTablePaginationConfigurationTransfer->getDefaultSize() ?: $this->guiTableConfig->getDefaultPageSize();
    }
}
