<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ServicePointsRestApi\Processor\Builder;

use Generated\Shared\Transfer\ServicePointSearchRequestTransfer;
use Generated\Shared\Transfer\ServicePointStorageConditionsTransfer;
use Generated\Shared\Transfer\ServicePointStorageCriteriaTransfer;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ServicePointsRestApi\Dependency\Client\ServicePointsRestApiToStoreClientInterface;
use Spryker\Glue\ServicePointsRestApi\ServicePointsRestApiConfig;

class ServicePointSearchRequestBuilder implements ServicePointSearchRequestBuilderInterface
{
    /**
     * @var string
     */
    protected const REQUEST_PARAMETER_SEARCH_QUERY = 'q';

    /**
     * @var string
     */
    protected const REQUEST_PARAMETER_SERVICE_TYPE_KEY = 'serviceTypeKey';

    /**
     * @uses \Spryker\Client\ServicePointSearch\Plugin\Elasticsearch\Query\ServiceTypesServicePointSearchQueryExpanderPlugin::PARAMETER_SERVICE_TYPES
     *
     * @var string
     */
    protected const PARAMETER_SERVICE_TYPES = 'serviceTypes';

    /**
     * @uses \Spryker\Client\ServicePointSearch\Builder\ServicePointSearchSortConfigBuilder::DEFAULT_SORT_PARAM_KEY
     *
     * @var string
     */
    protected const PARAMETER_SORT = 'sort';

    /**
     * @uses \Spryker\Client\ServicePointSearch\Plugin\Elasticsearch\Query\ServicePointAddressRelationExcludeServicePointQueryExpanderPlugin::PARAMETER_EXCLUDE_ADDRESS_RELATION
     *
     * @var string
     */
    protected const PARAMETER_EXCLUDE_ADDRESS_RELATION = 'excludeAddressRelation';

    /**
     * @uses \Spryker\Client\ServicePointSearch\Plugin\Elasticsearch\Query\PaginatedServicePointSearchQueryExpanderPlugin::PARAMETER_OFFSET
     *
     * @var string
     */
    protected const PARAMETER_OFFSET = 'offset';

    /**
     * @uses \Spryker\Client\ServicePointSearch\Plugin\Elasticsearch\Query\PaginatedServicePointSearchQueryExpanderPlugin::PARAMETER_LIMIT
     *
     * @var string
     */
    protected const PARAMETER_LIMIT = 'limit';

    /**
     * @var \Spryker\Glue\ServicePointsRestApi\ServicePointsRestApiConfig
     */
    protected ServicePointsRestApiConfig $servicePointsRestApiConfig;

    /**
     * @var \Spryker\Glue\ServicePointsRestApi\Dependency\Client\ServicePointsRestApiToStoreClientInterface
     */
    protected ServicePointsRestApiToStoreClientInterface $storeClient;

    /**
     * @param \Spryker\Glue\ServicePointsRestApi\ServicePointsRestApiConfig $servicePointsRestApiConfig
     * @param \Spryker\Glue\ServicePointsRestApi\Dependency\Client\ServicePointsRestApiToStoreClientInterface $storeClient
     */
    public function __construct(
        ServicePointsRestApiConfig $servicePointsRestApiConfig,
        ServicePointsRestApiToStoreClientInterface $storeClient
    ) {
        $this->servicePointsRestApiConfig = $servicePointsRestApiConfig;
        $this->storeClient = $storeClient;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\ServicePointSearchRequestTransfer
     */
    public function createServicePointSearchRequestTransfer(RestRequestInterface $restRequest): ServicePointSearchRequestTransfer
    {
        $requestParameters = [static::PARAMETER_EXCLUDE_ADDRESS_RELATION => true];
        $requestParameters = $this->mapRestRequestFiltersToRequestParameters($restRequest, $requestParameters);
        $requestParameters = $this->mapRestRequestSortsToRequestParameters($restRequest, $requestParameters);
        $requestParameters = $this->mapRestRequestPageToRequestParameters($restRequest, $requestParameters);

        return (new ServicePointSearchRequestTransfer())
            ->setRequestParameters($requestParameters)
            ->setSearchString($restRequest->getHttpRequest()->get(static::REQUEST_PARAMETER_SEARCH_QUERY));
    }

    /**
     * @param list<string> $servicePointUuids
     *
     * @return \Generated\Shared\Transfer\ServicePointStorageCriteriaTransfer
     */
    public function createServicePointStorageCriteriaTransfer(array $servicePointUuids): ServicePointStorageCriteriaTransfer
    {
        $servicePointStorageConditionsTransfer = (new ServicePointStorageConditionsTransfer())
            ->setUuids($servicePointUuids)
            ->setStoreName($this->storeClient->getCurrentStore()->getNameOrFail());

        return (new ServicePointStorageCriteriaTransfer())
            ->setServicePointStorageConditions($servicePointStorageConditionsTransfer);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param array<string, mixed> $requestParameters
     *
     * @return array<string, mixed>
     */
    protected function mapRestRequestFiltersToRequestParameters(RestRequestInterface $restRequest, array $requestParameters): array
    {
        foreach ($restRequest->getFilters() as $resourceName => $resourceFilters) {
            if ($resourceName !== ServicePointsRestApiConfig::RESOURCE_SERVICE_POINTS || !isset($resourceFilters[0])) {
                continue;
            }

            /** @var \Spryker\Glue\GlueApplication\Rest\Request\Data\Filter $filter */
            $filter = $resourceFilters[0];
            if ($filter->getField() === static::REQUEST_PARAMETER_SERVICE_TYPE_KEY) {
                $requestParameters[static::PARAMETER_SERVICE_TYPES] = [$filter->getValue()];

                continue;
            }

            $requestParameters[$filter->getField()] = $filter->getValue();
        }

        return $requestParameters;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param array<string, mixed> $requestParameters
     *
     * @return array<string, mixed>
     */
    protected function mapRestRequestSortsToRequestParameters(RestRequestInterface $restRequest, array $requestParameters): array
    {
        $allowedSortFields = $this->servicePointsRestApiConfig->getAllowedSortFields();
        foreach ($restRequest->getSort() as $sort) {
            if (!in_array($sort->getField(), $allowedSortFields, true)) {
                continue;
            }

            $requestParameters[static::PARAMETER_SORT] = strtolower(sprintf('%s_%s', $sort->getField(), $sort->getDirection()));

            break;
        }

        return $requestParameters;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param array<string, mixed> $requestParameters
     *
     * @return array<string, mixed>
     */
    protected function mapRestRequestPageToRequestParameters(RestRequestInterface $restRequest, array $requestParameters): array
    {
        if ($restRequest->getPage() === null) {
            return $requestParameters;
        }

        $requestParameters[static::PARAMETER_OFFSET] = $restRequest->getPage()->getOffset();
        $requestParameters[static::PARAMETER_LIMIT] = $restRequest->getPage()->getLimit();

        return $requestParameters;
    }
}
