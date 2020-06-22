<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CmsPagesContentProductAbstractListsResourceRelationship\Processor\Reader;

use Spryker\Glue\CmsPagesContentProductAbstractListsResourceRelationship\CmsPagesContentProductAbstractListsResourceRelationshipConfig;
use Spryker\Glue\CmsPagesContentProductAbstractListsResourceRelationship\Dependency\Client\CmsPagesContentProductAbstractListsResourceRelationshipToCmsStorageClientInterface;
use Spryker\Glue\CmsPagesContentProductAbstractListsResourceRelationship\Dependency\Client\CmsPagesContentProductAbstractListsResourceRelationshipToStoreClientInterface;
use Spryker\Glue\CmsPagesContentProductAbstractListsResourceRelationship\Dependency\RestApiResource\CmsPagesContentProductAbstractListsResourceRelationshipToContentProductAbstractListsRestApiResourceInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class ContentProductAbstractListReader implements ContentProductAbstractListReaderInterface
{
    /**
     * @var \Spryker\Glue\CmsPagesContentProductAbstractListsResourceRelationship\Dependency\Client\CmsPagesContentProductAbstractListsResourceRelationshipToCmsStorageClientInterface
     */
    protected $cmsStorageClient;

    /**
     * @var \Spryker\Glue\CmsPagesContentProductAbstractListsResourceRelationship\Dependency\Client\CmsPagesContentProductAbstractListsResourceRelationshipToStoreClientInterface
     */
    protected $storeClient;

    /**
     * @var \Spryker\Glue\CmsPagesContentProductAbstractListsResourceRelationship\Dependency\RestApiResource\CmsPagesContentProductAbstractListsResourceRelationshipToContentProductAbstractListsRestApiResourceInterface
     */
    protected $contentProductAbstractListRestApiResource;

    /**
     * @param \Spryker\Glue\CmsPagesContentProductAbstractListsResourceRelationship\Dependency\Client\CmsPagesContentProductAbstractListsResourceRelationshipToCmsStorageClientInterface $cmsStorageClient
     * @param \Spryker\Glue\CmsPagesContentProductAbstractListsResourceRelationship\Dependency\Client\CmsPagesContentProductAbstractListsResourceRelationshipToStoreClientInterface $storeClient
     * @param \Spryker\Glue\CmsPagesContentProductAbstractListsResourceRelationship\Dependency\RestApiResource\CmsPagesContentProductAbstractListsResourceRelationshipToContentProductAbstractListsRestApiResourceInterface $contentProductAbstractListRestApiResource
     */
    public function __construct(
        CmsPagesContentProductAbstractListsResourceRelationshipToCmsStorageClientInterface $cmsStorageClient,
        CmsPagesContentProductAbstractListsResourceRelationshipToStoreClientInterface $storeClient,
        CmsPagesContentProductAbstractListsResourceRelationshipToContentProductAbstractListsRestApiResourceInterface $contentProductAbstractListRestApiResource
    ) {
        $this->cmsStorageClient = $cmsStorageClient;
        $this->storeClient = $storeClient;
        $this->contentProductAbstractListRestApiResource = $contentProductAbstractListRestApiResource;
    }

    /**
     * @phpstan-return array<string, array<string, \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface>>
     *
     * @param string[] $cmsPageReferences
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return array[]
     */
    public function getContentProductAbstractListsResources(array $cmsPageReferences, RestRequestInterface $restRequest): array
    {
        $storeName = $this->storeClient->getCurrentStore()->getName();
        $cmsPageStorageTransfers = $this->cmsStorageClient->getCmsPageStorageByUuids(
            $cmsPageReferences,
            $restRequest->getMetadata()->getLocale(),
            $storeName
        );

        $groupedContentProductAbstractListKeys = [];
        $contentProductAbstractListKeys = [];
        foreach ($cmsPageStorageTransfers as $cmsPageStorageTransfer) {
            $contentWidgetParameterMap = $cmsPageStorageTransfer->getContentWidgetParameterMap();
            if (!empty($contentWidgetParameterMap[CmsPagesContentProductAbstractListsResourceRelationshipConfig::TWIG_FUNCTION_NAME])) {
                $contentProductAbstractListKeys = array_merge($contentProductAbstractListKeys, $contentWidgetParameterMap[CmsPagesContentProductAbstractListsResourceRelationshipConfig::TWIG_FUNCTION_NAME]);
                $groupedContentProductAbstractListKeys[$cmsPageStorageTransfer->getUuid()] = $contentWidgetParameterMap[CmsPagesContentProductAbstractListsResourceRelationshipConfig::TWIG_FUNCTION_NAME];
            }
        }

        $contentProductAbstractListResources = $this->contentProductAbstractListRestApiResource->getContentProductAbstractListsByKeys(
            $contentProductAbstractListKeys,
            $restRequest,
            $storeName
        );

        if (!$contentProductAbstractListResources) {
            return [];
        }

        $mappedContentProductAbstractListResources = [];
        foreach ($groupedContentProductAbstractListKeys as $cmsPageUuid => $contentProductAbstractListKeys) {
            foreach ($contentProductAbstractListKeys as $contentProductAbstractListKey => $contentProductAbstractListValue) {
                $mappedContentProductAbstractListResources[$cmsPageUuid][$contentProductAbstractListKey] = $contentProductAbstractListResources[$contentProductAbstractListValue];
            }
        }

        return $mappedContentProductAbstractListResources;
    }
}
