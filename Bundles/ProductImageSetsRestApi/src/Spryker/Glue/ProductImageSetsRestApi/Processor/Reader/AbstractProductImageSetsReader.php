<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductImageSetsRestApi\Processor\Reader;

use Generated\Shared\Transfer\ProductAbstractImageStorageTransfer;
use Generated\Shared\Transfer\ProductAbstractStorageTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ProductImageSetsRestApi\Dependency\Client\ProductImageSetsRestApiToProductImageStorageClientInterface;
use Spryker\Glue\ProductImageSetsRestApi\Dependency\Client\ProductImageSetsRestApiToProductStorageClientInterface;
use Spryker\Glue\ProductImageSetsRestApi\Processor\Mapper\AbstractProductImageSetsMapperInterface;
use Spryker\Glue\ProductImageSetsRestApi\ProductImageSetsRestApiConfig;
use Spryker\Glue\ProductsRestApi\ProductsRestApiConfig;
use Symfony\Component\HttpFoundation\Response;

class AbstractProductImageSetsReader implements AbstractProductImageSetsReaderInterface
{
    protected const PRODUCT_ABSTRACT_MAPPING_TYPE = 'sku';
    protected const SELF_LINK_FORMAT = '%s/%s/%s';

    /**
     * @var \Spryker\Glue\ProductImageSetsRestApi\Dependency\Client\ProductImageSetsRestApiToProductStorageClientInterface
     */
    protected $productStorageClient;

    /**
     * @var \Spryker\Glue\ProductImageSetsRestApi\Dependency\Client\ProductImageSetsRestApiToProductImageStorageClientInterface
     */
    protected $productImageStorageClient;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\ProductImageSetsRestApi\Processor\Mapper\AbstractProductImageSetsMapperInterface
     */
    protected $abstractProductImageSetsMapper;

    /**
     * @param \Spryker\Glue\ProductImageSetsRestApi\Dependency\Client\ProductImageSetsRestApiToProductStorageClientInterface $productStorageClient
     * @param \Spryker\Glue\ProductImageSetsRestApi\Dependency\Client\ProductImageSetsRestApiToProductImageStorageClientInterface $productImageStorageClient
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\ProductImageSetsRestApi\Processor\Mapper\AbstractProductImageSetsMapperInterface $abstractProductImageSetsMapper
     */
    public function __construct(
        ProductImageSetsRestApiToProductStorageClientInterface $productStorageClient,
        ProductImageSetsRestApiToProductImageStorageClientInterface $productImageStorageClient,
        RestResourceBuilderInterface $restResourceBuilder,
        AbstractProductImageSetsMapperInterface $abstractProductImageSetsMapper
    ) {
        $this->productStorageClient = $productStorageClient;
        $this->productImageStorageClient = $productImageStorageClient;
        $this->restResourceBuilder = $restResourceBuilder;
        $this->abstractProductImageSetsMapper = $abstractProductImageSetsMapper;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getAbstractProductImageSets(RestRequestInterface $restRequest): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();
        $parentResource = $restRequest->findParentResourceByType(ProductsRestApiConfig::RESOURCE_ABSTRACT_PRODUCTS);
        if (!$parentResource) {
            return $restResponse->addError(
                $this->createAbstractProductNotFoundError()
            );
        }

        $abstractSku = $parentResource->getId();
        $restResource = $this->findAbstractProductImageSetsBySku($abstractSku, $restRequest);

        if ($restResource === null) {
            $restErrorTransfer = $this->createAbstractProductImageSetsNotFoundError();

            return $restResponse->addError($restErrorTransfer);
        }

        return $restResponse->addResource($restResource);
    }

    /**
     * @param string $sku
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface|null
     */
    public function findAbstractProductImageSetsBySku(string $sku, RestRequestInterface $restRequest): ?RestResourceInterface
    {
        $abstractProductStorageData = $this->productStorageClient
            ->findProductAbstractStorageDataByMapping(
                static::PRODUCT_ABSTRACT_MAPPING_TYPE,
                $sku,
                $restRequest->getMetadata()->getLocale()
            );

        if (!$abstractProductStorageData) {
            return null;
        }

        $abstractProductTransfer = (new ProductAbstractStorageTransfer())->fromArray(
            $abstractProductStorageData,
            true
        );

        $productImageAbstractStorageTransfer = $this->productImageStorageClient
            ->findProductImageAbstractStorageTransfer($abstractProductTransfer->getIdProductAbstract(), $restRequest->getMetadata()->getLocale());

        if (!$productImageAbstractStorageTransfer) {
            return null;
        }

        return $this->buildProductImageSetsResource($sku, $productImageAbstractStorageTransfer);
    }

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\ProductAbstractImageStorageTransfer $productImageAbstractStorageTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    protected function buildProductImageSetsResource(string $sku, ProductAbstractImageStorageTransfer $productImageAbstractStorageTransfer): RestResourceInterface
    {
        $restProductAbstractImageSetAttributesTransfer = $this->abstractProductImageSetsMapper
            ->mapProductAbstractImageStorageTransferToRestProductImageSetsAttributesTransfer($productImageAbstractStorageTransfer);

        $restResource = $this->restResourceBuilder->createRestResource(
            ProductImageSetsRestApiConfig::RESOURCE_ABSTRACT_PRODUCT_IMAGE_SETS,
            $sku,
            $restProductAbstractImageSetAttributesTransfer
        );

        $restResourceSelfLink = sprintf(
            static::SELF_LINK_FORMAT,
            ProductsRestApiConfig::RESOURCE_ABSTRACT_PRODUCTS,
            $sku,
            ProductImageSetsRestApiConfig::RESOURCE_ABSTRACT_PRODUCT_IMAGE_SETS
        );
        $restResource->addLink(RestResourceInterface::RESOURCE_LINKS_SELF, $restResourceSelfLink);

        return $restResource;
    }

    /**
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer
     */
    protected function createAbstractProductNotFoundError(): RestErrorMessageTransfer
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(ProductsRestApiConfig::RESPONSE_CODE_CANT_FIND_ABSTRACT_PRODUCT)
            ->setStatus(Response::HTTP_NOT_FOUND)
            ->setDetail(ProductsRestApiConfig::RESPONSE_DETAIL_CANT_FIND_ABSTRACT_PRODUCT);

        return $restErrorTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer
     */
    protected function createAbstractProductImageSetsNotFoundError(): RestErrorMessageTransfer
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(ProductImageSetsRestApiConfig::RESPONSE_CODE_ABSTRACT_PRODUCT_IMAGE_SETS_NOT_FOUND)
            ->setStatus(Response::HTTP_NOT_FOUND)
            ->setDetail(ProductImageSetsRestApiConfig::RESPONSE_DETAIL_ABSTRACT_PRODUCT_IMAGE_SETS_NOT_FOUND);

        return $restErrorTransfer;
    }
}
