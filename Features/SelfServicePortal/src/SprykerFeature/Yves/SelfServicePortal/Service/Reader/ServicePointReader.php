<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Service\Reader;

use Generated\Shared\Transfer\ServicePointSearchCollectionTransfer;
use Generated\Shared\Transfer\ServicePointSearchRequestTransfer;
use Spryker\Client\ServicePointSearch\ServicePointSearchClientInterface;
use SprykerFeature\Yves\SelfServicePortal\Controller\ServicePointSearchController;
use SprykerFeature\Yves\SelfServicePortal\Plugin\Router\SelfServicePortalPageRouteProviderPlugin;
use Twig\Environment;

class ServicePointReader implements ServicePointReaderInterface
{
    /**
     * @param \Spryker\Client\ServicePointSearch\ServicePointSearchClientInterface $servicePointSearchClient
     * @param \Twig\Environment $twigEnvironment
     */
    public function __construct(
        protected ServicePointSearchClientInterface $servicePointSearchClient,
        protected Environment $twigEnvironment
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\ServicePointSearchRequestTransfer $servicePointSearchRequestTransfer
     *
     * @return string
     */
    public function searchServicePoints(ServicePointSearchRequestTransfer $servicePointSearchRequestTransfer): string
    {
        $searchResults = $this->servicePointSearchClient->searchServicePoints($servicePointSearchRequestTransfer);
        $servicePointSearchCollectionTransfer = $searchResults[ServicePointSearchController::RESULT_FORMATTER] ?? new ServicePointSearchCollectionTransfer();
        $requestParameters = $servicePointSearchRequestTransfer->getRequestParameters();
        $serviceTypeKeys = $requestParameters[ServicePointSearchController::SEARCH_REQUEST_PARAMETER_SERVICE_TYPES] ?? null;
        $serviceTypeUuid = $requestParameters[ServicePointSearchController::SEARCH_REQUEST_PARAMETER_SERVICE_TYPE_UUID] ?? null;
        $shipmentTypeUuid = $requestParameters[ServicePointSearchController::SEARCH_REQUEST_PARAMETER_SHIPMENT_TYPE_UUID] ?? null;
        $itemGroupKeys = $requestParameters[ServicePointSearchController::SEARCH_REQUEST_PARAMETER_ITEM_GROUP_KEYS] ?? [];
        $itemTransfers = $requestParameters[ServicePointSearchController::SEARCH_REQUEST_PARAMETER_ITEMS] ?? [];

        return $this->twigEnvironment->render(
            '@ServicePointWidget/views/service-point-list/service-point-list.twig',
            [
                'servicePoints' => $servicePointSearchCollectionTransfer->getServicePoints()->getArrayCopy(),
                'nbResults' => $servicePointSearchCollectionTransfer->getNbResultsOrFail(),
                'offset' => $requestParameters[ServicePointSearchController::SEARCH_REQUEST_PARAMETER_OFFSET],
                'limit' => $requestParameters[ServicePointSearchController::SEARCH_REQUEST_PARAMETER_LIMIT],
                'serviceTypeKey' => $serviceTypeKeys ? reset($serviceTypeKeys) : null,
                'serviceTypeUuid' => $serviceTypeUuid,
                'shipmentTypeUuid' => $shipmentTypeUuid,
                'itemGroupKeys' => $itemGroupKeys,
                'searchString' => $servicePointSearchRequestTransfer->getSearchString(),
                'searchRoute' => SelfServicePortalPageRouteProviderPlugin::ROUTE_NAME_SSP_SERVICE_POINT_SEARCH,
                'items' => $itemTransfers,
            ],
        );
    }
}
