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
    protected $contentProductAbstractListsRestApiResource;

    /**
     * @param \Spryker\Glue\CmsPagesContentProductAbstractListsResourceRelationship\Dependency\Client\CmsPagesContentProductAbstractListsResourceRelationshipToCmsStorageClientInterface $cmsStorageClient
     * @param \Spryker\Glue\CmsPagesContentProductAbstractListsResourceRelationship\Dependency\Client\CmsPagesContentProductAbstractListsResourceRelationshipToStoreClientInterface $storeClient
     * @param \Spryker\Glue\CmsPagesContentProductAbstractListsResourceRelationship\Dependency\RestApiResource\CmsPagesContentProductAbstractListsResourceRelationshipToContentProductAbstractListsRestApiResourceInterface $contentProductAbstractListsRestApiResource
     */
    public function __construct(
        CmsPagesContentProductAbstractListsResourceRelationshipToCmsStorageClientInterface $cmsStorageClient,
        CmsPagesContentProductAbstractListsResourceRelationshipToStoreClientInterface $storeClient,
        CmsPagesContentProductAbstractListsResourceRelationshipToContentProductAbstractListsRestApiResourceInterface $contentProductAbstractListsRestApiResource
    ) {
        $this->cmsStorageClient = $cmsStorageClient;
        $this->storeClient = $storeClient;
        $this->contentProductAbstractListsRestApiResource = $contentProductAbstractListsRestApiResource;
    }

    /**
     * @phpstan-return array<string, array<string, \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface>>
     *
     * @param string[] $cmsPageUuids
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[][]
     */
    public function getContentProductAbstractListsResources(array $cmsPageUuids, RestRequestInterface $restRequest): array
    {
        $localeName = $restRequest->getMetadata()->getLocale();
        $storeName = $this->storeClient->getCurrentStore()->getName();
        $cmsPageStorageTransfers = $this->cmsStorageClient->getCmsPageStorageByUuids(
            $cmsPageUuids,
            $localeName,
            $storeName
        );

        if (!$cmsPageStorageTransfers) {
            return [];
        }

        $groupedContentProductAbstractListKeys = $this->getGroupedContentProductAbstractListKeys($cmsPageStorageTransfers);

        if (!$groupedContentProductAbstractListKeys) {
            return [];
        }
        $contentProductAbstractListKeys = array_merge(...array_values($groupedContentProductAbstractListKeys));

        $contentProductAbstractListResources = $this->contentProductAbstractListsRestApiResource
            ->getContentProductAbstractListsByKeys($contentProductAbstractListKeys, $localeName);

        if (!$contentProductAbstractListResources) {
            return [];
        }

        return $this->groupContentProductAbstractListsByCmsPageUuid(
            $contentProductAbstractListResources,
            $groupedContentProductAbstractListKeys
        );
    }

    /**
     * @phpstan-return array<string, array<string, string>>
     *
     * @param \Generated\Shared\Transfer\CmsPageStorageTransfer[] $cmsPageStorageTransfers
     *
     * @return string[][]
     */
    protected function getGroupedContentProductAbstractListKeys(array $cmsPageStorageTransfers): array
    {
        $groupedContentProductAbstractListKeys = [];
        foreach ($cmsPageStorageTransfers as $cmsPageStorageTransfer) {
            $contentWidgetParameterMap = $cmsPageStorageTransfer->getContentWidgetParameterMap();
            if (empty($contentWidgetParameterMap[CmsPagesContentProductAbstractListsResourceRelationshipConfig::TWIG_FUNCTION_NAME])) {
                continue;
            }

            $groupedContentProductAbstractListKeys[$cmsPageStorageTransfer->getUuid()]
                = $contentWidgetParameterMap[CmsPagesContentProductAbstractListsResourceRelationshipConfig::TWIG_FUNCTION_NAME];
        }

        return $groupedContentProductAbstractListKeys;
    }

    /**
     * @phpstan-param array<string, \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface> $contentProductAbstractListResources
     * @phpstan-param array<string, array<string, string>> $groupedContentProductAbstractListKeys
     *
     * @phpstan-return array<string, array<string, \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface>>
     *
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $contentProductAbstractListResources
     * @param string[][] $groupedContentProductAbstractListKeys
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[][]
     */
    protected function groupContentProductAbstractListsByCmsPageUuid(
        array $contentProductAbstractListResources,
        array $groupedContentProductAbstractListKeys
    ): array {
        $groupedContentProductAbstractListResources = [];
        foreach ($groupedContentProductAbstractListKeys as $cmsPageUuid => $contentProductAbstractListKeys) {
            foreach ($contentProductAbstractListKeys as $contentProductAbstractListKey) {
                if (!isset($contentProductAbstractListResources[$contentProductAbstractListKey])) {
                    continue;
                }
                $groupedContentProductAbstractListResources[$cmsPageUuid][$contentProductAbstractListKey] = $contentProductAbstractListResources[$contentProductAbstractListKey];
            }
        }

        return $groupedContentProductAbstractListResources;
    }
}
