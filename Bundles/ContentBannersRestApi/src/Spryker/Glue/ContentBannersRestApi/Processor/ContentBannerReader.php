<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ContentBannersRestApi\Processor;

use Generated\Shared\Transfer\ContentTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\ContentBannersRestApi\ContentBannersRestApiConfig;
use Spryker\Glue\ContentBannersRestApi\Dependency\Client\ContentBannersRestApiToContentStorageClientInterface;
use Spryker\Glue\ContentBannersRestApi\Mapper\ContentBannerMapperInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Symfony\Component\HttpFoundation\Response;

class ContentBannerReader implements ContentBannerReaderInterface
{
    //@TODO Remove after content banner release
    protected const CONTENT_TYPE_BANNER = 'banner';

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\ContentBannersRestApi\Mapper\ContentBannerMapperInterface
     */
    protected $contentBannerMapper;

    /**
     * @var \Spryker\Glue\ContentBannersRestApi\Dependency\Client\ContentBannersRestApiToContentStorageClientInterface
     */
    protected $contentStorageClient;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\ContentBannersRestApi\Mapper\ContentBannerMapperInterface $contentBannerMapper
     * @param \Spryker\Glue\ContentBannersRestApi\Dependency\Client\ContentBannersRestApiToContentStorageClientInterface $contentStorageClient
     */
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder,
        ContentBannerMapperInterface $contentBannerMapper,
        ContentBannersRestApiToContentStorageClientInterface $contentStorageClient
    ) {
        $this->restResourceBuilder = $restResourceBuilder;
        $this->contentBannerMapper = $contentBannerMapper;
        $this->contentStorageClient = $contentStorageClient;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getContentBannerById(RestRequestInterface $restRequest): RestResponseInterface
    {
        $response = $this->restResourceBuilder->createRestResponse();

        $idContentBanner = $restRequest->getResource()->getId();
        if (empty($idContentBanner)) {
            return $this->addContentBannerIdNotSpecifiedError($response);
        }

        //@TODO Uncomment when content feature will be released
//        $contentTransfer = $this->contentStorageClient->findContentStorageData(
//            $idContentBanner,
//            $restRequest->getMetadata()->getLocale()
//        );

        //@TODO Delete when content feature will be released
        $contentTransfer = (new ContentTransfer())->setContentTypeKey(static::CONTENT_TYPE_BANNER);

        if (!$contentTransfer) {
            return $this->addContentBannerNotFoundError($response);
        }

        if ($contentTransfer->getContentTypeKey() !== static::CONTENT_TYPE_BANNER) {
            return $this->addContentTypeInvalidError($response);
        }

        $restContentBannerAttributes = $this->contentBannerMapper->mapContentTransferToRestContentBannerAttributes($contentTransfer);

        $restResource = $this->restResourceBuilder->createRestResource(
            ContentBannersRestApiConfig::RESOURCE_CONTENT_BANNERS,
            $idContentBanner,
            $restContentBannerAttributes
        );

        return $response->addResource($restResource);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $response
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function addContentBannerIdNotSpecifiedError(RestResponseInterface $response): RestResponseInterface
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(ContentBannersRestApiConfig::RESPONSE_CODE_CONTENT_ID_IS_MISSING)
            ->setStatus(Response::HTTP_BAD_REQUEST)
            ->setDetail(ContentBannersRestApiConfig::RESPONSE_DETAILS_CONTENT_ID_IS_MISSING);

        return $response->addError($restErrorTransfer);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $response
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function addContentBannerNotFoundError(RestResponseInterface $response): RestResponseInterface
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(ContentBannersRestApiConfig::RESPONSE_CODE_CONTENT_NOT_FOUND)
            ->setStatus(Response::HTTP_NOT_FOUND)
            ->setDetail(ContentBannersRestApiConfig::RESPONSE_DETAILS_CONTENT_NOT_FOUND);

        return $response->addError($restErrorTransfer);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $response
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function addContentTypeInvalidError(RestResponseInterface $response): RestResponseInterface
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(ContentBannersRestApiConfig::RESPONSE_CODE_CONTENT_TYPE_INVALID)
            ->setStatus(Response::HTTP_NOT_FOUND)
            ->setDetail(ContentBannersRestApiConfig::RESPONSE_DETAILS_CONTENT_TYPE_INVALID);

        return $response->addError($restErrorTransfer);
    }
}
