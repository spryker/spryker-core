<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ContentProductAbstractListsRestApi\Processor\Reader;

use Spryker\Glue\ContentProductAbstractListsRestApi\ContentProductAbstractListsRestApiConfig;
use Spryker\Glue\ContentProductAbstractListsRestApi\Dependency\Client\ContentProductAbstractListsRestApiToCmsStorageClientInterface;
use Spryker\Glue\ContentProductAbstractListsRestApi\Dependency\Client\ContentProductAbstractListsRestApiToContentProductClientInterface;
use Spryker\Glue\ContentProductAbstractListsRestApi\Dependency\Client\ContentProductAbstractListsRestApiToStoreClientInterface;
use Spryker\Glue\ContentProductAbstractListsRestApi\Processor\RestResponseBuilder\ContentProductAbstractListRestResponseBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Throwable;

class ContentProductAbstractListReader implements ContentProductAbstractListReaderInterface
{
    /**
     * @var \Spryker\Glue\ContentProductAbstractListsRestApi\Dependency\Client\ContentProductAbstractListsRestApiToContentProductClientInterface
     */
    protected $contentProductClient;

    /**
     * @var \Spryker\Glue\ContentProductAbstractListsRestApi\Processor\RestResponseBuilder\ContentProductAbstractListRestResponseBuilderInterface
     */
    protected $contentProductAbstractListRestResponseBuilder;

    /**
     * @var \Spryker\Glue\ContentProductAbstractListsRestApi\Dependency\Client\ContentProductAbstractListsRestApiToCmsStorageClientInterface
     */
    protected $cmsStorageClient;

    /**
     * @var \Spryker\Glue\ContentProductAbstractListsRestApi\Dependency\Client\ContentProductAbstractListsRestApiToStoreClientInterface
     */
    protected $storeClient;

    /**
     * @param \Spryker\Glue\ContentProductAbstractListsRestApi\Dependency\Client\ContentProductAbstractListsRestApiToContentProductClientInterface $contentProductClient
     * @param \Spryker\Glue\ContentProductAbstractListsRestApi\Processor\RestResponseBuilder\ContentProductAbstractListRestResponseBuilderInterface $contentProductAbstractListRestResponseBuilder
     * @param \Spryker\Glue\ContentProductAbstractListsRestApi\Dependency\Client\ContentProductAbstractListsRestApiToCmsStorageClientInterface $cmsStorageClient
     * @param \Spryker\Glue\ContentProductAbstractListsRestApi\Dependency\Client\ContentProductAbstractListsRestApiToStoreClientInterface $storeClient
     */
    public function __construct(
        ContentProductAbstractListsRestApiToContentProductClientInterface $contentProductClient,
        ContentProductAbstractListRestResponseBuilderInterface $contentProductAbstractListRestResponseBuilder,
        ContentProductAbstractListsRestApiToCmsStorageClientInterface $cmsStorageClient,
        ContentProductAbstractListsRestApiToStoreClientInterface $storeClient
    ) {
        $this->contentProductClient = $contentProductClient;
        $this->contentProductAbstractListRestResponseBuilder = $contentProductAbstractListRestResponseBuilder;
        $this->cmsStorageClient = $cmsStorageClient;
        $this->storeClient = $storeClient;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getContentProductAbstractListById(RestRequestInterface $restRequest): RestResponseInterface
    {
        $parentResource = $restRequest->findParentResourceByType(
            ContentProductAbstractListsRestApiConfig::RESOURCE_CONTENT_PRODUCT_ABSTRACT_LISTS
        );

        if (!$parentResource || !$parentResource->getId()) {
            return $this->contentProductAbstractListRestResponseBuilder->addContentItemIdNotSpecifiedError();
        }

        try {
            $contentProductAbstractListTypeTransfer = $this->contentProductClient->executeProductAbstractListTypeByKey(
                $parentResource->getId(),
                $restRequest->getMetadata()->getLocale()
            );
        } catch (Throwable $e) {
            return $this->contentProductAbstractListRestResponseBuilder->addContentTypeInvalidError();
        }

        if (!$contentProductAbstractListTypeTransfer) {
            return $this->contentProductAbstractListRestResponseBuilder->addContentItemtNotFoundError();
        }

        return $this->contentProductAbstractListRestResponseBuilder
            ->createContentProductAbstractListsRestResponse($contentProductAbstractListTypeTransfer, $restRequest);
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
        $cmsPageStorageTransfers = $this->cmsStorageClient->getCmsPageStorageByUuids(
            $cmsPageReferences,
            $restRequest->getMetadata()->getLocale(),
            $this->storeClient->getCurrentStore()->getName()
        );

        $groupedContentProductAbstractListKeys = [];
        $contentProductAbstractListKeys = [];
        foreach ($cmsPageStorageTransfers as $cmsPageStorageTransfer) {
            $contentWidgetParameterMap = $cmsPageStorageTransfer->getContentWidgetParameterMap();
            if (
                isset($contentWidgetParameterMap[ContentProductAbstractListsRestApiConfig::TWIG_FUNCTION_NAME])
                && !empty($contentWidgetParameterMap[ContentProductAbstractListsRestApiConfig::TWIG_FUNCTION_NAME])
            ) {
                $contentProductAbstractListKeys = array_merge($contentProductAbstractListKeys, $contentWidgetParameterMap[ContentProductAbstractListsRestApiConfig::TWIG_FUNCTION_NAME]);
                $groupedContentProductAbstractListKeys[$cmsPageStorageTransfer->getUuid()] = $contentWidgetParameterMap[ContentProductAbstractListsRestApiConfig::TWIG_FUNCTION_NAME];
            }
        }

        $contentProductAbstractListTypeTransfers = $this->contentProductClient->executeProductAbstractListTypeByKeys(
            $contentProductAbstractListKeys,
            $restRequest->getMetadata()->getLocale()
        );

        if (!$contentProductAbstractListTypeTransfers) {
            return [];
        }

        $mappedProductAbstractListTypeTransfers = [];
        foreach ($groupedContentProductAbstractListKeys as $cmsPageUuid => $contentProductAbstractListKeys) {
            foreach ($contentProductAbstractListKeys as $contentProductAbstractListKey => $contentProductAbstractListValue) {
                $mappedProductAbstractListTypeTransfers[$cmsPageUuid][$contentProductAbstractListKey] = $contentProductAbstractListTypeTransfers[$contentProductAbstractListValue];
            }
        }

        return $this->contentProductAbstractListRestResponseBuilder
            ->createContentProductAbstractListsRestResources($mappedProductAbstractListTypeTransfers, $restRequest);
    }
}
