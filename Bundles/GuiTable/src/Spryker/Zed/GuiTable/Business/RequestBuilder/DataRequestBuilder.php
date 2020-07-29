<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GuiTable\Business\RequestBuilder;

use Generated\Shared\Transfer\GuiTableConfigurationTransfer;
use Generated\Shared\Transfer\GuiTableDataRequestTransfer;
use Spryker\Zed\GuiTable\Dependency\Facade\GuiTableToLocaleFacadeInterface;
use Spryker\Zed\GuiTable\Dependency\Service\GuiTableToUtilEncodingServiceInterface;
use Spryker\Zed\GuiTable\GuiTableConfig;

class DataRequestBuilder implements DataRequestBuilderInterface
{
    protected const DEFAULT_SORT_DIRECTION = 'ASC';

    /**
     * @var \Spryker\Zed\GuiTable\Dependency\Service\GuiTableToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @var \Spryker\Zed\GuiTable\Dependency\Facade\GuiTableToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\GuiTable\GuiTableConfig
     */
    protected $guiTableConfig;

    /**
     * @var array|\Spryker\Zed\GuiTableExtension\Dependency\Plugin\RequestFilterValueNormalizerPluginInterface[]
     */
    protected $requestFilterValueNormalizerPlugins;

    /**
     * @param \Spryker\Zed\GuiTable\Dependency\Service\GuiTableToUtilEncodingServiceInterface $utilEncodingService
     * @param \Spryker\Zed\GuiTable\Dependency\Facade\GuiTableToLocaleFacadeInterface $localeFacade
     * @param \Spryker\Zed\GuiTable\GuiTableConfig $guiTableConfig
     * @param \Spryker\Zed\GuiTableExtension\Dependency\Plugin\RequestFilterValueNormalizerPluginInterface[] $requestFilterValueNormalizerPlugins
     */
    public function __construct(
        GuiTableToUtilEncodingServiceInterface $utilEncodingService,
        GuiTableToLocaleFacadeInterface $localeFacade,
        GuiTableConfig $guiTableConfig,
        array $requestFilterValueNormalizerPlugins = []
    ) {
        $this->requestFilterValueNormalizerPlugins = $requestFilterValueNormalizerPlugins;
        $this->utilEncodingService = $utilEncodingService;
        $this->guiTableConfig = $guiTableConfig;
        $this->localeFacade = $localeFacade;
    }

    /**
     * @param mixed[] $requestParams
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableDataRequestTransfer
     */
    public function buildGuiTableDataRequest(
        array $requestParams,
        GuiTableConfigurationTransfer $guiTableConfigurationTransfer
    ): GuiTableDataRequestTransfer {
        $guiTableDataRequestTransfer = new GuiTableDataRequestTransfer();

        $guiTableDataRequestTransfer->setSearchTerm($requestParams['search'] ?? null);
        $guiTableDataRequestTransfer->setIdLocale($this->localeFacade->getCurrentLocale()->getIdLocale());

        $guiTableDataRequestTransfer = $this->hydratePagination($requestParams, $guiTableConfigurationTransfer, $guiTableDataRequestTransfer);
        $guiTableDataRequestTransfer = $this->hydrateOrder($requestParams, $guiTableDataRequestTransfer);
        $guiTableDataRequestTransfer = $this->hydrateFilters($requestParams, $guiTableConfigurationTransfer, $guiTableDataRequestTransfer);

        return $guiTableDataRequestTransfer;
    }

    /**
     * @param string $filterType
     * @param mixed $value
     *
     * @return mixed
     */
    protected function normalizeFilterValue(string $filterType, $value)
    {
        foreach ($this->requestFilterValueNormalizerPlugins as $requestFilterValueNormalizerPlugin) {
            if ($filterType !== $requestFilterValueNormalizerPlugin->getFilterType()) {
                continue;
            }

            $value = $requestFilterValueNormalizerPlugin->normalizeFilterValue($value);
        }

        return $value;
    }

    /**
     * @param array $requestParams
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     * @param \Generated\Shared\Transfer\GuiTableDataRequestTransfer $guiTableDataRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableDataRequestTransfer
     */
    protected function hydrateFilters(
        array $requestParams,
        GuiTableConfigurationTransfer $guiTableConfigurationTransfer,
        GuiTableDataRequestTransfer $guiTableDataRequestTransfer
    ): GuiTableDataRequestTransfer {
        $requestFilters = $this->utilEncodingService->decodeJson($requestParams['filter'] ?? '[]', true);
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
            $normalizedFilterValue = $this->normalizeFilterValue($guiTableFilterTransfer->getType(), $filterValue);

            $guiTableDataRequestTransfer->addFilter($idFilter, $normalizedFilterValue);
        }

        return $guiTableDataRequestTransfer;
    }

    /**
     * @param array $requestParams
     * @param \Generated\Shared\Transfer\GuiTableDataRequestTransfer $guiTableDataRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableDataRequestTransfer
     */
    protected function hydrateOrder(
        array $requestParams,
        GuiTableDataRequestTransfer $guiTableDataRequestTransfer
    ): GuiTableDataRequestTransfer {
        if (isset($requestParams['sortBy'])) {
            $guiTableDataRequestTransfer->setOrderBy($requestParams['sortBy']);
            $guiTableDataRequestTransfer->setOrderDirection($requestParams['sortDirection'] ?? static::DEFAULT_SORT_DIRECTION);
        }

        return $guiTableDataRequestTransfer;
    }

    /**
     * @param array $requestParams
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     * @param \Generated\Shared\Transfer\GuiTableDataRequestTransfer $guiTableDataRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableDataRequestTransfer
     */
    protected function hydratePagination(
        array $requestParams,
        GuiTableConfigurationTransfer $guiTableConfigurationTransfer,
        GuiTableDataRequestTransfer $guiTableDataRequestTransfer
    ): GuiTableDataRequestTransfer {
        $guiTableDataRequestTransfer
            ->setPage(isset($requestParams['page']) ? (int)$requestParams['page'] : 1)
            ->setPageSize(
                isset($requestParams['pageSize']) ? (int)$requestParams['pageSize'] : $this->getDefaultPageSize($guiTableConfigurationTransfer)
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
