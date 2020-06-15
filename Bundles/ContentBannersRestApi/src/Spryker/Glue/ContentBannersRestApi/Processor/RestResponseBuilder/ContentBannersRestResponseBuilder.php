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
use Spryker\Glue\ContentBannersRestApi\Mapper\ContentBannerMapperInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Symfony\Component\HttpFoundation\Response;

class ContentBannersRestResponseBuilder implements ContentBannersRestResponseBuilderInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\ContentBannersRestApi\Mapper\ContentBannerMapperInterface
     */
    protected $contentBannerMapper;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\ContentBannersRestApi\Mapper\ContentBannerMapperInterface $contentBannerMapper
     */
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder,
        ContentBannerMapperInterface $contentBannerMapper
    ) {
        $this->restResourceBuilder = $restResourceBuilder;
        $this->contentBannerMapper = $contentBannerMapper;
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function addContentBannerIdNotSpecifiedError(): RestResponseInterface
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
    public function addContentBannerNotFoundError(): RestResponseInterface
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
    public function addContentTypeInvalidError(): RestResponseInterface
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
     * @param array $mappedContentTypeContextTransfers
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    public function createContentBannersRestResources(array $mappedContentTypeContextTransfers): array
    {
        $contentBannersRestResources = [];
//        foreach ($mappedContentTypeContextTransfers as $cmsPageUuid => $contentTypeContextTransfers) {
//            foreach ($contentTypeContextTransfers as $contentBannerKey => $contentTypeContextTransfer) {
//                $contentBannersRestResources[$cmsPageUuid] = $this->createContentBannersRestResource(
//                    $contentTypeContextTransfer,
//                    $contentBannerKey
//                );
//            }
//        }

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
        return $this->restResourceBuilder->createRestResource(
            ContentBannersRestApiConfig::RESOURCE_CONTENT_BANNERS,
            $contentBannerKey,
            $this->contentBannerMapper->mapBannerTypeTransferToRestContentBannerAttributes(
                $contentBannerTypeTransfer,
                new RestContentBannerAttributesTransfer()
            )
        );
    }
}
