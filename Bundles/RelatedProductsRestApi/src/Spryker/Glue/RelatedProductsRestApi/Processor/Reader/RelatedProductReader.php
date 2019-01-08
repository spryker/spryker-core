<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\RelatedProductsRestApi\Processor\Reader;

use Generated\Shared\Transfer\AbstractProductsRestAttributesTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ProductsRestApi\ProductsRestApiConfig;
use Spryker\Glue\RelatedProductsRestApi\Dependency\Client\RelatedProductsRestApiToProductRelationStorageClientInterface;
use Spryker\Glue\RelatedProductsRestApi\Dependency\Client\RelatedProductsRestApiToProductStorageClientInterface;
use Symfony\Component\HttpFoundation\Response;

class RelatedProductReader implements RelatedProductReaderInterface
{
    protected const PRODUCT_ABSTRACT_MAPPING_TYPE = 'sku';
    protected const KEY_ID_PRODUCT_ABSTRACT = 'id_product_abstract';

    /**
     * @var \Spryker\Glue\RelatedProductsRestApi\Dependency\Client\RelatedProductsRestApiToProductStorageClientInterface
     */
    protected $productStorageClient;

    /**
     * @var \Spryker\Glue\RelatedProductsRestApi\Dependency\Client\RelatedProductsRestApiToProductRelationStorageClientInterface
     */
    protected $productRelationStorageClient;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @param \Spryker\Glue\RelatedProductsRestApi\Dependency\Client\RelatedProductsRestApiToProductStorageClientInterface $productStorageClient
     * @param \Spryker\Glue\RelatedProductsRestApi\Dependency\Client\RelatedProductsRestApiToProductRelationStorageClientInterface $productRelationStorageClient
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     */
    public function __construct(
        RelatedProductsRestApiToProductStorageClientInterface $productStorageClient,
        RelatedProductsRestApiToProductRelationStorageClientInterface $productRelationStorageClient,
        RestResourceBuilderInterface $restResourceBuilder
    ) {
        $this->productStorageClient = $productStorageClient;
        $this->productRelationStorageClient = $productRelationStorageClient;
        $this->restResourceBuilder = $restResourceBuilder;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function readRelatedProducts(RestRequestInterface $restRequest): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        $parentResource = $restRequest->findParentResourceByType(ProductsRestApiConfig::RESOURCE_ABSTRACT_PRODUCTS);
        if (!$parentResource || !$parentResource->getId()) {
            return $restResponse->addError($this->createProductAbstractSkuMissingError());
        }

        $sku = $parentResource->getId();
        $localeName = $restRequest->getMetadata()->getLocale();

        $abstractProductData = $this->productStorageClient
            ->findProductAbstractStorageDataByMapping(
                static::PRODUCT_ABSTRACT_MAPPING_TYPE,
                $sku,
                $localeName
            );

        if (!$abstractProductData) {
            return $restResponse->addError($this->createProductAbstractNotFoundError());
        }

        $relatedProductsEntityTransfer = $this->productRelationStorageClient
            ->findRelatedProducts($abstractProductData[static::KEY_ID_PRODUCT_ABSTRACT], $localeName);

        $this->addAbstractProductsResource($restResponse, $relatedProductsEntityTransfer);

        return $restResponse;
    }

    /**
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer
     */
    protected function createProductAbstractSkuMissingError(): RestErrorMessageTransfer
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(ProductsRestApiConfig::RESPONSE_CODE_ABSTRACT_PRODUCT_SKU_IS_NOT_SPECIFIED)
            ->setStatus(Response::HTTP_BAD_REQUEST)
            ->setDetail(ProductsRestApiConfig::RESPONSE_DETAIL_ABSTRACT_PRODUCT_SKU_IS_NOT_SPECIFIED);

        return $restErrorTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer
     */
    protected function createProductAbstractNotFoundError(): RestErrorMessageTransfer
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(ProductsRestApiConfig::RESPONSE_CODE_CANT_FIND_ABSTRACT_PRODUCT)
            ->setStatus(Response::HTTP_NOT_FOUND)
            ->setDetail(ProductsRestApiConfig::RESPONSE_DETAIL_CANT_FIND_ABSTRACT_PRODUCT);

        return $restErrorTransfer;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     * @param \Generated\Shared\Transfer\ProductViewTransfer[] $productViewTransfers
     *
     * @return void
     */
    protected function addAbstractProductsResource(RestResponseInterface $restResponse, array $productViewTransfers): void
    {
        foreach ($productViewTransfers as $productViewTransfer) {
            $restResource = $this->restResourceBuilder->createRestResource(
                ProductsRestApiConfig::RESOURCE_ABSTRACT_PRODUCTS,
                $productViewTransfer->getSku(),
                (new AbstractProductsRestAttributesTransfer())->fromArray($productViewTransfer->toArray(), true)
            );

            $restResponse->addResource($restResource);
        }
    }
}
