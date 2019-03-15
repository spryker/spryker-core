<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ContentProductAbstractListsRestApi\Processor;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\ContentProductAbstractListsRestApi\ContentProductAbstractListsRestApiConfig;
use Spryker\Glue\ContentProductAbstractListsRestApi\Dependency\Client\ContentProductAbstractListsRestApiToContentStorageClientInterface;
use Spryker\Glue\ContentProductAbstractListsRestApi\Dependency\Resource\ContentProductAbstractListsRestApiToProductsRestApiResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Symfony\Component\HttpFoundation\Response;

class ContentProductAbstractListReader implements ContentProductAbstractListReaderInterface
{
    /**
     * @uses /Spryker\Shared\ContentProduct/ContentProductConfig::CONTENT_TYPE_PRODUCT_ABSTRACT_LIST
     */
    protected const CONTENT_TYPE_PRODUCT_ABSTRACT_LIST = 'Abstract Product List';

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\ContentProductAbstractListsRestApi\Dependency\Client\ContentProductAbstractListsRestApiToContentStorageClientInterface
     */
    protected $contentStorageClient;

    /**
     * @var \Spryker\Glue\ContentProductAbstractListsRestApi\Dependency\Resource\ContentProductAbstractListsRestApiToProductsRestApiResourceInterface
     */
    protected $productsRestApiResource;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\ContentProductAbstractListsRestApi\Dependency\Client\ContentProductAbstractListsRestApiToContentStorageClientInterface $contentStorageClient
     * @param \Spryker\Glue\ContentProductAbstractListsRestApi\Dependency\Resource\ContentProductAbstractListsRestApiToProductsRestApiResourceInterface $productsRestApiResource
     */
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder,
        ContentProductAbstractListsRestApiToContentStorageClientInterface $contentStorageClient,
        ContentProductAbstractListsRestApiToProductsRestApiResourceInterface $productsRestApiResource
    ) {
        $this->restResourceBuilder = $restResourceBuilder;
        $this->contentStorageClient = $contentStorageClient;
        $this->productsRestApiResource = $productsRestApiResource;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getContentItemById(RestRequestInterface $restRequest): RestResponseInterface
    {
        $parentResource = $restRequest->findParentResourceByType(
            ContentProductAbstractListsRestApiConfig::RESOURCE_CONTENT_PRODUCTS
        );
        $restResponse = $this->restResourceBuilder->createRestResponse();

        if (!$parentResource || !$parentResource->getId()) {
            return $this->addContentProductIdNotSpecifiedError($restResponse);
        }

        $idContentItem = $parentResource->getId();

        $executedContentStorageTransfer = $this->contentStorageClient->findContentById(
            (int)$idContentItem,
            $restRequest->getMetadata()->getLocale()
        );

        if (!$executedContentStorageTransfer) {
            return $this->addContentProductNotFoundError($restResponse);
        }

        if ($executedContentStorageTransfer->getType() !== static::CONTENT_TYPE_PRODUCT_ABSTRACT_LIST) {
            return $this->addContentTypeInvalidError($restResponse);
        }

        $idProductAbstractList = $executedContentStorageTransfer->getContent();

        foreach ($idProductAbstractList as $idProductAbstract) {
            $abstractProductResource = $this->productsRestApiResource->findProductAbstractById($idProductAbstract, $restRequest);

            $restResponse->addResource($abstractProductResource);
        }

        return $restResponse;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $response
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function addContentProductIdNotSpecifiedError(RestResponseInterface $response): RestResponseInterface
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(ContentProductAbstractListsRestApiConfig::RESPONSE_CODE_CONTENT_ID_IS_MISSING)
            ->setStatus(Response::HTTP_BAD_REQUEST)
            ->setDetail(ContentProductAbstractListsRestApiConfig::RESPONSE_DETAILS_CONTENT_ID_IS_MISSING);
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
            ->setCode(ContentProductAbstractListsRestApiConfig::RESPONSE_CODE_CONTENT_NOT_FOUND)
            ->setStatus(Response::HTTP_NOT_FOUND)
            ->setDetail(ContentProductAbstractListsRestApiConfig::RESPONSE_DETAILS_CONTENT_NOT_FOUND);
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
            ->setCode(ContentProductAbstractListsRestApiConfig::RESPONSE_CODE_CONTENT_TYPE_INVALID)
            ->setStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->setDetail(ContentProductAbstractListsRestApiConfig::RESPONSE_DETAILS_CONTENT_TYPE_INVALID);
        return $response->addError($restErrorTransfer);
    }
}
