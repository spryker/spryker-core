<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ContentBannersRestApi\Processor\RestResponseBuilder;

use Generated\Shared\Transfer\ContentBannerTypeTransfer;
use Generated\Shared\Transfer\RestContentBannerAttributesTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\ContentBannersRestApi\ContentBannersRestApiConfig;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Symfony\Component\HttpFoundation\Response;

class ContentBannerRestResponseBuilder implements ContentBannerRestResponseBuilderInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     */
    public function __construct(RestResourceBuilderInterface $restResourceBuilder)
    {
        $this->restResourceBuilder = $restResourceBuilder;
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createContentBannerIdNotSpecifiedErrorResponse(): RestResponseInterface
    {
        return $this->restResourceBuilder
            ->createRestResponse()
            ->addError(
                (new RestErrorMessageTransfer())
                    ->setCode(ContentBannersRestApiConfig::RESPONSE_CODE_CONTENT_KEY_IS_MISSING)
                    ->setStatus(Response::HTTP_BAD_REQUEST)
                    ->setDetail(ContentBannersRestApiConfig::RESPONSE_DETAILS_CONTENT_KEY_IS_MISSING)
            );
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createContentBannerNotFoundErrorResponse(): RestResponseInterface
    {
        return $this->restResourceBuilder
            ->createRestResponse()
            ->addError(
                (new RestErrorMessageTransfer())
                    ->setCode(ContentBannersRestApiConfig::RESPONSE_CODE_CONTENT_NOT_FOUND)
                    ->setStatus(Response::HTTP_NOT_FOUND)
                    ->setDetail(ContentBannersRestApiConfig::RESPONSE_DETAILS_CONTENT_NOT_FOUND)
            );
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createContentTypeInvalidErrorResponse(): RestResponseInterface
    {
        return $this->restResourceBuilder
            ->createRestResponse()
            ->addError(
                (new RestErrorMessageTransfer())
                    ->setCode(ContentBannersRestApiConfig::RESPONSE_CODE_CONTENT_TYPE_INVALID)
                    ->setStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
                    ->setDetail(ContentBannersRestApiConfig::RESPONSE_DETAILS_CONTENT_TYPE_INVALID)
            );
    }

    /**
     * @param \Generated\Shared\Transfer\ContentBannerTypeTransfer $contentBannerTypeTransfer
     * @param string $contentBannerKey
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createContentBannersRestResponse(
        ContentBannerTypeTransfer $contentBannerTypeTransfer,
        string $contentBannerKey
    ): RestResponseInterface {
        $contentBannerRestResource = $this->createContentBannersRestResource($contentBannerTypeTransfer, $contentBannerKey);

        return $this->restResourceBuilder
            ->createRestResponse()
            ->addResource($contentBannerRestResource);
    }

    /**
     * @phpstan-param array<string, \Generated\Shared\Transfer\ContentBannerTypeTransfer> $contentBannerTypeTransfers
     *
     * @phpstan-return array<string, \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface>
     *
     * @param \Generated\Shared\Transfer\ContentBannerTypeTransfer[] $contentBannerTypeTransfers
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    public function createContentBannersRestResources(array $contentBannerTypeTransfers): array
    {
        $contentBannersRestResources = [];
        foreach ($contentBannerTypeTransfers as $contentBannerKey => $contentBannerTypeTransfer) {
            $contentBannersRestResources[$contentBannerKey] = $this->createContentBannersRestResource(
                $contentBannerTypeTransfer,
                $contentBannerKey
            );
        }

        return $contentBannersRestResources;
    }

    /**
     * @param \Generated\Shared\Transfer\ContentBannerTypeTransfer $contentBannerTypeTransfer
     * @param string $contentBannerKey
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    protected function createContentBannersRestResource(
        ContentBannerTypeTransfer $contentBannerTypeTransfer,
        string $contentBannerKey
    ): RestResourceInterface {
        $restContentBannerAttributesTransfer = (new RestContentBannerAttributesTransfer())
            ->fromArray($contentBannerTypeTransfer->modifiedToArray(), true);

        return $this->restResourceBuilder->createRestResource(
            ContentBannersRestApiConfig::RESOURCE_CONTENT_BANNERS,
            $contentBannerKey,
            $restContentBannerAttributesTransfer
        );
    }
}
