<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CmsPagesRestApi\Processor\CmsPage;

use Spryker\Glue\CmsPagesRestApi\Dependency\Client\CmsPagesRestApiToCmsStorageClientInterface;
use Spryker\Glue\CmsPagesRestApi\Processor\RestResponseBuilder\CmsPageRestResponseBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponse;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class CmsPageReader implements CmsPageReaderInterface
{
    protected const MAPPING_TYPE_UUID = 'uuid';

    /**
     * @var \Spryker\Glue\CmsPagesRestApi\Processor\RestResponseBuilder\CmsPageRestResponseBuilderInterface
     */
    protected $cmsPageRestResponseBuilder;

    /**
     * @var \Spryker\Glue\CmsPagesRestApi\Dependency\Client\CmsPagesRestApiToCmsStorageClientInterface
     */
    protected $cmsPageStorageClient;

    /**
     * @param \Spryker\Glue\CmsPagesRestApi\Processor\RestResponseBuilder\CmsPageRestResponseBuilderInterface $cmsPageRestResponseBuilder
     * @param \Spryker\Glue\CmsPagesRestApi\Dependency\Client\CmsPagesRestApiToCmsStorageClientInterface $cmsPageStorageClient
     */
    public function __construct(
        CmsPageRestResponseBuilderInterface $cmsPageRestResponseBuilder,
        CmsPagesRestApiToCmsStorageClientInterface $cmsPageStorageClient
    ) {
        $this->cmsPageRestResponseBuilder = $cmsPageRestResponseBuilder;
        $this->cmsPageStorageClient = $cmsPageStorageClient;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function searchCmsPages(RestRequestInterface $restRequest): RestResponseInterface
    {
        return new RestResponse();

//        $searchString = $this->getRequestParameter($restRequest, CatalogSearchRestApiConfig::QUERY_STRING_PARAMETER);
//        if (empty($searchString)) {
//            return $this->cmsPageRestResponseBuilder->createCmsPageEmptyRestResponse();
//        }
//
//        $requestParameters = $this->getAllRequestParameters($restRequest);
//        $suggestions = $this->catalogClient->catalogSuggestSearch($searchString, $requestParameters);
//        $restSuggestionsAttributesTransfer = $this
//            ->catalogSearchSuggestionsResourceMapper
//            ->mapSuggestionsToRestAttributesTransfer($suggestions);
//
//        $restResource = $this->cmsPageRestResponseBuilder->createRestResource(
//            CatalogSearchRestApiConfig::RESOURCE_CATALOG_SEARCH_SUGGESTIONS,
//            null,
//            $restSuggestionsAttributesTransfer
//        );
    }

    /**
     * @inheritDoc
     */
    public function getCmsPageByResourceId(RestRequestInterface $restRequest): RestResponseInterface
    {
        $cmsPageUuid = $restRequest->getResource()->getId();

        $cmsPageStorageTransfers = $this->cmsPageStorageClient->getCmsPageStorageByUuids(
            [$cmsPageUuid],
            static::MAPPING_TYPE_UUID,
            $restRequest->getMetadata()->getLocale(),
            APPLICATION_STORE
        );

        if (!isset($cmsPageStorageTransfers[$cmsPageUuid])) {
            return $this->cmsPageRestResponseBuilder->createCmsPageEmptyRestResponse();
        }

        return $this->cmsPageRestResponseBuilder->createCmsPageRestResponse($cmsPageStorageTransfers[$cmsPageUuid]);
    }
}
