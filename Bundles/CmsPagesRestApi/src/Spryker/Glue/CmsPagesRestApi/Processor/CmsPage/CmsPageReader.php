<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CmsPagesRestApi\Processor\CmsPage;

use Generated\Shared\Transfer\RestCmsPagesAttributesTransfer;
use Generated\Shared\Transfer\RestCmsPageTransfer;
use Spryker\Glue\CmsPagesRestApi\CmsPagesRestApiConfig;
use Spryker\Glue\CmsPagesRestApi\Dependency\Client\CmsPagesRestApiToCmsPageSearchClientInterface;
use Spryker\Glue\CmsPagesRestApi\Dependency\Client\CmsPagesRestApiToCmsStorageClientInterface;
use Spryker\Glue\CmsPagesRestApi\Processor\Mapper\CmsPagesResourceMapperInterface;
use Spryker\Glue\CmsPagesRestApi\Processor\RestResponseBuilder\CmsPageRestResponseBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class CmsPageReader implements CmsPageReaderInterface
{
    protected const MAPPING_TYPE_UUID = 'uuid';
    protected const PARAMETER_NAME_PAGE = 'page';
    protected const PARAMETER_NAME_ITEMS_PER_PAGE = 'ipp';

    /**
     * @var \Spryker\Glue\CmsPagesRestApi\Processor\RestResponseBuilder\CmsPageRestResponseBuilderInterface
     */
    protected $cmsPageRestResponseBuilder;

    /**
     * @var \Spryker\Glue\CmsPagesRestApi\Dependency\Client\CmsPagesRestApiToCmsStorageClientInterface
     */
    protected $cmsPageStorageClient;

    /**
     * @var \Spryker\Glue\CmsPagesRestApi\Dependency\Client\CmsPagesRestApiToCmsPageSearchClientInterface
     */
    protected $cmsPageSearchClient;

    /**
     * @var \Spryker\Glue\CmsPagesRestApi\Processor\Mapper\CmsPagesResourceMapperInterface
     */
    protected $cmsPagesResourceMapper;

    /**
     * @param \Spryker\Glue\CmsPagesRestApi\Processor\RestResponseBuilder\CmsPageRestResponseBuilderInterface $cmsPageRestResponseBuilder
     * @param \Spryker\Glue\CmsPagesRestApi\Dependency\Client\CmsPagesRestApiToCmsStorageClientInterface $cmsPageStorageClient
     * @param \Spryker\Glue\CmsPagesRestApi\Dependency\Client\CmsPagesRestApiToCmsPageSearchClientInterface $cmsPageSearchClient
     * @param \Spryker\Glue\CmsPagesRestApi\Processor\Mapper\CmsPagesResourceMapperInterface $cmsPagesResourceMapper
     */
    public function __construct(
        CmsPageRestResponseBuilderInterface $cmsPageRestResponseBuilder,
        CmsPagesRestApiToCmsStorageClientInterface $cmsPageStorageClient,
        CmsPagesRestApiToCmsPageSearchClientInterface $cmsPageSearchClient,
        CmsPagesResourceMapperInterface $cmsPagesResourceMapper
    ) {
        $this->cmsPageRestResponseBuilder = $cmsPageRestResponseBuilder;
        $this->cmsPageStorageClient = $cmsPageStorageClient;
        $this->cmsPageSearchClient = $cmsPageSearchClient;
        $this->cmsPagesResourceMapper = $cmsPagesResourceMapper;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function searchCmsPages(RestRequestInterface $restRequest): RestResponseInterface
    {
        $searchString = $this->getRequestParameter($restRequest, CmsPagesRestApiConfig::QUERY_STRING_PARAMETER);
        $requestParameters = $this->getAllRequestParameters($restRequest);
        $searchResult = $this->cmsPageSearchClient->search($searchString, $requestParameters);

        $restSearchAttributesTransfer = $this->cmsPagesResourceMapper
            ->mapSearchResultToRestAttributesTransfer($searchResult, new RestCmsPagesAttributesTransfer());

        return $this->cmsPageRestResponseBuilder->createCmsPageCollectionRestResponse($restSearchAttributesTransfer);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
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

        $restCmsPageTransfer = (new RestCmsPageTransfer())->fromArray($cmsPageStorageTransfers[$cmsPageUuid]->toArray(), true);

        return $this->cmsPageRestResponseBuilder->createCmsPageRestResponse($cmsPageUuid, $restCmsPageTransfer);
    }

    /**
     * @phpstan-return array<string, mixed>
     *
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return array
     */
    protected function getAllRequestParameters(RestRequestInterface $restRequest): array
    {
        $params = $restRequest->getHttpRequest()->query->all();
        if ($restRequest->getPage()) {
            $params[static::PARAMETER_NAME_ITEMS_PER_PAGE] = $restRequest->getPage()->getLimit();
            $params[static::PARAMETER_NAME_PAGE] = ($restRequest->getPage()->getOffset() / $restRequest->getPage()->getLimit()) + 1;
        }

        return $params;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param string $parameterName
     *
     * @return string
     */
    protected function getRequestParameter(RestRequestInterface $restRequest, string $parameterName): string
    {
        return $restRequest->getHttpRequest()->query->get($parameterName, '');
    }
}
