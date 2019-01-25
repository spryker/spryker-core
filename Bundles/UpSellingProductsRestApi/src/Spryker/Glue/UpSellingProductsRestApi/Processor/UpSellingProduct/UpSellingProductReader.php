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
use Spryker\Glue\UpSellingProductsRestApi\Processor\Quote\QuoteReaderInterface;
use Spryker\Glue\UpSellingProductsRestApi\Processor\RestResponseBuilder\UpSellingProductRestResponseBuilderInterface;

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
     * @var \Spryker\Glue\UpSellingProductsRestApi\Processor\RestResponseBuilder\UpSellingProductRestResponseBuilderInterface
     */
    protected $upSellingProductRestResponseBuilder;

    /**
     * @param \Spryker\Glue\UpSellingProductsRestApi\Processor\Quote\QuoteReaderInterface $quoteReader
     * @param \Spryker\Glue\UpSellingProductsRestApi\Dependency\Client\UpSellingProductsRestApiToProductRelationStorageClientInterface $productRelationStorageClient
     * @param \Spryker\Glue\UpSellingProductsRestApi\Processor\RestResponseBuilder\UpSellingProductRestResponseBuilderInterface $upSellingProductRestResponseBuilder
     */
    public function __construct(
        QuoteReaderInterface $quoteReader,
        UpSellingProductsRestApiToProductRelationStorageClientInterface $productRelationStorageClient,
        UpSellingProductRestResponseBuilderInterface $upSellingProductRestResponseBuilder
    ) {
        $this->quoteReader = $quoteReader;
        $this->productRelationStorageClient = $productRelationStorageClient;
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

        $quoteTransfer = $this->quoteReader->findQuoteByUuid($parentResource->getId(), $restRequest);
        if (!$quoteTransfer) {
            return $this->upSellingProductRestResponseBuilder->createCartNotFoundError();
        }

        $upSellingProductAbstractIds = $this->productRelationStorageClient
            ->findUpSellingAbstractProductIds($quoteTransfer);

        return $this->upSellingProductRestResponseBuilder
            ->buildUpSellingProductCollectionRestResponse($restRequest, $upSellingProductAbstractIds);
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
