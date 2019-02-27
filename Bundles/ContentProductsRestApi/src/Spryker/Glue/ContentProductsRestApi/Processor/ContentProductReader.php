<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ContentProductsRestApi\Processor;

use Generated\Shared\Transfer\RestContentAbstractProductListAttributesTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\ContentProductsRestApi\ContentProductsRestApiConfig;
use Spryker\Glue\ContentProductsRestApi\Dependency\Client\ContentProductsRestApiToContentStorageClientInterface;
use Spryker\Glue\ContentProductsRestApi\Mapper\ContentAbstractProductMapperInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Symfony\Component\HttpFoundation\Response;

class ContentProductReader implements ContentProductReaderInterface
{
    /** @uses \Spryker\Shared\ContentProduct\ContentProductConfig::CONTENT_TERM_PRODUCT_ABSTRACT_LIST */
    protected const CONTENT_TYPE_PRODUCT = 'Abstract Product List';

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\ContentProductsRestApi\Dependency\Client\ContentProductsRestApiToContentStorageClientInterface
     */
    protected $contentStorageClient;

    /**
     * @var \Spryker\Glue\ContentProductsRestApi\Mapper\ContentAbstractProductMapperInterface
     */
    protected $contentProductMapper;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\ContentProductsRestApi\Dependency\Client\ContentProductsRestApiToContentStorageClientInterface $contentStorageClient
     * @param \Spryker\Glue\ContentProductsRestApi\Mapper\ContentAbstractProductMapperInterface $contentProductMapper
     */
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder,
        ContentProductsRestApiToContentStorageClientInterface $contentStorageClient,
        ContentAbstractProductMapperInterface $contentProductMapper
    ) {
        $this->restResourceBuilder = $restResourceBuilder;
        $this->contentStorageClient = $contentStorageClient;
        $this->contentProductMapper = $contentProductMapper;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getContentProductById(RestRequestInterface $restRequest): RestResponseInterface
    {
        $response = $this->restResourceBuilder->createRestResponse();
        $idContentProduct = $restRequest->getResource()->getId();

        if (!$idContentProduct) {
            return $this->addContentProductIdNotSpecifiedError($response);
        }

        $executedContentStorageTransfer = $this->contentStorageClient->findContentById(
            (int)$idContentProduct,
            $restRequest->getMetadata()->getLocale()
        );

        if (!$executedContentStorageTransfer) {
            return $this->addContentProductNotFoundError($response);
        }

        if ($executedContentStorageTransfer->getType() !== static::CONTENT_TYPE_PRODUCT) {
            return $this->addContentTypeInvalidError($response);
        }

        $restContentAbstractProductAttributes = $this->contentProductMapper
            ->mapExecutedContentStorageTransferToRestContentAbstractProductListAttributes(
                $executedContentStorageTransfer,
                new RestContentAbstractProductListAttributesTransfer()
            );

        $restResource = $this->restResourceBuilder->createRestResource(
            ContentProductsRestApiConfig::RESOURCE_CONTENT_PRODUCTS,
            $restRequest->getResource()->getId(),
            $restContentAbstractProductAttributes
        );

        return $response->addResource($restResource);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $response
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function addContentProductIdNotSpecifiedError(RestResponseInterface $response): RestResponseInterface
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(ContentProductsRestApiConfig::RESPONSE_CODE_CONTENT_ID_IS_MISSING)
            ->setStatus(Response::HTTP_BAD_REQUEST)
            ->setDetail(ContentProductsRestApiConfig::RESPONSE_DETAILS_CONTENT_ID_IS_MISSING);
        return $response->addError($restErrorTransfer);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $response
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function addContentProductNotFoundError(RestResponseInterface $response): RestResponseInterface
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(ContentProductsRestApiConfig::RESPONSE_CODE_CONTENT_NOT_FOUND)
            ->setStatus(Response::HTTP_NOT_FOUND)
            ->setDetail(ContentProductsRestApiConfig::RESPONSE_DETAILS_CONTENT_NOT_FOUND);
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
            ->setCode(ContentProductsRestApiConfig::RESPONSE_CODE_CONTENT_TYPE_INVALID)
            ->setStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->setDetail(ContentProductsRestApiConfig::RESPONSE_DETAILS_CONTENT_TYPE_INVALID);
        return $response->addError($restErrorTransfer);
    }
}
