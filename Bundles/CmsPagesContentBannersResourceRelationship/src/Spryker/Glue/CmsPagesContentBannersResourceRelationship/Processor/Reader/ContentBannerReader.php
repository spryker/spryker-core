<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CmsPagesContentBannersResourceRelationship\Processor\Reader;

use Spryker\Glue\CmsPagesContentBannersResourceRelationship\CmsPagesContentBannersResourceRelationshipConfig;
use Spryker\Glue\CmsPagesContentBannersResourceRelationship\Dependency\Client\CmsPagesContentBannersResourceRelationshipToCmsStorageClientInterface;
use Spryker\Glue\CmsPagesContentBannersResourceRelationship\Dependency\Client\CmsPagesContentBannersResourceRelationshipToStoreClientInterface;
use Spryker\Glue\CmsPagesContentBannersResourceRelationship\Dependency\RestApiResource\CmsPagesContentBannersResourceRelationshipToContentBannersRestApiResourceInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class ContentBannerReader implements ContentBannerReaderInterface
{
    /**
     * @var \Spryker\Glue\CmsPagesContentBannersResourceRelationship\Dependency\Client\CmsPagesContentBannersResourceRelationshipToCmsStorageClientInterface
     */
    protected $cmsStorageClient;

    /**
     * @var \Spryker\Glue\CmsPagesContentBannersResourceRelationship\Dependency\Client\CmsPagesContentBannersResourceRelationshipToStoreClientInterface
     */
    protected $storeClient;

    /**
     * @var \Spryker\Glue\CmsPagesContentBannersResourceRelationship\Dependency\RestApiResource\CmsPagesContentBannersResourceRelationshipToContentBannersRestApiResourceInterface
     */
    protected $contentBannerRestApiResource;

    /**
     * @param \Spryker\Glue\CmsPagesContentBannersResourceRelationship\Dependency\Client\CmsPagesContentBannersResourceRelationshipToCmsStorageClientInterface $cmsStorageClient
     * @param \Spryker\Glue\CmsPagesContentBannersResourceRelationship\Dependency\Client\CmsPagesContentBannersResourceRelationshipToStoreClientInterface $storeClient
     * @param \Spryker\Glue\CmsPagesContentBannersResourceRelationship\Dependency\RestApiResource\CmsPagesContentBannersResourceRelationshipToContentBannersRestApiResourceInterface $contentBannerRestApiResource
     */
    public function __construct(
        CmsPagesContentBannersResourceRelationshipToCmsStorageClientInterface $cmsStorageClient,
        CmsPagesContentBannersResourceRelationshipToStoreClientInterface $storeClient,
        CmsPagesContentBannersResourceRelationshipToContentBannersRestApiResourceInterface $contentBannerRestApiResource
    ) {
        $this->cmsStorageClient = $cmsStorageClient;
        $this->storeClient = $storeClient;
        $this->contentBannerRestApiResource = $contentBannerRestApiResource;
    }

    /**
     * @phpstan-return array<string, array<string, \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface>>
     *
     * @param string[] $cmsPageReferences
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return array[]
     */
    public function getContentBannersResources(array $cmsPageReferences, RestRequestInterface $restRequest): array
    {
        $cmsPageStorageTransfers = $this->cmsStorageClient->getCmsPageStorageByUuids(
            $cmsPageReferences,
            $restRequest->getMetadata()->getLocale(),
            $this->storeClient->getCurrentStore()->getName()
        );

        $groupedContentBannerKeys = [];
        $contentBannerKeys = [];
        foreach ($cmsPageStorageTransfers as $cmsPageStorageTransfer) {
            $contentWidgetParameterMap = $cmsPageStorageTransfer->getContentWidgetParameterMap();
            if (!empty($contentWidgetParameterMap[CmsPagesContentBannersResourceRelationshipConfig::TWIG_FUNCTION_NAME])) {
                $contentBannerKeys = array_merge($contentBannerKeys, $contentWidgetParameterMap[CmsPagesContentBannersResourceRelationshipConfig::TWIG_FUNCTION_NAME]);
                $groupedContentBannerKeys[$cmsPageStorageTransfer->getUuid()] = $contentWidgetParameterMap[CmsPagesContentBannersResourceRelationshipConfig::TWIG_FUNCTION_NAME];
            }
        }

        $contentBannerResources = $this->contentBannerRestApiResource->getContentBannersByKeys($contentBannerKeys, $restRequest);
        if (!$contentBannerResources) {
            return [];
        }

        $mappedContentBannerResources = [];
        foreach ($groupedContentBannerKeys as $cmsPageUuid => $contentBannerKeys) {
            foreach ($contentBannerKeys as $contentBannerKey => $contentBannerValue) {
                $mappedContentBannerResources[$cmsPageUuid][$contentBannerKey] = $contentBannerResources[$contentBannerValue];
            }
        }

        return $mappedContentBannerResources;
    }
}
