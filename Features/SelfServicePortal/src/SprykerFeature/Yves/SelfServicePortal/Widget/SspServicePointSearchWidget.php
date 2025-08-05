<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Widget;

use Generated\Shared\Transfer\ServicePointSearchRequestTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerFeature\Yves\SelfServicePortal\SelfServicePortalConfig getConfig()
 * @method \SprykerFeature\Yves\SelfServicePortal\SelfServicePortalFactory getFactory()
 */
class SspServicePointSearchWidget extends AbstractWidget
{
    /**
     * @var string
     */
    protected const NAME = 'SspServicePointSearchWidget';

    /**
     * @var string
     */
    protected const PARAMETER_GOOGLE_MAPS_API_KEY = 'googleMapsApiKey';

    /**
     * @var string
     */
    protected const PARAMETER_SEARCH_RESULT_LIMIT = 'searchResultLimit';

    /**
     * @var string
     */
    protected const PARAMETER_SEARCH_RESULTS = 'searchResults';

    /**
     * @var string
     */
    protected const PARAMETER_SEARCH_ROUTE = 'searchRoute';

    /**
     * @var string
     */
    protected const PARAMETER_SERVICE_TYPE_KEY = 'serviceTypeKey';

    /**
     * @var string
     */
    protected const PARAMETER_SERVICE_TYPE_UUID = 'serviceTypeUuid';

    /**
     * @var string
     */
    protected const PARAMETER_SHIPMENT_TYPE_UUID = 'shipmentTypeUuid';

    /**
     * @var string
     */
    protected const PARAMETER_ITEM_GROUP_KEYS = 'itemGroupKeys';

    /**
     * @var string
     */
    protected const PARAMETER_ITEMS = 'items';

    /**
     * @uses \Spryker\Client\ServicePointSearch\Plugin\Elasticsearch\Query\ServiceTypesServicePointSearchQueryExpanderPlugin::PARAMETER_SERVICE_TYPES
     *
     * @var string
     */
    protected const SEARCH_REQUEST_PARAMETER_SERVICE_TYPES = 'serviceTypes';

    /**
     * @uses \Spryker\Client\ServicePointSearch\Plugin\Elasticsearch\Query\PaginatedServicePointSearchQueryExpanderPlugin::PARAMETER_OFFSET
     *
     * @var string
     */
    protected const SEARCH_REQUEST_PARAMETER_OFFSET = 'offset';

    /**
     * @uses \Spryker\Client\ServicePointSearch\Plugin\Elasticsearch\Query\PaginatedServicePointSearchQueryExpanderPlugin::PARAMETER_LIMIT
     *
     * @var string
     */
    protected const SEARCH_REQUEST_PARAMETER_LIMIT = 'limit';

    /**
     * @var string
     *
     * @uses \SprykerShop\Yves\ServicePointWidget\Plugin\Router\ServicePointWidgetRouteProviderPlugin::ROUTE_NAME_SEARCH
     */
    protected const ROUTE_NAME_SEARCH = 'service-point-widget/search';

