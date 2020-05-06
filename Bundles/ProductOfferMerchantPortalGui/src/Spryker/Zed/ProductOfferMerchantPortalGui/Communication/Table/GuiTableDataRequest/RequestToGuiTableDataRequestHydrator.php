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
class RequestToGuiTableDataRequestHydrator implements RequestToGuiTableDataRequestHydratorInterface
{
    use BundleConfigResolverAwareTrait;

    protected const DEFAULT_SORT_DIRECTION = 'ASC';

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\GuiTableDataRequest\FilterValueNormalizerPluginInterface[]
     */
    private $filterValueNormalizerPlugins;

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Service\ProductOfferMerchantPortalGuiToUtilEncodingServiceInterface
     */
    private $utilEncodingService;

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToLocaleFacadeInterface
     */
    private $localeFacade;

    /**
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Service\ProductOfferMerchantPortalGuiToUtilEncodingServiceInterface $utilEncodingService
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
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $configurationTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableDataRequestTransfer
     */
    public function hydrate(Request $request, GuiTableConfigurationTransfer $configurationTransfer): GuiTableDataRequestTransfer
    {
        $guiTableRequest = new GuiTableDataRequestTransfer();

        $guiTableRequest->setSearchTerm($request->query->get('search'));
        $guiTableRequest->setLocale($this->localeFacade->getCurrentLocale());

        $this->hydratePagination($guiTableRequest, $request);
        $this->hydrateOrder($request, $guiTableRequest);
        $this->hydrateFilters($request, $configurationTransfer, $guiTableRequest);

        return $guiTableRequest;
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

            break;
        }

        return $value;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $configurationTransfer
     * @param \Generated\Shared\Transfer\GuiTableDataRequestTransfer $guiTableRequest
     *
     * @return void
     */
    protected function hydrateFilters(
        Request $request,
        GuiTableConfigurationTransfer $configurationTransfer,
        GuiTableDataRequestTransfer $guiTableRequest
    ): void {
        $requestFilters = $this->utilEncodingService->decodeJson($request->query->get('filter', '[]'), true);

        foreach ($configurationTransfer->getFilters() as $filterDefinition) {
            $filterId = $filterDefinition->getId();
            if (!array_key_exists($filterId, $requestFilters)) {
                continue;
            }

            $filterValue = $requestFilters[$filterDefinition->getId()];
            $normalizedFilterValue = $this->normalizeFilterValue($filterDefinition->getId(), $filterValue);

            $guiTableRequest->addFilters($filterId, $normalizedFilterValue);
        }
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\GuiTableDataRequestTransfer $guiTableRequest
     *
     * @return void
     */
    protected function hydrateOrder(Request $request, GuiTableDataRequestTransfer $guiTableRequest): void
    {
        if ($request->query->get('sortBy')) {
            $guiTableRequest->setOrderBy($request->query->get('sortBy'));
            $guiTableRequest->setOrderDirection($request->query->get('sortDirection', static::DEFAULT_SORT_DIRECTION));
        }
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableDataRequestTransfer $guiTableRequest
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return void
     */
    protected function hydratePagination(GuiTableDataRequestTransfer $guiTableRequest, Request $request): void
    {
        $guiTableRequest
            ->setPage($request->query->get('page', $this->getConfig()->getTableDefaultPage()))
            ->setPageSize($request->query->get('pageSize', $this->getConfig()->getTableDefaultPageSize()));
    }
}
