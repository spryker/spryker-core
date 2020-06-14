<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\GuiTableDataRequest;

use Generated\Shared\Transfer\GuiTableConfigurationTransfer;
use Generated\Shared\Transfer\GuiTableDataRequestTransfer;
use Spryker\Zed\Kernel\BundleConfigResolverAwareTrait;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToLocaleFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Service\ProductOfferMerchantPortalGuiToUtilEncodingServiceInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductOfferMerchantPortalGui\ProductOfferMerchantPortalGuiConfig getConfig()
 */
class GuiTableDataRequestBuilder implements GuiTableDataRequestBuilderInterface
{
    use BundleConfigResolverAwareTrait;

    protected const DEFAULT_SORT_DIRECTION = 'ASC';

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\GuiTableDataRequest\FilterValueNormalizerPluginInterface[]
     */
    protected $filterValueNormalizerPlugins;

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Service\ProductOfferMerchantPortalGuiToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Service\ProductOfferMerchantPortalGuiToUtilEncodingServiceInterface $utilEncodingService
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToLocaleFacadeInterface $localeFacade
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\GuiTableDataRequest\FilterValueNormalizerPluginInterface[] $filterValueNormalizerPlugins
     */
    public function __construct(
        ProductOfferMerchantPortalGuiToUtilEncodingServiceInterface $utilEncodingService,
        ProductOfferMerchantPortalGuiToLocaleFacadeInterface $localeFacade,
        array $filterValueNormalizerPlugins
    ) {
        $this->filterValueNormalizerPlugins = $filterValueNormalizerPlugins;
        $this->utilEncodingService = $utilEncodingService;
        $this->localeFacade = $localeFacade;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableDataRequestTransfer
     */
    public function buildGuiTableDataRequest(Request $request, GuiTableConfigurationTransfer $guiTableConfigurationTransfer): GuiTableDataRequestTransfer
    {
        $guiTableDataRequestTransfer = new GuiTableDataRequestTransfer();

        $guiTableDataRequestTransfer->setSearchTerm($request->query->get('search'));
        $guiTableDataRequestTransfer->setIdLocale($this->localeFacade->getCurrentLocale()->getIdLocale());

        $guiTableDataRequestTransfer = $this->hydratePagination($guiTableDataRequestTransfer, $request);
        $guiTableDataRequestTransfer = $this->hydrateOrder($request, $guiTableDataRequestTransfer);
        $guiTableDataRequestTransfer = $this->hydrateFilters($request, $guiTableConfigurationTransfer, $guiTableDataRequestTransfer);

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
        foreach ($this->filterValueNormalizerPlugins as $filterValueNormalizerPlugin) {
            if (!$filterValueNormalizerPlugin->isApplicable($filterType)) {
                continue;
            }

            $value = $filterValueNormalizerPlugin->normalizeValue($value);
        }

        return $value;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     * @param \Generated\Shared\Transfer\GuiTableDataRequestTransfer $guiTableDataRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableDataRequestTransfer
     */
    protected function hydrateFilters(
        Request $request,
        GuiTableConfigurationTransfer $guiTableConfigurationTransfer,
        GuiTableDataRequestTransfer $guiTableDataRequestTransfer
    ): GuiTableDataRequestTransfer {
        $requestFilters = $this->utilEncodingService->decodeJson($request->query->get('filter', '[]'), true);

        foreach ($guiTableConfigurationTransfer->getFilters() as $guiTableFilterTransfer) {
            $idFilter = $guiTableFilterTransfer->getId();
            if (!array_key_exists($idFilter, $requestFilters)) {
                continue;
            }

            $filterValue = $requestFilters[$guiTableFilterTransfer->getId()];
            $normalizedFilterValue = $this->normalizeFilterValue($guiTableFilterTransfer->getType(), $filterValue);

            $guiTableDataRequestTransfer->addFilter($idFilter, $normalizedFilterValue);
        }

        return $guiTableDataRequestTransfer;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\GuiTableDataRequestTransfer $guiTableDataRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableDataRequestTransfer
     */
    protected function hydrateOrder(Request $request, GuiTableDataRequestTransfer $guiTableDataRequestTransfer): GuiTableDataRequestTransfer
    {
        if ($request->query->get('sortBy')) {
            $guiTableDataRequestTransfer->setOrderBy($request->query->get('sortBy'));
            $guiTableDataRequestTransfer->setOrderDirection($request->query->get('sortDirection', static::DEFAULT_SORT_DIRECTION));
        }

        return $guiTableDataRequestTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableDataRequestTransfer $guiTableDataRequestTransfer
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\GuiTableDataRequestTransfer
     */
    protected function hydratePagination(GuiTableDataRequestTransfer $guiTableDataRequestTransfer, Request $request): GuiTableDataRequestTransfer
    {
        $guiTableDataRequestTransfer
            ->setPage((int)$request->query->getInt('page', $this->getConfig()->getTableDefaultPage()))
            ->setPageSize((int)$request->query->getInt('pageSize', $this->getConfig()->getTableDefaultPageSize()));

        return $guiTableDataRequestTransfer;
    }
}
