<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\GuiTableDataRequest;

use Generated\Shared\Transfer\GuiTableConfigurationTransfer;
use Generated\Shared\Transfer\GuiTableDataRequestTransfer;
use Generated\Shared\Transfer\OrderByTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Service\ProductOfferMerchantPortalGuiToUtilEncodingServiceInterface;
use Symfony\Component\HttpFoundation\Request;

class RequestToGuiTableDataRequestHydrator extends AbstractPlugin implements RequestToGuiTableDataRequestHydratorInterface
{
    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\GuiTableDataRequest\FilterValueNormalizerPluginInterface[]
     */
    private $filterValueNormalizerPlugins;

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Service\ProductOfferMerchantPortalGuiToUtilEncodingServiceInterface
     */
    private $utilEncodingService;

    /**
     * RequestToGuiTableDataRequestHydrator constructor.
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Service\ProductOfferMerchantPortalGuiToUtilEncodingServiceInterface $utilEncodingService
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\GuiTableDataRequest\FilterValueNormalizerPluginInterface[] $filterValueNormalizerPlugins
     */
    public function __construct(ProductOfferMerchantPortalGuiToUtilEncodingServiceInterface $utilEncodingService, array $filterValueNormalizerPlugins)
    {
        $this->filterValueNormalizerPlugins = $filterValueNormalizerPlugins;
        $this->utilEncodingService = $utilEncodingService;
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

        $guiTableRequest
            ->setSearchTerm($request->query->get('search'))
                    // @todo grab default from config
            ->setPage($request->query->get('pageSize', 1))
                    // @todo grab default from config
            ->setPageSize($request->query->get('pageSize', 10));

        if ($request->query->get('sortBy')) {
            $orderBy = new OrderByTransfer();
            $orderBy->setProperty($request->query->get('sortBy'));
            // @todo grab default from config
            $orderBy->setDirection($request->query->get('sortDirection', 'ASC'));
            $guiTableRequest->addOrder($orderBy);
        }

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
}
