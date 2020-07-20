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
     * @param string[] $cmsPageUuids
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[][]
     */
    public function getContentBannersResources(array $cmsPageUuids, RestRequestInterface $restRequest): array
    {
        $localeName = $restRequest->getMetadata()->getLocale();
        $cmsPageStorageTransfers = $this->cmsStorageClient->getCmsPageStorageByUuids(
            $cmsPageUuids,
            $localeName,
            $this->storeClient->getCurrentStore()->getName()
        );

        if (!$cmsPageStorageTransfers) {
            return [];
        }

        $groupedContentBannerKeys = $this->getGroupedContentBannerKeys($cmsPageStorageTransfers);

        if (!$groupedContentBannerKeys) {
            return [];
        }

        $contentBannerKeys = array_merge(...array_values($groupedContentBannerKeys));

        $contentBannerResources = $this->contentBannerRestApiResource->getContentBannersByKeys($contentBannerKeys, $localeName);
        if (!$contentBannerResources) {
            return [];
        }

        return $this->groupRestResourcesByCmsPageUuid($contentBannerResources, $groupedContentBannerKeys);
    }

    /**
     * @phpstan-return array<string, array<string, string>>
     *
     * @param \Generated\Shared\Transfer\CmsPageStorageTransfer[] $cmsPageStorageTransfers
     *
     * @return string[][]
     */
    protected function getGroupedContentBannerKeys(array $cmsPageStorageTransfers): array
    {
        $groupedContentBannerKeys = [];
        foreach ($cmsPageStorageTransfers as $cmsPageStorageTransfer) {
            $contentWidgetParameterMap = $cmsPageStorageTransfer->getContentWidgetParameterMap();
            if (empty($contentWidgetParameterMap[CmsPagesContentBannersResourceRelationshipConfig::TWIG_FUNCTION_NAME])) {
                continue;
            }
            $groupedContentBannerKeys[$cmsPageStorageTransfer->getUuid()]
                = $contentWidgetParameterMap[CmsPagesContentBannersResourceRelationshipConfig::TWIG_FUNCTION_NAME];
        }

        return $groupedContentBannerKeys;
    }

    /**
     * @phpstan-param array<string, \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface> $contentBannerResources
     * @phpstan-param array<string, array<string, string>> $groupedContentBannerKeys
     *
     * @phpstan-return array<string, array<string, \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface>>
     *
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $contentBannerResources
     * @param string[][] $groupedContentBannerKeys
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[][]
     */
    protected function groupRestResourcesByCmsPageUuid(array $contentBannerResources, array $groupedContentBannerKeys): array
    {
        $groupedContentBannerResources = [];
        foreach ($groupedContentBannerKeys as $cmsPageUuid => $contentBannerKeys) {
            foreach ($contentBannerKeys as $contentBannerKey) {
                if (!isset($contentBannerResources[$contentBannerKey])) {
                    continue;
                }
                $groupedContentBannerResources[$cmsPageUuid][$contentBannerKey] = $contentBannerResources[$contentBannerKey];
            }
        }

        return $groupedContentBannerResources;
    }
}
