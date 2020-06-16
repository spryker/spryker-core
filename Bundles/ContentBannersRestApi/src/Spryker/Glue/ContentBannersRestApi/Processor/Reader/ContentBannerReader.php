<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ContentBannersRestApi\Processor\Reader;

use Spryker\Client\ContentBanner\Exception\MissingBannerTermException;
use Spryker\Glue\ContentBannersRestApi\ContentBannersRestApiConfig;
use Spryker\Glue\ContentBannersRestApi\Dependency\Client\ContentBannersRestApiToCmsStorageClientInterface;
use Spryker\Glue\ContentBannersRestApi\Dependency\Client\ContentBannersRestApiToContentBannerClientInterface;
use Spryker\Glue\ContentBannersRestApi\Processor\RestResponseBuilder\ContentBannersRestResponseBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class ContentBannerReader implements ContentBannerReaderInterface
{
    protected const MAPPING_TYPE_UUID = 'uuid';
    /**
     * @uses \Spryker\Shared\ContentBanner\ContentBannerConfig::CONTENT_TYPE_BANNER
     */
    protected const CONTENT_TYPE_BANNER = 'Banner';

    /**
     * @var \Spryker\Glue\ContentBannersRestApi\Dependency\Client\ContentBannersRestApiToContentBannerClientInterface
     */
    protected $contentBannerClient;

    /**
     * @var \Spryker\Glue\ContentBannersRestApi\Processor\RestResponseBuilder\ContentBannersRestResponseBuilderInterface
     */
    protected $contentBannersRestResponseBuilder;

    /**
     * @var \Spryker\Glue\ContentBannersRestApi\Dependency\Client\ContentBannersRestApiToCmsStorageClientInterface
     */
    protected $cmsStorageClient;

    /**
     * @param \Spryker\Glue\ContentBannersRestApi\Dependency\Client\ContentBannersRestApiToContentBannerClientInterface $contentBannerClient
     * @param \Spryker\Glue\ContentBannersRestApi\Processor\RestResponseBuilder\ContentBannersRestResponseBuilderInterface $contentBannersRestResponseBuilder
     * @param \Spryker\Glue\ContentBannersRestApi\Dependency\Client\ContentBannersRestApiToCmsStorageClientInterface $cmsStorageClient
     */
    public function __construct(
        ContentBannersRestApiToContentBannerClientInterface $contentBannerClient,
        ContentBannersRestResponseBuilderInterface $contentBannersRestResponseBuilder,
        ContentBannersRestApiToCmsStorageClientInterface $cmsStorageClient
    ) {
        $this->contentBannerClient = $contentBannerClient;
        $this->contentBannersRestResponseBuilder = $contentBannersRestResponseBuilder;
        $this->cmsStorageClient = $cmsStorageClient;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getContentBannerById(RestRequestInterface $restRequest): RestResponseInterface
    {
        $contentBannerKey = $restRequest->getResource()->getId();
        if (!$contentBannerKey) {
            return $this->contentBannersRestResponseBuilder->addContentBannerIdNotSpecifiedError();
        }

        try {
            $contentBannerTypeTransfer = $this->contentBannerClient->executeBannerTypeByKey(
                $contentBannerKey,
                $restRequest->getMetadata()->getLocale()
            );
        } catch (MissingBannerTermException $bannerTermException) {
            return $this->contentBannersRestResponseBuilder->addContentTypeInvalidError();
        }

        if (!$contentBannerTypeTransfer) {
            return $this->contentBannersRestResponseBuilder->addContentBannerNotFoundError();
        }

        return $this->contentBannersRestResponseBuilder->createContentBannersRestResponse($contentBannerTypeTransfer, $contentBannerKey);
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
            APPLICATION_STORE
        );

        $groupedContentBannerKeys = [];
        $contentBannerKeys = [];
        foreach ($cmsPageStorageTransfers as $cmsPageStorageTransfer) {
            $contentWidgetParameterMap = $cmsPageStorageTransfer->getContentWidgetParameterMap();
            if (
                isset($contentWidgetParameterMap[ContentBannersRestApiConfig::TWIG_FUNCTION_NAME])
                && !empty($contentWidgetParameterMap[ContentBannersRestApiConfig::TWIG_FUNCTION_NAME])
            ) {
                $contentBannerKeys = array_merge($contentBannerKeys, $contentWidgetParameterMap[ContentBannersRestApiConfig::TWIG_FUNCTION_NAME]);
                $groupedContentBannerKeys[$cmsPageStorageTransfer->getUuid()] = $contentWidgetParameterMap[ContentBannersRestApiConfig::TWIG_FUNCTION_NAME];
            }
        }

        $contentBannerTypeTransfers = $this->contentBannerClient->executeBannerTypeByKeys(
            $contentBannerKeys,
            $restRequest->getMetadata()->getLocale()
        );

        $mappedContentBannerTypeTransfers = [];
        foreach ($groupedContentBannerKeys as $cmsPageUuid => $contentBannerKeys) {
            foreach ($contentBannerKeys as $contentBannerKey => $contentBannerValue) {
                $mappedContentBannerTypeTransfers[$cmsPageUuid][$contentBannerKey] = $contentBannerTypeTransfers[$contentBannerValue];
            }
        }

        return $this->contentBannersRestResponseBuilder
            ->createContentBannersRestResources($mappedContentBannerTypeTransfers);
    }
}
