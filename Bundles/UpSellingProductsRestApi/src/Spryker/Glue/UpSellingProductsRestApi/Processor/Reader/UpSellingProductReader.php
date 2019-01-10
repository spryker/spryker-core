<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\UpSellingProductsRestApi\Processor\Reader;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\CartsRestApi\CartsRestApiConfig;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\UpSellingProductsRestApi\Dependency\Client\UpSellingProductsRestApiToProductRelationStorageClientInterface;
use Spryker\Glue\UpSellingProductsRestApi\Dependency\Resource\UpSellingProductsRestApiToProductsRestApiResourceInterface;
use Symfony\Component\HttpFoundation\Response;

class UpSellingProductReader implements UpSellingProductReaderInterface
{
    /**
     * @var \Spryker\Glue\UpSellingProductsRestApi\Processor\Reader\QuoteReaderInterface
     */
    protected $quoteReader;

    /**
     * @var \Spryker\Glue\UpSellingProductsRestApi\Dependency\Client\UpSellingProductsRestApiToProductRelationStorageClientInterface
     */
    protected $productRelationStorageClient;

    /**
     * @var \Spryker\Glue\UpSellingProductsRestApi\Dependency\Resource\UpSellingProductsRestApiToProductsRestApiResourceInterface
     */
    protected $productsRestApiResource;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @param \Spryker\Glue\UpSellingProductsRestApi\Processor\Reader\QuoteReaderInterface $quoteReader
     * @param \Spryker\Glue\UpSellingProductsRestApi\Dependency\Client\UpSellingProductsRestApiToProductRelationStorageClientInterface $productRelationStorageClient
     * @param \Spryker\Glue\UpSellingProductsRestApi\Dependency\Resource\UpSellingProductsRestApiToProductsRestApiResourceInterface $productsRestApiResource
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     */
    public function __construct(
        QuoteReaderInterface $quoteReader,
        UpSellingProductsRestApiToProductRelationStorageClientInterface $productRelationStorageClient,
        UpSellingProductsRestApiToProductsRestApiResourceInterface $productsRestApiResource,
        RestResourceBuilderInterface $restResourceBuilder
    ) {
        $this->quoteReader = $quoteReader;
        $this->productRelationStorageClient = $productRelationStorageClient;
        $this->productsRestApiResource = $productsRestApiResource;
        $this->restResourceBuilder = $restResourceBuilder;
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

        $upSellingProducts = $this->productRelationStorageClient
            ->findUpSellingProducts($quoteTransfer, $restRequest->getMetadata()->getLocale());

        $this->addAbstractProductResources($restRequest, $restResponse, $upSellingProducts);

        return $restResponse;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     * @param \Generated\Shared\Transfer\ProductViewTransfer[] $productViewTransfers
     *
     * @return void
     */
    protected function addAbstractProductResources(
        RestRequestInterface $restRequest,
        RestResponseInterface $restResponse,
        array $productViewTransfers
    ): void {
        foreach ($productViewTransfers as $productViewTransfer) {
            $abstractProductResource = $this->productsRestApiResource->findProductAbstractBySku(
                $productViewTransfer->getSku(),
                $restRequest
            );

            if ($abstractProductResource) {
                $restResponse->addResource($abstractProductResource);
            }
        }
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface|null
     */
    protected function findParentResourceByType(RestRequestInterface $restRequest): ?RestResourceInterface
    {
        return $restRequest->findParentResourceByType(CartsRestApiConfig::RESOURCE_CARTS)
            ?? $restRequest->findParentResourceByType(CartsRestApiConfig::RESOURCE_GUEST_CARTS);
    }

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