    /**
     * @param string|null $serviceTypeKey
     * @param string|null $serviceTypeUuid
     * @param string|null $shipmentTypeUuid
     * @param list<string> $itemGroupKeys
     * @param int|null $searchResultLimit
     * @param bool $isInitialRenderEnabled
     * @param list<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     * @param string $searchRoute
     */
    public function __construct(
        ?string $serviceTypeKey = null,
        ?string $serviceTypeUuid = null,
        ?string $shipmentTypeUuid = null,
        array $itemGroupKeys = [],
        ?int $searchResultLimit = null,
        bool $isInitialRenderEnabled = true,
        array $itemTransfers = [],
        string $searchRoute = self::ROUTE_NAME_SEARCH
    ) {
        if (!$searchResultLimit) {
            $searchResultLimit = $this->getConfig()->getSearchResultLimit();
        }

        $this->addSearchResultLimitParameter($searchResultLimit);
        $this->addSearchRouteParameter($searchRoute);
        $this->addServiceTypeKeyParameter($serviceTypeKey);
        $this->addServiceTypeUuidParameter($serviceTypeUuid);
        $this->addShipmentTypeUuidParameter($shipmentTypeUuid);
        $this->addItemGroupKeysParameter($itemGroupKeys);
        $this->addSearchResultsParameter(
            $isInitialRenderEnabled,
            $searchResultLimit,
            $serviceTypeKey,
            $serviceTypeUuid,
            $shipmentTypeUuid,
            $itemGroupKeys,
            $itemTransfers,
        );
        $this->addGoogleMapsApiKeyParameter();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public static function getName(): string
    {
        return static::NAME;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@SelfServicePortal/views/ssp-service-point-search/ssp-service-point-search.twig';
    }

    protected function addGoogleMapsApiKeyParameter(): void
    {
        $this->addParameter(static::PARAMETER_GOOGLE_MAPS_API_KEY, $this->getConfig()->getGoogleMapsApiKey());
    }

    protected function addSearchResultLimitParameter(int $searchResultLimit): void
    {
        $this->addParameter(static::PARAMETER_SEARCH_RESULT_LIMIT, $searchResultLimit);
    }

    /**
     * @param bool $isInitialRenderEnabled
     * @param int $searchResultLimit
     * @param string|null $serviceTypeKey
     * @param string|null $serviceTypeUuid
     * @param string|null $shipmentTypeUuid
     * @param list<string> $itemGroupKeys
     * @param list<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return void
     */
    protected function addSearchResultsParameter(
        bool $isInitialRenderEnabled,
        int $searchResultLimit,
        ?string $serviceTypeKey = null,
        ?string $serviceTypeUuid = null,
        ?string $shipmentTypeUuid = null,
        array $itemGroupKeys = [],
        array $itemTransfers = []
    ): void {
        $this->addParameter(
            static::PARAMETER_SEARCH_RESULTS,
            $this->getSearchResults(
                $isInitialRenderEnabled,
                $searchResultLimit,
                $serviceTypeKey,
                $serviceTypeUuid,
                $shipmentTypeUuid,
                $itemGroupKeys,
                $itemTransfers,
            ),
        );
    }

    protected function addSearchRouteParameter(string $searchRoute): void
    {
        $this->addParameter(static::PARAMETER_SEARCH_ROUTE, $searchRoute);
    }

    protected function addServiceTypeKeyParameter(?string $serviceTypeKey = null): void
    {
        $this->addParameter(static::PARAMETER_SERVICE_TYPE_KEY, $serviceTypeKey);
    }

    protected function addServiceTypeUuidParameter(?string $serviceTypeUuid = null): void
    {
        $this->addParameter(static::PARAMETER_SERVICE_TYPE_UUID, $serviceTypeUuid);
    }

    protected function addShipmentTypeUuidParameter(?string $shipmentTypeUuid = null): void
    {
        $this->addParameter(static::PARAMETER_SHIPMENT_TYPE_UUID, $shipmentTypeUuid);
    }

    /**
     * @param list<string> $itemGroupKeys
     *
     * @return void
     */
    protected function addItemGroupKeysParameter(array $itemGroupKeys): void
    {
        $this->addParameter(static::PARAMETER_ITEM_GROUP_KEYS, $itemGroupKeys);
    }

    /**
     * @param bool $isInitialRenderEnabled
     * @param int $searchResultLimit
     * @param string|null $serviceTypeKey
     * @param string|null $serviceTypeUuid
     * @param string|null $shipmentTypeUuid
     * @param list<string> $itemGroupKeys
     * @param list<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return string
     */
    protected function getSearchResults(
        bool $isInitialRenderEnabled,
        int $searchResultLimit,
        ?string $serviceTypeKey = null,
        ?string $serviceTypeUuid = null,
        ?string $shipmentTypeUuid = null,
        array $itemGroupKeys = [],
        array $itemTransfers = []
    ): string {
        if (!$isInitialRenderEnabled) {
            return '';
        }

        $servicePointSearchRequestTransfer = $this->createServicePointSearchRequestTransfer(
            $searchResultLimit,
            $serviceTypeKey,
            $serviceTypeUuid,
            $shipmentTypeUuid,
            $itemGroupKeys,
            $itemTransfers,
        );

        return $this->getFactory()
            ->createServicePointReader()
            ->searchServicePoints($servicePointSearchRequestTransfer);
    }

    /**
     * @param int $searchResultLimit
     * @param string|null $serviceTypeKey
     * @param string|null $serviceTypeUuid
     * @param string|null $shipmentTypeUuid
     * @param list<string> $itemGroupKeys
     * @param list<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return \Generated\Shared\Transfer\ServicePointSearchRequestTransfer
     */
    protected function createServicePointSearchRequestTransfer(
        int $searchResultLimit,
        ?string $serviceTypeKey = null,
        ?string $serviceTypeUuid = null,
        ?string $shipmentTypeUuid = null,
        array $itemGroupKeys = [],
        array $itemTransfers = []
    ): ServicePointSearchRequestTransfer {
        $requestParameters = [
            static::SEARCH_REQUEST_PARAMETER_OFFSET => 0,
            static::SEARCH_REQUEST_PARAMETER_LIMIT => $searchResultLimit,
        ];

        if ($serviceTypeKey) {
            $requestParameters[static::SEARCH_REQUEST_PARAMETER_SERVICE_TYPES] = [$serviceTypeKey];
        }

        if ($serviceTypeUuid) {
            $requestParameters[static::PARAMETER_SERVICE_TYPE_UUID] = $serviceTypeUuid;
        }

        if ($shipmentTypeUuid) {
            $requestParameters[static::PARAMETER_SHIPMENT_TYPE_UUID] = $shipmentTypeUuid;
        }

        if ($itemGroupKeys) {
            $requestParameters[static::PARAMETER_ITEM_GROUP_KEYS] = $itemGroupKeys;
        }

        if ($itemTransfers) {
            $requestParameters[static::PARAMETER_ITEMS] = $itemTransfers;
        }

        return (new ServicePointSearchRequestTransfer())->setRequestParameters($requestParameters);
    }
}
