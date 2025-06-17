<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Controller;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ServicePointSearchRequestTransfer;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method \SprykerFeature\Yves\SelfServicePortal\SelfServicePortalFactory getFactory()
 */
class ServicePointSearchController extends AbstractController
{
    /**
     * @uses \Spryker\Client\ServicePointSearch\Plugin\Elasticsearch\Query\ServiceTypesServicePointSearchQueryExpanderPlugin::PARAMETER_SERVICE_TYPES
     *
     * @var string
     */
    public const SEARCH_REQUEST_PARAMETER_SERVICE_TYPES = 'serviceTypes';

    /**
     * @var string
     */
    public const SEARCH_REQUEST_PARAMETER_SERVICE_TYPE_UUID = 'serviceTypeUuid';

    /**
     * @var string
     */
    public const SEARCH_REQUEST_PARAMETER_SHIPMENT_TYPE_UUID = 'shipmentTypeUuid';

    /**
     * @var string
     */
    public const SEARCH_REQUEST_PARAMETER_ITEM_GROUP_KEYS = 'itemGroupKeys';

    /**
     * @uses \Spryker\Client\ServicePointSearch\Plugin\Elasticsearch\Query\PaginatedServicePointSearchQueryExpanderPlugin::PARAMETER_OFFSET
     *
     * @var string
     */
    public const SEARCH_REQUEST_PARAMETER_OFFSET = 'offset';

    /**
     * @uses \Spryker\Client\ServicePointSearch\Plugin\Elasticsearch\Query\PaginatedServicePointSearchQueryExpanderPlugin::PARAMETER_LIMIT
     *
     * @var string
     */
    public const SEARCH_REQUEST_PARAMETER_LIMIT = 'limit';

    /**
     * @var string
     */
    public const SEARCH_REQUEST_PARAMETER_SORT = 'sort';

    /**
     * @var string
     */
    public const PARAMETER_SERVICE_TYPE_KEY = 'serviceTypeKey';

    /**
     * @var string
     */
    public const PARAMETER_SEARCH_STRING = 'searchString';

    /**
     * @var string
     */
    public const SEARCH_REQUEST_PARAMETER_SKU = 'sku';

    /**
     * @var string
     *
     * @uses \Spryker\Client\ServicePointSearch\Plugin\Elasticsearch\ResultFormatter\ServicePointSearchResultFormatterPlugin::NAME
     */
    public const RESULT_FORMATTER = 'ServicePointSearchCollection';

    /**
     * @var string
     */
    public const SEARCH_REQUEST_PARAMETER_QUANTITY = 'quantity';

    /**
     * @var string
     */
    public const SEARCH_REQUEST_PARAMETER_ITEMS = 'items';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request): Response
    {
        $servicePointSearchRequestTransfer = $this->createServicePointSearchRequestTransfer($request);

        $searchResults = $this->getFactory()
            ->createServicePointReader()
            ->searchServicePoints($servicePointSearchRequestTransfer);

        return $this->getFactory()->createResponse($searchResults);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\ServicePointSearchRequestTransfer
     */
    protected function createServicePointSearchRequestTransfer(Request $request): ServicePointSearchRequestTransfer
    {
        $requestParameters = [
            static::SEARCH_REQUEST_PARAMETER_OFFSET => (int)$request->query->get(static::SEARCH_REQUEST_PARAMETER_OFFSET),
            static::SEARCH_REQUEST_PARAMETER_LIMIT => (int)$request->query->get(static::SEARCH_REQUEST_PARAMETER_LIMIT),
            static::SEARCH_REQUEST_PARAMETER_SORT => $request->query->get(static::SEARCH_REQUEST_PARAMETER_SORT),
        ];

        $serviceTypeKey = $request->query->get(static::PARAMETER_SERVICE_TYPE_KEY);

        if ($serviceTypeKey) {
            $requestParameters[static::SEARCH_REQUEST_PARAMETER_SERVICE_TYPES] = [$serviceTypeKey];
        }

        $serviceTypeUuid = $request->query->get(static::SEARCH_REQUEST_PARAMETER_SERVICE_TYPE_UUID);

        if ($serviceTypeUuid) {
            $requestParameters[static::SEARCH_REQUEST_PARAMETER_SERVICE_TYPE_UUID] = $serviceTypeUuid;
        }

        $shipmentTypeUuid = $request->query->get(static::SEARCH_REQUEST_PARAMETER_SHIPMENT_TYPE_UUID);

        if ($shipmentTypeUuid) {
            $requestParameters[static::SEARCH_REQUEST_PARAMETER_SHIPMENT_TYPE_UUID] = $shipmentTypeUuid;
        }

        /** @var string $sku */
        $sku = (string)$request->query->get(static::SEARCH_REQUEST_PARAMETER_SKU);

        /** @var int $quantity */
        $quantity = (int)$request->query->get(static::SEARCH_REQUEST_PARAMETER_QUANTITY, 1);

        $itemTransfers = [
            (new ItemTransfer())
                ->setSkuOrFail($sku)
                ->setQuantity($quantity)
                ->setIsMerchantCheckSkipped(true),
        ];

        $requestParameters[static::SEARCH_REQUEST_PARAMETER_ITEMS] = $itemTransfers;

        $searchString = (string)$request->query->get(static::PARAMETER_SEARCH_STRING);
        $searchString = htmlentities($searchString, ENT_QUOTES, 'UTF-8');

        return (new ServicePointSearchRequestTransfer())
            ->setSearchString($searchString)
            ->setRequestParameters($requestParameters);
    }
}
