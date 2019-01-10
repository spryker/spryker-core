<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\UpSellingProductsRestApi\Processor\UpSellingProduct;

use Spryker\Glue\CartsRestApi\CartsRestApiConfig;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\UpSellingProductsRestApi\Dependency\Client\UpSellingProductsRestApiToProductRelationStorageClientInterface;
use Spryker\Glue\UpSellingProductsRestApi\Dependency\Resource\UpSellingProductsRestApiToProductsRestApiResourceInterface;
use Spryker\Glue\UpSellingProductsRestApi\Processor\Quote\QuoteReaderInterface;

class UpSellingProductReader implements UpSellingProductReaderInterface
{
    /**
     * @var \Spryker\Glue\UpSellingProductsRestApi\Processor\Quote\QuoteReaderInterface
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
     * @var \Spryker\Glue\UpSellingProductsRestApi\Processor\UpSellingProduct\UpSellingProductRestResponseBuilderInterface
     */
    protected $upSellingProductRestResponseBuilder;

    /**
     * @param \Spryker\Glue\UpSellingProductsRestApi\Processor\Quote\QuoteReaderInterface $quoteReader
     * @param \Spryker\Glue\UpSellingProductsRestApi\Dependency\Client\UpSellingProductsRestApiToProductRelationStorageClientInterface $productRelationStorageClient
     * @param \Spryker\Glue\UpSellingProductsRestApi\Dependency\Resource\UpSellingProductsRestApiToProductsRestApiResourceInterface $productsRestApiResource
     * @param \Spryker\Glue\UpSellingProductsRestApi\Processor\UpSellingProduct\UpSellingProductRestResponseBuilderInterface $upSellingProductRestResponseBuilder
     */
    public function __construct(
        QuoteReaderInterface $quoteReader,
        UpSellingProductsRestApiToProductRelationStorageClientInterface $productRelationStorageClient,
        UpSellingProductsRestApiToProductsRestApiResourceInterface $productsRestApiResource,
        UpSellingProductRestResponseBuilderInterface $upSellingProductRestResponseBuilder
    ) {
        $this->quoteReader = $quoteReader;
        $this->productRelationStorageClient = $productRelationStorageClient;
        $this->productsRestApiResource = $productsRestApiResource;
        $this->upSellingProductRestResponseBuilder = $upSellingProductRestResponseBuilder;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function readUpSellingProducts(RestRequestInterface $restRequest): RestResponseInterface
    {
        $parentResource = $this->findParentResourceByType($restRequest);

        if (!$parentResource || !$parentResource->getId()) {
            return $this->upSellingProductRestResponseBuilder->createCartIdMissingError();
        }

        $quoteTransfer = $this->quoteReader->findQuoteByUuid($restRequest, $parentResource->getId());

        if (!$quoteTransfer) {
            return $this->upSellingProductRestResponseBuilder->createCartNotFoundError();
        }

        $upSellingProducts = $this->productRelationStorageClient
            ->findUpSellingProducts($quoteTransfer, $restRequest->getMetadata()->getLocale());

        return $this->upSellingProductRestResponseBuilder
            ->createAbstractProductsRestResponse($restRequest, $upSellingProducts);
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
}
