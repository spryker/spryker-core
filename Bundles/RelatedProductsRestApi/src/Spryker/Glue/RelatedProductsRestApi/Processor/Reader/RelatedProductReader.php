<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\RelatedProductsRestApi\Processor\Reader;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestLinkInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ProductsRestApi\ProductsRestApiConfig;
use Spryker\Glue\RelatedProductsRestApi\Dependency\Client\RelatedProductsRestApiToProductRelationStorageClientInterface;
use Spryker\Glue\RelatedProductsRestApi\Dependency\Client\RelatedProductsRestApiToProductStorageClientInterface;
use Spryker\Glue\RelatedProductsRestApi\Processor\Mapper\RelatedProductsResourceMapperInterface;
use Spryker\Glue\RelatedProductsRestApi\RelatedProductsRestApiConfig;
use Symfony\Component\HttpFoundation\Response;

class RelatedProductReader implements RelatedProductReaderInterface
{
    protected const PRODUCT_ABSTRACT_MAPPING_TYPE = 'sku';
    protected const KEY_ID_PRODUCT_ABSTRACT = 'id_product_abstract';

    protected const SELF_LINK_FORMAT = '%s/%s/%s';

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
     * @var \Spryker\Glue\RelatedProductsRestApi\Processor\Mapper\RelatedProductsResourceMapperInterface
     */
    protected $relatedProductsResourceMapper;

    /**
     * @param \Spryker\Glue\RelatedProductsRestApi\Dependency\Client\RelatedProductsRestApiToProductStorageClientInterface $productStorageClient
     * @param \Spryker\Glue\RelatedProductsRestApi\Dependency\Client\RelatedProductsRestApiToProductRelationStorageClientInterface $productRelationStorageClient
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\RelatedProductsRestApi\Processor\Mapper\RelatedProductsResourceMapperInterface $relatedProductsResourceMapper
     */
    public function __construct(
        RelatedProductsRestApiToProductStorageClientInterface $productStorageClient,
        RelatedProductsRestApiToProductRelationStorageClientInterface $productRelationStorageClient,
        RestResourceBuilderInterface $restResourceBuilder,
        RelatedProductsResourceMapperInterface $relatedProductsResourceMapper
    ) {
        $this->productStorageClient = $productStorageClient;
        $this->productRelationStorageClient = $productRelationStorageClient;
        $this->restResourceBuilder = $restResourceBuilder;
        $this->relatedProductsResourceMapper = $relatedProductsResourceMapper;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param string|null $sku
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function readRelatedProducts(RestRequestInterface $restRequest, ?string $sku = null): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        if (!$sku) {
            $parentResource = $restRequest->findParentResourceByType(ProductsRestApiConfig::RESOURCE_ABSTRACT_PRODUCTS);
            if (!$parentResource || !$parentResource->getId()) {
                return $restResponse->addError($this->createProductAbstractSkuMissingError());
            }

            $sku = $parentResource->getId();
        }

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

        $restResource = $this->buildRelatedProductsResource($sku, $relatedProductsEntityTransfer);

        return $restResponse->addResource($restResource);
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
     * @param string $sku
     * @param \Generated\Shared\Transfer\ProductViewTransfer[] $productViewTransfers
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    protected function buildRelatedProductsResource(string $sku, array $productViewTransfers): RestResourceInterface
    {
        $restRelatedProductsAttributesTransfer = $this->relatedProductsResourceMapper
            ->mapRelatedProductsTransferToRestRelatedProductsAttributesTransfer($productViewTransfers);

        $restResource = $this->restResourceBuilder->createRestResource(
            RelatedProductsRestApiConfig::RESOURCE_RELATED_PRODUCTS,
            $sku,
            $restRelatedProductsAttributesTransfer
        );

        $restResourceSelfLink = sprintf(
            static::SELF_LINK_FORMAT,
            ProductsRestApiConfig::RESOURCE_ABSTRACT_PRODUCTS,
            $sku,
            RelatedProductsRestApiConfig::RESOURCE_RELATED_PRODUCTS
        );
        $restResource->addLink(RestLinkInterface::LINK_SELF, $restResourceSelfLink);

        return $restResource;
    }
}
