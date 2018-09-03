<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductsRestApi\Processor\AbstractProducts;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ProductsRestApi\Dependency\Client\ProductsRestApiToProductResourceAliasStorageClientInterface;
use Spryker\Glue\ProductsRestApi\Processor\ConcreteProducts\ConcreteProductsReaderInterface;
use Spryker\Glue\ProductsRestApi\Processor\Mapper\AbstractProductsResourceMapperInterface;
use Spryker\Glue\ProductsRestApi\ProductsRestApiConfig;
use Symfony\Component\HttpFoundation\Response;

class AbstractProductsReader implements AbstractProductsReaderInterface
{
    protected const PRODUCT_CONCRETE_IDS_KEY = 'product_concrete_ids';

    /**
     * @var \Spryker\Glue\ProductsRestApi\Dependency\Client\ProductsRestApiToProductResourceAliasStorageClientInterface
     */
    protected $productResourceAliasStorageClient;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\ProductsRestApi\Processor\Mapper\AbstractProductsResourceMapperInterface
     */
    protected $abstractProductsResourceMapper;

    /**
     * @var \Spryker\Glue\ProductsRestApi\Processor\ConcreteProducts\ConcreteProductsReaderInterface
     */
    protected $concreteProductsReader;

    /**
     * @param \Spryker\Glue\ProductsRestApi\Dependency\Client\ProductsRestApiToProductResourceAliasStorageClientInterface $productResourceAliasStorageClient
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\ProductsRestApi\Processor\Mapper\AbstractProductsResourceMapperInterface $abstractProductsResourceMapper
     * @param \Spryker\Glue\ProductsRestApi\Processor\ConcreteProducts\ConcreteProductsReaderInterface $concreteProductsReader
     */
    public function __construct(
        ProductsRestApiToProductResourceAliasStorageClientInterface $productResourceAliasStorageClient,
        RestResourceBuilderInterface $restResourceBuilder,
        AbstractProductsResourceMapperInterface $abstractProductsResourceMapper,
        ConcreteProductsReaderInterface $concreteProductsReader
    ) {
        $this->productResourceAliasStorageClient = $productResourceAliasStorageClient;
        $this->restResourceBuilder = $restResourceBuilder;
        $this->abstractProductsResourceMapper = $abstractProductsResourceMapper;
        $this->concreteProductsReader = $concreteProductsReader;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getProductAbstractStorageData(RestRequestInterface $restRequest): RestResponseInterface
    {
        $response = $this->restResourceBuilder->createRestResponse();

        $resourceIdentifier = $restRequest->getResource()->getId();

        if (empty($resourceIdentifier)) {
            $restErrorTransfer = (new RestErrorMessageTransfer())
                ->setStatus(Response::HTTP_BAD_REQUEST);
            return $response->addError($restErrorTransfer);
        }

        $abstractProductData = $this->productResourceAliasStorageClient
            ->findProductAbstractStorageDataBySku(
                $resourceIdentifier,
                $restRequest->getMetadata()->getLocale()
            );

        if (!$abstractProductData) {
            $restErrorTransfer = (new RestErrorMessageTransfer())
                ->setCode(ProductsRestApiConfig::RESPONSE_CODE_CANT_FIND_ABSTRACT_PRODUCT)
                ->setStatus(Response::HTTP_NOT_FOUND)
                ->setDetail(ProductsRestApiConfig::RESPONSE_DETAIL_CANT_FIND_ABSTRACT_PRODUCT);

            return $response->addError($restErrorTransfer);
        }
        $restResource = $this->abstractProductsResourceMapper
            ->mapAbstractProductsResponseAttributesTransferToRestResponse($abstractProductData);
        $restResource = $this->addConcreteProducts($restResource, $restRequest);

        return $response->addResource($restResource);
    }

    /**
     * @param string $sku
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface|null
     */
    public function findProductAbstractBySku(string $sku, RestRequestInterface $restRequest): ?RestResourceInterface
    {
        $abstractProductData = $this->productResourceAliasStorageClient->findProductAbstractStorageDataBySku(
            $sku,
            $restRequest->getMetadata()->getLocale()
        );

        if (!$abstractProductData) {
            return null;
        }

        return $this->abstractProductsResourceMapper
            ->mapAbstractProductsResponseAttributesTransferToRestResponse($abstractProductData);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $restResource
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    protected function addConcreteProducts(RestResourceInterface $restResource, RestRequestInterface $restRequest): RestResourceInterface
    {
        /** @var \Generated\Shared\Transfer\AbstractProductsRestAttributesTransfer $attributes */
        $attributes = $restResource->getAttributes();
        $concreteProductsResourceList = $this->concreteProductsReader
            ->findProductConcretesByProductConcreteSkus(
                $attributes->getAttributeMap()[static::PRODUCT_CONCRETE_IDS_KEY],
                $restRequest
            );

        foreach ($concreteProductsResourceList as $concreteProductResource) {
            $restResource->addRelationship($concreteProductResource);
        }

        return $restResource;
    }
}
