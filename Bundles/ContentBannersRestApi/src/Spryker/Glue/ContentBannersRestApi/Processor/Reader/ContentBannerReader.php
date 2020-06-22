<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ContentBannersRestApi\Processor\Reader;

use Spryker\Client\ContentBanner\Exception\MissingBannerTermException;
use Spryker\Glue\ContentBannersRestApi\Dependency\Client\ContentBannersRestApiToContentBannerClientInterface;
use Spryker\Glue\ContentBannersRestApi\Processor\RestResponseBuilder\ContentBannerRestResponseBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class ContentBannerReader implements ContentBannerReaderInterface
{
    /**
     * @var \Spryker\Glue\ContentBannersRestApi\Dependency\Client\ContentBannersRestApiToContentBannerClientInterface
     */
    protected $contentBannerClient;

    /**
     * @var \Spryker\Glue\ContentBannersRestApi\Processor\RestResponseBuilder\ContentBannerRestResponseBuilderInterface
     */
    protected $contentBannerRestResponseBuilder;

    /**
     * @param \Spryker\Glue\ContentBannersRestApi\Dependency\Client\ContentBannersRestApiToContentBannerClientInterface $contentBannerClient
     * @param \Spryker\Glue\ContentBannersRestApi\Processor\RestResponseBuilder\ContentBannerRestResponseBuilderInterface $contentBannerRestResponseBuilder
     */
    public function __construct(
        ContentBannersRestApiToContentBannerClientInterface $contentBannerClient,
        ContentBannerRestResponseBuilderInterface $contentBannerRestResponseBuilder
    ) {
        $this->contentBannerClient = $contentBannerClient;
        $this->contentBannerRestResponseBuilder = $contentBannerRestResponseBuilder;
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
            return $this->contentBannerRestResponseBuilder->addContentBannerIdNotSpecifiedError();
        }

        try {
            $contentBannerTypeTransfer = $this->contentBannerClient->executeBannerTypeByKey(
                $contentBannerKey,
                $restRequest->getMetadata()->getLocale()
            );
        } catch (MissingBannerTermException $bannerTermException) {
            return $this->contentBannerRestResponseBuilder->addContentTypeInvalidError();
        }

        if (!$contentBannerTypeTransfer) {
            return $this->contentBannerRestResponseBuilder->addContentBannerNotFoundError();
        }

        return $this->contentBannerRestResponseBuilder->createContentBannersRestResponse($contentBannerTypeTransfer, $contentBannerKey);
    }

    /**
     * @phpstan-param array<string, string> $contentBannerKeys
     *
     * @phpstan-return array<string, \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface>
     *
     * @param string[] $contentBannerKeys
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    public function getContentBannersResources(array $contentBannerKeys, RestRequestInterface $restRequest): array
    {
        $contentBannerTypeTransfers = $this->contentBannerClient->executeBannerTypeByKeys(
            $contentBannerKeys,
            $restRequest->getMetadata()->getLocale()
        );

        if (!$contentBannerTypeTransfers) {
            return [];
        }

        return $this->contentBannerRestResponseBuilder
            ->createContentBannersRestResources($contentBannerTypeTransfers);
    }
}
