<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ContentProductAbstractListsRestApi\Processor\Reader;

use Spryker\Glue\ContentProductAbstractListsRestApi\ContentProductAbstractListsRestApiConfig;
use Spryker\Glue\ContentProductAbstractListsRestApi\Dependency\Client\ContentProductAbstractListsRestApiToContentProductClientInterface;
use Spryker\Glue\ContentProductAbstractListsRestApi\Processor\RestResponseBuilder\ContentProductAbstractListRestResponseBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Throwable;

class ContentProductAbstractListReader implements ContentProductAbstractListReaderInterface
{
    /**
     * @uses \Spryker\Shared\ContentProduct\ContentProductConfig::CONTENT_TYPE_PRODUCT_ABSTRACT_LIST
     */
    protected const CONTENT_TYPE_PRODUCT_ABSTRACT_LIST = 'Abstract Product List';

    /**
     * @var \Spryker\Glue\ContentProductAbstractListsRestApi\Dependency\Client\ContentProductAbstractListsRestApiToContentProductClientInterface
     */
    protected $contentProductClient;

    /**
     * @var \Spryker\Glue\ContentProductAbstractListsRestApi\Processor\RestResponseBuilder\ContentProductAbstractListRestResponseBuilderInterface
     */
    protected $contentProductAbstractListRestResponseBuilder;

    /**
     * @param \Spryker\Glue\ContentProductAbstractListsRestApi\Dependency\Client\ContentProductAbstractListsRestApiToContentProductClientInterface $contentProductClient
     * @param \Spryker\Glue\ContentProductAbstractListsRestApi\Processor\RestResponseBuilder\ContentProductAbstractListRestResponseBuilderInterface $contentProductAbstractListRestResponseBuilder
     */
    public function __construct(
        ContentProductAbstractListsRestApiToContentProductClientInterface $contentProductClient,
        ContentProductAbstractListRestResponseBuilderInterface $contentProductAbstractListRestResponseBuilder
    ) {
        $this->contentProductClient = $contentProductClient;
        $this->contentProductAbstractListRestResponseBuilder = $contentProductAbstractListRestResponseBuilder;
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
     * @param string[] $cmsPageReferences
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return array[]
     */
    public function getContentProductAbstractListsResources(array $cmsPageReferences, RestRequestInterface $restRequest): array
    {
//        $cmsPageStorageTransfers = $this->cmsStorageClient->getCmsPageStorageByUuids(
//            $cmsPageReferences,
//            $restRequest->getMetadata()->getLocale(),
//            APPLICATION_STORE
//        );
//
//        $groupedContentBannerKeys = [];
//        $contentBannerKeys = [];
//        foreach ($cmsPageStorageTransfers as $cmsPageStorageTransfer) {
//            $contentWidgetParameterMap = $cmsPageStorageTransfer->getContentWidgetParameterMap();
//            if (
//                isset($contentWidgetParameterMap[ContentBannersRestApiConfig::TWIG_FUNCTION_NAME])
//                && !empty($contentWidgetParameterMap[ContentBannersRestApiConfig::TWIG_FUNCTION_NAME])
//            ) {
//                $contentBannerKeys = array_merge($contentBannerKeys, $contentWidgetParameterMap[ContentBannersRestApiConfig::TWIG_FUNCTION_NAME]);
//                $groupedContentBannerKeys[$cmsPageStorageTransfer->getUuid()] = $contentWidgetParameterMap[ContentBannersRestApiConfig::TWIG_FUNCTION_NAME];
//            }
//        }
//
//        $contentBannerTypeTransfers = $this->contentBannerClient->executeBannerTypeByKeys(
//            $contentBannerKeys,
//            $restRequest->getMetadata()->getLocale()
//        );
//
//        $mappedContentBannerTypeTransfers = [];
//        foreach ($groupedContentBannerKeys as $cmsPageUuid => $contentBannerKeys) {
//            foreach ($contentBannerKeys as $contentBannerKey => $contentBannerValue) {
//                $mappedContentBannerTypeTransfers[$cmsPageUuid][$contentBannerKey] = $contentBannerTypeTransfers[$contentBannerValue];
//            }
//        }
        $mappedProductAbstractListTypeTransfers = [];

        return $this->contentProductAbstractListRestResponseBuilder
            ->createContentProductAbstractListsRestResources($mappedProductAbstractListTypeTransfers, $restRequest);
    }
}
