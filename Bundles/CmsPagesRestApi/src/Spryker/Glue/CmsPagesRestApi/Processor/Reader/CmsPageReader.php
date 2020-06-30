<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CmsPagesRestApi\Processor\Reader;

use Spryker\Glue\CmsPagesRestApi\CmsPagesRestApiConfig;
use Spryker\Glue\CmsPagesRestApi\Dependency\Client\CmsPagesRestApiToCmsPageSearchClientInterface;
use Spryker\Glue\CmsPagesRestApi\Dependency\Client\CmsPagesRestApiToCmsStorageClientInterface;
use Spryker\Glue\CmsPagesRestApi\Dependency\Client\CmsPagesRestApiToStoreClientInterface;
use Spryker\Glue\CmsPagesRestApi\Processor\RestResponseBuilder\CmsPageRestResponseBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class CmsPageReader implements CmsPageReaderInterface
{
    /**
     * @uses \Spryker\Client\CmsPageSearch\Plugin\Elasticsearch\ResultFormatter\RawCmsPageSearchResultFormatterPlugin::NAME
     */
    protected const SEARCH_RESULT_CMS_PAGES = 'cms_pages';

    /**
     * @uses \Spryker\Client\CmsPageSearch\Plugin\Elasticsearch\ResultFormatter\PaginatedCmsPageResultFormatterPlugin::NAME
     */
    protected const SEARCH_RESULT_PAGINATION = 'pagination';

    protected const ID_CMS_PAGE = 'id_cms_page';
    /**
     * @uses \Spryker\Client\CmsPageSearch\CmsPageSearchConfig::PAGINATION_PARAMETER_NAME_PAGE
     */
    protected const PAGINATION_PARAMETER_NAME_PAGE = 'page';

    /**
     * @uses \Spryker\Client\CmsPageSearch\CmsPageSearchConfig::PAGINATION_ITEMS_PER_PAGE_PARAMETER_NAME
     */
    protected const PAGINATION_ITEMS_PER_PAGE_PARAMETER_NAME = 'ipp';

    /**
     * @var \Spryker\Glue\CmsPagesRestApi\Processor\RestResponseBuilder\CmsPageRestResponseBuilderInterface
     */
    protected $cmsPageRestResponseBuilder;

    /**
     * @var \Spryker\Glue\CmsPagesRestApi\Dependency\Client\CmsPagesRestApiToCmsStorageClientInterface
     */
    protected $cmsStorageClient;

    /**
     * @var \Spryker\Glue\CmsPagesRestApi\Dependency\Client\CmsPagesRestApiToCmsPageSearchClientInterface
     */
    protected $cmsPageSearchClient;

    /**
     * @var \Spryker\Glue\CmsPagesRestApi\Dependency\Client\CmsPagesRestApiToStoreClientInterface
     */
    protected $storeClient;

    /**
     * @param \Spryker\Glue\CmsPagesRestApi\Processor\RestResponseBuilder\CmsPageRestResponseBuilderInterface $cmsPageRestResponseBuilder
     * @param \Spryker\Glue\CmsPagesRestApi\Dependency\Client\CmsPagesRestApiToCmsStorageClientInterface $cmsStorageClient
     * @param \Spryker\Glue\CmsPagesRestApi\Dependency\Client\CmsPagesRestApiToCmsPageSearchClientInterface $cmsPageSearchClient
     * @param \Spryker\Glue\CmsPagesRestApi\Dependency\Client\CmsPagesRestApiToStoreClientInterface $storeClient
     */
    public function __construct(
        CmsPageRestResponseBuilderInterface $cmsPageRestResponseBuilder,
        CmsPagesRestApiToCmsStorageClientInterface $cmsStorageClient,
        CmsPagesRestApiToCmsPageSearchClientInterface $cmsPageSearchClient,
        CmsPagesRestApiToStoreClientInterface $storeClient
    ) {
        $this->cmsPageRestResponseBuilder = $cmsPageRestResponseBuilder;
        $this->cmsStorageClient = $cmsStorageClient;
        $this->cmsPageSearchClient = $cmsPageSearchClient;
        $this->storeClient = $storeClient;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function searchCmsPages(RestRequestInterface $restRequest): RestResponseInterface
    {
        $searchString = $restRequest->getHttpRequest()->query->get(CmsPagesRestApiConfig::QUERY_STRING_PARAMETER, '');

        $requestParameters = $this->getAllRequestParameters($restRequest);
        $searchResult = $this->cmsPageSearchClient->search($searchString, $requestParameters);

        if (!$searchResult[static::SEARCH_RESULT_CMS_PAGES]) {
            return $this->cmsPageRestResponseBuilder->createEmptyResponse();
        }

        $totalPagesFound = $searchResult[static::SEARCH_RESULT_PAGINATION]->getNumFound();

        $cmsPageStorageTransfers = $this->cmsStorageClient->getCmsPageStorageByIds(
            $this->getCmsPageIds($searchResult[static::SEARCH_RESULT_CMS_PAGES]),
            $restRequest->getMetadata()->getLocale(),
            $this->storeClient->getCurrentStore()->getName()
        );

        return $this->cmsPageRestResponseBuilder->createCmsPageCollectionRestResponse($cmsPageStorageTransfers, $totalPagesFound);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getCmsPageById(RestRequestInterface $restRequest): RestResponseInterface
    {
        $cmsPageUuid = $restRequest->getResource()->getId();

        $cmsPageStorageTransfers = $this->cmsStorageClient->getCmsPageStorageByUuids(
            [$cmsPageUuid],
            $restRequest->getMetadata()->getLocale(),
            $this->storeClient->getCurrentStore()->getName()
        );

        $desiredCmsPageStorageTransfer = reset($cmsPageStorageTransfers);
        if (
            !$desiredCmsPageStorageTransfer
            || !$desiredCmsPageStorageTransfer->getUuid()
            || $desiredCmsPageStorageTransfer->getUuid() !== $cmsPageUuid
        ) {
            return $this->cmsPageRestResponseBuilder->createCmsPageNotFoundErrorRestResponse();
        }

        return $this->cmsPageRestResponseBuilder->createCmsPageRestResponse($desiredCmsPageStorageTransfer);
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
            $params[static::PAGINATION_ITEMS_PER_PAGE_PARAMETER_NAME] = $restRequest->getPage()->getLimit();
            $params[static::PAGINATION_PARAMETER_NAME_PAGE] = ($restRequest->getPage()->getOffset() / $restRequest->getPage()->getLimit()) + 1;
        }

        return $params;
    }

    /**
     * @param array[] $cmsPages
     *
     * @return int[]
     */
    protected function getCmsPageIds(array $cmsPages): array
    {
        $cmsPageIds = [];
        foreach ($cmsPages as $cmsPage) {
            $cmsPageIds[] = $cmsPage[static::ID_CMS_PAGE];
        }

        return $cmsPageIds;
    }
}
