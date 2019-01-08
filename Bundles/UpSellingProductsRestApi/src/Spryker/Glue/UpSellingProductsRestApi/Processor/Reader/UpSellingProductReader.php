<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\UpSellingProductsRestApi\Processor\Reader;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\CartsRestApi\CartsRestApiConfig;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestLinkInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ProductsRestApi\ProductsRestApiConfig;
use Spryker\Glue\UpSellingProductsRestApi\Dependency\Client\UpSellingProductsRestApiToProductRelationStorageClientInterface;
use Spryker\Glue\UpSellingProductsRestApi\Processor\Mapper\UpSellingProductsResourceMapperInterface;
use Spryker\Glue\UpSellingProductsRestApi\UpSellingProductsRestApiConfig;
use Symfony\Component\HttpFoundation\Response;

abstract class UpSellingProductReader implements UpSellingProductReaderInterface
{
    protected const SELF_LINK_FORMAT = '%s/%s/%s';

    /**
     * @var \Spryker\Glue\UpSellingProductsRestApi\Processor\Reader\QuoteReaderInterface
     */
    protected $quoteReader;

    /**
     * @var \Spryker\Glue\UpSellingProductsRestApi\Dependency\Client\UpSellingProductsRestApiToProductRelationStorageClientInterface
     */
    protected $productRelationStorageClient;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\UpSellingProductsRestApi\Processor\Mapper\UpSellingProductsResourceMapperInterface
     */
    protected $upSellingProductsResourceMapper;

    /**
     * @param \Spryker\Glue\UpSellingProductsRestApi\Processor\Reader\QuoteReaderInterface $quoteReader
     * @param \Spryker\Glue\UpSellingProductsRestApi\Dependency\Client\UpSellingProductsRestApiToProductRelationStorageClientInterface $productRelationStorageClient
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\UpSellingProductsRestApi\Processor\Mapper\UpSellingProductsResourceMapperInterface $upSellingProductsResourceMapper
     */
    public function __construct(
        QuoteReaderInterface $quoteReader,
        UpSellingProductsRestApiToProductRelationStorageClientInterface $productRelationStorageClient,
        RestResourceBuilderInterface $restResourceBuilder,
        UpSellingProductsResourceMapperInterface $upSellingProductsResourceMapper
    ) {
        $this->quoteReader = $quoteReader;
        $this->productRelationStorageClient = $productRelationStorageClient;
        $this->restResourceBuilder = $restResourceBuilder;
        $this->upSellingProductsResourceMapper = $upSellingProductsResourceMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $localeName
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    protected function getUpSellingProductsResource(QuoteTransfer $quoteTransfer, string $localeName): RestResourceInterface
    {
        $upSellingProducts = $this->productRelationStorageClient
            ->findUpSellingProducts($quoteTransfer, $localeName);
        $restUpSellingProductsAttributesTransfers = $this->upSellingProductsResourceMapper
            ->mapUpSellingProductsTransferToRestUpSellingProductsAttributesTransfer($upSellingProducts);

        $uuid = $quoteTransfer->getUuid();
        $restResource = $this->restResourceBuilder->createRestResource(
            UpSellingProductsRestApiConfig::RESOURCE_UP_SELLING_PRODUCTS,
            $uuid,
            $restUpSellingProductsAttributesTransfers
        );
        $restResourceSelfLink = sprintf(
            static::SELF_LINK_FORMAT,
            ProductsRestApiConfig::RESOURCE_ABSTRACT_PRODUCTS,
            $uuid,
            UpSellingProductsRestApiConfig::RESOURCE_UP_SELLING_PRODUCTS
        );
        $restResource->addLink(RestLinkInterface::LINK_SELF, $restResourceSelfLink);

        return $restResource;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function readUpSellingProducts(RestRequestInterface $restRequest): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();
        $parentResource = $this->findParentResourceByType($restRequest);

        if (!$parentResource || !$parentResource->getId()) {
            return $restResponse->addError($this->createCartIdMissingError());
        }

        $quoteTransfer = $this->quoteReader->findQuoteByUuid($restRequest, $parentResource->getId());

        if (!$quoteTransfer) {
            return $restResponse->addError($this->createCartNotFoundError());
        }

        $restResource = $this->getUpSellingProductsResource(
            $quoteTransfer,
            $restRequest->getMetadata()->getLocale()
        );

        return $restResponse->addResource($restResource);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface|null
     */
    abstract protected function findParentResourceByType(RestRequestInterface $restRequest): ?RestResourceInterface;

    /**
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer
     */
    protected function createCartNotFoundError(): RestErrorMessageTransfer
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(CartsRestApiConfig::RESPONSE_CODE_CART_NOT_FOUND)
            ->setStatus(Response::HTTP_NOT_FOUND)
            ->setDetail(CartsRestApiConfig::EXCEPTION_MESSAGE_CART_WITH_ID_NOT_FOUND);

        return $restErrorTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer
     */
    protected function createCartIdMissingError(): RestErrorMessageTransfer
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(CartsRestApiConfig::RESPONSE_CODE_CART_ID_MISSING)
            ->setStatus(Response::HTTP_BAD_REQUEST)
            ->setDetail(CartsRestApiConfig::EXCEPTION_MESSAGE_CART_ID_MISSING);

        return $restErrorTransfer;
    }
}
